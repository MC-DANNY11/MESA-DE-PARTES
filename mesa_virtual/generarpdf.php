<?php
session_start();

// Verificar si hay datos en la sesión
if (isset($_SESSION['pdf_data'])) {
    $pdf_data = $_SESSION['pdf_data'];
    unset($_SESSION['pdf_data']);  // Limpiar la sesión después de usarla
} else {
    // Si no hay datos, redirigir al usuario
    header('Location: formulario.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Envío</title>
</head>
<body>
    <h2>Datos del Expediente</h2>
    <p><strong>Remitente:</strong> <?php echo $pdf_data['remitente']; ?></p>
    <p><strong>Asunto:</strong> <?php echo $pdf_data['asunto']; ?></p>
    <p><strong>Folio:</strong> <?php echo $pdf_data['folio']; ?></p>
    <p><strong>Tipo de Persona:</strong> <?php echo $pdf_data['tipo_persona']; ?></p>
    <p><strong>Correo:</strong> <?php echo $pdf_data['correo']; ?></p>
    <p><strong>Teléfono:</strong> <?php echo $pdf_data['telefono']; ?></p>
    <p><strong>Dirección:</strong> <?php echo $pdf_data['direccion']; ?></p>
    <p><strong>Fecha:</strong> <?php echo $pdf_data['fecha']; ?></p>
    <p><strong>Código de Seguridad:</strong> <?php echo $pdf_data['codigo_seguridad']; ?></p>
    <p><strong>Número de Expediente:</strong> <?php echo $pdf_data['numero_expediente']; ?></p>

    <!-- Botón para generar PDF -->
    <form action="generar_pdf.php" method="post">
        <input type="hidden" name="pdf_data" value="<?php echo base64_encode(serialize($pdf_data)); ?>">
        <button type="submit">Generar PDF</button>
    </form>
</body>
</html>
