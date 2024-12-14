<?php
session_start();
if (
    !empty($_POST['usuario']) && !empty($_POST['nombres']) && !empty($_POST['correo']) && !empty($_POST['codigo'])
    && !empty($_POST['rol']) && !empty($_POST['estado'])
) {
    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');

    try {
        // Recoger los datos del formulario
        $codigo = $_POST['codigo'];
        $usuario = $_POST['usuario'];
        $nombres = $_POST['nombres'];
        $correo = $_POST['correo'];
        $rol = $_POST['rol'];
        $estado = $_POST['estado'];
        $fecha_registro = date("Y-m-d H:i:s"); // Formato correcto de la fecha

        // Iniciar una transacción
        $con->beginTransaction();

        // Actualizar el usuario
        $u_usuario = "UPDATE tblusuarios SET usuario=?, nombres_apellidos=?, correo=?, rol=?, estado=? WHERE idUsuario=?";
        $stmt_libro = $con->prepare($u_usuario);
        $resultado = $stmt_libro->execute([$usuario, $nombres, $correo, $rol, $estado, $codigo]);

        if ($resultado) {
            $id_registro = $codigo;  // Se usa el código del usuario actualizado

            // Registrar en el historial
            $tblafectada = "Usuarios";
            $t_cambio = "Se actualizó datos del Usuario";
            $u_responsable = $_SESSION['id_usuario'];

            $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
            $smt_historial = $con->prepare($a_historial);
            $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha_registro]);

            // Confirmar la transacción
            $con->commit();

            $_SESSION['exito'] = "Se ha actualizado correctamente.";
        } else {
            throw new Exception("Error al actualizar los datos.");
        }

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $con->rollBack();
        $_SESSION['alerta'] = "Hubo un error: " . $e->getMessage();
    }

    header('location:' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
} else {
    $_SESSION['alerta'] = "Todos los campos necesarios están vacíos.";
    header('location:' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
}
?>
