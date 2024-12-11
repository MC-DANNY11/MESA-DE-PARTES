<?php
// areas/get_area.php
session_start();
require "../config/db_connection.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$id = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM areas WHERE id_area = ?");
$stmt->execute([$id]);
$area = $stmt->fetch();

if ($area) {
    header("Content-Type: application/json");
    echo json_encode($area);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Área no encontrada"]);
}
?>
