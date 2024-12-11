<?php
// db_connection.php

$host = 'localhost';      // Host de la base de datos (en XAMPP suele ser localhost)
$dbname = 'bdmesadepartes'; // Nombre de la base de datos que creaste
$username = 'mullisaca';       // Usuario de MySQL (en XAMPP el usuario predeterminado es 'root')
$password = '123';           // Contraseña de MySQL (en XAMPP normalmente está vacía)

try {
    // Crear una conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Establecer el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si falla la conexión, muestra el error
    die("Error en la conexión: " . $e->getMessage());
}
?>
