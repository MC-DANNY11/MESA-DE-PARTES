<?php include "../template/header.php"; ?>
<?php
include "../config/db_connection.php";

// Variables para la paginación
$itemsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Filtro de búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Consultar el historial de expedientes con paginación y búsqueda
$query = "
    SELECT 
        h.id_historial,
        h.id_expediente,
        u.nombre AS usuario,
        h.estado_documento,
        h.descripcion,
        DATE_FORMAT(h.fecha_hora, '%d/%m/%Y %H:%i:%s') AS fecha_hora
    FROM historial_expedientes h
    JOIN usuarios u ON h.id_usuario = u.id_usuario
    WHERE h.id_expediente LIKE :search OR u.nombre LIKE :search
    ORDER BY h.fecha_hora DESC
    LIMIT :offset, :itemsPerPage
";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$historial = $stmt->fetchAll(PDO::FETCH_OBJ);

// Contar el total de registros para la paginación
$countQuery = "
    SELECT COUNT(*) as total 
    FROM historial_expedientes h
    JOIN usuarios u ON h.id_usuario = u.id_usuario
    WHERE h.id_expediente LIKE :search OR u.nombre LIKE :search
";
$countStmt = $pdo->prepare($countQuery);
$countStmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$countStmt->execute();
$totalRecords = $countStmt->fetch(PDO::FETCH_OBJ)->total;
$totalPages = ceil($totalRecords / $itemsPerPage);
?>

<a href="Reporte_EXCEL.php" class="add-area-btn">
    <i class="fa-solid fa-file-excel"></i> Exportar a Excel
</a>
<a href="Reporte_PDF.php" class="add-area-btn">
    <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
</a>

<!-- Campo de búsqueda -->
<form method="GET" class="search-form">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Buscar por expediente o usuario..." class="search-input">
    <button type="submit" class="search-btn">Buscar</button>
</form>

<div class="table-container">
    <table >
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
</div>

<!-- Paginación -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search) ?>">Anterior</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>" class="<?= $i == $page ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search) ?>">Siguiente</a>
    <?php endif; ?>
</div>

<?php include "../template/footer.php"; ?>
