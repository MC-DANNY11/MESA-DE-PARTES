<?php 
session_start();
if (!empty($_POST['area']) && !empty($_POST['codigo'])){
    $area=$_POST['area'];
    $codigo=$_POST['codigo'];

    $derivar="UPDATE seguimiento set id_area=? WHERE id_seguimiento=?";
    $stmt = $pdo->prepare($derivar);
    $stmt->execute([$area, $codigo]);
    header('Location: index.php');
    exit();
}