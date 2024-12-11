<?php
session_start();
require_once "../config/db_connection.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

try {
    // Conexión y configuración
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Obtener el rol y el área del usuario logueado
    $user_id = $_SESSION["user_id"];
    $query_user = "SELECT rol, id_area FROM usuarios WHERE id_usuario = ?";
    $stmt_user = $pdo->prepare($query_user);
    $stmt_user->execute([$user_id]);
    $user = $stmt_user->fetch();
    if (!$user) {
        throw new Exception("Usuario no encontrado.");
    }
    $rol = $user["rol"];
    $id_area_usuario = $user["id_area"];

    // Variables de búsqueda
    $busqueda_expediente = isset($_GET["expediente"])
        ? $_GET["expediente"]
        : "";
    $busqueda_remitente = isset($_GET["remitente"]) ? $_GET["remitente"] : "";

    // Comprobar si se ha enviado el formulario de atención de expediente
    if (
        $_SERVER["REQUEST_METHOD"] === "POST"
        // isset($_GET["id_expediente"])
    ) {
        $id_expediente = $_POST["id_expediente"];
        $respuesta = $_POST["respuesta"]; // La respuesta que se agrega a seguimiento

        // Verificar si se ha subido un archivo PDF
        if (
            isset($_FILES["pdf_file"]) &&
            $_FILES["pdf_file"]["error"] === UPLOAD_ERR_OK
        ) {
            // Nombre del archivo y ubicación temporal
            $pdf_name = $_FILES["pdf_file"]["name"];
            $pdf_tmp_name = $_FILES["pdf_file"]["tmp_name"];

            // Directorio donde se guardarán los archivos
            $archivo_respuesta =
                "respuesta_" . $id_expediente . "_" . time() . ".pdf";
            $upload_path = "../respuesta_archivos/" . $archivo_respuesta;
            // $pdf_dest = $upload_dir . basename($pdf_name);

            // Mover el archivo desde la ubicación temporal al directorio de destino
            if (move_uploaded_file($pdf_tmp_name, $upload_path)) {
                // Registrar la respuesta y el archivo PDF en la tabla de seguimiento
                $stmt_seguimiento = $pdo->prepare(
                    "INSERT INTO seguimiento (id_expediente, estado, respuesta, fecha_hora)
                     VALUES (?, 'Atendido', ?, NOW())"
                );
                $stmt_seguimiento->execute([$id_expediente, $respuesta]);

                // Actualizar el estado del expediente
                $stmt_exp_update = $pdo->prepare(
                    "UPDATE expedientes SET estado = 'en proceso', archivo_respuesta = ? WHERE id_expediente = ?"
                );
                $stmt_exp_update->execute([$archivo_respuesta, $id_expediente]);

                // Redirigir a la página de lista de expedientes o mostrar un mensaje de éxito
                header("Location: index.php");
                exit();
            } else {
                // Error al mover el archivo
                echo "Error al subir el archivo PDF.";
            }
        } else {
            // Si no se sube un archivo o hay un error en la carga
            $stmt_seguimiento = $pdo->prepare(
                "INSERT INTO seguimiento (id_expediente, estado, respuesta, fecha_hora)
                 VALUES (?, 'Atendido', ?, NOW())"
            );
            $stmt_seguimiento->execute([$id_expediente, $respuesta]);

            // Actualizar el estado del expediente
            $stmt_exp_update = $pdo->prepare(
                "UPDATE expedientes SET estado = 'Atendido' WHERE id_expediente = ?"
            );
            $stmt_exp_update->execute([$id_expediente]);

            // Redirigir a la página de lista de expedientes o mostrar un mensaje de éxito
            header("Location: index.php");
            exit();
        }
    }

    // Código de paginación y obtención de expedientes
    $expedientes_por_pagina = 5;
    $pagina_actual = isset($_GET["pagina"]) ? (int) $_GET["pagina"] : 1;
    $pagina_actual = max($pagina_actual, 1); // Asegurarse de que no sea menor que 1
    $offset = ($pagina_actual - 1) * $expedientes_por_pagina;

    // Filtrar la consulta dependiendo de los parámetros de búsqueda
    $where_clauses = [];
    $params = [];

    if ($busqueda_expediente) {
        $where_clauses[] = "e.id_expediente LIKE :expediente";
        $params[":expediente"] = "%" . $busqueda_expediente . "%";
    }

    if ($busqueda_remitente) {
        $where_clauses[] = "e.remitente LIKE :remitente";
        $params[":remitente"] = "%" . $busqueda_remitente . "%";
    }

    $where_sql = "";
    if (count($where_clauses) > 0) {
        $where_sql = "WHERE " . implode(" AND ", $where_clauses);
    }

    // Modificar la consulta según el rol del usuario
    if ($rol == "admin") {
        // Los administradores ven todos los expedientes
        $query = "SELECT e.*,
            COALESCE(
                (SELECT s.estado FROM seguimiento s WHERE s.id_expediente = e.id_expediente ORDER BY s.fecha_hora DESC LIMIT 1),
                'Pendiente'
            ) as estado_seguimiento,
            (SELECT a.nombre FROM areas a JOIN seguimiento s ON a.id_area = s.id_area WHERE s.id_expediente = e.id_expediente ORDER BY s.fecha_hora DESC LIMIT 1) as area_destino,
            e.archivo
        FROM expedientes e
        LEFT JOIN seguimiento s ON e.id_expediente = s.id_expediente
        LEFT JOIN areas a ON s.id_area = a.id_area
        $where_sql
        GROUP BY e.id_expediente
        ORDER BY e.id_expediente ASC
        LIMIT :limit OFFSET :offset";
    } else {
        // Los empleados ven solo los expedientes derivados a su área
        $query = "SELECT e.*,
                         s.fecha_hora as fecha_seguimiento,
                         s.estado as estado_seguimiento,
                         a.nombre as area_destino,
                         e.archivo
                  FROM expedientes e
                  INNER JOIN seguimiento s ON e.id_expediente = s.id_expediente
                  INNER JOIN areas a ON s.id_area = a.id_area
                  WHERE s.id_area = :id_area
                  $where_sql
                  GROUP BY e.id_expediente
                  ORDER BY e.id_expediente ASC
                  LIMIT :limit OFFSET :offset";
        $params[":id_area"] = $id_area_usuario;
    }

    // Preparar la consulta con los parámetros de búsqueda
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":limit", $expedientes_por_pagina, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();

    // Obtener los expedientes
    $expedientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el total de expedientes para calcular el número de páginas
    $query_total = "SELECT COUNT(*) FROM expedientes $where_sql";
    $stmt_total = $pdo->prepare($query_total);
    foreach ($params as $key => $value) {
        $stmt_total->bindValue($key, $value);
    }
    $stmt_total->execute();
    $total_expedientes = $stmt_total->fetchColumn();
    $total_paginas = ceil($total_expedientes / $expedientes_por_pagina);
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
