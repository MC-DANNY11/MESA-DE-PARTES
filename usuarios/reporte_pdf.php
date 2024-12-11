<?php
require "../TCPDF/tcpdf.php"; // Incluye TCPDF

// Asegúrate de que el usuario tiene permisos de administrador
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require "../config/db_connection.php";

// Crear un objeto TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont("helvetica", "", 12);

// Título del PDF
$pdf->Cell(0, 10, "Reporte de Usuarios", 0, 1, "C");

// Encabezados de la tabla
$pdf->SetFont("helvetica", "B", 10);
$pdf->Cell(20, 10, "ID", 1, 0, "C");
$pdf->Cell(40, 10, "Nombre", 1, 0, "C");
$pdf->Cell(40, 10, "Nombre de Usuario", 1, 0, "C");
$pdf->Cell(40, 10, "Correo", 1, 0, "C");
$pdf->Cell(30, 10, "Rol", 1, 0, "C");
$pdf->Cell(30, 10, "Área", 1, 1, "C");

// Obtener los usuarios desde la base de datos
$stmt = $pdo->prepare("SELECT * FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll();

// Llenar la tabla con los datos
$pdf->SetFont("helvetica", "", 10);
foreach ($usuarios as $usuario) {
    $pdf->Cell(20, 10, $usuario["id_usuario"], 1, 0, "C");
    $pdf->Cell(40, 10, $usuario["nombre"], 1, 0, "C");
    $pdf->Cell(40, 10, $usuario["nombre_usuario"], 1, 0, "C");
    $pdf->Cell(40, 10, $usuario["correo"], 1, 0, "C");
    $pdf->Cell(30, 10, $usuario["rol"], 1, 0, "C");
    $pdf->Cell(30, 10, $usuario["id_area"], 1, 1, "C");
}

// Output PDF
$pdf->Output("reporte_usuarios.pdf", "I");
exit();
?>
