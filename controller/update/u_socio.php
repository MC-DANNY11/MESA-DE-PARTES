<?php
session_start();
if (!empty($_POST['codigo']) && !empty($_POST['dni']) && !empty($_POST['nombres']) && !empty($_POST['apellidos'])) {

    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');



    try {
        // Recoger los datos del formulario
        $codigo = $_POST['codigo'];
        $dni = $_POST['dni'];
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $celular = $_POST['celular'];
        $fecha_registro = date("Y-m-d H:i:s");

        // Iniciar una transacción
        $con->beginTransaction();

        // Actualizar el libro
        $u_socio = "UPDATE tblsocios SET dni=?, nombres=?, apellido=?, celular=? WHERE idSocio=?";
        $stmt = $con->prepare($u_socio);
        $resultado = $stmt->execute([$dni, $nombres, $apellidos, $celular, $codigo]);

        if ($resultado) {
            $id_registro = $codigo;  // Se usa el código del libro actualizado

            // Registrar en el historial
            $tblafectada = "Socios";
            $t_cambio = "Se actualizó datos del Socio";
            $u_responsable = $_SESSION['id_usuario'];

            $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
            $smt_historial = $con->prepare($a_historial);
            $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha]);

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