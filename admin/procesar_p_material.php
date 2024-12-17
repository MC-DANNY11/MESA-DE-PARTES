<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y NO tiene el rol de administrador
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_usuario']) ) {
    header("Location:../index.php");
    exit;
}

// Manejo de selección de materiales
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['materiales'])) {
    $_SESSION['materialesSeleccionados'] = $_POST['materiales']; // Almacena los materiales seleccionados en la sesión
}

// Procesa la eliminación de materiales si se ha enviado la solicitud para eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarMaterial'])) {
    $idEliminar = $_POST['eliminarMaterial'];
    
    if (isset($_SESSION['materialesSeleccionados'][$idEliminar])) {
        unset($_SESSION['materialesSeleccionados'][$idEliminar]); // Elimina el material de la sesión
    }
}

// Obtener materiales seleccionados
$materialesSeleccionados = isset($_SESSION['materialesSeleccionados']) ? $_SESSION['materialesSeleccionados'] : [];
$idPrestamo = isset($_GET['id_prestamo']) ? $_GET['id_prestamo'] : null;
// Incluir la plantilla del pie de página
include "../template/footer.php";
?>

<!-- Agregar SweetAlert CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<!-- Agregar SweetAlert JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<div class="container mt-2">
    <?php if (!empty($materialesSeleccionados)): ?>
        <h3 class="text-center mb-2">Materiales Seleccionados para Préstamo</h3>
        <div class="card">
            <div class="table-responsive">
                <form id="prestamoForm" method="POST" action="../controller/create/procesar_prestamo_material.php">
                    <!-- Campo oculto con los materiales seleccionados en formato JSON -->
                    <input type="hidden" name="materiales" id="materialesSeleccionadosInput" value='<?= json_encode($materialesSeleccionados) ?>'>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="dni" class="form-label">N° DNI</label>
                                <input type="number" placeholder="Ingresar DNI del solicitante" required name="dni" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="fecha" class="form-label">Fecha Préstamo</label>
                                <input readonly type="date" id="fecha" name="fecha" value="<?= date('Y-m-d'); ?>" required class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="hasta" class="form-label">Fecha Devolución</label>
                                <input type="date" required name="hasta" class="form-control">
                            </div>
                        </div>
                    </div>
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Disponibilidad</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Cantidad a Prestar</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="materialesSeleccionados">
                            <?php foreach ($materialesSeleccionados as $idMaterial => $material): ?>
                                <?php $materialData = json_decode($material); ?>
                                <tr id="fila-<?= htmlspecialchars($idMaterial) ?>">
                                    <th scope="row"><?= htmlspecialchars($idMaterial) ?></th>
                                    <td><?= htmlspecialchars($materialData->titulo) ?></td> <!-- Mostrar el nombre del material -->
                                    <td><?= htmlspecialchars($materialData->autor) ?></td> <!-- Cantidad disponible -->
                                    <td><?= htmlspecialchars($materialData->anio) ?></td> <!-- Estado del material -->
                                    <td>
                                        <input type="number" name="cantidad[<?= htmlspecialchars($idMaterial) ?>]" min="1" value="1" class="form-control">
                                    </td>
                                    <td>
                                        <!-- Botón para eliminar material -->
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar(<?= htmlspecialchars($idMaterial) ?>)">Quitar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <br>
                    <button class="btn btn-primary btn-sm" type="submit">Finalizar Préstamo</button>
                    <a class="btn btn-secondary btn-sm" href="realizar_prestamo_material.php">Volver</a>
                </form>
            </div>
        </div>
    <?php else: ?>
        <h3 class="text-center">No hay materiales seleccionados.</h3>
        <a class="btn btn-secondary btn-sm" href="realizar_prestamo_material.php">Volver</a>
        <!-- Enlace para generar el PDF, pasando el id_prestamo a través de la URL -->
        <?php if (!empty($idPrestamo)): ?>
            <a class="btn btn-secondary btn-sm"
                href="../TCPDF/examples/pdf_prestamo_materiales.php?id_prestamo=<?= htmlspecialchars($idPrestamo) ?>"
                target="_blank">Generar pdf</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Formulario oculto para eliminar materiales -->
<form id="eliminarForm" method="POST" action="">
    <input type="hidden" name="eliminarMaterial" id="eliminarMaterialInput">
</form>

<script>
// Función para confirmar y eliminar el material
function confirmarEliminar(idMaterial) {
    swal({
        title: "¿Estás seguro?",
        text: "¡Al quitar de la lista no se registrará el material como prestado!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Sí, quitar!",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false
    }, function() {
        // Enviar el formulario para eliminar el material
        document.getElementById('eliminarMaterialInput').value = idMaterial;
        document.getElementById('eliminarForm').submit();
    });
}
</script>
