<?php
include "../template/header.php";

?>

<div class="container-fluid mt-4">
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Agregar
    </button>



    <div class="card">
        <table class="table alter mb-0" id="example" style="width:100%; max-width:100%;">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <?php
            include("../config/dbase/conexion.php");
            $sql = "SELECT * FROM tblcajas";
            $datos = $con->prepare($sql);
            $datos->execute();
            $cajas = $datos->fetchall(PDO::FETCH_OBJ)
                ?>
            <tbody>
                <?php foreach ($cajas as $dato) { ?>
                    <tr>
                        <th scope="row"><?= !empty($dato->idCaja) ? $dato->idCaja : 'No hay registro'; ?></th>
                        <td><?= !empty($dato->nom_caja) ? $dato->nom_caja : 'No hay registro'; ?></td>
                        <td><?= !empty($dato->descripcion) ? $dato->descripcion : 'Vacio'; ?></td>
                        <td><a data-bs-toggle="modal" data-bs-target="#staticBackdrop1<?= $dato->idCaja; ?>" href=""
                                class="btn btn-secondary btn-sm"><i class="fa fa-pencil-square-o"
                                    aria-hidden="true"></i></a>|
                                    <a href="#" data-href="../controller/delete/d_caja.php?id=<?= $dato->idCaja; ?>"
                                    class="btn btn-danger btn-sm"
                                    style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"
                                    onclick="EliminarCaja(event)">
                                    <i class="fa fa-trash" aria-hidden="true"
                                        style="margin-right: 5px; font-size: 16px;"></i>
                                </a>
                        </td>
                    </tr>

                    <!-- modal para editar datros de la editorial-->
                    <div class="modal fade" id="staticBackdrop1<?= $dato->idCaja; ?>" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Editar Datos Caja ó Secció</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row " action="../controller/update/u_caja.php" method="POST">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input hidden type="text" class="form-control" id="validationCustom01"
                                                    name="codigo" value="<?= $dato->idCaja; ?>">
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control"oninput="capitalizeWords(this)"
                                                        name="nombre" value="<?= $dato->nom_caja; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">Descripción</label>
                                                    <input type="text" class="form-control" name="descripcion" value="<?= $dato->descripcion; ?>"
                                                    oninput="capitalizeWords(this)">
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button style="border-radius:15px" type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button style="border-radius:15px" type="submit" class="btn btn-primary">Agregar</button>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Agregar Nueva Caja o Sección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controller/create/c_caja.php" method="POST">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="validationCustom01" name="nombre" value=""
                                    oninput="capitalizeWords(this)" required>
                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">Descripción</label>
                                <input type="text" class="form-control" name="descripcion" value=""
                                    placeholder="Opcional" oninput="capitalizeWords(this)">
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