<?php 
session_start();
if (!empty($_POST['dni']) && !empty($_POST['nombres']) && !empty($_POST['apellidos'])){
    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');
    
    // Recoger los datos del formulario
    $dni = $_POST['dni'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $celular = $_POST['celular'];
    $fecha_registro = date("Y-m-d H:i:s");
    
    // Insertar el libro
    $a_socio = "INSERT INTO tblSocios (dni, nombres, apellido, celular,fecha_registro) VALUES (?, ?, ?, ?, ?)";
    $smt_socio = $con->prepare($a_socio);
    $smt_socio->execute([$dni,$nombres, $apellidos, $celular,  $fecha_registro]);
    
    // Verificar si la inserción fue exitosa
    if($smt_socio){
        // Capturar el último id insertado
        $id_registro = $con->lastInsertId();
        
        // Registrar en el historial
        $tblafectada = "Socios";
        $t_cambio = "Se agregó nuevo Socio";
        $u_responsable = $_SESSION['id_usuario'];
        
        $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
        $smt_historial = $con->prepare($a_historial);
        $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha_registro]);

        // Mensaje de éxito
        $_SESSION['exito'] = "Socio registrado correctamente";
        header('location:'.$_SERVER['HTTP_REFERER']);
    }
    else{
        $_SESSION['alerta'] = "Hubo un error al registrar al Socio";
        header('location:'.$_SERVER['HTTP_REFERER']);
    }
}
else {
    $_SESSION['alerta'] = "Debe llenar todos los campos.";
    header('location:'.$_SERVER['HTTP_REFERER']);
}
?>
