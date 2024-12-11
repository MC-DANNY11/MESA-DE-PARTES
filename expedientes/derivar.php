<?php
session_start();
require_once "../config/db_connection.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

// Inicializar variables
$success_message = "";
$error_message = "";

if (isset($_GET["id"])) {
    $id_expediente = $_GET["id"];

    // Verificar si el formulario fue enviado
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Validar que se haya seleccionado un área
        if (
            isset($_POST["id_area_destino"]) &&
            !empty($_POST["id_area_destino"])
        ) {
            $id_area_destino = $_POST["id_area_destino"];
            $estado = "pendiente"; // El estado de la derivación

            try {
                // Insertar el seguimiento en la base de datos
                $stmt = $pdo->prepare(
                    "INSERT INTO seguimiento (id_expediente, id_area, estado) VALUES (?, ?, ?)"
                );
                $stmt->execute([$id_expediente, $id_area_destino, $estado]);

                $success_message =
                    "El expediente ha sido derivado correctamente al área seleccionada.";
            } catch (PDOException $e) {
                // Si ocurre un error al insertar en la base de datos
                $error_message =
                    "Error al derivar el expediente: " . $e->getMessage();
            }
        } else {
            $error_message = "Por favor, seleccione un área de destino.";
        }
    }
} else {
    // Si no se pasa un 'id' en la URL, mostrar un error
    $error_message = "No se ha encontrado el expediente.";
}

// Obtener las áreas disponibles
$areas = $pdo->query("SELECT * FROM areas")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Derivar Expediente</title>
</head>
<body>
    <h2>Derivar Expediente</h2>

    <!-- Mostrar mensaje de éxito o error -->
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
        <a href="index.php">Volver a la lista de expedientes</a>
    <?php elseif (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php else: ?>
        <!-- Formulario para seleccionar el área de destino -->
        <form method="post">
            <label>Seleccione Área de Destino:</label>
            <select name="id_area_destino" required>
                <?php foreach ($areas as $area): ?>
                    <option value="<?php echo $area[
                        "id_area"
                    ]; ?>"><?php echo $area["nombre"]; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Derivar</button>
        </form>
    <?php endif; ?>
</body>
</html>
