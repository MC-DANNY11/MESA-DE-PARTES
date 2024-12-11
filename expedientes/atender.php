<?php
session_start();
require_once "../config/db_connection.php";
// Verificar si el usuario está autenticado
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}
// // Verificar si se ha pasado el 'id_expediente' por URL
// if (isset($_GET["id_expediente"])) {
//     $id_expediente = $_GET["id_expediente"];
//     var_dump($id_expediente); // Depuración para ver el valor del id_expediente
//     $id_expediente = intval($id_expediente); // Convertir a entero explícitamente para la consulta
// } else {
//     echo "No se encontró el expediente.";
//     exit();
// }
// Procesar formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_expediente = $_POST["id_expediente"];
    // Validar si el archivo fue subido
    if (isset($_FILES["pdf_file"]) && $_FILES["pdf_file"]["error"] == 0) {
        $file = $_FILES["pdf_file"];
        $file_name = $file["name"];
        $file_tmp_name = $file["tmp_name"];
        $file_size = $file["size"];
        $file_type = $file["type"];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        // Validar que el archivo es un PDF
        // if ($file_ext == "pdf") {
        // Crear un nombre único para el archivo
        $archivo_respuesta =
            "respuesta_" . $id_expediente . "_" . time() . ".pdf";
        $upload_path = "../respuesta_archivos/" . $archivo_respuesta;

        // Mover el archivo a la carpeta de subidas
        if (move_uploaded_file($file_tmp_name, $upload_path)) {
            // Registrar el estado "Atendido" en la tabla de seguimiento
            try {
                $respuesta = $_POST["respuesta"]; // Obtener la respuesta

                // Insertar un nuevo registro en la tabla de seguimiento con el estado "Atendido"
                $stmt_seguimiento = $pdo->prepare(
                    "INSERT INTO seguimiento (id_expediente, estado, respuesta, fecha_hora)
                         VALUES (?, 'Atendido', ?, NOW())"
                );
                $stmt_seguimiento->execute([$id_expediente, $respuesta]);

                // Actualizar la tabla 'expedientes' con el archivo de respuesta
                $stmt_exp = $pdo->prepare(
                    "UPDATE expedientes SET estado = 'Atendido',  archivo_respuesta = ? WHERE id_expediente = ?"
                );
                $stmt_exp->execute([$archivo_respuesta, $id_expediente]);

                $success_message =
                    "El expediente ha sido atendido y la respuesta se ha registrado con éxito.";
            } catch (PDOException $e) {
                $error_message =
                    "Error al atender el expediente: " . $e->getMessage();
            }
        } else {
            $error_message = "Hubo un problema al subir el archivo.";
        }
        // } else {
        //     $error_message = "El archivo debe ser un PDF.";
        // }
    } else {
        $error_message = "Por favor, seleccione un archivo PDF para subir.";
    }
}

// Obtener los detalles del expediente
$stmt = $pdo->prepare("SELECT * FROM expedientes WHERE id_expediente = ?");
$stmt->execute([$id_expediente]);
$expediente = $stmt->fetch();
// Verificar si se encontró el expediente
if (!$expediente) {
    $error_message = "No se encontró el expediente con ID: " . $id_expediente;
} else {
    // Si se encuentra el expediente, procesamos los datos
    // Aquí puedes agregar más lógica para mostrar la información del expediente si lo deseas
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Atender Expediente</title>
</head>
<body>
    <h2>Atender Expediente</h2>
    <!-- Mostrar mensajes de éxito o error -->
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php elseif (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <!-- Formulario para subir el archivo PDF y atender el expediente -->
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_expediente" id="id_expediente" value="<?php echo $id_expediente; ?>">
        <label for="respuesta">Respuesta:</label><br>
        <textarea name="respuesta" id="respuesta" rows="4" cols="50" required></textarea><br><br>
        <label for="pdf_file">Seleccione el archivo PDF:</label>
        <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" required><br><br>
        <button type="submit">Atender</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>
