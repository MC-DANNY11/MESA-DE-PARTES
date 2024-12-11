<?php
require "config/db_connection.php";

// Datos del nuevo usuario
$nombre = "Administrador";
$nombre_usuario = "admin";
$correo = "admin@ejemplo.com";
$contraseña = password_hash("123", PASSWORD_DEFAULT); // Cambia "tu_contraseña_segura" a la contraseña deseada
$rol = "admin";

// Inserta el usuario en la base de datos
$stmt = $pdo->prepare(
    "INSERT INTO usuarios (nombre, nombre_usuario, correo, contraseña, rol) VALUES (?, ?, ?, ?, ?)"
);
$stmt->execute([$nombre, $nombre_usuario, $correo, $contraseña, $rol]);

echo "Usuario insertado correctamente";
?>
