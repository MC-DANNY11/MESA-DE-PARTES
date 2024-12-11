<?php include"../template/header.php"; 
 include "../config/db_connection.php";

 $areas="SELECT * FROM areas ";
 $stmt = $pdo->prepare($areas);
 $stmt->execute();
 $areas = $stmt->fetchAll(PDO::FETCH_OBJ);
?>
    <a href="reporteareasEXCEL.php" class="add-area-btn">
        <i class="fa-solid fa-file-excel"></i> Exportar a Excel
    </a>
    <a href="reporteareasPDF.php" class="add-area-btn">
        <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
    </a>
    <a href="#" class="add-area-btn" data-bs-toggle="modal" data-bs-target="#staticBackdropcrear">
        <i class="fa-solid fa-plus"></i> Agregar Área
    </a>
    <div class="table-container">
        <table>
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
                            <i class=" fa-solid fa-edit"></i>
                        </button>

                        <a href="javascript:void(0);" class="icon-btn delete-btn">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>

                <!-- Button trigger modal -->

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop<?= $area->id_area; ?>" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="../validate/update/u_area.php" method="post">
                                <div class="modal-body">
                                    <div class="row">
                                        <input hidden type="number" class="form-control" id="exampleInputPassword1"
                                            name="nombre" value="<?= $area->id_area; ?>">
                                        <div class="col-md-6 mb-2">
                                            <label for="exampleInputPassword1" class="form-label">Nombre Area</label>
                                            <input type="text" class="form-control" id="exampleInputPassword1"
                                                name="nombre" value="<?= $area->nombre; ?>">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="exampleInputPassword1" class="form-label">Descripción</label>
                                            <input type="text" class="form-control" id="exampleInputPassword1"
                                                name="descripcion" value="<?= $area->descripcion; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>">Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>">Siguiente</a>
        <?php endif; ?>
    </div>

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


<?php include"../template/footer.php"; ?>