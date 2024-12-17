<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y NO tiene el rol de administrador
if (!isset($_SESSION['usuario']) && !isset($_SESSION['id_usuario']) && !isset($_SESSION['rol' == 'Administrador'])) {
    header("Location:../index.php");
    exit; // Asegura que el script se detenga después de la redirección
} ?>
<div class="container-fluid mt-4">
    <button style="border-radius:10px" type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Agregar
    </button>

    <?php
    include("../config/dbase/conexion.php");
    $sql = "SELECT * FROM tblmateriales";
    $datos = $con->prepare($sql);
    $datos->execute();
    $materiales = $datos->fetchall(PDO::FETCH_OBJ)
        ?>

    <div class="card">
        <table class="table alter mb-0" id="example" style=" max-width:100%;">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Cant.</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">N° Pago</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Cond.</th>
                    <th scope="col">Valor</th>
                    <th scope="col">OBS</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($materiales as $dato) {
                    ?>
                    <tr>
                        <th scope="row"><?= $dato->idMaterial; ?></th>
                        <td><?= $dato->cantidad; ?></td>
                        <td><?= $dato->nombre; ?></td>
                        <td><?= !empty($dato->descripcion) ? $dato->descripcion : 'N/A'; ?></td>
                        <td><?= $dato->num_pago; ?></td>
                        <td><?= $dato->estado; ?></td>
                        <td><?= $dato->condicion; ?></td>
                        <td>S/ <?= $dato->valor; ?></td>
                        <td><?= $dato->observacion; ?></td>
                        <td><a href="" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdropL<?= $dato->idMaterial; ?>"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"
                                    style="margin-right: 5px; font-size: 16px;"></i></a>|
                                <a href="#" data-href="../controller/delete/d_material.php?id=<?= $dato->idMaterial; ?>"
                                    class="btn btn-danger btn-sm"
                                    style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"
                                    onclick="EliminarMaterial(event)">
                                    <i class="fa fa-trash" aria-hidden="true"
                                        style="margin-right: 5px; font-size: 16px;"></i>
                                </a>

                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdropL<?= $dato->idMaterial; ?>" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Editar Datos de Libro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="../controller/update/u_material.php" method="POST">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input hidden type="number" class="form-control" name="codigo"
                                                    value="<?= $dato->idMaterial; ?>" required>

                                                <div class="col-md-2">
                                                    <label for="validationCustom01" class="form-label">Cantidad</label>
                                                    <input type="number" class="form-control"
                                                        oninput="capitalizeWords(this)" name="cantidad" value="<?= $dato->cantidad; ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationCustom01" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" oninput="capitalizeWords(this)"
                                                        name="nombre" value="<?= $dato->nombre; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom02" class="form-label">Descripción</label>
                                                    <input type="text" class="form-control" oninput="capitalizeWords(this)"
                                                        name="descripcion" placeholder="Opcional" value="<?= $dato->descripcion; ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 mb-2">
                                                    <label for="validationCustom04" class="form-label">Estado</label>
                                                    <select class="form-select" id="validationCustom04" name="estado"
                                                        required>
                                                        <option value="<?= $dato->estado; ?>"><?= $dato->estado; ?>
                                                        </option>
                                                        <option value="B">Bueno</option>
                                                        <option value="R">Regular</option>
                                                        <option value="M">Malo</option>

                                                    </select>

                                                </div>
                                                <div class="col-md-3">
                                                    <label for="validationCustom04" class="form-label">Condición</label>
                                                    <select class="form-select" id="validationCustom04" name="condicion">
                                                        <option value="<?= $dato->condicion; ?>"><?= $dato->condicion; ?>
                                                        </option>
                                                        <option value="C">Bueno</option>
                                                        <option value="D">Regular</option>
                                                        <option value="M">Malo</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="validationCustom01" class="form-label">N°
                                                        Pago</label>
                                                    <input type="number" class="form-control" id="validationCustom01"
                                                        name="num_pago" value="<?= $dato->num_pago; ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="validationCustom01" class="form-label">Valor</label>
                                                    <input type="number" class="form-control" id="validationCustom01"
                                                        name="valor" value="<?= $dato->valor; ?>">
                                                </div>
                                            </div>
                                            <div class="row">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="validationCustom01" class="form-label">Observación</label>
                                                <input type="text" class="form-control" oninput="capitalizeWords(this)"
                                                    name="observacion" value="<?= $dato->observacion; ?>">
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Modal para agregar -->

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Agregar Nuevo Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controller/create/c_material.php" method="POST">
                    <div class="col-md-12">
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <label for="validationCustom01" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" oninput="capitalizeWords(this)"
                                    name="cantidad" value="1" required readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom01" class="form-label">Nombre</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)" name="nombre"
                                    value="" required>
                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom02" class="form-label">Descripción</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)"
                                    name="descripcion" placeholder="Opcional" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label for="validationCustom04" class="form-label">Estado</label>
                                <select class="form-select" id="validationCustom04" name="estado" required>
                                    <option selected disabled value="">Seleccionar Estado</option>
                                    <option value="B">Bueno</option>
                                    <option value="R">Regular</option>
                                    <option value="M">Malo</option>

                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="validationCustom04" class="form-label">Condición</label>
                                <select class="form-select" id="validationCustom04" name="condicion">
                                    <option selected disabled value="">Seleccionar Condición</option>
                                    <option value="C">Bueno</option>
                                    <option value="D">Regular</option>
                                    <option value="M">Malo</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="validationCustom01" class="form-label">N° Pago</label>
                                <input type="number" class="form-control" id="validationCustom01" name="num_pago"
                                    value="" placeholder="Opcional">
                            </div>
                            <div class="col-md-3">
                                <label for="validationCustom01" class="form-label">Valor</label>
                                <input type="number" class="form-control" id="validationCustom01"
                                placeholder="Opcional" name="valor" value="">
                            </div>
                        </div>
                        <div class="row">
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom01" class="form-label">Observación</label>
                            <input type="text" class="form-control" oninput="capitalizeWords(this)" name="observacion"
                                value="" placeholder="Opcional" >
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button style="border-radius:15px" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button style="border-radius:15px" type="submit" class="btn btn-primary">Agregar</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php include "../template/footer.php"; ?>