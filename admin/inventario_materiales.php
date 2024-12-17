<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y NO tiene el rol de administrador
if (!isset($_SESSION['usuario']) && !isset($_SESSION['id_usuario']) && !isset($_SESSION['rol' == 'Administrador'])) {
    header("Location:../index.php");
    exit; // Asegura que el script se detenga después de la redirección
} ?>
<div class="container-fluid mt-4">
    <!--
    <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" href="#">
        <img src="../lib/img/pdf.png" alt="" style="width: 20px; height: 20px; object-fit: contain;"> Pdf
    </a>
    <a class="btn btn-outline-secondary btn-sm" href="../Excel/registro.php" data-bs-toggle="modal"
        data-bs-target="#exampleModalexcel">
        <img src="../lib/img/excel.png" alt="" style="width: 20px; height: 20px; object-fit: contain;"> Excel
    </a>
    -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <a class="btn btn-outline-secondary btn-sm" href="../TCPDF/examples/i_materiales.php" target="_blank">
                <img src="../lib/img/pdf.png" alt="" style="width: 20px; height: 20px; object-fit: contain;"> Pdf
            </a>
            <a class="btn btn-outline-secondary btn-sm" href="../Excel/i_materiales.php">
                <img src="../lib/img/excel.png" alt="" style="width: 20px; height: 20px; object-fit: contain;"> Excel
            </a>
            <a class="btn btn-outline-secondary btn-sm" href="inventario.php">
                <i class="fa fa-book" aria-hidden="true"></i> Libros
            </a>
            <a class="btn btn-outline-secondary btn-sm" href="inventario_bienes.php">
                <i class="fa fa-television" aria-hidden="true"></i> Bienes
            </a>
        </div>
        <h3 class="text-center flex-grow-1">Inventario de Materiales y/o Maquetas</h3>
    </div>
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
    <footer class="pie-de-pagina">
        <div class="pie-contenido">
            © 2024 Mi Panel de Administración
        </div>
    </footer>
</div>

<!-- modales para generar pdf y exvel-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel">GENERAR REPORTES EN PDF</h4>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../TCPDF/examples/pdf_libros.php" method="POST" target="_blank">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">Seleccionar estado</label>
                            <select class="form-select" id="validationCustom04" name="tipo" required>
                                <option selected disabled value="">Seleccionar Opciones</option>
                                <option value="Todos">Todos</option>
                                <option value="B">Bueno</option>
                                <option value="R">Regular</option>
                                <option value="M">Malo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">Seleccionar sección</label>
                            <select class="form-select" name="caja" required>
                                <option selected disabled value="">Seleccionar Sección</option>
                                <option value="Todas">Todas las Cajas</option> <!-- Opción para todas las cajas -->
                                <?php foreach ($cajas as $caja): ?>
                                    <option value="<?= $caja->idCaja; ?>"><?= $caja->nom_caja; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn bg-gradient-primary">Generar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalexcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel">GENERAR REPORTE EN EXCEL</h4>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../Excel/libros.php" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">Seleccionar estado</label>
                            <select class="form-select" id="validationCustom04" name="tipo" required>
                                <option selected disabled value="">Seleccionar Opciones</option>
                                <option value="Todos">Todos</option>
                                <option value="B">Bueno</option>
                                <option value="R">Regular</option>
                                <option value="M">Malo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">Seleccionar sección</label>
                            <select class="form-select" name="caja" required>
                                <option selected disabled value="">Seleccionar Sección</option>
                                <option value="Todas">Todas las Cajas</option> <!-- Opción para todas las cajas -->
                                <?php foreach ($cajas as $caja): ?>
                                    <option value="<?= $caja->idCaja; ?>"><?= $caja->nom_caja; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn bg-gradient-primary">Generar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<?php include "../template/footer.php"; ?>