<?php
require_once "../TCPDF/tcpdf.php";
require_once "../config/db_connection.php";

class MYPDF extends TCPDF
{
    public function Header()
    {
        // Añadir el logo en la parte superior izquierda
        $this->Image('../imagenes/ESCUDO DISTRITO DE PALCA.jpg', 10, 10, 15, 0);

        // Título del reporte
        $this->SetFont("helvetica", "B", 15);
        $this->Cell(0, 15, "Reporte de ÁREAS", 0, 1, "C");
        
        $this->SetFont("helvetica", "", 9);
        $this->Ln(5);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont("helvetica", "I", 8);
        $this->Cell(0, 10, "Página " . $this->getAliasNumPage() . "/" . $this->getAliasNbPages(), 0, 0, "C");
    }
}

// Crear nuevo documento PDF en formato vertical
$pdf = new MYPDF("P", "mm", "A4", true, "UTF-8");

// Configurar documento
$pdf->SetCreator("Sistema de Áreas");
$pdf->SetAuthor("Administrador");
$pdf->SetTitle("Reporte de ÁREAS");

// Configurar márgenes
$pdf->SetMargins(10, 30, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Agregar página
$pdf->AddPage();
$pdf->SetFont("helvetica", "", 8);

// Consulta SQL actualizada
$query = "SELECT * FROM areas";

try {
    // Ejecutar la consulta usando $pdo
    $stmt = $pdo->query($query);
    $areas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al ejecutar la consulta: " . $e->getMessage());
}

// Encabezados de la tabla
$header = ["ID", "Nombre", "Descripción"];
$w = [30, 80, 80];

// Imprimir encabezados
$pdf->SetFillColor(224, 224, 224); // Color de fondo para encabezados
$pdf->SetTextColor(0);
$pdf->SetFont("", "B", 9);
foreach ($header as $key => $col) {
    $pdf->Cell($w[$key], 8, $col, 1, 0, "C", true);
}
$pdf->Ln();

// Imprimir filas
$pdf->SetFont("", "", 9);
$pdf->SetFillColor(245, 245, 245); // Color de fondo alternado para filas
$fill = false;

if (!empty($areas)) {
    foreach ($areas as $area) {
        $pdf->Cell($w[0], 6, $area["id_area"], "LR", 0, "C", $fill);
        $pdf->Cell($w[1], 6, $area["nombre"], "LR", 0, "L", $fill);
        $pdf->Cell($w[2], 6, $area["descripcion"], "LR", 0, "L", $fill);
        $pdf->Ln();
        $fill = !$fill;
    }
} else {
    // Mostrar mensaje si no hay datos
    $pdf->Cell(array_sum($w), 6, "No se encontraron áreas.", 1, 0, "C");
}

// Línea de cierre
$pdf->Cell(array_sum($w), 0, "", "T");

// Generar PDF
$pdf->Output("reporte_areas.pdf", "I");
exit();
?>
