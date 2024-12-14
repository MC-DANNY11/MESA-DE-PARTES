<?php
session_start();
if (!empty($_POST['nombre']) && !empty($_POST['cantidad']) && !empty($_POST['autor']) && !empty($_POST['editorial']) && !empty($_POST['caja']) && !empty($_POST['estado'])){
    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');

    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $autor = $_POST['autor'];
    $editorial = $_POST['editorial'];
    $anho = $_POST['anho'];
    $isbn = $_POST['isbn'];
    $caja = $_POST['caja'];
    // Datos para actualizar en la tabla inventario
    $estado = $_POST['estado'];
    $valor = $_POST['valor'];
    $num_pago = $_POST['num_pago'];
    $observacion = $_POST['observacion'] ?? null; // Permitir que sea opcional
    $fecha_registro = date("Y-m-d H:i:s");

    // Insertar el libro
    $a_libro = "INSERT INTO tbllibros (titulo, autor, isbn, año_publicacion, editorial_id, cantidad_total, fecha_registro, idCaja) VALUES (?, ?, ?, ?, ?, ?, ?,?)";
    $smt_libros = $con->prepare($a_libro);
    $smt_libros->execute([$nombre, $autor, $isbn, $anho, $editorial, $cantidad, $fecha_registro,$caja]);

    // Verificar si la inserción fue exitosa
    if ($smt_libros) {
        // Capturar el último id insertado
        $id_registro = $con->lastInsertId();

        // Registrar en el historial
        $tblafectada = "Libros";
        $t_cambio = "Se agregó nuevo libro";
        $u_responsable = $_SESSION['id_usuario'];

        $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
        $smt_historial = $con->prepare($a_historial);
        $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha_registro]);

        $a_inventario="INSERT INTO tblinventarios (libro_id,estado , num_pago,valor, observacion,disponible,fecha_actualizacion) VALUES (?,?,?,?,?,?,?)";
        $smt_inventario = $con->prepare($a_inventario);
        $smt_inventario->execute([$id_registro, $estado, $num_pago, $valor, $observacion,$cantidad, $fecha_registro]);


        // Mensaje de éxito
        $_SESSION['exito'] = "Libro registrado correctamente";
        header('location:' . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['alerta'] = "Hubo un error al registrar el libro";
        header('location:' . $_SERVER['HTTP_REFERER']);
    }
} else {
    $_SESSION['alerta'] = "Debe llenar todos los campos.";
    header('location:' . $_SERVER['HTTP_REFERER']);
}
?>