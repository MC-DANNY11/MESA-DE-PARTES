<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y NO tiene el rol de administrador
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_usuario']) ) {
    header("Location:../index.php");
    exit;
}

// Manejo de selección de libros
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['libros'])) {
    $_SESSION['librosSeleccionados'] = $_POST['libros']; // Almacena los libros seleccionados en la sesión
}

// Procesa la eliminación de libros si se ha enviado la solicitud para eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarLibro'])) {
    $idEliminar = $_POST['eliminarLibro'];

    if (isset($_SESSION['librosSeleccionados'][$idEliminar])) {
        unset($_SESSION['librosSeleccionados'][$idEliminar]); // Elimina el libro de la sesión
    }
}

// Obtener libros seleccionados
$librosSeleccionados = isset($_SESSION['librosSeleccionados']) ? $_SESSION['librosSeleccionados'] : [];

// Capturar el id_prestamo de la URL si está presente
$idPrestamo = isset($_GET['id_prestamo']) ? $_GET['id_prestamo'] : null;

include "../template/footer.php";
?>

<!-- Agregar SweetAlert CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<!-- Agregar SweetAlert JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<div class="container mt-2">
    <?php if (!empty($librosSeleccionados)): ?>
        <h3 class="text text-center mb-2">Libros Seleccionados para Préstamo</h3>
        <div class="card">
            <div class="table-responsive">
                <form id="prestamoForm" method="POST" action="../controller/create/procesar_prestamo.php">
                    <!-- Campo oculto con los libros seleccionados en formato JSON -->
                    <input type="hidden" name="libros" id="librosSeleccionadosInput"
                        value='<?= json_encode($librosSeleccionados) ?>'>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="dni" class="form-label">N° DNI </label>
                                <input type="number" placeholder="Ingresar DNI del solicitante" required name="dni">
                            </div>
                            <div class="col-md-4">
                                <label for="fecha" class="form-label">Fecha Préstamo</label>
                                <input readonly type="date" id="fecha" name="fecha" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="hasta" class="form-label">Fecha Devolución</label>
                                <input type="date" required name="hasta">
                            </div>
                        </div>
                    </div>
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Título</th>
                                <th scope="col">Autor</th>
                                <th scope="col">Año P.</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="librosSeleccionados">
                            <?php foreach ($librosSeleccionados as $idLibro => $libro): ?>
                                <?php $libroData = json_decode($libro); ?>
                                <tr id="fila-<?= htmlspecialchars($idLibro) ?>">
                                    <th scope="row"><?= htmlspecialchars($idLibro) ?></th>
                                    <td><?= htmlspecialchars($libroData->titulo) ?></td>
                                    <td><?= htmlspecialchars($libroData->autor) ?></td>
                                    <td><?= htmlspecialchars($libroData->anio) ?></td>
                                    <td>
                                        <input type="number" name="cantidad[<?= htmlspecialchars($idLibro) ?>]" min="1"
                                            value="1">
                                    </td>
                                    <td>
                                        <!-- Botón para eliminar libro -->
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmarEliminar(<?= htmlspecialchars($idLibro) ?>)">Quitar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <br>
                    <button class="btn btn-primary btn-sm" type="submit">Finalizar Préstamo</button> |
                    <a class="btn btn-secondary btn-sm" href="realizar_prestamo.php">Volver</a>
                </form>
            </div>
        </div>
    <?php else: ?>
        <h3 class="text text-center">No hay libros seleccionados.</h3>
        <a class="btn btn-secondary btn-sm" href="realizar_prestamo.php">Volver</a>
        <!-- Enlace para generar el PDF, pasando el id_prestamo a través de la URL -->
        <?php if (!empty($idPrestamo)): ?>
            <a class="btn btn-secondary btn-sm"
                href="../TCPDF/examples/pdf_prestamo_libros.php?id_prestamo=<?= htmlspecialchars($idPrestamo) ?>"
                target="_blank">Generar pdf</a>
        <?php endif; ?>

    <?php endif; ?>
</div>

<!-- Formulario oculto para eliminar libros -->
<form id="eliminarForm" method="POST" action="">
    <input type="hidden" name="eliminarLibro" id="eliminarLibroInput">
</form>

<script>
    // Función para confirmar y eliminar el libro
    function confirmarEliminar(idLibro) {
        swal({
            title: "¿Estás seguro?",
            text: "¡Al quitar de la lista no se registrará el libro como prestado!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, quitar!",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false
        }, function () {
            // Enviar el formulario para eliminar el libro
            document.getElementById('eliminarLibroInput').value = idLibro;
            document.getElementById('eliminarForm').submit();
        });
    }
</script>