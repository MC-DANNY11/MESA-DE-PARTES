<?php
require_once '../config/db_connection.php';
require_once '../vendor/autoload.php'; // Para usar PhpSpreadsheet

$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Estilos para los encabezados
$headerStyle = [
    'font' => [
        'bold' => true,
        'size' => 14,
        'color' => ['rgb' => 'FFFFFF']
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => '1E90FF'] // Color de fondo azul
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'] // Color de borde negro
        ]
    ]
];

// Estilos para las filas de datos
$dataStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ],
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ]
];

// Encabezados de la hoja
$sheet->setCellValue('A1', 'ID Usuario')
      ->setCellValue('B1', 'Nombre')
      ->setCellValue('C1', 'Nombre de Usuario')
      ->setCellValue('D1', 'Correo')
      ->setCellValue('E1', 'Rol')
      ->setCellValue('F1', 'Área');

// Aplicar estilo a los encabezados
$sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

// Ajustar el ancho automáticamente
foreach (range('A', 'F') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Consulta para obtener los usuarios con el nombre del área
$sql = "SELECT usuarios.id_usuario, usuarios.nombre, usuarios.nombre_usuario, usuarios.correo, usuarios.rol, areas.nombre AS area_nombre
        FROM usuarios
        JOIN areas ON usuarios.id_area = areas.id_area";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    $rowCount = 2; // Comenzar desde la segunda fila

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sheet->setCellValue('A' . $rowCount, $row['id_usuario'])
              ->setCellValue('B' . $rowCount, $row['nombre'])
              ->setCellValue('C' . $rowCount, $row['nombre_usuario'])
              ->setCellValue('D' . $rowCount, $row['correo'])
              ->setCellValue('E' . $rowCount, $row['rol'])
              ->setCellValue('F' . $rowCount, $row['area_nombre']);

        // Aplicar estilo a cada fila de datos
        $sheet->getStyle('A' . $rowCount . ':F' . $rowCount)->applyFromArray($dataStyle);
        $rowCount++;
    }
}

// Configuración para descargar el archivo como Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="usuarios_report.xlsx"');
header('Cache-Control: max-age=0');

// Guardar el archivo Excel
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
