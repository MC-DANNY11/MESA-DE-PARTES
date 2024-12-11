<?php
require_once '../config/db_connection.php';
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Crear una instancia de Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reporte de Expedientes');

// Estilo para los encabezados
$headerStyle = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'borders' => [
        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E0E0E0']
    ]
];

// Establecer encabezados
$headers = [
    'A1' => 'ID',
    'B1' => 'Fecha',
    'C1' => 'Remitente',
    'D1' => 'Tipo Trámite',
    'E1' => 'Asunto',
    'F1' => 'DNI/RUC',
    'G1' => 'Estado',
    'H1' => 'Área Actual',
    'I1' => 'Código Seguimiento',
    'J1' => 'Telefono',
];

foreach ($headers as $cell => $value) {
    $sheet->setCellValue($cell, $value);
}
$sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

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
$rowCount = 2;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sheet->setCellValue('A' . $rowCount, $row['id_expediente'])
          ->setCellValue('B' . $rowCount, date('d/m/Y H:i', strtotime($row['fecha_hora'])))
          ->setCellValue('C' . $rowCount, $row['remitente'])
          ->setCellValue('D' . $rowCount, $row['tipo_tramite'])
          ->setCellValue('E' . $rowCount, $row['asunto'])
          ->setCellValue('F' . $rowCount, $row['dni_ruc'])
          ->setCellValue('G' . $rowCount, $row['estado'])
          ->setCellValue('H' . $rowCount, $row['area_nombre'])
          ->setCellValue('I' . $rowCount, $row['codigo_seguridad'])
          ->setCellValue('J' . $rowCount, $row['telefono']);
    $rowCount++;
}

// Autoajustar columnas
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Configurar cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_expedientes.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>