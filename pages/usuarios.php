<?php
include "../template/header.php";
include "../config/db_connection.php";

// Número de registros por página
$items_per_page = 10;

// Página actual
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

// Índice inicial para la consulta
$offset = ($page - 1) * $items_per_page;

// Contar el número total de registros
$count_query = "SELECT COUNT(*) AS total FROM usuarios";
$stmt = $pdo->prepare($count_query);
$stmt->execute();
$total_rows = $stmt->fetch(PDO::FETCH_OBJ)->total;

// Calcular el número total de páginas
$total_pages = ceil($total_rows / $items_per_page);

// Consultar usuarios con paginación
$areas = "SELECT u.*, a.nombre AS nom_area 
          FROM usuarios u 
          JOIN areas a ON u.id_area = a.id_area
          LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($areas);
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);

// Consultar áreas para el modal
$area_query = "SELECT * FROM areas";
$stmt = $pdo->prepare($area_query);
$stmt->execute();
$areas = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<a href="../EXCEL/reporteUsuario_excel.php" class="add-area-btn">
    <i class="fa-solid fa-file-excel"></i> Exportar a Excel
</a>
<a href="../PDF/reporteUsuario_pdf.php" class="add-area-btn">
    <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
</a>
<a href="#" class="add-area-btn" data-bs-toggle="modal" data-bs-target="#staticBackdropcrear">
    <i class="fa-solid fa-plus"></i> Agregar Usuario
</a>

<div class="table-container">
    <table id="example">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombres y Apellidos</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Area</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $dato): ?>
            <tr>
                <td><?= $dato->id_usuario; ?></td>
                <td><?= $dato->nombre_usuario; ?></td>
                <td><?= $dato->nombre; ?></td>
                <td><?= $dato->correo; ?></td>
                <td><?= $dato->rol; ?></td>
                <td><?= $dato->nom_area; ?></td>
                <td class="action-links">
                    <button class="icon-btn edit-btn" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop<?= $dato->id_usuario; ?>">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <a href="" class="icon-btn delete-btn">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>

            <!-- Modal para editar usuario -->
            <div class="modal fade" id="staticBackdrop<?= $dato->id_usuario; ?>" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Editar Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="../validate/create/c_usuario.php" method="post">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="usuario" class="form-label">Usuario</label>
                                        <input type="text" class="form-control" name="usuario" required
                                            value="<?= $dato->nombre_usuario; ?>"> 
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="nombres" class="form-label">Nombres y Apellidos</label>
                                        <input type="text" class="form-control" name="nombres" value="<?= $dato->nombre; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="correo" class="form-label">Correo</label>
                                        <input type="text" class="form-control" name="correo" required value="<?= $dato->correo; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="rol" class="form-label">Seleccionar Rol</label>
                                        <select class="form-select" name="rol">
                                            <option value="<?= $dato->rol; ?>"><?= $dato->rol; ?></option>
                                            <option value="admin">Administrador</option>
                                            <option value="empleado">Empleado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="area" class="form-label">Seleccionar Área</label>
                                        <select class="form-select" name="area">
                                            <option value="<?= $dato->id_area; ?>"><?= $dato->nom_area; ?></option>
                                            <?php foreach ($areas as $area) { ?>
                                            <option value="<?= $area->id_area; ?>"><?= $area->nombre; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>

<div class="modal fade" id="staticBackdropcrear" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../validate/create/c_usuario.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="exampleInputPassword1" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="exampleInputPassword1" name="usuario" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="exampleInputPassword1" class="form-label">Nombres y Apellidos</label>
                            <input type="text" class="form-control" id="exampleInputPassword1" name="nombres">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="exampleInputPassword1" class="form-label">Correo</label>
                            <input type="text" class="form-control" id="exampleInputPassword1" name="correo" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                            <input type="text" class="form-control" id="exampleInputPassword1" name="contra">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="disabledSelect" class="form-label">Seleccionar Rol</label>
                            <select id="" class="form-select" name="rol">
                                <option>Seleccionar Rol</option>
                                <option value="admin">Administrador</option>
                                <option value="empleado">Empleado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="disabledSelect" class="form-label">Seleccionar Area</label>
                            <select id="" class="form-select" name="area">
                                <option>Seleccionar Area</option>
                                <?php foreach ($areas as $area) { ?>
                                <option value="<?php echo $area->id_area;?>"><?php echo $area->nombre;?>
                                </option>
                                <?php }?>

                            </select>
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
