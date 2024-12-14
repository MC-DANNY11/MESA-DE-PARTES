<?php
session_start();

if (!empty($_POST)) {
    include "../config/db_connection.php";
    date_default_timezone_set('America/Lima');

    // Obtener la fecha y hora actual
    $fecha_actual = date('Y-m-d H:i:s');

    // Validar que todos los campos requeridos están presentes
    if (
        !empty($_POST['tipo_persona']) &&
        !empty($_POST['tipo_identificacion']) &&
        !empty($_POST['dni_ruc']) &&
        !empty($_POST['nombre']) &&
        !empty($_POST['apellido_paterno']) &&
        !empty($_POST['apellido_materno']) &&
        !empty($_POST['telefono']) &&
        !empty($_POST['correo']) &&
        !empty($_POST['direccion']) &&
        !empty($_POST['asunto']) &&
        !empty($_POST['tipo_documento']) &&
        !empty($_POST['folio'])
    ) {
        try {
            $tipo_persona = $_POST['tipo_persona'];
            $tipo_identificacion = $_POST['tipo_identificacion'];
            $dni_ruc = $_POST['dni_ruc'];
            $nombre = trim($_POST['nombre']);
            $apellido_paterno = trim($_POST['apellido_paterno']);
            $apellido_materno = trim($_POST['apellido_materno']);
            $telefono = $_POST['telefono'];
            $correo = $_POST['correo'];
            $direccion = trim($_POST['direccion']);
            $asunto = trim($_POST['asunto']);
            $tipo_documento = $_POST['tipo_documento'];
            $folio = $_POST['folio'];
            $estado = 'pendiente';
            $area = 1;
            $notas_referencias = !empty($_POST['notas_referencias']) ? trim($_POST['notas_referencias']) : null;

            // Generar código de seguridad aleatorio de 6 caracteres
            $codigo_seguridad = strtoupper(bin2hex(random_bytes(3)));

            // Obtener el último número de expediente y generar el siguiente
            $query_numero = "SELECT MAX(numero_expediente) AS ultimo FROM expedientes";
            $stmt_numero = $pdo->query($query_numero);
            $ultimo_expediente = $stmt_numero->fetch(PDO::FETCH_ASSOC)['ultimo'];
            $numero_expediente = str_pad((int)$ultimo_expediente + 1, 9, '0', STR_PAD_LEFT);

            // Manejo del archivo adjunto
            $archivo_nombre = null;
            if (!empty($_FILES['archivo']['name'])) {
                $archivo_nombre = time() . '_' . $_FILES['archivo']['name'];
                $ruta_destino = "uploads/" . $archivo_nombre;
                move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino);
            }

            // Insertar en la base de datos
            $query = "INSERT INTO expedientes (
                        fecha_hora, remitente, tipo_tramite, asunto, folio, tipo_persona, dni_ruc, 
                        correo, telefono, direccion, estado, archivo, apellido_paterno, 
                        apellido_materno, notas_referencias, codigo_seguridad, tipo_documento, 
                        numero_expediente, id_area
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($query);
            $stmt->execute([ 
                $fecha_actual,
                $nombre,
                $tipo_identificacion,
                $asunto,
                $folio,
                $tipo_persona,
                $dni_ruc,
                $correo,
                $telefono,
                $direccion,
                $estado,
                $archivo_nombre,
                $apellido_paterno,
                $apellido_materno,
                $notas_referencias,
                $codigo_seguridad,
                $tipo_documento,
                $numero_expediente,
                $area
            ]);

            // Almacenar los datos en la sesión para mostrar en la página de visualización
            $_SESSION['registro'] = [
                'fecha_hora' => $fecha_actual,
                'nombre' => $nombre.' '.$apellido_paterno .' '.$apellido_materno,
                'tipo_identificacion' => $tipo_identificacion,
                'asunto' => $asunto,
                'folio' => $folio,
                'tipo_persona' => $tipo_persona,
                'dni_ruc' => $dni_ruc,
                'correo' => $correo,
                'telefono' => $telefono,
                'direccion' => $direccion,
                'estado' => $estado,
                'archivo' => $archivo_nombre,
                'notas_referencias' => $notas_referencias,
                'codigo_seguridad' => $codigo_seguridad,
                'tipo_documento' => $tipo_documento,
                'numero_expediente' => $numero_expediente,
                'area' => $area
            ];

            $_SESSION['exito'] = "Trámite registrado correctamente.";
            header('Location:ver_expediente.php');
            exit();

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al registrar el trámite: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    $_SESSION['error'] = "Solicitud no válida.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
