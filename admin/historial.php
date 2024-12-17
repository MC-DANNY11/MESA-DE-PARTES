<?php 
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y NO tiene el rol de administrador
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_usuario']) ) {
    header("Location: ../index.php");
    exit; // Asegura que el script se detenga después de la redirección
}

// Verifica si el usuario tiene el rol de 'Bibliotecario' para mostrar un mensaje específico
if (isset($_SESSION['usuario']) && isset($_SESSION['id_usuario']) && $_SESSION['rol'] === 'Bibliotecario') {
    echo '<div class="container-fluid"><h1 class="text text-center">Esta página no está disponible</h1></div>';
    exit; // Asegura que el script se detenga después de mostrar el mensaje
}
?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdropp"
                href="#">
                <img src="../lib/img/pdf.png" alt="" style="width: 24px; height: 24px; object-fit: contain;"> Pdf
            </a>
            <a class="btn btn-outline-secondary btn-sm" href="#" data-bs-toggle="modal"
                data-bs-target="#staticBackdrope">
                <img src="../lib/img/excel.png" alt="" style="width: 24px; height: 24px; object-fit: contain;"> Excel
            </a>
        </div>
    </div>
    <!--
<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Agregar
    </button>
    -->
    <?php
    include("../config/dbase/conexion.php");
    $sql = "SELECT * FROM tblhistorialcambios";
    $datos = $con->prepare($sql);
    $datos->execute();
    $historial = $datos->fetchall(PDO::FETCH_OBJ)
        ?>

    <div class="card">
        <table class="table alter mb-0" id="example" style="width:100%; max-width:100%;">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">TBL Afectada</th>
                    <th scope="col">Cod Reg.</th>
                    <th scope="col">Tipo Cambio</th>
                    <th scope="col">Responsable</th>
                    <th scope="col">Fecha Y Hora</th>
                    <!--<th scope="col">Acciones</th>-->
                </tr>
            </thead>
            <?php
            include("../config/dbase/conexion.php");
            $sql = "SELECT U.*,H.* FROM tblhistorialcambios H JOIN tblUsuarios U ON H.usuario_responsable_id=U.idUsuario ";
            $datos = $con->prepare($sql);
            $datos->execute();
            $libros = $datos->fetchall(PDO::FETCH_OBJ)
                ?>
            <tbody>
                <?php foreach ($libros as $dato) {
                    ?>
                <tr>
                    <th scope="row"><?= $dato->idHistorialCambio; ?></th>
                    <td><?= $dato->tabla_afectada; ?></td>
                    <td><?= $dato->id_registro_afectado; ?></td>
                    <td><?= $dato->tipo_cambio; ?></td>
                    <td><?= $dato->nombres_apellidos; ?></td>
                    <td><?= $dato->fecha_cambio; ?></td>
                    <!--<td><a href="" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdropL<?= $dato->idLibro; ?>"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"
                                    style="margin-right: 5px; font-size: 16px;"></i></a>|
                            <a href="" class="btn btn-danger btn-sm"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;">
                                <i class="fa fa-trash" aria-hidden="true" style="margin-right: 5px; font-size: 16px;"></i>

                            </a>

                        </td>-->
                </tr>
                <!-- Modal -->
                <div class="modal fade" id="staticBackdropL<?= $dato->idLibro; ?>" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
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
                                            <input hidden type="text" class="form-control" name="nombre"
                                                value="<?= $dato->idLibro; ?>" required>
                                            <div class="col-md-6">
                                                <label for="validationCustom01" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" name="nombre"
                                                    value="<?= $dato->titulo; ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="validationCustom02" class="form-label">Autor</label>
                                                <input type="text" class="form-control" name="autor"
                                                    value="<?= $dato->autor; ?>" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="validationCustom01" class="form-label">Isbn</label>
                                                <input type="text" class="form-control" name="isbn"
                                                    value="<?= $dato->isbn; ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="validationCustom02" class="form-label">Año
                                                    Publicación</label>
                                                <input type="number" class="form-control" name="anho"
                                                    value="<?= $dato->año_publicacion; ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="validationCustom02" class="form-label">Cantidad </label>
                                                <input type="number" class="form-control" name="cantidad"
                                                    value="<?= $dato->cantidad_total; ?>" required>
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

<?php
    include("../config/dbase/conexion.php");
    $usua = "SELECT * FROM tblusuarios";
    $datos = $con->prepare($usua);
    $datos->execute();
    $usuarios = $datos->fetchall(PDO::FETCH_OBJ);

    $histo = "SELECT DISTINCT tabla_afectada FROM tblhistorialcambios";
$datosh = $con->prepare($histo);
$datosh->execute();
$historia = $datosh->fetchAll(PDO::FETCH_OBJ);

        ?>
<!-- modales para generar pdf y excel-->
<div class="modal fade" id="staticBackdropp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel">GENERAR REPORTE DE PRÉSTAMOS EN PDF</h4>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../TCPDF/examples/i_historial.php" method="POST" target="_blank">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="validationCustom04" class="form-label">Seleccionar Usuario - Nombres y Apelidos
                                - Rol</label>
                            <select class="form-select" id="validationCustom04" name="usuario" required>
                                <option selected disabled value="">Seleccionar Usuario - Nombres y Apelidos - Rol
                                </option>
                                <option value="Todos">Todos</option>
                                <?php foreach ($usuarios as $usuario):?>
                                <option value="<?= $usuario->idUsuario;?>">
                                    <?= $usuario->usuario. " - " .$usuario->nombres_apellidos. " - " .$usuario->rol;?>
                                </option>
                                <?php endforeach;?>
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
                        <div class="col-md-4">
                            <label for="validationCustom04" class="form-label">Seleccionar Tabla o Sección</label>
                            <select class="form-select" id="validationCustom04" name="tabla" required>
                                <option selected disabled value="">Seleccionar Tabla</option>
                                <option value="Todos">Todos</option>
                                <?php foreach ($historia as $hito): ?>
                                <option value="<?= htmlspecialchars($hito->tabla_afectada); ?>">
                                    <?= htmlspecialchars($hito->tabla_afectada); ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom02" class="form-label">Desde</label>
                            <input type="date" class="form-control" name="desde" value="">
                        </div>
                        <div class="col-md-4">
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel">GENERAR REPORTE DE PRÉSTAMOS EN EXCEL</h4>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../Excel/i_historial.php" method="POST">
                <div class="row">
                        <div class="col-md-12">
                            <label for="validationCustom04" class="form-label">Seleccionar Usuario - Nombres y Apelidos
                                - Rol</label>
                            <select class="form-select" id="validationCustom04" name="usuario" required>
                                <option selected disabled value="">Seleccionar Usuario - Nombres y Apelidos - Rol
                                </option>
                                <option value="Todos">Todos</option>
                                <?php foreach ($usuarios as $usuario):?>
                                <option value="<?= $usuario->idUsuario;?>">
                                    <?= $usuario->usuario. " - " .$usuario->nombres_apellidos. " - " .$usuario->rol;?>
                                </option>
                                <?php endforeach;?>
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
                        <div class="col-md-4">
                            <label for="validationCustom04" class="form-label">Seleccionar Tabla o Sección</label>
                            <select class="form-select" id="validationCustom04" name="tabla" required>
                                <option selected disabled value="">Seleccionar Tabla</option>
                                <option value="Todos">Todos</option>
                                <?php foreach ($historia as $hito): ?>
                                <option value="<?= htmlspecialchars($hito->tabla_afectada); ?>">
                                    <?= htmlspecialchars($hito->tabla_afectada); ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom02" class="form-label">Desde</label>
                            <input type="date" class="form-control" name="desde" value="">
                        </div>
                        <div class="col-md-4">
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
<?php include "../template/footer.php"; ?>