<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_usuario']) ) {
    header("Location: ../index.php");
    exit;
}

// Manejo de selección de libros/materiales
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['materiales'])) {
    $_SESSION['materialesSeleccionados'] = $_POST['materiales']; // Almacena los materiales seleccionados en la sesión
}

// Obtener materiales seleccionados
$materialesSeleccionados = isset($_SESSION['materialesSeleccionados']) ? $_SESSION['materialesSeleccionados'] : [];

// Obtener la lista de materiales desde la base de datos
include("../config/dbase/conexion.php");
$sql = "SELECT *
        FROM tblmateriales where disponible>0";
$datos = $con->prepare($sql);
$datos->execute();
$materiales = $datos->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container mt-2">
    <h3 class="text-center mb-2">Realizar Préstamo de Materiales</h3>
    <div class="card">
        <div class="table-responsive">
            <form method="POST" action="procesar_p_material.php"> <!-- Cambia el action según tu lógica -->
                <table class="table alter mb-0" id="example" style="width:100%; max-width:100%;">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Disp.</th>
                            <th scope="col">Estado</th>
                            <th scope="col">N° Pago</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materiales as $dato) { ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($dato->idMaterial); ?></th>
                                <td><?= htmlspecialchars($dato->nombre); ?></td>
                                <td><?= htmlspecialchars($dato->disponible); ?></td>
                                <td><?= htmlspecialchars($dato->estado); ?></td>
                                <td><?= htmlspecialchars($dato->num_pago); ?></td> <!-- Aquí se muestra la disponibilidad -->
                                <td><?= htmlspecialchars($dato->valor); ?></td>
                                <td>
                                    <!-- Verifica si el material está en la lista de seleccionados -->
                                    <input type="checkbox" name="materiales[<?= htmlspecialchars($dato->idMaterial); ?>]" value='{"titulo":"<?= addslashes($dato->nombre); ?>", "autor":"<?= addslashes($dato->cantidad); ?>", "anio":"<?= addslashes($dato->estado); ?>"}'
                                        <?php if (isset($materialesSeleccionados[$dato->idMaterial])) echo 'checked'; ?> >
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <button class="btn btn-primary mt-3" type="submit">Prestar</button>
            </form>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>
