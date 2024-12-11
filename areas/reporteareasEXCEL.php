<?php
require '../vendor/autoload.php';  // Asegúrate de instalar PhpSpreadsheet con Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Asegúrate de que el usuario tiene permisos de administrador
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../config/db_connection.php';

try {
    // Crear una nueva hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Título del reporte
    $sheet->setCellValue('A1', 'Reporte de ÁREAS');
    $sheet->mergeCells('A1:B1'); // Combinar celdas para el título
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

    // Encabezados de la tabla
    $sheet->setCellValue('A3', 'ID');
    $sheet->setCellValue('B3', 'Nombre');
    $sheet->getStyle('A3:B3')->getFont()->setBold(true);
    $sheet->getStyle('A3:B3')->getAlignment()->setHorizontal('center');

    // Obtener los datos de las áreas
    $stmt = $pdo->prepare("SELECT * FROM areas");
    $stmt->execute();
    $areas = $stmt->fetchAll();

    if (count($areas) > 0) {
        // Rellenar los datos en la hoja de cálculo
        $row = 4; // Inicia en la fila 4
        foreach ($areas as $area) {
            $sheet->setCellValue('A' . $row, $area['id_area']);
            $sheet->setCellValue('B' . $row, $area['nombre']);
            $row++;
        }
    } else {
        // Si no hay datos, mostrar un mensaje en la hoja
        $sheet->setCellValue('A4', 'No se encontraron registros de áreas.');
        $sheet->mergeCells('A4:B4');
        $sheet->getStyle('A4')->getFont()->setItalic(true);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');
    }

    // Ajustar automáticamente el ancho de las columnas
    foreach (range('A', 'B') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // Preparar la descarga del archivo Excel
    $writer = new Xlsx($spreadsheet);
    $fileName = 'reporte_areas.xlsx';

    // Enviar encabezados HTTP para descargar el archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit();

} catch (Exception $e) {
    // Manejo de errores
    echo 'Error al generar el reporte: ' . $e->getMessage();
    exit();
}
?>
