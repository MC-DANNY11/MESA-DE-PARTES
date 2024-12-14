<?php 
session_start();
if (!empty($_POST['nombre']) ){

    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $fecha_registro = date("Y-m-d H:i:s"); // Formato correcto de la fecha
    
    try {
        // Insertar el usuario
        $a_caja = "INSERT INTO tblcajas (nom_caja, descripcion) VALUES (?, ?)";
        $smt_caja = $con->prepare($a_caja);
        $smt_caja->execute([$nombre, $descripcion]);

        // Capturar el último id insertado
        $id_registro = $con->lastInsertId();
        
        // Registrar en el historial
        $tblafectada = "Editorial";
        $t_cambio = "Se agregó nueva Editorial";
        $u_responsable = $_SESSION['id_usuario']; // Asegúrate de que esté configurado
        
        $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) 
                        VALUES (?, ?, ?, ?, ?)";
        $smt_historial = $con->prepare($a_historial);
        $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha_registro]);

        // Mensaje de éxito
        $_SESSION['exito'] = "Caja o Sección registrado correctamente";
        header('location:'.$_SERVER['HTTP_REFERER']);
    } catch (PDOException $e) {
        // Manejo de errores SQL
        $_SESSION['alerta'] = "Hubo un error al registrar la Caja o Sección: " . $e->getMessage();
        header('location:'.$_SERVER['HTTP_REFERER']);
    }

} else {
    $_SESSION['alerta'] = "Debe llenar los campos requeridos.";
    header('location:'.$_SERVER['HTTP_REFERER']);
}
?>
