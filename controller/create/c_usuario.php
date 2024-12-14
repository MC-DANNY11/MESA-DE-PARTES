<?php 
session_start();
if (!empty($_POST['usuario']) && !empty($_POST['nombres']) && !empty($_POST['correo']) && !empty($_POST['contrasena'])
    && !empty($_POST['rol']) && !empty($_POST['estado'])){

    include '../../config/dbase/conexion.php';

    date_default_timezone_set('America/Lima');
    $usuario = $_POST['usuario'];
    $nombres = $_POST['nombres'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT); 
    $rol = $_POST['rol'];
    $estado = $_POST['estado'];
    $fecha_registro = date("Y-m-d H:i:s"); 
    
    try {
        // Verificar si el usuario o correo ya existen
        $verificar = "SELECT COUNT(*) FROM tblUsuarios WHERE usuario = ? OR correo = ?";
        $stmt_verificar = $con->prepare($verificar);
        $stmt_verificar->execute([$usuario, $correo]);
        $existe = $stmt_verificar->fetchColumn();

        if ($existe > 0) {
            // Si el usuario o correo ya existen, mostrar mensaje de error
            $_SESSION['alerta'] = "El usuario o correo ya existen.";
            header('location:'.$_SERVER['HTTP_REFERER']);
            exit;
        }

        // Insertar el usuario
        $a_usuario = "INSERT INTO tblUsuarios (usuario, nombres_apellidos, correo, contrasenha, rol, estado, fecha_registro) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $smt_user = $con->prepare($a_usuario);
        $smt_user->execute([$usuario, $nombres, $correo, $contrasena, $rol, $estado, $fecha_registro]);

        // Capturar el último id insertado
        $id_registro = $con->lastInsertId();
        
        // Registrar en el historial
        $tblafectada = "Usuarios";
        $t_cambio = "Se agregó nuevo Usuario";
        $u_responsable = $_SESSION['id_usuario']; // Asegúrate de que esté configurado
        
        $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) 
                        VALUES (?, ?, ?, ?, ?)";
        $smt_historial = $con->prepare($a_historial);
        $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha_registro]);

        // Mensaje de éxito
        $_SESSION['exito'] = "Usuario registrado correctamente";
        header('location:'.$_SERVER['HTTP_REFERER']);
    } catch (PDOException $e) {
        // Manejo de errores SQL
        $_SESSION['alerta'] = "Hubo un error al registrar el Usuario: " . $e->getMessage();
        header('location:'.$_SERVER['HTTP_REFERER']);
    }

} else {
    $_SESSION['alerta'] = "Debe llenar todos los campos.";
    header('location:'.$_SERVER['HTTP_REFERER']);
}
?>
