<?php
require "../TCPDF/tcpdf.php"; // Incluye TCPDF

// Asegúrate de que el usuario tiene permisos de administrador
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require "../config/db_connection.php";

try {
    // Crear un objeto TCPDF
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont("helvetica", "", 12);

    // Título del PDF
    $pdf->Cell(0, 10, "Reporte de ÁREAS", 0, 1, "C");

    // Espaciado
    $pdf->Ln(10);

    // Encabezados de la tabla
    $pdf->SetFont("helvetica", "B", 10);
    $pdf->Cell(20, 10, "ID", 1, 0, "C");
    $pdf->Cell(80, 10, "Nombre", 1, 1, "C"); // Ajustado a 80 para más espacio

    // Obtener los usuarios desde la base de datos
    $stmt = $pdo->prepare("SELECT * FROM areas");
    $stmt->execute();
    $areas = $stmt->fetchAll();

    if (count($areas) > 0) {
        // Llenar la tabla con los datos
        $pdf->SetFont("helvetica", "", 10);
        foreach ($areas as $area) {
            $pdf->Cell(20, 10, $area["id_area"], 1, 0, "C");
            $pdf->Cell(80, 10, $area["nombre"], 1, 1, "C");
        }
    } else {
        // Mensaje en caso de no encontrar datos
        $pdf->Cell(0, 10, "No se encontraron registros de áreas.", 1, 1, "C");
    }

    // Output PDF
    $pdf->Output("reporte_areas.pdf", "I");
    exit();
} catch (Exception $e) {
    // Manejo de errores
    echo "Error al generar el reporte: " . $e->getMessage();
    exit();
}
?>
