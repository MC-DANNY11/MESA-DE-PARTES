<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y NO tiene el rol de administrador
if (!isset($_SESSION['usuario']) &&!isset($_SESSION['id_usuario'])&& !isset($_SESSION['rol'=='Administrador'])) {
    header("Location:../index.php");
    exit; // Asegura que el script se detenga después de la redirección
} ?>
<div class="container-fluid mt-4">
    <button style="border-radius:10px" type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Agregar
    </button>


    <div class="card">
        <table class="table alter mb-0" id="example" style="width:100%; max-width:100%;">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">DNI</th>
                    <th scope="col">Nombres Y Apellidos</th>
                    <th scope="col">Celular</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <?php
            include("../config/dbase/conexion.php");
            $sql = "SELECT * FROM tblSocios  ";
            $datos = $con->prepare($sql);
            $datos->execute();
            $socios = $datos->fetchall(PDO::FETCH_OBJ)
                ?>
            <tbody>
                <?php foreach ($socios as $dato) {
                    ?>
                    <tr>
                        <th scope="row"><?= $dato->idSocio; ?></th>
                        <th ><?= $dato->dni; ?></th>
                        <td><?= $dato->nombres." ".$dato->apellido; ?></td>
                        <td><?= $dato->celular; ?></td>
                        <td><a href="" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdropL<?= $dato->idSocio; ?>"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"
                                    style="margin-right: 5px; font-size: 16px;"></i></a>|
                                    <a href="#" data-href="../controller/delete/d_socio.php?id=<?= $dato->idSocio; ?>"
                                    class="btn btn-danger btn-sm"
                                    style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"
                                    onclick="EliminarSocio(event)">
                                    <i class="fa fa-trash" aria-hidden="true"
                                        style="margin-right: 5px; font-size: 16px;"></i>
                                </a>|<a href="realizar_prestamo.php?id=<?= $dato->idSocio; ?>" class="btn btn-secondary btn-sm" 
                                >Prestar</a>

                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdropL<?= $dato->idSocio; ?>" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Editar Datos del Socio</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="../controller/update/u_socio.php" method="POST">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input hidden type="number" class="form-control" name="codigo"
                                                    value="<?= $dato->idSocio; ?>" required>
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">DNI</label>
                                                    <input type="Number" class="form-control" name="dni"
                                                        value="<?= $dato->dni; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom02" class="form-label">Nombres</label>
                                                    <input type="text" class="form-control" name="nombres"
                                                        value="<?= $dato->nombres; ?>" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">Apellidos</label>
                                                    <input type="text" class="form-control" name="apellidos"
                                                        value="<?= $dato->apellido; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom02" class="form-label">Celular</label>
                                                    <input type="text" class="form-control" name="celular"
                                                        value="<?= $dato->celular; ?>" >
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button style="border-radius:15px" type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button style="border-radius:15px" type="submit" class="btn btn-primary">Actualizar</button>
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
                <h5 class="modal-title" id="staticBackdropLabel">Agregar Nuevo Socio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controller/create/c_socio.php" method="POST">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">DNI</label>
                                <input type="Number" class="form-control" id="validationCustom01" name="dni" value=""
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom02" class="form-label">Nombres</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)" name="nombres" value=""
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)" name="apellidos" value=""
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Celular</label>
                                <input type="text" class="form-control" id="validationCustom02" name="celular" value=""
                                placeholder="Opcional"    >
                            </div>
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