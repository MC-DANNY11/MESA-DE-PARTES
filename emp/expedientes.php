<?php include "../etemplate/header.php"; ?>

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
                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $dato->id_expediente; ?>"
                            class="btn btn-primary btn-sm" title="Derivar expediente">
                            <i class="fas fa-exchange-alt"></i>
                        </a>|
                        <a href="#" data-href="../validate/delete/d_expediente.php?id=<?= $dato->id_expediente; ?>"
                        class="btn btn-danger btn-sm"class="icon-btn delete-btn" onclick="EliminarExpediente(event)">
                        <i class="fa fa-trash" aria-hidden="true" style="margin-right: 5px; font-size: 16px;"></i>
                    </a>|
                        <?php if (!empty($dato->archivo) && file_exists("../mesa_virtual/uploads/" . $dato->archivo)): ?>
                        <a href="../mesa_virtual/uploads/<?= htmlspecialchars(basename($dato->archivo)); ?>"
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
            <div class="modal fade" id="exampleModal<?= $dato->id_expediente; ?>" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Derivar Expediente</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../validate/u_derivar.php" method="post"  enctype="multipart/form-data">
                                <input hidden type="number" name="codigo"
                                    value="<?= htmlspecialchars($dato->id_expediente); ?>">
                                <div class="mb-3">
                                    <label for="areaSelect" class="form-label">Área Destino</label>
                                    <select id="areaSelect" class="form-select" name="area">
                                        <option value="">Seleccione un área</option>
                                        <?php foreach ($areas as $area): ?>
                                        <option value="<?= $area->id_area; ?>"><?= htmlspecialchars($area->nombre); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="exampleInputPassword1" class="form-label">Selecionar PDF:</label>
                                    <input type="file" class="form-control" id="exampleInputPassword1" name="archivo">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="exampleInputPassword1" class="form-label">Respuesta</label>
                                    <textarea class="form-control" id="textarea" rows="3" name="respuesta"></textarea>
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
<div class="modal fade" id="staticBackdropcrear" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../validate/create/c_area.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="exampleInputPassword1" class="form-label">Nombre Area</label>
                            <input type="text" class="form-control" id="exampleInputPassword1" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="exampleInputPassword1" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="exampleInputPassword1" name="descripcion">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<?php include"../etemplate/footer.php"; ?>