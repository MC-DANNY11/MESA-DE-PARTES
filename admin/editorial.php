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



    <div class="card">
        <table class="table alter mb-0" id="example" style=" max-width:100%;">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Fec Registro</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <?php
            include("../config/dbase/conexion.php");
            $sql = "SELECT * FROM tbleditoriales";
            $datos = $con->prepare($sql);
            $datos->execute();
            $editoriales = $datos->fetchall(PDO::FETCH_OBJ)
                ?>
            <tbody>
                <?php foreach ($editoriales as $dato) { ?>
                    <tr>
                        <th scope="row"><?= !empty($dato->idEditorial) ? $dato->idEditorial : 'No hay registro'; ?></th>
                        <td><?= !empty($dato->nombre) ? $dato->nombre : 'No hay registro'; ?></td>
                        <td><?= !empty($dato->direccion) ? $dato->direccion : 'Dirección no registrada'; ?></td>
                        <td><?= !empty($dato->telefono) ? $dato->telefono : 'Teléfono no registrada'; ?></td>
                        <td><?= !empty($dato->fecha_registro) ? $dato->fecha_registro : 'N/A'; ?></td>
                        <td>
                            <a  style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;" data-bs-toggle="modal" data-bs-target="#staticBackdrop1<?= $dato->idEditorial; ?>" href=""
                                class="btn btn-secondary btn-sm"><i class="fa fa-pencil-square-o"
                                    aria-hidden="true"></i></a>|
                                <a href="#"  data-href="../controller/delete/d_editorial.php?id=<?= $dato->idEditorial; ?>"
                                    class="btn btn-danger btn-sm"
                                    style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"
                                    onclick="EliminarEditorial(event)">
                                    <i class="fa fa-trash" aria-hidden="true"
                                        style="margin-right: 5px; font-size: 16px;"></i>
                                </a>
                        </td>
                    </tr>

                    <!-- modal para editar datros de la editorial-->
                    <div class="modal fade" id="staticBackdrop1<?= $dato->idEditorial; ?>" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Editar Datos Editorial</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row " action="../controller/update/u_editorial.php" method="POST">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input hidden type="text" class="form-control" id="validationCustom01"
                                                    name="codigo" value="<?= $dato->idEditorial; ?>">
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" oninput="capitalizeWords(this)"
                                                        name="nombre" value="<?= $dato->nombre; ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">Telefono</label>
                                                    <input type="text" class="form-control" name="telefono" value="<?= $dato->telefono; ?>"
                                                        >
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="validationCustom02" class="form-label">Dirección</label>
                                                    <input type="text" class="form-control" oninput="capitalizeWords(this)"
                                                        name="direccion" value="<?= $dato->direccion; ?>" >
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
    <footer class="pie-de-pagina">
        <div class="pie-contenido">
            © 2024 Mi Panel de Administración
        </div>
    </footer>
</div>


<!-- Modal para agregar -->

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Agregar Nueva Editorial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controller/create/c_editorial.php" method="POST">
                    <div class="col-md12">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">Nombre</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)" name="nombre" value=""
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">Telefono</label>
                                <input type="number" class="form-control" name="telefono" value="" placeholder="Opcional">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="validationCustom02" class="form-label">Dirección</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)" name="direccion"
                                    value="" placeholder="Opcional">
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