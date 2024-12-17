<?php 
include "../template/header.php"; 
include "../config/db_connection.php";

// Determinar cuántos resultados por página
$results_per_page = 10;

// Determinar la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Obtener el total de registros
$query_total = "SELECT COUNT(*) FROM areas";
$stmt_total = $pdo->prepare($query_total);
$stmt_total->execute();
$total_records = $stmt_total->fetchColumn();

// Calcular el número total de páginas
$total_pages = ceil($total_records / $results_per_page);

// Obtener las áreas con límite para la paginación
$query = "SELECT * FROM areas LIMIT :start_from, :results_per_page";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
$stmt->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
$stmt->execute();
$areas = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!-- Exportar a Excel y PDF -->
<a href="../EXCEL/reporteareasEXCEL.php" class="add-area-btn">
    <i class="fa-solid fa-file-excel"></i> Exportar a Excel
</a>
<a href="../PDF/reporteareasPDF.php" class="add-area-btn">
    <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
</a>
<a href="#" class="add-area-btn" data-bs-toggle="modal" data-bs-target="#staticBackdropcrear">
    <i class="fa-solid fa-plus"></i> Agregar Área
</a>

<div class="table-container">
    <table id="example">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($areas as $area): ?>
            <tr>
                <td><?= $area->id_area; ?></td>
                <td><?= $area->nombre; ?></td>
                <td><?= $area->descripcion; ?></td>
                <td class="action-links">
                    <!-- Botón de editar con ícono verde -->
                    <button class="icon-btn edit-btn" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop<?= $area->id_area; ?>">
                        <i class="fa-solid fa-edit"></i>
                    </button> |
                    <a href="#" data-href="../validate/delete/d_area.php?id=<?= $area->id_area; ?>"
                        class="icon-btn delete-btn" onclick="EliminarArea(event)">
                        <i class="fa fa-trash" aria-hidden="true" style="margin-right: 5px; font-size: 16px;"></i>
                    </a>
                </td>
            </tr>
            <!-- Modal para editar -->
            <div class="modal fade" id="staticBackdrop<?= $area->id_area; ?>" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Editar Área</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="../validate/update/u_areas.php" method="post">
                            <div class="modal-body">
                                <div class="row">
                                    <input hidden type="number" class="form-control" name="codigo" value="<?= $area->id_area; ?>">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Nombre Area</label>
                                        <input type="text" class="form-control" name="nombre" value="<?= $area->nombre; ?>">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" class="form-control" name="descripcion" value="<?= $area->descripcion; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal para agregar nueva área -->
<div class="modal fade" id="staticBackdropcrear" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Agregar Área</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../validate/create/c_area.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Nombre Area</label>
                            <input type="text" class="form-control" name="nombre"  oninput="capitalizeWords(this)" required> 
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Descripción</label>
                            <input type="text" class="form-control"  oninput="capitalizeWords(this)" name="descripcion">
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

<?php include "../template/footer.php"; ?>
