<?php
session_start();
require "../config/db_connection.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

$id = $_GET["id"];
$stmt = $pdo->prepare("DELETE FROM notificaciones WHERE id_notificacion = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit();
?>
