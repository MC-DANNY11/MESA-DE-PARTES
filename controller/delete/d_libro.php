<?php
// Incluir el archivo de conexión a la base de datos
require '../../config/dbase/conexion.php'; // Asegúrate de tener la conexión establecida

if (isset($_GET['id'])) {
    $idLibro = $_GET['id'];

    // Prepara la consulta para eliminar el registro
    $eliminar = "DELETE FROM tbllibros WHERE idLibro = ?";
    $stmt = $con->prepare($eliminar);

    // Ejecuta la consulta y verifica si se eliminó un registro
    if ($stmt->execute([$idLibro])) {
        $_SESSION['alerta']="Se ha eliminado correctamente";
        header("Location: " . $_SERVER['HTTP_REFERER'] );
        exit();
    } else {
        $_SESSION['alerta']="No se ha podido elimar el registro";
        header("Location: " . $_SERVER['HTTP_REFERER'] );
        exit();
    }
} else {
    $_SESSION['alerta']="Error el id es incorrecto";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>