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
            <a class="btn btn-outline-secondary btn-sm" href="../TCPDF/examples/i_libros.php" target="_blank">
                <img src="../lib/img/pdf.png" alt="" style="width: 20px; height: 20px; object-fit: contain;"> Pdf
            </a>
            <a class="btn btn-outline-secondary btn-sm" href="../Excel/i_libros.php">
                <img src="../lib/img/excel.png" alt="" style="width: 20px; height: 20px; object-fit: contain;"> Excel
            </a>
            <a class="btn btn-outline-secondary btn-sm" href="inventario_materiales.php">
                <i class="fa fa-cubes" aria-hidden="true"></i> Materiales
            </a>
            <a class="btn btn-outline-secondary btn-sm" href="inventario_bienes.php">
                <i class="fa fa-television" aria-hidden="true"></i> Bienes
            </a>
        </div>
        <h3 class="text-center flex-grow-1">Inventario de Libros</h3>
    </div>

    <?php
    include("../config/dbase/conexion.php");

    // Obtener editoriales
    $sql_editoriales = "SELECT * FROM tbleditoriales";
    $datos_editoriales = $con->prepare($sql_editoriales);
    $datos_editoriales->execute();
    $editoriales = $datos_editoriales->fetchAll(PDO::FETCH_OBJ);

    // Obtener cajas
    $sql_cajas = "SELECT * FROM tblcajas";
    $datos_cajas = $con->prepare($sql_cajas);
    $datos_cajas->execute();
    $cajas = $datos_cajas->fetchAll(PDO::FETCH_OBJ);

    // Obtener inventarios
    $sql_libros = "SELECT E.*, L.*, I.*, C.* 
               FROM tblinventarios I 
               JOIN tblLibros L ON I.libro_id = L.idLibro 
               JOIN tblcajas C ON L.idCaja = C.idCaja 
               JOIN tblEditoriales E ON L.editorial_id = E.idEditorial 
               ORDER BY I.idInventario ASC";
    $datos_libros = $con->prepare($sql_libros);
    $datos_libros->execute();
    $libros = $datos_libros->fetchAll(PDO::FETCH_OBJ);
    ?>

    <div class="card">
        <table class="table alter mb-0" id="example" style=" max-width:100%;">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Cant.</th>
                    <th scope="col">Disp.</th>
                    <th scope="col">Titulo Libro</th>
                    <th scope="col">Editorial / Autor</th>
                    <th scope="col">Edición</th>
                    <th scope="col">N° Pago</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Observación</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($libros as $dato): ?>
                    <tr>
                        <th scope="row"><?= $dato->idInventario; ?></th>
                        <td><?= $dato->cantidad_total; ?></td>
                        <td><?= $dato->disponible; ?></td>
                        <td><?= $dato->titulo; ?></td>
                        <td><?= $dato->nombre . " / " . $dato->autor; ?></td>
                        <td><?= $dato->año_publicacion; ?></td>
                        <td><?= $dato->num_pago; ?></td>
                        <td><?= $dato->estado; ?></td>
                        <td><?= $dato->valor; ?></td>
                        <td><?= $dato->observacion; ?></td>
                        <td>
                            <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdropL<?= $dato->idInventario; ?>">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a> |
                            <a href="#" data-href="../controller/delete/d_inventario.php?id=<?= $dato->idInventario; ?>"
                                class="btn btn-danger btn-sm"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"
                                onclick="EliminarInventario(event)">
                                <i class="fa fa-trash" aria-hidden="true" style="margin-right: 5px; font-size: 16px;"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdropL<?= $dato->idInventario; ?>" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Editar Datos de Inventario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="../controller/update/u_inventario.php" method="POST">
                                        <input hidden type="number" class="form-control" name="id"
                                            value="<?= $dato->idInventario; ?>" required>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>ID Libro</label>
                                                <input type="number" name="codigo" readonly value="<?= $dato->idLibro; ?>"
                                                    required>
                                            </div>
                                            <div class="col-md-5">
                                                <label>Nombre</label>
                                                <input type="text" name="nombre" value="<?= $dato->titulo; ?>" required>
                                            </div>
                                            <div class="col-md-5">
                                                <label>Autor</label>
                                                <input type="text" name="autor" value="<?= $dato->autor; ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Isbn</label>
                                                <input type="text" name="isbn" value="<?= $dato->isbn; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Año Publicación</label>
                                                <input type="number" name="anho" value="<?= $dato->año_publicacion; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Cantidad</label>
                                                <input type="number" name="cantidad" value="<?= $dato->cantidad_total; ?>"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Editorial</label>
                                                <select class="form-select" name="editorial" required>
                                                    <option value="<?= $dato->editorial_id; ?>" selected>
                                                        <?= $dato->nombre; ?>
                                                    </option>
                                                    <?php foreach ($editoriales as $editorial): ?>
                                                        <?php if ($editorial->idEditorial != $dato->editorial_id): ?>
                                                            <option value="<?= $editorial->idEditorial; ?>">
                                                                <?= $editorial->nombre; ?>
                                                            </option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label>N° Pago</label>
                                                <input type="number" name="num_pago" value="<?= $dato->num_pago; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Valor</label>
                                                <input type="number" name="valor" value="<?= $dato->valor; ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Estado</label>
                                                <select class="form-select" name="estado" required>
                                                    <option value="<?= $dato->estado; ?>"><?= $dato->estado; ?></option>
                                                    <option value="B">Bueno</option>
                                                    <option value="R">Regular</option>
                                                    <option value="M">Malo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Caja ó Sección</label>
                                                <select class="form-select" name="caja" required>
                                                    <option value="<?= $dato->idCaja; ?>" selected>
                                                        <?= $dato->nom_caja; ?>
                                                    </option>
                                                    <?php foreach ($cajas as $caja): ?>
                                                        <?php if ($caja->idCaja != $dato->idCaja): ?>
                                                            <option value="<?= $caja->idCaja; ?>">
                                                                <?= $caja->nom_caja; ?>
                                                            </option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Observación</label>
                                                <input type="text" name="observacion" value="<?= $dato->observacion; ?>">
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
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
                                <input type="text" class="form-control" id="validationCustom01" name="nombre" value=""
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom02" class="form-label">Autor</label>
                                <input type="text" class="form-control" id="validationCustom02" name="autor" value=""
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="validationCustom01" class="form-label">Isbn</label>
                                <input type="text" class="form-control" id="validationCustom01" name="isbn" value=""
                                    required>
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