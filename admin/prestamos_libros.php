<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y NO tiene el rol de administrador
if (!isset($_SESSION['usuario']) && !isset($_SESSION['id_usuario']) && !isset($_SESSION['rol' == 'Administrador'])) {
    header("Location:../index.php");
    exit; // Asegura que el script se detenga después de la redirección
} ?>
<div class="container-fluid mt-4">
    <div>
        <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdropp" href="#">
            <img src="../lib/img/pdf.png" alt="" style="width: 24px; height: 24px; object-fit: contain;"> Pdf
        </a>
        <a class="btn btn-outline-secondary btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrope">
            <img src="../lib/img/excel.png" alt="" style="width: 24px; height: 24px; object-fit: contain;"> Excel
        </a>
        <a class="btn btn-primary btn-sm" href="realizar_prestamo.php">Prestar Libros</a>
        <a class="btn btn-primary btn-sm" href="realizar_prestamo_material.php">Prestar Materiales</a>
        <a class="btn btn-primary btn-sm" href="devolver_libro.php">Devolver</a>
    </div>
    <!--<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Agregar
    </button>-->

    <?php
    include("../config/dbase/conexion.php");
    $sql = "SELECT * FROM tbleditoriales";
    $datos = $con->prepare($sql);
    $datos->execute();
    $editoriales = $datos->fetchall(PDO::FETCH_OBJ)
        ?>

    <div class="card">
        <div class="table-responsive">
            <table class="table alter mb-0" id="example" style=" max-width:100%;">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Titulo Libro</th>
                        <th scope="col">Nombres y Apellidos</th>
                        <th scope="col">Prestado</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">F. Prestamo</th>
                        <th scope="col">F. Devolución</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include("../config/dbase/conexion.php");
                    $sql = "SELECT D.*, L.*, P.*, S.* 
            FROM tbldetalleprestamos D 
            JOIN tblprestamos P ON D.prestamo_id = P.idPrestamo 
            JOIN tblLibros L ON D.libro_id = L.idLibro 
            JOIN tblsocios S ON P.socio_id = S.idSocio
            ORDER BY P.idPrestamo desc";

                    $datos = $con->prepare($sql);
                    $datos->execute();
                    $libros = $datos->fetchAll(PDO::FETCH_OBJ);

                    foreach ($libros as $dato) {
                        ?>
                    <tr>
                        <th scope="row"><?= $dato->idPrestamo; ?></th>
                        <td><?= htmlspecialchars($dato->titulo); ?></td>
                        <td><?= htmlspecialchars($dato->nombres . " " . $dato->apellido); ?></td>
                        <td><?= htmlspecialchars($dato->cantidad); ?></td>
                        <td><?= htmlspecialchars($dato->isbn); ?></td>
                        <td><?= htmlspecialchars($dato->fecha_prestamo); ?></td>
                        <td><?= htmlspecialchars($dato->fecha_devolucion); ?></td>
                        <td>
                            <a <?php if ($dato->estado == 'Devuelto') : ?> class="btn btn-success btn-sm"
                                <?php else : ?> class="btn btn-danger btn-sm" <?php endif; ?> href="#">
                                <?= $dato->estado; ?>
                            </a>
                        </td>

                        <td>
                            <a href="" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdropL<?= $dato->idDetallePrestamo; ?>"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"
                                    style="margin-right: 5px; font-size: 16px;"></i>
                            </a>|
                            <a href="#"
                                data-href="../controller/delete/d_prestamo.php?id=<?= $dato->idDetallePrestamo; ?>"
                                class="btn btn-danger btn-sm"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"
                                onclick="EliminarPrestamo(event)">
                                <i class="fa fa-trash" aria-hidden="true"
                                    style="margin-right: 5px; font-size: 16px;"></i>
                            </a>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrope<?= $dato->idDetallePrestamo; ?>"
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
                                    <form action="../controller/update/u_devolucion.php" method="POST">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input hidden type="text" class="form-control" name="codigo"
                                                    value="<?= $dato->idDetallePrestamo; ?>" required
                                                    oninput="capitalizeWords(this)">
                                                <input hidden type="text" class="form-control" name="cod"
                                                    value="<?= $dato->idPrestamo; ?>" required
                                                    oninput="capitalizeWords(this)">
                                                <div class="col-md-4">
                                                    <label for="validationCustom02" class="form-label">Libro ID</label>
                                                    <input readonly type="number" class="form-control" name="id"
                                                        value="<?= $dato->libro_id; ?>" oninput="capitalizeWords(this)"
                                                        required>
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="validationCustom01" class="form-label">Titulo
                                                    </label>
                                                    <input readonly type="text" class="form-control" name="nombre"
                                                        value="<?= $dato->titulo; ?>" required
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
                                                        value="<?= $dato->cantidad; ?>" oninput="capitalizeWords(this)"
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
                    <div class="modal fade" id="staticBackdropL<?= $dato->idDetallePrestamo; ?>"
                        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Cambiar fecha de Devolución</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="../controller/update/u_prestamo.php" method="POST">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input hidden type="text" class="form-control" name="codigo"
                                                    value="<?= $dato->idDetallePrestamo; ?>" required
                                                    oninput="capitalizeWords(this)">
                                                <input hidden type="text" class="form-control" name="cod"
                                                    value="<?= $dato->idPrestamo; ?>" required
                                                    oninput="capitalizeWords(this)">
                                                <div class="col-md-2">
                                                    <label for="validationCustom02" class="form-label">Libro ID</label>
                                                    <input readonly type="number" class="form-control" name="id"
                                                        value="<?= $dato->libro_id; ?>" oninput="capitalizeWords(this)"
                                                        required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationCustom01" class="form-label">Nombre Completo
                                                        del prestatario</label>
                                                    <input readonly type="text" class="form-control" name="nombre"
                                                        value="<?= $dato->titulo; ?>" required
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
                                <button type="button" class="btn bg-gradient-info" data-bs-dismiss="modal"
                            style="background-color: #6c757d; border-color: #6c757d; color: white; border-radius:20px">Cancelar</button>

                        <button type="submit" style="color: white; border-radius:20px"
                            class="btn bg-gradient-primary">Actualizar</button>
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


<!-- modales para generar pdf y excel-->
<div class="modal fade" id="staticBackdropp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel">GENERAR REPORTE DE PRÉSTAMOS EN PDF</h4>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../TCPDF/examples/pdf_prestamos_libros.php" method="POST" target="_blank">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label">DNI ó Todos </label>
                            <input type="number" class="form-control" name="dni" placeholder="Todos ó Ingresar DNI"
                                value="">
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">Seleccionar Estado</label>
                            <select class="form-select" id="validationCustom04" name="estado" required>
                                <option selected disabled value="">Seleccionar Estado</option>
                                <option value="Todos">Todos</option>
                                <option value="Prestado">Prestado</option>
                                <option value="Devuelto">Devuelto</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="validationCustom02" class="form-label">Si no ingresa fecha especifica, se
                                generar Todas la Fechas</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label">Desde</label>
                            <input type="date" class="form-control" name="desde" value="">
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label">Hasta </label>
                            <input type="date" class="form-control" name="hasta" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-info" data-bs-dismiss="modal"
                            style="background-color: #6c757d; border-color: #6c757d; color: white; border-radius:20px">Cancelar</button>

                        <button type="submit" style="color: white; border-radius:20px"
                            class="btn bg-gradient-primary">Generar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="staticBackdrope" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel">GENERAR REPORTE DE PRÉSTAMOS EN EXCEL</h4>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../Excel/prestamo_libros.php" method="POST">
                <div class="row">
                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label">DNI ó Todos </label>
                            <input type="number" class="form-control" name="dni" placeholder="Todos ó Ingresar DNI"
                                value="">
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">Seleccionar Estado</label>
                            <select class="form-select" id="validationCustom04" name="estado" required>
                                <option selected disabled value="">Seleccionar Estado</option>
                                <option value="Todos">Todos</option>
                                <option value="Prestado">Prestado</option>
                                <option value="Devuelto">Devuelto</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="validationCustom02" class="form-label">Si no ingresa fecha especifica, se
                                generar Todas la Fechas</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label">Desde</label>
                            <input type="date" class="form-control" name="desde" value="">
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label">Hasta </label>
                            <input type="date" class="form-control" name="hasta" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-info" data-bs-dismiss="modal"
                            style="background-color: #6c757d; border-color: #6c757d; color: white; border-radius:15px">Cancelar</button>

                        <button type="submit" style="color: white; border-radius:15px"
                            class="btn bg-gradient-primary">Generar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<?php include "../template/footer.php"; ?>