<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y NO tiene el rol de administrador
if (!isset($_SESSION['usuario']) && !isset($_SESSION['id_usuario']) && !isset($_SESSION['rol' == 'Administrador'])) {
    header("Location:../index.php");
    exit; // Asegura que el script se detenga después de la redirección
} ?>
<div class="container-fluid mt-4">
    <div>
    <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" href="#">
        <img src="../lib/img/pdf.png" alt="" style="width: 24px; height: 24px; object-fit: contain;"> Pdf
    </a>
    <a class="btn btn-outline-secondary btn-sm" href="../Excel/registro.php" data-bs-toggle="modal"
        data-bs-target="#exampleModalexcel">
        <img src="../lib/img/excel.png" alt="" style="width: 24px; height: 24px; object-fit: contain;"> Excel
    </a>
        <a class="btn btn-primary btn-sm" href="realizar_prestamo.php">Prestar Libros</a>
        <a class="btn btn-primary btn-sm" href="realizar_prestamo_material.php">Prestar Materiales</a>
    </div>
    <!--<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Agregar
    </button>-->

   

    <div class="card">
        <div class="table-responsive">
            <table class="table alter mb-0" id="example" style=" max-width:100%;">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre Material</th>
                        <th scope="col">Nombres y Apellidos</th>
                        <th scope="col">Prestado</th>
                        <th scope="col">Entregado</th>
                        <th scope="col">F. Prestamo</th>
                        <th scope="col">F. Devolución</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include("../config/dbase/conexion.php");
                    $sql = "SELECT D.idDPMaterial, D.estado as esMaterial, D.entregado,D.cantidad as canPrestado, M.*, P.*, S.* 
            FROM tbl_dp_material D 
            JOIN tbl_p_material P ON D.idPMaterial = P.idPMaterial 
            JOIN tblmateriales M ON D.idMaterial = M.idMaterial 
            JOIN tblsocios S ON P.idSocio = S.idSocio";

                    $datos = $con->prepare($sql);
                    $datos->execute();
                    $materiales = $datos->fetchAll(PDO::FETCH_OBJ);

                    foreach ($materiales as $dato) {
                        ?>
                    <tr>
                        <th scope="row"><?= $dato->idPMaterial; ?></th>
                        <td><?= htmlspecialchars($dato->nombre); ?></td>
                        <td><?= htmlspecialchars($dato->nombres . " " . $dato->apellido); ?></td>
                        <td><?= htmlspecialchars($dato->canPrestado); ?></td>
                        <td><?= htmlspecialchars($dato->entregado); ?></td>
                        <td><?= htmlspecialchars($dato->fecha_prestamo); ?></td>
                        <td><?= htmlspecialchars($dato->fecha_devolucion); ?></td>
                        <td>
                            <a <?php if ($dato->esMaterial == 'Devuelto') : ?> class="btn btn-success btn-sm disabled"
                                <?php else : ?> class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrope<?= $dato->idDPMaterial; ?>" <?php endif; ?>
                                href="#">
                                <?= $dato->esMaterial; ?>
                            </a>
                        </td>

                        <td>
                            <a href="" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdropL<?= $dato->idDPMaterial; ?>"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"
                                    style="margin-right: 5px; font-size: 16px;"></i>
                            </a>|
                            <a href="#"
                                data-href="../controller/delete/d_prestamo_material.php?id=<?= $dato->idDPMaterial; ?>"
                                class="btn btn-danger btn-sm"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"
                                onclick="EliminarPrestamoMaterial(event)">
                                <i class="fa fa-trash" aria-hidden="true"
                                    style="margin-right: 5px; font-size: 16px;"></i>
                            </a>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrope<?= $dato->idDPMaterial; ?>"
                        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Devolución de prestamo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="../controller/update/u_devolucion_material.php" method="POST">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input hidden type="text" class="form-control" name="codigo"
                                                    value="<?= $dato->idDPMaterial; ?>" required
                                                    oninput="capitalizeWords(this)">
                                                <input hidden type="text" class="form-control" name="cod"
                                                    value="<?= $dato->idPMaterial; ?>" required
                                                    oninput="capitalizeWords(this)">
                                                <div class="col-md-4">
                                                    <label for="validationCustom02" class="form-label">Material ID</label>
                                                    <input readonly type="number" class="form-control" name="id"
                                                        value="<?= $dato->idMaterial; ?>" oninput="capitalizeWords(this)"
                                                        required>
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="validationCustom01" class="form-label">Nom Material
                                                    </label>
                                                    <input readonly type="text" class="form-control" name="nombre"
                                                        value="<?= $dato->nombre; ?>" required
                                                        oninput="capitalizeWords(this)">
                                                </div>


                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">Nombre Completo
                                                    </label>
                                                    <input readonly type="text" class="form-control" name="nombre"
                                                        value="<?= $dato->nombres . " .$dato->apellido"; ?>" required
                                                        oninput="capitalizeWords(this)">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="validationCustom02" class="form-label">Cantidad</label>
                                                    <input readonly type="number" class="form-control" name="cantidad"
                                                        value="<?= $dato->canPrestado; ?>" oninput="capitalizeWords(this)"
                                                        required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="validationCustom02" class="form-label">Entregar</label>
                                                    <input type="number" class="form-control" name="entregado" value=""
                                                        oninput="capitalizeWords(this)">
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Devolver</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="staticBackdropL<?= $dato->idDPMaterial; ?>"
                        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Cambiar fecha de Devolución</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="../controller/update/u_prestamo_material.php" method="POST">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input hidden type="text" class="form-control" name="codigo"
                                                    value="<?= $dato->idDPMaterial; ?>" required
                                                    oninput="capitalizeWords(this)">
                                                <input hidden type="text" class="form-control" name="cod"
                                                    value="<?= $dato->idPMaterial; ?>" required
                                                    oninput="capitalizeWords(this)">
                                                <div class="col-md-2">
                                                    <label for="validationCustom02" class="form-label">Material ID</label>
                                                    <input readonly type="number" class="form-control" name="id"
                                                        value="<?= $dato->idMaterial; ?>" oninput="capitalizeWords(this)"
                                                        required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationCustom01" class="form-label">Nombre Completo
                                                        del prestatario</label>
                                                    <input readonly type="text" class="form-control" name="nombre"
                                                        value="<?= $dato->nombre; ?>" required
                                                        oninput="capitalizeWords(this)">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">Nombre Completo
                                                        del prestatario</label>
                                                    <input readonly type="text" class="form-control" name="nombre"
                                                        value="<?= $dato->nombres . " .$dato->apellido"; ?>" required
                                                        oninput="capitalizeWords(this)">
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="validationCustom02" class="form-label">Cantidad</label>
                                                    <input readonly type="number" class="form-control" name="cantidad"
                                                        value="<?= $dato->cantidad; ?>" oninput="capitalizeWords(this)"
                                                        required>
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="validationCustom01" class="form-label">Fecha
                                                        Préstamo</label>
                                                    <input readonly type="date" class="form-control" name="desde"
                                                        value="<?= isset($dato->fecha_prestamo) ? date('Y-m-d', strtotime($dato->fecha_prestamo)) : ''; ?>"
                                                        required>
                                                </div>
                                                <div class="col-md-5">
                                                    <label for="validationCustom02" class="form-label">Fecha
                                                        Devolución</label>
                                                    <input type="date" class="form-control" name="hasta"
                                                        value="<?= isset($dato->fecha_devolucion) ? date('Y-m-d', strtotime($dato->fecha_devolucion)) : ''; ?>"
                                                        required>
                                                </div>
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
</div>


<!-- Modal para agregar -->

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Agregar Nuevo Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controller/create/c_libro.php" method="POST">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">Nombre</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)" name="nombre"
                                    value="" required>
                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom02" class="form-label">Autor</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)" name="autor"
                                    value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="validationCustom01" class="form-label">Isbn</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)" name="isbn"
                                    value="" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Año Publicación</label>
                                <input type="number" class="form-control" id="validationCustom02" name="anho" value=""
                                    required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Cantidad </label>
                                <input type="number" class="form-control" id="validationCustom02" name="cantidad"
                                    value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="validationCustom04" class="form-label">Editorial</label>
                                <select class="form-select" id="validationCustom04" name="editorial" required>
                                    <option selected disabled value="">Seleccionar Editorial</option>
                                    <?php foreach ($editoriales as $editorial): ?>
                                    <option value="<?= $editorial->idEditorial; ?>"><?= $editorial->nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
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

<!-- modales para generar pdf y excel-->
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
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
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
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
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