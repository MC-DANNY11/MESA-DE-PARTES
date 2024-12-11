<?php 
session_start();
if(!empty($_POST['usuario'])&&!empty($_POST['nombres'])&&!empty($_POST['correo'])&&!empty($_POST['contra'])
&&!empty($_POST['rol'])&&!empty($_POST['area'])){
    include "../../config/db_connection.php";
    $usuario = $_POST['usuario'];
    $nombres = $_POST['nombres'];
    $correo = $_POST['correo'];
    $contra = $_POST['contra'];
    $rol = $_POST['rol'];
    $area = $_POST['area'];
    $contra = password_hash($contra, PASSWORD_DEFAULT);
    $user="INSERT INTO usuarios (nombre, nombre_usuario, correo,contraseña,rol,id_area) VALUES (?,?,?,?,?,?)";
    $stmt=$pdo->prepare($user);
    $stmt->execute([$nombres, $usuario, $correo, $contra, $rol, $area]);
    if($stmt){
        $_SESSION['exito']="Usuario registrado correctamente";
        header('location:'.$_SERVER['HTTP_REFERER']);
        exit();
    }
}
else{
    $_SESSION['error']="Todos los campos son obligatorios";
    header('location:'.$_SERVER['HTTP_REFERER']);
    exit();
}
?>