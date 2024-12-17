<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y tiene el rol adecuado
if (!isset($_SESSION['usuario']) && !isset($_SESSION['id_usuario']) && !isset($_SESSION['rol' == 'Administrador'])) {
    header("Location:../index.php");
    exit;
}
?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-2"><a class="btn btn-primary btn-sm" href="prestamos_libros.php">Volver</a></div>
        <form action="" method="post" class="row">
            <div class="col-md-3">
                <input type="number" class="form-control" id="dni" name="dni" value="" placeholder="Ingresar DNI" maxlength="8" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>
    </div>

    <?php
    include("../config/dbase/conexion.php");

    // Variable para almacenar los resultados (vacío por defecto)
    $libros = [];

    // Verifica si se ha enviado el formulario
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['dni'])) {
        $dni = $_POST['dni'];

        // Consulta con filtro por DNI y estado "Prestado"
        $sql = "SELECT D.*, L.*, P.*, S.*
                FROM tbldetalleprestamos D
                JOIN tblprestamos P ON D.prestamo_id = P.idPrestamo
                JOIN tblLibros L ON D.libro_id = L.idLibro
                JOIN tblsocios S ON P.socio_id = S.idSocio
                WHERE S.dni = :dni AND D.estado = 'Prestado'
                ORDER BY P.idPrestamo DESC";

        $datos = $con->prepare($sql);
        $datos->bindParam(':dni', $dni, PDO::PARAM_INT);
        $datos->execute();
        $libros = $datos->fetchAll(PDO::FETCH_OBJ);
    }
    ?>

    <div class="card mt-4">
        <div class="table-responsive">
            <form action="../controller/update/u_devolucion.php" method="post">
                <table class="table alter mb-0" id="example" style="width:100%; max-width:100%;">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Titulo Libro</th>
                            <th scope="col">Nombres y Apellidos</th>
                            <th scope="col">Prestado</th>
                            <th scope="col">Entregado</th>
                            <th scope="col">F. Prestamo</th>
                            <th scope="col">F. Devolución</th>
                            <th scope="col">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($libros)) : ?>
                            <?php foreach ($libros as $dato) : ?>
                                <tr>
                                    <th scope="row"><?= $dato->idPrestamo; ?></th>
                                    <td><?= htmlspecialchars($dato->titulo); ?></td>
                                    <td><?= htmlspecialchars($dato->nombres . " " . $dato->apellido); ?></td>
                                    <td><?= htmlspecialchars($dato->cantidad); ?></td>
                                    <td><?= htmlspecialchars($dato->entregado); ?></td>
                                    <td><?= htmlspecialchars($dato->fecha_prestamo); ?></td>
                                    <td><?= htmlspecialchars($dato->fecha_devolucion); ?></td>
                                    <td>
                                        <input type="checkbox" name="devolver[]" value="<?= $dato->idDetallePrestamo; ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
                                <tr>
                                    <td colspan="8" class="text-center">No se encontraron datos para el DNI ingresado o no hay préstamos con estado "Prestado".</td>
                                </tr>
                            <?php endif; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if (!empty($libros)) : ?>
                    <button type="submit" class="btn btn-primary mt-3" >Devolver Libros</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>
