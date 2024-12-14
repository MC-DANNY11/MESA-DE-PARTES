<?php 
session_start();
if (!empty($_POST['cantidad']) && !empty($_POST['nombre']) && !empty($_POST['estado'])){

    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');

    $cantidad = $_POST['cantidad'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $estado = $_POST['estado'];
    $condicion = $_POST['condicion'];
    $numpago = $_POST['num_pago'];
    $valor = $_POST['valor'];
    $observacion = $_POST['observacion'];
    $fecha_registro = date("Y-m-d H:i:s"); // Formato correcto de la fecha
    
    try {
        // Insertar el usuario
        $a_material = "INSERT INTO tblmateriales (nombre, descripcion, cantidad, observacion, num_pago, estado, condicion,valor) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $smt_mate = $con->prepare($a_material);
        $smt_mate->execute([$nombre, $descripcion, $cantidad, $observacion, $numpago, $estado, $condicion,$valor]);

        // Capturar el último id insertado
        $id_registro = $con->lastInsertId();
        
        // Registrar en el historial
        $tblafectada = "Materiales";
        $t_cambio = "Se agregó nuevo Material";
        $u_responsable = $_SESSION['id_usuario']; // Asegúrate de que esté configurado
        
        $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) 
                        VALUES (?, ?, ?, ?, ?)";
        $smt_historial = $con->prepare($a_historial);
        $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha_registro]);

        // Mensaje de éxito
        $_SESSION['exito'] = "Material registrado correctamente";
        header('location:'.$_SERVER['HTTP_REFERER']);
    } catch (PDOException $e) {
        // Manejo de errores SQL
        $_SESSION['alerta'] = "Hubo un error al registrar el Material: " . $e->getMessage();
        header('location:'.$_SERVER['HTTP_REFERER']);
    }

} else {
    $_SESSION['alerta'] = "Debe llenar todos los campos.";
    header('location:'.$_SERVER['HTTP_REFERER']);
}
?>
