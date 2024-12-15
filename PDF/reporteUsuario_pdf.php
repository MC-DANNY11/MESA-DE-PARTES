<?php
require_once "../TCPDF/tcpdf.php";
require_once "../config/db_connection.php";

class MYPDF extends TCPDF
{
    public function Header()
    {
        // Añadir el logo en la parte superior izquierda
        $this->Image('../imagenes/ESCUDO DISTRITO DE PALCA.jpg', 10, 10, 20, 0); // Ajusta el tamaño y la posición según sea necesario

        // Título del reporte
        $this->SetFont("helvetica", "B", 15);
        $this->Cell(0, 15, "Reporte de Usuarios", 0, 1, "C");
        $this->SetFont("helvetica", "", 9);
        
        $this->Ln(5);
    }

    public function Footer()
    {
        // Pie de página
        $this->SetY(-15);
        $this->SetFont("helvetica", "I", 8);
        $this->Cell(0, 10, "Página " . $this->getAliasNumPage() . "/" . $this->getAliasNbPages(), 0, 0, "C");
    }
}

// Crear nuevo documento PDF
$pdf = new MYPDF("L", "mm", "A4", true, "UTF-8");

// Configurar documento
$pdf->SetCreator("Sistema de Administración");
$pdf->SetAuthor("Administrador");
$pdf->SetTitle("Reporte de Usuarios");

// Configurar márgenes
$pdf->SetMargins(10, 40, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Agregar página
$pdf->AddPage();
$pdf->SetFont("helvetica", "", 8);

// Consulta SQL para obtener los usuarios
$query = "SELECT usuarios.id_usuario, usuarios.nombre, usuarios.nombre_usuario, usuarios.correo, usuarios.rol, areas.nombre AS area_nombre
          FROM usuarios
          JOIN areas ON usuarios.id_area = areas.id_area";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll();

// Encabezados de la tabla
$header = ["ID", "Nombre", "Usuario", "Correo", "Rol", "Área"];
$w = [20, 50, 50, 50, 40, 40];

// Imprimir encabezados
$pdf->SetFillColor(200, 220, 255);
$pdf->SetTextColor(0);
$pdf->SetFont("", "B", 9);
foreach ($header as $i => $column) {
    $pdf->Cell($w[$i], 10, $column, 1, 0, "C", true);
}
$pdf->Ln();

// Imprimir filas de la tabla
$pdf->SetFont("", "", 9);
$fill = false;
foreach ($usuarios as $usuario) {
    $pdf->Cell($w[0], 10, $usuario["id_usuario"], "LR", 0, "C", $fill);
    $pdf->Cell($w[1], 10, $usuario["nombre"], "LR", 0, "C", $fill);
    $pdf->Cell($w[2], 10, $usuario["nombre_usuario"], "LR", 0, "C", $fill);
    $pdf->Cell($w[3], 10, $usuario["correo"], "LR", 0, "C", $fill);
    $pdf->Cell($w[4], 10, $usuario["rol"], "LR", 0, "C", $fill);
    $pdf->Cell($w[5], 10, $usuario["area_nombre"], "LR", 0, "C", $fill);
    $pdf->Ln();
    $fill = !$fill;
}

// Línea de cierre
$pdf->Cell(array_sum($w), 0, "", "T");

// Generar PDF
$pdf->Output("reporte_usuarios.pdf", "I");
exit();
?>
