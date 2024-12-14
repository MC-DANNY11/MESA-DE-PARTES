<?php 
session_start();
if (!empty($_POST['cantidad']) && !empty($_POST['nombre']) && !empty($_POST['codigopatrimonial']) && !empty($_POST['estado'])){

    include '../../config/dbase/conexion.php';
    date_default_timezone_set('America/Lima');

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
    
    try {
        // Insertar el usuario
        $a_material = "INSERT INTO tblbienes (cod_patrimonial,nombre, cantidad, color, valor, estado, condicion, modelo,dimensiones) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $smt_mate = $con->prepare($a_material);
        $smt_mate->execute([$codPatrimonial,$nombre, $cantidad, $color, $valor, $estado, $condicion, $modelo,$dimensiones]);

        // Capturar el último id insertado
        $id_registro = $con->lastInsertId();
        
        // Registrar en el historial
        $tblafectada = "Bienes";
        $t_cambio = "Se agregó nuevo Bien no Depreciable";
        $u_responsable = $_SESSION['id_usuario']; // Asegúrate de que esté configurado
        
        $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) 
                        VALUES (?, ?, ?, ?, ?)";
        $smt_historial = $con->prepare($a_historial);
        $smt_historial->execute([$tblafectada, $id_registro, $t_cambio, $u_responsable, $fecha_registro]);

        // Mensaje de éxito
        $_SESSION['exito'] = "Bien no Depreciable registrado correctamente";
        header('location:'.$_SERVER['HTTP_REFERER']);
    } catch (PDOException $e) {
        // Manejo de errores SQL
        $_SESSION['alerta'] = "Hubo un error al registrar el Bien no Depreciable: " . $e->getMessage();
        header('location:'.$_SERVER['HTTP_REFERER']);
    }

} else {
    $_SESSION['alerta'] = "Debe llenar todos los campos.";
    header('location:'.$_SERVER['HTTP_REFERER']);
}
?>
