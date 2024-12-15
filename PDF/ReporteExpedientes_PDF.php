<?php
require_once "../TCPDF/tcpdf.php";
require_once "../config/db_connection.php";

class MYPDF extends TCPDF
{
    public function Header()
    {
        // Añadir el logo en la parte superior derecha
        $this->Image('../imagenes/ESCUDO DISTRITO DE PALCA.jpg', 10, 10, 15, 0,); // Ajusta el tamaño y la posición según sea necesario

        // Título del reporte
        $this->SetFont("helvetica", "B", 15);
        $this->Cell(0, 15, "Reporte de Expedientes", 0, 1, "C");
        $this->SetFont("helvetica", "", 9);
        
        $this->Ln(5);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont("helvetica", "I", 8);
        $this->Cell(
            0,
            10,
            "Página " . $this->getAliasNumPage() . "/" . $this->getAliasNbPages(),
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
$pdf->SetMargins(10, 30, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Agregar página
$pdf->AddPage();
$pdf->SetFont("helvetica", "", 8);

// Consulta SQL actualizada
$query = "SELECT
    e.id_expediente,
    e.fecha_hora,
    CONCAT(e.remitente, ' ', COALESCE(e.apellido_paterno, ''), ' ', COALESCE(e.apellido_materno, '')) AS remitente,
    e.codigo_seguridad,
    e.telefono,
    e.asunto,
    e.notas_referencias,
    e.estado,
    COALESCE(a.nombre, 'Sin asignar') AS area_nombre
FROM expedientes e
LEFT JOIN seguimiento s ON e.id_expediente = s.id_expediente
LEFT JOIN areas a ON s.id_area = a.id_area
GROUP BY e.id_expediente
ORDER BY e.fecha_hora DESC";

try {
    // Ejecutar la consulta usando $pdo
    $stmt = $pdo->query($query);
    $expedientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al ejecutar la consulta: " . $e->getMessage());
}

// Encabezados de la tabla
$header = [
    "N° Exp.",
    "Remitente",
    "Código",
    "Teléfono",
    "Asunto",
    "Fecha",
    "Estado",
    "Nota",
    "Área",
];
$w = [13, 55, 20, 25, 40, 25, 20, 50, 30];

// Imprimir encabezados
$pdf->SetFillColor(224, 224, 224);
$pdf->SetTextColor(0);
$pdf->SetFont("", "B", 9);
for ($i = 0; $i < count($header); $i++) {
    $pdf->Cell($w[$i], 8, $header[$i], 1, 0, "C", true);
}
$pdf->Ln();

// Imprimir filas
$pdf->SetFont("", "", 9);
$pdf->SetFillColor(245, 245, 245);
$fill = false;

if (!empty($expedientes)) {
    foreach ($expedientes as $row) {
        $pdf->Cell($w[0], 6, $row["id_expediente"], "LR", 0, "C", $fill);
        $pdf->Cell($w[1], 6, $row["remitente"], "LR", 0, "L", $fill);
        $pdf->Cell($w[2], 6, $row["codigo_seguridad"], "LR", 0, "C", $fill);
        $pdf->Cell($w[3], 6, $row["telefono"], "LR", 0, "C", $fill);
        $pdf->Cell($w[4], 6, substr($row["asunto"], 0, 50), "LR", 0, "L", $fill);
        $pdf->Cell($w[5], 6, date("d/m/Y ", strtotime($row["fecha_hora"])), "LR", 0, "C", $fill);
        $pdf->Cell($w[6], 6, $row["estado"], "LR", 0, "C", $fill);
        $pdf->Cell($w[7], 6, substr($row["notas_referencias"], 0, 50), "LR", 0, "L", $fill);
        $pdf->Cell($w[8], 6, $row["area_nombre"], "LR", 0, "C", $fill);
        $pdf->Ln();
        $fill = !$fill;
    }
} else {
    // Mostrar mensaje si no hay datos
    $pdf->Cell(array_sum($w), 6, "No se encontraron expedientes.", 1, 0, "C");
}

// Línea de cierre
$pdf->Cell(array_sum($w), 0, "", "T");

// Generar PDF
$pdf->Output("reporte_expedientes.pdf", "I");
exit();
?>
