<?php
session_start();
include '../../config/dbase/conexion.php';

// Función para obtener la cantidad ya entregada
function obtenerCantidadEntregada($con, $idDPMaterial) {
    $sql = "SELECT entregado FROM tbl_dp_material WHERE idDPMaterial = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$idDPMaterial]);
    $resultado = $stmt->fetch(PDO::FETCH_OBJ);
    return $resultado ? $resultado->entregado : 0; // Maneja el caso de que no se encuentre el ID
}

// Función para actualizar la tabla de detalle de préstamos de materiales
function actualizarDetalleMaterial($con, $entregado, $idDPMaterial) {
    $sql = "UPDATE tbl_dp_material SET entregado = entregado + ? WHERE idDPMaterial = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$entregado, $idDPMaterial]);
}

// Función para actualizar el inventario de materiales
function actualizarInventarioMaterial($con, $entregado, $idMaterial) {
    $consultaDisponible = "SELECT disponible FROM tblmateriales WHERE idMaterial = ?";
    $stmt = $con->prepare($consultaDisponible);
    $stmt->execute([$idMaterial]);
    $disponible = $stmt->fetch(PDO::FETCH_OBJ);

    if ($disponible) {
        // Actualizar disponibilidad de materiales
        $nuevaDisponibilidad = $disponible->disponible + $entregado;
        $sqlInventario = "UPDATE tblmateriales SET disponible = ? WHERE idMaterial = ?";
        $stmt = $con->prepare($sqlInventario);
        $stmt->execute([$nuevaDisponibilidad, $idMaterial]);
    }
}

// Función para actualizar el estado del detalle de préstamo de materiales
function actualizarEstadoMaterial($con, $estado, $idDPMaterial) {
    $sqlEstado = "UPDATE tbl_dp_material SET estado = ? WHERE idDPMaterial = ?";
    $stmt = $con->prepare($sqlEstado);
    $stmt->execute([$estado, $idDPMaterial]);
}

// Función para crear un nuevo historial
date_default_timezone_set('America/Lima');
$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['id_usuario'];
$tabla = 'Préstamos de Materiales';
$tipCambio = 'Devolución de materiales';

function crearHistorial($con, $tabla, $idPMaterial, $tipCambio, $idUsuario, $fecha) {
    $sqlHistorial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sqlHistorial);
    $stmt->execute([$tabla, $idPMaterial, $tipCambio, $idUsuario, $fecha]);
}

// Función para redirigir con éxito o error
function redirigir($mensaje, $tipo = 'exito') {
    $_SESSION[$tipo] = $mensaje;
    header('location:' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'error.php'));
    exit();
}

if (!empty($_POST['entregado'])) {
    if (!empty($_POST['codigo']) && !empty($_POST['cod']) && !empty($_POST['id']) && !empty($_POST['cantidad'])) {
        $idDPMaterial = $_POST['codigo'];  // ID de detalle del préstamo de materiales
        $idPMaterial = $_POST['cod'];       // ID del préstamo de materiales
        $idMaterial = $_POST['id'];         // ID del material
        $cantidadPrestada = $_POST['cantidad'];  // Cantidad prestada
        $entregado = $_POST['entregado'];   // Cantidad entregada
        $estado = 'Devuelto';

        // Verifica que no se entreguen más materiales de los prestados
        if ($entregado <= 0) {
            redirigir("Ups.. :(, Lo estás haciendo mal.", 'alerta');
        }

        // Obtener la cantidad ya entregada
        $cantidadEntregada = obtenerCantidadEntregada($con, $idDPMaterial);

        // Verificar que la cantidad a entregar no exceda la cantidad prestada
        if (($entregado + $cantidadEntregada) > $cantidadPrestada) {
            redirigir("No se puede entregar más materiales de los prestados.", 'alerta');
        }

        // Si se entrega la cantidad exacta de materiales prestados
        if (($entregado + $cantidadEntregada) == $cantidadPrestada) {
            // Actualizar detalles y disponibilidad de materiales
            actualizarDetalleMaterial($con, $entregado, $idDPMaterial);
            actualizarInventarioMaterial($con, $entregado, $idMaterial);
            actualizarEstadoMaterial($con, $estado, $idDPMaterial);  // Cambiado a tbl_dp_material
            crearHistorial($con, $tabla, $idPMaterial, $tipCambio, $idUsuario, $fecha);

            redirigir("Materiales entregados correctamente.");
        } elseif (($entregado + $cantidadEntregada) < $cantidadPrestada) {
            // Si se entrega una parte de los materiales
            actualizarDetalleMaterial($con, $entregado, $idDPMaterial);
            actualizarInventarioMaterial($con, $entregado, $idMaterial);

            // Calcular la cantidad que falta por entregar
            $falta = $cantidadPrestada - ($entregado + $cantidadEntregada);
            redirigir("El material se entregó pero faltan: " . $falta . " unidades.");
        }
    } else {
        redirigir("Los campos importantes están vacíos.", 'alerta');
    }
} else {
    redirigir("No se especificó la cantidad a devolver.", 'alerta');
}
?>
