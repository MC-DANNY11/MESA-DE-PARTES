<?php
require_once('tcpdf_include.php');

// Verificar si se han enviado datos a través de POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados por POST y sanitizar los valores
    $campos = [
        'fecha_hora', 'nombre', 'tipo_identificacion', 'asunto', 'folio',
        'dni_ruc', 'correo', 'telefono', 'direccion', 'estado',
        'notas_referencias', 'codigo_seguridad', 'tipo_documento',
        'numero_expediente'
    ];

    $datos = [];
    foreach ($campos as $campo) {
        $datos[$campo] = htmlspecialchars($_POST[$campo] ?? '');
    }

    // Crear un nuevo documento PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Configurar el documento
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Generador de PDF');
    $pdf->SetTitle('Detalles del Expediente');
    $pdf->SetSubject('Información del expediente');
    $pdf->SetKeywords('TCPDF, PDF, expediente');

    // Eliminar encabezado predeterminado para personalizar el diseño
    $pdf->setPrintHeader(false);

    // Establecer la fuente predeterminada
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setFont('helvetica', '', 12);

    // Agregar una página
    $pdf->AddPage();

    // Agregar el logo y el texto en la misma línea
    $logo = '../../imagenes/ESCUDO DISTRITO DE PALCA.jpg'; // Ruta del logo
    if (file_exists($logo)) {
        $pdf->Image($logo, 10, 10, 20, 20); // X, Y, ancho, alto
    }
    $pdf->SetXY(35, 15); // Posición del texto
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetTextColor(0, 128, 0); // Color verde
    $pdf->Cell(0, 10, 'Municipalidad Distrital de Palca', 0, 1, 'C');

    // Agregar encabezado del documento
    $pdf->Ln(10); // Salto de línea
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Comprobante de Envío de Documento', 0, 1, 'C');
    $pdf->Ln(5);

    // Agregar la tabla
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetFillColor(0, 128, 0); // Fondo verde
    $pdf->SetTextColor(255, 255, 255); // Texto blanco
    $pdf->Cell(70, 10, 'Campo', 1, 0, 'C', 1);
    $pdf->Cell(110, 10, 'Valor', 1, 1, 'C', 1);

    // Rellenar los datos de la tabla
    $pdf->SetFont('helvetica', '', 12);
    $pdf->SetTextColor(0, 0, 0); // Texto negro

    foreach ($datos as $campo => $valor) {
        $isBold = in_array($campo, ['codigo_seguridad', 'numero_expediente']);
        $pdf->SetFont('', $isBold ? 'B' : '');

        $pdf->Cell(70, 10, ucfirst(str_replace('_', ' ', $campo)), 1, 0, 'L');
        $pdf->Cell(110, 10, $valor, 1, 1, 'L');
    }

    // Agregar un párrafo explicativo
    $pdf->Ln(5); // Salto de línea
    $pdf->SetFont('helvetica', '', 11);
    $pdf->MultiCell(0, 10, "Recomendación: Por favor, descargue este comprobante para su archivo personal y no comparta el código de seguridad con terceros. Este código es único y está diseñado para realizar el seguimiento de su documento de forma segura. Asegúrese de conservarlo en un lugar seguro.", 0, 'J');

    // Agregar archivo si existe
    if (!empty($datos['archivo'])) {
        $pdf->Ln(5); // Salto de línea
        $pdf->SetFont('helvetica', 'I', 11);
        $pdf->MultiCell(0, 10, "Archivo adjunto: uploads/" . $datos['archivo'], 0, 'L');
    }

    // Salida del PDF
    $pdf->Output('detalles_expediente.pdf', 'I');
} else {
    echo 'No se recibieron datos para generar el PDF.';
}
?>
