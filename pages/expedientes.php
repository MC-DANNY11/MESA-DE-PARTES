<?php include "../template/header.php"; ?>
<!-- Main Content -->
<div class="content">

<?php
include "../config/db_connection.php";

// Consultar todas las áreas disponibles
$stmt = "SELECT * FROM Areas";
$stmt = $pdo->prepare($stmt);
$stmt->execute();
$areas = $stmt->fetchAll(PDO::FETCH_OBJ);

// Consultar expedientes con nombre del área
$query = "SELECT 
    id_expediente,
    DATE_FORMAT(fecha_hora, '%d/%m/%Y %H:%i:%s') AS fecha_hora,
    remitente,
    tipo_tramite,
    asunto,
    folio,
    tipo_persona,
    dni_ruc,
    correo,
    telefono,
    direccion,
    estado,
    archivo,
    apellido_paterno,
    apellido_materno,
    notas_referencias,
    codigo_seguridad,
    tipo_documento,
    LPAD(numero_expediente, 9, '0') AS numero_expediente,
    a.nombre AS nom_area  -- Mostrar nombre del área
FROM expedientes e 
JOIN areas a ON e.id_area = a.id_area
ORDER BY fecha_hora DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$expedientes = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<a href="Reporte_EXCEL.php" class="add-area-btn">
    <i class="fa-solid fa-file-excel"></i> Exportar a Excel
</a>
<a href="Reporte_PDF.php" class="add-area-btn">
    <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
</a>

<!-- Campo de búsqueda -->
<input type="text" id="searchInput" placeholder="Buscar por expediente o remitente..." class="search-input">
<!-- Botón de búsqueda -->
<button id="searchButton" class="search-btn">Buscar</button>

<table class="table-container">
    <thead >
        <tr>
            <th>Expediente</th>
            <th>Remitente</th>
            <th>Código</th>
            <th>Teléfono</th>
            <th>Asunto</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Nota</th>
            <th>Área</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($expedientes)): ?>
            <?php foreach ($expedientes as $dato): ?>
                <tr>
                    <td><?= htmlspecialchars($dato->numero_expediente); ?></td>
                    <td><?= htmlspecialchars($dato->remitente); ?></td>
                    <td><?= htmlspecialchars($dato->codigo_seguridad); ?></td>
                    <td><?= htmlspecialchars($dato->telefono); ?></td>
                    <td><?= htmlspecialchars($dato->asunto); ?></td>
                    <td><?= htmlspecialchars($dato->fecha_hora); ?></td>
                    <td><?= htmlspecialchars($dato->estado); ?></td>
                    <td><?= htmlspecialchars($dato->notas_referencias); ?></td>
                    <td><?= htmlspecialchars($dato->nom_area); ?></td> <!-- Mostrar nombre del área -->
                    <td>
                        <div class="btn-group" role="group">
                            <a href="javascript:void(0);" onclick="mostrarModal(<?= $dato->id_expediente; ?>);" class="btn btn-primary btn-sm" title="Atender expediente">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $dato->id_expediente; ?>" class="btn btn-warning btn-sm" title="Derivar expediente">
                                <i class="fas fa-exchange-alt"></i>
                            </a>
                            <a href="javascript:void(0);" onclick="mostrarPopupEliminar(<?= $dato->id_expediente; ?>);" class="btn btn-danger btn-sm" title="Eliminar expediente">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            <?php if (!empty($dato->archivo) && file_exists("../mesa_virtual/uploads/" . $dato->archivo)): ?>
                            <a href="../mesa_virtual/uploads/<?= htmlspecialchars(basename($dato->archivo)); ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <!-- Modal para derivar -->
                <div class="modal fade" id="exampleModal<?= $dato->id_expediente; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Derivar Expediente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="../validate/update/u_derivar.php" method="post">
                                    <input type="number" name="codigo" value="<?= htmlspecialchars($dato->id_expediente); ?>">
                                    <div class="mb-3">
                                        <label for="areaSelect" class="form-label">Área Destino</label>
                                        <select id="areaSelect" class="form-select" name="area">
                                            <option value="">Seleccione un área</option>
                                            <?php foreach ($areas as $area): ?>
                                                <option value="<?= $area->id_area; ?>"><?= htmlspecialchars($area->nombre); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Derivar</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10" class="text-center">No se encontraron expedientes.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include "../template/footer.php"; ?>
