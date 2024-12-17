<?php
include "../template/header.php";

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) && !isset($_SESSION['id_usuario']) ) {
    header("Location:../index.php");
    exit; // Asegura que el script se detenga después de la redirección
} 

// Manejo de selección de libros
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['libros'])) {
    $_SESSION['librosSeleccionados'] = $_POST['libros']; // Almacena los libros seleccionados en la sesión
}

// Obtener libros seleccionados
$librosSeleccionados = isset($_SESSION['librosSeleccionados']) ? $_SESSION['librosSeleccionados'] : [];

// Obtener la lista de libros desde la base de datos
include("../config/dbase/conexion.php");
$sql = "SELECT E.*, L.*, C.*, I.disponible
        FROM tblLibros L 
        JOIN tblcajas C ON L.idCaja = C.idCaja 
        JOIN tblEditoriales E ON L.editorial_id = E.idEditorial
        LEFT JOIN tblinventarios I ON L.idLibro = I.libro_id 
        WHERE I.disponible > 0"; // Filtrar por libros disponibles

$datos = $con->prepare($sql);
$datos->execute();
$libros = $datos->fetchAll(PDO::FETCH_OBJ);
?>
<div class="container mt-2">
    <h3 class="text text-center mb-2">Realizar Préstamo de Libros</h3>
    <div class="card">
        <div class="table-responsive">
            <form method="POST" action="procesar_prestamo.php"> <!-- Cambia el action según tu lógica -->
                <table class="table alter mb-0" id="example" style="width:100%; max-width:100%;">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Título</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Año P.</th>
                            <th scope="col">Disp.</th>
                            <th scope="col">Editorial</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros as $dato) { ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($dato->idLibro); ?></th>
                                <td><?= htmlspecialchars($dato->titulo); ?></td>
                                <td><?= htmlspecialchars($dato->autor); ?></td>
                                <td><?= htmlspecialchars($dato->año_publicacion); ?></td>
                                <td><?= htmlspecialchars($dato->disponible); ?></td> <!-- Aquí se muestra la disponibilidad -->
                                <td><?= htmlspecialchars($dato->nombre); ?></td>
                                <td>
                                    <!-- Verifica si el libro está en la lista de seleccionados -->
                                    <input type="checkbox" name="libros[<?= htmlspecialchars($dato->idLibro); ?>]" value='{"titulo":"<?= addslashes($dato->titulo); ?>", "autor":"<?= addslashes($dato->autor); ?>", "anio":"<?= addslashes($dato->año_publicacion); ?>"}'
                                        <?php if (isset($librosSeleccionados[$dato->idLibro])) echo 'checked'; ?> >
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
