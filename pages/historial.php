<?php include "../template/header.php"; ?>
<!-- Main Content -->
<div class="content">

<?php
include "../config/db_connection.php";

// Consultar el historial de expedientes
$query = "SELECT 
    h.id_historial,
    h.id_expediente,
    u.nombre AS usuario,
    h.estado_documento,
    h.descripcion,
    DATE_FORMAT(h.fecha_hora, '%d/%m/%Y %H:%i:%s') AS fecha_hora
FROM historial_expedientes h
JOIN usuarios u ON h.id_usuario = u.id_usuario
ORDER BY h.fecha_hora DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$historial = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<a href="Reporte_EXCEL.php" class="add-area-btn">
    <i class="fa-solid fa-file-excel"></i> Exportar a Excel
</a>
<a href="Reporte_PDF.php" class="add-area-btn">
    <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
</a>

<!-- Campo de búsqueda -->
<input type="text" id="searchInput" placeholder="Buscar por expediente o usuario..." class="search-input">
<!-- Botón de búsqueda -->
<button id="searchButton" class="search-btn">Buscar</button>

<table class="table-container">
    <thead>
        <tr>
            <th>ID Historial</th>
            <th>ID Expediente</th>
            <th>Usuario</th>
            <th>Estado</th>
            <th>Descripción</th>
            <th>Fecha y Hora</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($historial)): ?>
            <?php foreach ($historial as $dato): ?>
                <tr>
                    <td><?= htmlspecialchars($dato->id_historial); ?></td>
                    <td><?= htmlspecialchars($dato->id_expediente); ?></td>
                    <td><?= htmlspecialchars($dato->usuario); ?></td>
                    <td><?= htmlspecialchars($dato->estado_documento); ?></td>
                    <td><?= htmlspecialchars($dato->descripcion); ?></td>
                    <td><?= htmlspecialchars($dato->fecha_hora); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No se encontraron registros en el historial.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include "../template/footer.php"; ?>