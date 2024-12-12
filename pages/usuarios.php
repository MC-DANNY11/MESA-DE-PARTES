<?php include"../template/header.php"; 
 include "../config/db_connection.php";

 $areas = "SELECT u.*, a.nombre AS nom_area 
 FROM usuarios u 
 JOIN areas a 
 ON u.id_area = a.id_area";
$stmt = $pdo->prepare($areas);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);


 $stmt ="SELECT * FROM Areas";
 $stmt = $pdo->prepare($stmt);
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
            <?php foreach ($usuarios as $dato): ?>
            <tr>
                <td><?= $dato->id_usuario; ?></td>
                <td><?= $dato->nombre_usuario; ?></td>
                <td><?= $dato->nombre; ?></td>
                <td class="action-links">
                    <!-- Botón de editar con ícono verde -->
                    <button class="icon-btn edit-btn" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop<?= $dato->id_usuario; ?>">
                        <i class=" fa-solid fa-edit"></i>
                    </button>

                    <a href="javascript:void(0);" class="icon-btn delete-btn">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>

            <!-- Button trigger modal -->

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop<?= $dato->id_usuario; ?>" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                        <input type="text" class="form-control" id="exampleInputPassword1"
                                            name="usuario" required  value="<?= $dato->nombre_usuario; ?>"> 
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="exampleInputPassword1" class="form-label">Nombres y
                                            Apellidos</label>
                                        <input type="text" class="form-control" id="exampleInputPassword1"
                                            name="nombres" value="<?= $dato->nombre; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="exampleInputPassword1" class="form-label">Correo</label>
                                        <input type="text" class="form-control" id="exampleInputPassword1" name="correo"
                                            required value="<?= $dato->correo; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="disabledSelect" class="form-label">Seleccionar Rol</label>
                                        <select id="" class="form-select" name="rol">
                                        <option value="<?= $dato->rol; ?>"><?= $dato->rol; ?></option>
                                            <option value="admin">Administrador</option>
                                            <option value="empleado">Empleado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="disabledSelect" class="form-label">Seleccionar Area</label>
                                        <select id="" class="form-select" name="area">
                                            <option value="<?= $dato->id_area; ?>"><?= $dato->nom_area; ?></option>
                                            <?php foreach ($areas as $area) { ?>
                                            <option value="<?php echo $area->id_area;?>"><?php echo $area->nombre;?>
                                            </option>
                                            <?php }?>

                                        </select>
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


<?php include"../template/footer.php"; ?>