<?php
session_start();
include '../../config/dbase/conexion.php';

// Función para obtener la cantidad ya entregada
function obtenerCantidadEntregada($con, $idDetallePrestamo) {
    $sql = "SELECT entregado FROM tbldetalleprestamos WHERE idDetallePrestamo = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$idDetallePrestamo]);
    $resultado = $stmt->fetch(PDO::FETCH_OBJ);
    return $resultado->entregado;
}

// Función para actualizar la tabla de detalle de préstamos
function actualizarDetallePrestamo($con, $entregado, $idDetallePrestamo) {
    $sql = "UPDATE tbldetalleprestamos SET entregado = entregado + ? WHERE idDetallePrestamo = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$entregado, $idDetallePrestamo]);
}

// Función para actualizar el inventario
function actualizarInventario($con, $entregado, $idLibro) {
    $consultaDisponible = "SELECT disponible FROM tblinventarios WHERE libro_id = ?";
    $stmt = $con->prepare($consultaDisponible);
    $stmt->execute([$idLibro]);
    $disponible = $stmt->fetch(PDO::FETCH_OBJ);

    // Actualizar disponibilidad
    $nuevaDisponibilidad = $disponible->disponible + $entregado;
    $sqlInventario = "UPDATE tblinventarios SET disponible = ? WHERE libro_id = ?";
    $stmt = $con->prepare($sqlInventario);
    $stmt->execute([$nuevaDisponibilidad, $idLibro]);
}

// Función para actualizar el estado en tbldetalleprestamos
function actualizarEstadoDetallePrestamo($con, $estado, $idDetallePrestamo) {
    $sqlEstado = "UPDATE tbldetalleprestamos SET estado = ? WHERE idDetallePrestamo = ?";
    $stmt = $con->prepare($sqlEstado);
    $stmt->execute([$estado, $idDetallePrestamo]);
}

// Función para crear un nuevo historial
date_default_timezone_set('America/Lima');
$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['id_usuario'];
$tabla = 'Prestamos';
$tipCambio = 'Devolución de libros';

function crearHistorial($con, $tabla, $idPrestamo, $tipCambio, $idUsuario, $fecha) {
    $sqlHistorial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sqlHistorial);
    $stmt->execute([$tabla, $idPrestamo, $tipCambio, $idUsuario, $fecha]);
}

// Procesar datos enviados desde el formulario
if (!empty($_POST['devolver']) && is_array($_POST['devolver'])) {
    foreach ($_POST['devolver'] as $idDetallePrestamo) {
        // Obtener información del préstamo
        $sqlDetalle = "SELECT * FROM tbldetalleprestamos WHERE idDetallePrestamo = ?";
        $stmt = $con->prepare($sqlDetalle);
        $stmt->execute([$idDetallePrestamo]);
        $detalle = $stmt->fetch(PDO::FETCH_OBJ);

        if ($detalle) {
            $idLibro = $detalle->libro_id;
            $cantidadPrestada = $detalle->cantidad;
            $cantidadEntregada = $detalle->entregado;

            $entregado = $cantidadPrestada - $cantidadEntregada; // Cantidad restante por entregar
            $estado = 'Devuelto';

            if ($entregado > 0) {
                // Actualizar detalles y disponibilidad
                actualizarDetallePrestamo($con, $entregado, $idDetallePrestamo);
                actualizarInventario($con, $entregado, $idLibro);
                actualizarEstadoDetallePrestamo($con, $estado, $idDetallePrestamo);
                crearHistorial($con, $tabla, $detalle->prestamo_id, $tipCambio, $idUsuario, $fecha);
            }
        }
    }

    // Redirigir con mensaje de éxito
    $_SESSION['exito'] = "Los libros seleccionados se devolvieron correctamente.";
    header('location:' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'prestamos_libros.php'));
    exit();
} else {
    // Redirigir con mensaje de error si no se seleccionó nada
    $_SESSION['alerta'] = "No se seleccionaron libros para devolver.";
    header('location:' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'prestamos_libros.php'));
    exit();
}
?>
