<?php 
session_start();
if (!empty($_POST['codigo']) && !empty($_POST['cantidad']) && !empty($_POST['nombre']) && !empty($_POST['codigopatrimonial']) && !empty($_POST['estado'])){

    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');

    $codigo = $_POST['codigo'];
    $cantidad = $_POST['cantidad'];
    $codPatrimonial = $_POST['codigopatrimonial'];
    $nombre = $_POST['nombre'];
    $estado = $_POST['estado'];
    $condicion = $_POST['condicion'];
    $color = $_POST['color'];
    $valor = $_POST['valor'];
    $modelo = $_POST['modelo'];
    $dimensiones = $_POST['dimensiones'];
    $fecha_registro = date("Y-m-d H:i:s"); // Formato correcto de la fecha

    // Actualizar la caja
    $u_caja = "UPDATE tblbienes SET cod_patrimonial=?, nombre=?, cantidad=?, color=?,valor=?,estado=?,condicion=?,modelo=?,dimensiones=? WHERE idBienes=?";
    $stmt_caja = $con->prepare($u_caja);
    $resultado = $stmt_caja->execute([$codPatrimonial,$nombre, $cantidad,$color,$valor,$estado,$condicion,$modelo,$dimensiones,$codigo]);

    if($resultado){
        // Registrar en el historial
        $id_registro = $codigo;  // El ID de la caja actualizada
        $tblafectada = "Bienes";
        $t_cambio = "Se actualizó datos de un Bien no despreciable";
        $u_responsable = $_SESSION['id_usuario'];

        $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) VALUES (?, ?, ?, ?, ?)";
        $smt_historial = $con->prepare($a_historial);
        $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha]);

        $_SESSION['exito'] = "Se ha actualizado correctamente.";
    } else {
        $_SESSION['alerta'] = "Hubo un error al actualizar: " . $stmt_caja->errorInfo()[2];
    }

    // Redirigir a la página anterior o una página de respaldo
    header('location:'.(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
} else {
    $_SESSION['alerta'] = "Todos los campos necesarios están vacíos.";
    header('location:'.(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'pagina_de_redireccion.php'));
}
?>
