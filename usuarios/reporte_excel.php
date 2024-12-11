<?php
// Incluir el archivo de conexión con PDO
require_once '../config/db_connection.php';
require_once '../vendor/autoload.php'; // Para usar PhpSpreadsheet

// Crear una instancia de la clase Spreadsheet
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados de la hoja
$sheet->setCellValue('A1', 'ID Usuario')
      ->setCellValue('B1', 'Nombre')
      ->setCellValue('C1', 'Nombre de Usuario')
      ->setCellValue('D1', 'Correo')
      ->setCellValue('E1', 'Rol')
      ->setCellValue('F1', 'Área');

// Consultar los usuarios usando PDO
$sql = "SELECT id_usuario, nombre, nombre_usuario, correo, rol, id_area FROM usuarios";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    $rowCount = 2; // Comenzar desde la segunda fila

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sheet->setCellValue('A' . $rowCount, $row['id_usuario'])
              ->setCellValue('B' . $rowCount, $row['nombre'])
              ->setCellValue('C' . $rowCount, $row['nombre_usuario'])
              ->setCellValue('D' . $rowCount, $row['correo'])
              ->setCellValue('E' . $rowCount, $row['rol'])
              ->setCellValue('F' . $rowCount, $row['id_area']);
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
