<?php
require_once "../TCPDF/tcpdf.php";
require_once "../config/db_connection.php";

class MYPDF extends TCPDF
{
    public function Header()
    {
        $this->SetFont("helvetica", "B", 15);
        $this->Cell(0, 15, "Reporte de Expedientes", 0, 1, "C");
        $this->SetFont("helvetica", "", 9);
        $this->Cell(
            0,
            10,
            "Fecha de generación: " . date("d/m/Y H:i:s"),
            0,
            1,
            "C"
        );
        $this->Ln(5);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont("helvetica", "I", 8);
        $this->Cell(
            0,
            10,
            "Página " .
                $this->getAliasNumPage() .
                "/" .
                $this->getAliasNbPages(),
            0,
            0,
            "C"
        );
    }
}

// Crear nuevo documento PDF
$pdf = new MYPDF("L", "mm", "A4", true, "UTF-8");

// Configurar documento
$pdf->SetCreator("Sistema de Mesa de Partes");
$pdf->SetAuthor("Administrador");
$pdf->SetTitle("Reporte de Expedientes");

// Configurar márgenes
$pdf->SetMargins(10, 40, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Agregar página
$pdf->AddPage();
$pdf->SetFont("helvetica", "", 8);

// Consulta SQL
$query = "SELECT
    e.id_expediente,
    e.fecha_hora,
    e.remitente,
    e.tipo_tramite,
    e.asunto,
    e.dni_ruc,
    e.estado,
    e.codigo_seguridad,
    e.telefono,
    COALESCE(a.nombre, 'Sin asignar') as area_nombre
FROM expedientes e
LEFT JOIN seguimiento s ON e.id_expediente = s.id_expediente
LEFT JOIN areas a ON s.id_area = a.id_area
GROUP BY e.id_expediente
ORDER BY e.fecha_hora DESC";

$stmt = $pdo->query($query);
$expedientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Encabezados de la tabla
$header = [
    "ID",
    "Fecha",
    "Remitente",
    "Tipo",
    "Asunto",
    "DNI/RUC",
    "Estado",
    "Área",
    "Código",
    "Telefono",
];
$w = [10, 28, 35, 25, 50, 25, 25, 35, 25, 25];

// Colores y estilos
$pdf->SetFillColor(224, 224, 224);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(128, 128, 128);
$pdf->SetLineWidth(0.3);
$pdf->SetFont("", "B", 9);

// Imprimir encabezados
for ($i = 0; $i < count($header); $i++) {
    $pdf->Cell($w[$i], 8, $header[$i], 1, 0, "C", true);
}
$pdf->Ln();

// Imprimir filas
$pdf->SetFont("", "", 9);
$pdf->SetFillColor(245, 245, 245);
$fill = false;

foreach ($expedientes as $row) {
    $pdf->Cell($w[0], 6, $row["id_expediente"], "LR", 0, "C", $fill);
    $pdf->Cell(
        $w[1],
        6,
        date("d/m/Y H:i", strtotime($row["fecha_hora"])),
        "LR",
        0,
        "C",
        $fill
    );
    $pdf->Cell($w[2], 6, $row["remitente"], "LR", 0, "L", $fill);
    $pdf->Cell($w[3], 6, $row["tipo_tramite"], "LR", 0, "C", $fill);
    $pdf->Cell($w[4], 6, substr($row["asunto"], 0, 50), "LR", 0, "L", $fill);
    $pdf->Cell($w[5], 6, $row["dni_ruc"], "LR", 0, "C", $fill);
    $pdf->Cell($w[6], 6, $row["estado"], "LR", 0, "C", $fill);
    $pdf->Cell($w[7], 6, $row["area_nombre"], "LR", 0, "C", $fill);
    $pdf->Cell($w[8], 6, $row["codigo_seguridad"], "LR", 0, "C", $fill);
    $pdf->Cell($w[9], 6, $row["telefono"], "LR", 0, "C", $fill);
    $pdf->Ln();
    $fill = !$fill;
}

// Línea de cierre
$pdf->Cell(array_sum($w), 0, "", "T");

// Generar PDF
$pdf->Output("reporte_expedientes.pdf", "I");
exit();
?>
