<?php
session_start();

if (!empty($_POST['nombre'])) {
    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');

    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fecha_registro = date("Y-m-d H:i:s");

    try {
        // Validar sesión de usuario
        if (!isset($_SESSION['id_usuario'])) {
            $_SESSION['alerta'] = "Error: Usuario no autenticado.";
            header('location:'.$_SERVER['HTTP_REFERER']);
            exit;
        }

        // Insertar el usuario
        $a_editorial = "INSERT INTO tblEditoriales (nombre, direccion, telefono, fecha_registro) VALUES (?, ?, ?, ?)";
        $smt_edi = $con->prepare($a_editorial);
        $smt_edi->execute([$nombre, $direccion, $telefono, $fecha_registro]);

        // Capturar el último id insertado
        $id_editorial = $con->lastInsertId();

        // Registrar en el historial
        $tblafectada = "Editorial";
        $t_cambio = "Se agregó nueva Editorial";
        $u_responsable = $_SESSION['id_usuario'];

        $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) 
                        VALUES (?, ?, ?, ?, ?)";
        $smt_historial = $con->prepare($a_historial);
        $smt_historial->execute([$tblafectada, $id_editorial, $t_cambio, $u_responsable, $fecha_registro]);

        // Mensaje de éxito
        $_SESSION['exito'] = "Editorial registrado correctamente";
        header('location:'.$_SERVER['HTTP_REFERER']);
    } catch (PDOException $e) {
        $_SESSION['alerta'] = "Hubo un error al registrar la Editorial: " . $e->getMessage();
        header('location:'.$_SERVER['HTTP_REFERER']);
    }
} else {
    $_SESSION['alerta'] = "Debe llenar todos los campos.";
    header('location:'.$_SERVER['HTTP_REFERER']);
}
?>
