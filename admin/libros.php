<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y NO tiene el rol de administrador
if (!isset($_SESSION['usuario']) && !isset($_SESSION['id_usuario']) && !isset($_SESSION['rol' == 'Administrador'])) {
    header("Location:../index.php");
    exit; // Asegura que el script se detenga después de la redirección
} ?>


<div class="container-fluid mt-4">
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Agregar
    </button>
    <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop1" href="#">
        <img src="../lib/img/pdf.png" alt="" style="width: 24px; height: 24px; object-fit: contain;"> Pdf
    </a>
    <a class="btn btn-outline-secondary btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop0">
        <img src="../lib/img/excel.png" alt="" style="width: 24px; height: 24px; object-fit: contain;"> Excel
    </a>



    <?php
    include("../config/dbase/conexion.php");
    $sql = "SELECT * FROM tbleditoriales";
    $datos = $con->prepare($sql);
    $datos->execute();
    $editoriales = $datos->fetchall(PDO::FETCH_OBJ)
        ?>
    <?php
    include("../config/dbase/conexion.php");
    $sql = "SELECT * FROM tblcajas";
    $datos = $con->prepare($sql);
    $datos->execute();
    $cajas = $datos->fetchall(PDO::FETCH_OBJ)
        ?>


    <div class="card">
        <div class="table-responsive">
            <table class="table alter mb-0" id="example" style=" max-width:100%;">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Autor</th>
                        <th scope="col">Isbn</th>
                        <th scope="col">Año P.</th>
                        <th scope="col">Cant.</th>
                        <th scope="col">Editorial</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <?php
                include("../config/dbase/conexion.php");
                $sql = "SELECT E.*,L.*,C.* FROM tblLibros L JOIN tblcajas C ON L.idCaja=C.idCaja JOIN tblEditoriales E ON L.editorial_id=E.idEditorial ";
                $datos = $con->prepare($sql);
                $datos->execute();
                $libros = $datos->fetchall(PDO::FETCH_OBJ)
                    ?>
                <tbody>
                    <?php foreach ($libros as $dato) {
                        ?>
                    <tr>
                        <th scope="row"><?= $dato->idLibro; ?></th>
                        <td><?= $dato->titulo; ?></td>
                        <td><?= $dato->autor; ?></td>
                        <td><?= $dato->isbn; ?></td>
                        <td><?= $dato->año_publicacion; ?></td>
                        <td><?= $dato->cantidad_total; ?></td>
                        <td><?= $dato->nombre; ?></td>
                        <td><?= $dato->state; ?></td>
                        <td><a href="" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdropL<?= $dato->idLibro; ?>"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"
                                    style="margin-right: 5px; font-size: 16px;"></i></a>|
                            <a href="#" data-href="../controller/delete/d_libro.php?id=<?= $dato->idLibro; ?>"
                                class="btn btn-danger btn-sm"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"
                                onclick="EliminarLibro(event)">
                                <i class="fa fa-trash" aria-hidden="true"
                                    style="margin-right: 5px; font-size: 16px;"></i>
                            </a>

                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdropL<?= $dato->idLibro; ?>" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Editar Datos de Libro</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="../controller/update/u_libro.php" method="POST">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input hidden type="number" class="form-control" name="codigo"
                                                    value="<?= $dato->idLibro; ?>" required>
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" name="nombre"
                                                        value="<?= $dato->titulo; ?>" required
                                                        oninput="capitalizeWords(this)">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom02" class="form-label">Autor</label>
                                                    <input type="text" class="form-control" name="autor"
                                                        value="<?= $dato->autor; ?>" oninput="capitalizeWords(this)">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="validationCustom01" class="form-label">Isbn</label>
                                                    <input type="text" class="form-control" name="isbn"
                                                        value="<?= $dato->isbn; ?>" required
                                                        oninput="capitalizeWords(this)">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationCustom02" class="form-label">Año
                                                        Publicación</label>
                                                    <input type="number" class="form-control" name="anho"
                                                        value="<?= $dato->año_publicacion; ?>"
                                                        oninput="capitalizeWords(this)">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationCustom02" class="form-label">Cantidad </label>
                                                    <input readonly type="number" class="form-control" name="cantidad"
                                                        value="<?= $dato->cantidad_total; ?>" required
                                                        oninput="capitalizeWords(this)">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="validationCustom04" class="form-label">Editorial</label>
                                                    <select class="form-select" name="editorial" required>
                                                        <!-- Primera opción: la editorial seleccionada -->
                                                        <option value="<?= $dato->editorial_id; ?>" selected>
                                                            <?= $dato->nombre; ?>
                                                        </option>

                                                        <!-- Otras opciones: todas las editoriales excepto la ya seleccionada -->
                                                        <?php foreach ($editoriales as $editorial): ?>
                                                        <?php if ($editorial->idEditorial != $dato->editorial_id): // Solo muestra las que no coinciden ?>
                                                        <option value="<?= $editorial->idEditorial; ?>">
                                                            <?= $editorial->nombre; ?>
                                                        </option>
                                                        <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom04" class="form-label">Caja ó
                                                        Sección</label>
                                                    <select class="form-select" name="caja" required>
                                                        <!-- Primera opción: la editorial seleccionada -->
                                                        <option value="<?= $dato->idCaja; ?>" selected>
                                                            <?= $dato->nom_caja; ?>
                                                        </option>

                                                        <!-- Otras opciones: todas las editoriales excepto la ya seleccionada -->
                                                        <?php foreach ($cajas as $caja): ?>
                                                        <?php if ($caja->idCaja != $dato->id_Caja): // Solo muestra las que no coinciden ?>
                                                        <option value="<?= $caja->idCaja; ?>">
                                                            <?= $caja->nom_caja; ?>
                                                        </option>
                                                        <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" style="border-radius:15px"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary"
                                        style="border-radius:15px">Actualizar</button>
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

    <div class="modal fade" id="staticBackdrop0" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="staticBackdropLabel">GENERAR REPORTES EN EXCEL</h4>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="../Excel/libros.php" method="POST" target="_blank">
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
                        <div class="row">
                            <div class="col-md-6">
                                <label for="validationCustom04" class="form-label">Editorial</label>
                                <select class="form-select" id="validationCustom04" name="editorial" required>
                                    <option selected disabled value="">Seleccionar Editorial</option>
                                    <option value="todoseditoriales">Todos</option>
                                    <?php foreach ($editoriales as $editorial): ?>
                                    <option value="<?= $editorial->idEditorial; ?>"><?= $editorial->nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="validationCustom04" class="form-label">Año</label>
                                <select class="form-select" id="validationCustom04" name="year" required
                                    style="max-height: 200px; overflow-y: auto;">
                                    <option selected disabled value="">Seleccionar Año</option>
                                    <option value="todosaños">Todos</option>
                                    <?php
                                $currentYear = date("Y"); // Año actual
                                $startYear = 1900; // Año de inicio
                                
                                // Generar todos los años desde el más reciente hasta el más antiguo en orden descendente
                                for ($year = $currentYear; $year >= $startYear; $year--) {
                                    echo "<option value=\"$year\">$year</option>";
                                }
                                ?>
                                </select </div>

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

</div>


<!-- Modal para agregar -->

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
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
                                <label for="validationCustom01" class="form-label">Título</label>
                                <input type="text" name="nombre" value="" required oninput="capitalizeWords(this)">
                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom02" class="form-label">Autor</label>
                                <input type="text" class="form-control" id="validationCustom02" name="autor" value=""
                                    placeholder="Opcional" oninput="capitalizeWords(this)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="validationCustom01" class="form-label">Isbn</label>
                                <input type="text" class="form-control" id="validationCustom01" name="isbn" value=""
                                    oninput="capitalizeWords(this)" required>
                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Año Publicación</label>
                                <input type="number" class="form-control" id="validationCustom02" name="anho" value=""
                                    placeholder="Opcional" oninput="capitalizeWords(this)">
                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Cantidad </label>
                                <input readonly type="number" class="form-control" id="validationCustom02"
                                    name="cantidad" value="1" required oninput="capitalizeWords(this)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="validationCustom04" class="form-label">Editorial</label>
                                <select class="form-select" id="validationCustom04" name="editorial" required>
                                    <option selected disabled value="">Seleccionar Editorial</option>
                                    <?php foreach ($editoriales as $editorial): ?>
                                    <option value="<?= $editorial->idEditorial; ?>"><?= $editorial->nombre; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">N° Pago </label>
                                <input type="number" class="form-control" name="num_pago" value=""
                                    placeholder="Opcional" oninput="capitalizeWords(this)">
                            </div>
                            <div class="col-md-4">
                                <label for="validationCustom02" class="form-label">Valor </label>
                                <input type="number" class="form-control" name="valor" value="" placeholder="Opcional"
                                    oninput="capitalizeWords(this)">
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
                                <label for="validationCustom04" class="form-label">Sección</label>
                                <select class="form-select" name="caja" required>
                                    <option selected disabled value="">Seleccionar Sección</option>
                                    <?php foreach ($cajas as $caja): ?>
                                    <option value="<?= $caja->idCaja; ?>"><?= $caja->nom_caja; ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom02" class="form-label">Observación </label>
                                <input type="text" class="form-control" name="observacion" value=""
                                    placeholder="Opcional" oninput="capitalizeWords(this)">
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" style="border-radius:15px"
                    data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="border-radius:15px">Agregar</button>
            </div>
            </form>
        </div>
    </div>
</div>



<!-- modales para generar pdf y excel-->
<div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                    <div class="row">
                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">Editorial</label>
                            <select class="form-select" id="validationCustom04" name="editorial" required>
                                <option selected disabled value="">Seleccionar Editorial</option>
                                <option value="todoseditoriales">Todos</option>
                                <?php foreach ($editoriales as $editorial): ?>
                                <option value="<?= $editorial->idEditorial; ?>"><?= $editorial->nombre; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="validationCustom04" class="form-label">Año</label>
                            <select class="form-select" id="validationCustom04" name="year" required
                                style="max-height: 200px; overflow-y: auto;">
                                <option selected disabled value="">Seleccionar Año</option>
                                <option value="todosaños">Todos</option>
                                <?php
                                $currentYear = date("Y"); // Año actual
                                $startYear = 1900; // Año de inicio
                                
                                // Generar todos los años desde el más reciente hasta el más antiguo en orden descendente
                                for ($year = $currentYear; $year >= $startYear; $year--) {
                                    echo "<option value=\"$year\">$year</option>";
                                }
                                ?>
                            </select </div>

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



<?php include "../template/footer.php"; ?>