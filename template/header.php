<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/7d9141c5ec.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="icon" href="../imagenes/ESCUDO DISTRITO DE PALCA.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/index.css">
    <title>Municipalidad Distrital de Palca</title>
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/table.css">

    <link rel="stylesheet" href="../sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../public/app/publico/css/lib/datatables-net/datatables.min.css">
    <link rel="stylesheet" href="../public/app/publico/css/separate/vendor/datatables-net.min.css">
    <link href="../public/app/publico/css/main.css" rel="stylesheet">
    <link href="../public/app/publico/css/mis_estilos/estilos.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://kit.fontawesome.com/7d9141c5ec.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <!-- Botón para mostrar/ocultar barra lateral -->
    <div class="boton-menu">
        <i class="fa fa-bars"></i>
    </div>

    <!-- Sidebar -->
    <div class="d-flex" id="custom-wrapper">
        <div class="barra-lateral">
            <div class="logo logo-container">
                <img src="../imagenes/ESCUDO DISTRITO DE PALCA.png" alt="">
                <label>Municipalidad Distrital de PALCA</label>
            </div>
            <?php
            // Obtener la ruta actual
            $current_page = basename($_SERVER['PHP_SELF']);
            ?>

            <ul class="navegacion">
                <li class="navegacion-items">
                    <a href="index.php"
                        class="navegacion-links <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                        <i class="fa fa-home" aria-hidden="true"></i> Dashboard
                    </a>
                </li>
                <li class="navegacion-items">
                    <a href="areas.php"
                        class="navegacion-links <?php echo ($current_page == 'areas.php') ? 'active' : ''; ?>">
                        <i class="fa fa-layer-group" aria-hidden="true"></i> Áreas
                    </a>
                </li>
                <li class="navegacion-items">
                    <a href="usuarios.php"
                        class="navegacion-links <?php echo ($current_page == 'usuarios.php') ? 'active' : ''; ?>">
                        <i class="fa fa-users" aria-hidden="true"></i> Usuarios
                    </a>
                </li>
                <li class="navegacion-items">
                    <a href="expedientes.php"
                        class="navegacion-links <?php echo ($current_page == 'expedientes.php') ? 'active' : ''; ?>">
                        <i class="fa fa-folder" aria-hidden="true"></i> Expedientes
                    </a>
                </li>
                <li class="navegacion-items">
                    <a href="respuesta.php"
                        class="navegacion-links <?php echo ($current_page == 'respuesta.php') ? 'active' : ''; ?>">
                        <i class="fa fa-exchange-alt"></i>Respuestas
                    </a>
                </li>
                <li class="navegacion-items">
                    <a href="historial.php"
                        class="navegacion-links <?php echo ($current_page == 'historial.php') ? 'active' : ''; ?>">
                        <i class="fa fa-history" aria-hidden="true"></i> Historial
                    </a>
                </li>
            </ul>

            <!-- Botón de Cerrar sesión al final -->
            <a href="../index.php" class="cerrar">
                <i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar sesión
            </a>
        </div>
        <?php if (isset($_SESSION['exito'])): ?>
            <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: '<?php echo $_SESSION['exito']; ?>',
                showConfirmButton: false,
                timer: 3500
            });
            </script>
            <?php unset($_SESSION['exito']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['alerta'])): ?>
            <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: '<?php echo $_SESSION['alerta']; ?>',
                showConfirmButton: false,
                timer: 4500
            });
            </script>
            <?php unset($_SESSION['alerta']); ?>
            <?php endif; ?>
        <div id="contenido-de-la-pagina">