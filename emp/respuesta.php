<?php include "../etemplate/header.php"; ?>

<?php
include "../config/db_connection.php";

// Consultar todas las áreas disponibles
$stmt = "SELECT * FROM Areas";
$stmt = $pdo->prepare($stmt);
$stmt->execute();
$areas = $stmt->fetchAll(PDO::FETCH_OBJ);

$query = "SELECT 
    s.id_seguimiento,
    DATE_FORMAT(s.fecha_hora, '%d/%m/%Y %H:%i:%s') AS fecha_hora,
    s.respuesta,
    e.estado,
    s.adjunto,
    u.nombre as nom_user,
    s.id_area,
    LPAD(e.numero_expediente, 9, '0') AS numero_expediente,
    a.nombre AS nom_area  -- Mostrar nombre del área
FROM seguimiento s
JOIN areas a ON s.id_area = a.id_area
JOIN usuarios u ON s.id_usuario = u.id_usuario
JOIN expedientes e ON s.id_expediente = e.id_expediente
ORDER BY s.fecha_hora DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$expedientes = $stmt->fetchAll(PDO::FETCH_OBJ);
?>



<a href="../EXCEL/ReporteExpedientes_EXCEL.php" class="add-area-btn">
    <i class="fa-solid fa-file-excel"></i> Exportar a Excel
</a>
<a href="../PDF/ReporteExpedientes_PDF.php" class="add-area-btn">
    <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
</a>


<div class="table-container">
    <table id="examples">
        <thead>
            <tr>
                <th>Expediente</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Respuesta</th>
                <th>Área</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($expedientes)): ?>
            <?php foreach ($expedientes as $dato): ?>
            <tr>
                <td><?= htmlspecialchars($dato->numero_expediente); ?></td>
                <td><?= htmlspecialchars($dato->nom_user); ?></td>
                <td><?= htmlspecialchars($dato->fecha_hora); ?></td>
                <td><?= htmlspecialchars($dato->estado); ?></td>
                <td><?= htmlspecialchars($dato->respuesta); ?></td>
                <td><?= htmlspecialchars($dato->nom_area); ?></td> <!-- Mostrar nombre del área -->
                <td>
                    <div class="btn-group" role="group">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $dato->id_seguimiento; ?>"
                            class="btn btn-primary btn-sm" title="Derivar expediente">
                            <i class="fa-solid fa-edit"></i>
                        </a>|
                        <a href="#" data-href="../validate/delete/d_seguimiento.php?id=<?= $dato->id_seguimiento; ?>"
                        class="btn btn-danger btn-sm"class="icon-btn delete-btn" onclick="EliminarSeguimiento(event)">
                        <i class="fa fa-trash" aria-hidden="true" style="margin-right: 5px; font-size: 16px;"></i>
                    </a>|
                        <?php if (!empty($dato->adjunto) && file_exists("../respuesta/" . $dato->adjunto)): ?>
                        <a href="../respuesta/<?= htmlspecialchars(basename($dato->adjunto)); ?>"
                            class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>
                        <?php else: ?>
                        <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>


            <!-- Modal para derivar -->
            <div class="modal fade" id="exampleModal<?= htmlspecialchars($dato->id_seguimiento); ?>" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Derivar Expediente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../validate/u_seguimiento.php" method="post" enctype="multipart/form-data">
                                <!-- Código oculto -->
                                <input type="hidden" name="codigo"
                                    value="<?= htmlspecialchars($dato->id_seguimiento); ?>">

                                <!-- Selección de área -->
                                <div class="mb-3">
                                    <label for="areaSelect" class="form-label">Área Destino</label>
                                    <select id="areaSelect" class="form-select" name="area" required>
                                        <option value="<?= htmlspecialchars($dato->id_area); ?>">
                                            <?= htmlspecialchars($dato->nom_area); ?></option>
                                        <?php foreach ($areas as $area): ?>
                                        <option value="<?= htmlspecialchars($area->id_area); ?>">
                                            <?= htmlspecialchars($area->nombre); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Mostrar nombre del archivo adjunto actual -->
                                <div class="mb-3">
                                    <label for="adjuntoActual" class="form-label">Archivo adjunto actual:</label>
                                    <input type="text" class="form-control" id="adjuntoActual" name="archivoActual"
                                        value="<?= !empty($dato->adjunto) ? htmlspecialchars($dato->adjunto) : ''; ?>"
                                        readonly>
                                </div>

                                <!-- Subir nuevo archivo -->
                                <div class="mb-3">
                                    <label for="archivoNuevo" class="form-label">Seleccionar nuevo archivo
                                        (opcional):</label>
                                    <input type="file" class="form-control" id="archivoNuevo" name="archivonuevo">
                                </div>

                                <!-- Respuesta -->
                                <div class="mb-3">
                                    <label for="respuesta" class="form-label">Respuesta</label>
                                    <textarea class="form-control" id="respuesta" rows="3" name="respuesta"
                                        required><?= htmlspecialchars($dato->respuesta); ?></textarea>
                                </div>

                                <!-- Botones del formulario -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
                        </div>
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
</div>
<script>
$(document).ready(function() {
    $('#examples').DataTable({
        responsive: true, // Hace la tabla adaptable
        paging: true, // Habilita la paginación
        searching: true, // Habilita el cuadro de búsqueda
        ordering: true, // Habilita el ordenamiento
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" // Traducción al español
        }
    });
});
</script>

</div>

<?php include"../etemplate/footer.php"; ?>