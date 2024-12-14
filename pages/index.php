<?php
require "../template/header.php";



// Conexión a la base de datos
//include "../config/db_conection.php";

// Consulta SQL para contar el total de libros
//$libros = "SELECT COUNT(idLibro) AS total_libros FROM tbllibros";
//$stmt = $con->prepare($libros);
//$stmt->execute();
//
//// Obtener el resultado
//$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
//$total_libros = $resultado['total_libros']; // Guardamos el valor en una variable
//// Consulta para contar el total de editoriales
//$editoriales_query = "SELECT COUNT(idEditorial) AS total_editoriales FROM tbleditoriales";
//$stmt_editoriales = $con->prepare($editoriales_query);
//$stmt_editoriales->execute();
//$total_editoriales = $stmt_editoriales->fetch(PDO::FETCH_ASSOC)['total_editoriales'];
//
//// Consulta para contar el total de secciones
//$secciones_query = "SELECT COUNT(idCaja) AS total_secciones FROM tblcajas";
//$stmt_secciones = $con->prepare($secciones_query);
//$stmt_secciones->execute();
//$total_secciones = $stmt_secciones->fetch(PDO::FETCH_ASSOC)['total_secciones'];
//
//// Consulta para contar el total de materiales
//$materiales_query = "SELECT COUNT(idMaterial) AS total_materiales FROM tblmateriales";
//$stmt_materiales = $con->prepare($materiales_query);
//$stmt_materiales->execute();
//$total_materiales = $stmt_materiales->fetch(PDO::FETCH_ASSOC)['total_materiales'];
//
//// Consulta SQL para contar el total de libros
//$bienes = "SELECT COUNT(idBienes) AS total_bienes FROM tblbienes";
//$stmt = $con->prepare($bienes);
//$stmt->execute();
//
//// Obtener el resultado
//$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
//$total_bienes = $resultado['total_bienes']; // Guardamos el valor en una variable
//
//
//// Consulta para contar el total de editoriales
//$usuarios = "SELECT COUNT(idUsuario) AS total_usuarios FROM tblusuarios";
//$stmt = $con->prepare($usuarios);
//$stmt->execute();
//$total_usuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total_usuarios'];
//
//// Consulta para contar el total de secciones
//$secciones_query = "SELECT COUNT(idCaja) AS total_secciones FROM tblcajas";
//$stmt_secciones = $con->prepare($secciones_query);
//$stmt_secciones->execute();
//$total_secciones = $stmt_secciones->fetch(PDO::FETCH_ASSOC)['total_secciones'];
//
//// Consulta para contar el total de materiales
//$socios = "SELECT COUNT(idSocio) AS total_socios FROM tblsocios";
//$stmt = $con->prepare($socios);
//$stmt->execute();
//$total_socios = $stmt->fetch(PDO::FETCH_ASSOC)['total_socios'];
?>

<!-- Estilos para las tarjetas -->
<link rel="stylesheet" href="../css/index.css">

<!-- Contenido del dashboard -->
<div class="container-fluid mt-4">
    <div class="row">

        <div class="col-md-3">
            <a href="libros.php" class="text-decoration-none">
                <div class="custom-card">
                    <!-- Ícono en la parte superior izquierda -->
                    <div class="custom-icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <!-- Número en la parte superior derecha -->
                    <h3 class="custom-number">5</h3> <!-- Mostramos el total de libros -->
                    <!-- Texto centrado abajo -->
                    <div class="custom-text">Libros</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="editorial.php" class="text-decoration-none">
                <div class="custom-card">
                    <!-- Ícono en la parte superior izquierda -->
                    <div class="custom-icon">
                        <i class="fa fa-newspaper-o"></i>
                    </div>
                    <!-- Número en la parte superior derecha -->
                    <h3 class="custom-number">5</h3>
                    <!-- Texto centrado abajo -->
                    <div class="custom-text">Editoriales</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="caja.php" class="text-decoration-none">
                <div class="custom-card">
                    <!-- Ícono en la parte superior izquierda -->
                    <div class="custom-icon">
                        <i class="fa fa-cubes" aria-hidden="true"></i>
                    </div>
                    <!-- Número en la parte superior derecha -->
                    <h3 class="custom-number">8</h3>
                    <!-- Texto centrado abajo -->
                    <div class="custom-text">Secciónes</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="materiales.php" class="text-decoration-none">
                <div class="custom-card">
                    <!-- Ícono en la parte superior izquierda -->
                    <div class="custom-icon">
                        <i class="fa fa-gavel" aria-hidden="true"></i>
                    </div>
                    <!-- Número en la parte superior derecha -->
                    <h3 class="custom-number">6</h3>
                    <!-- Texto centrado abajo -->
                    <div class="custom-text">Materiales</div>
                </div>
            </a>
        </div>
    </div>


    <script src="../lib/js/chart.js"></script>
    <?php include "../template/footer.php"; ?>