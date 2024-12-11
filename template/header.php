<?php
// Obtiene la página actual (sin la ruta completa)
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Gestión de Expedientes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }

        header {
            background-color: #2196F3;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .dashboard-container {
            display: flex;
            width: 100%;
        }

        .sidebar {
            background-color: #0c855d;
            color: #fff;
            width: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 10px;
            position: relative;
        }

        .sidebar img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        .sidebar h3 {
            font-size: 1rem;
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 10px 15px;
            margin: 5px 0;
            width: 100%;
            text-align: left;
            border-radius: 5px;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #ffffff;
            color: #0c855d;
        }

        .menu-toggle {
            cursor: pointer;
            font-size: 1.5rem;
            color: #ffffff;
        }

        .content {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../imagenes/ESCUDO DISTRITO DE PALCA.png" alt="Logo">
        <h3><strong>Municipalidad Distrital De Palca</strong></h3>
        <a href="../pages/index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>"><i class="fa-solid fa-home"></i> Inicio</a>
        <a href="../pages/areas.php" class="nav-link <?php echo $current_page == 'areas.php' ? 'active' : ''; ?>"><i class="fa-solid fa-layer-group"></i> Áreas</a>
        <a href="../pages/usuarios.php" class="nav-link <?php echo $current_page == 'usuarios.php' ? 'active' : ''; ?>"><i class="fa-solid fa-users"></i> Usuarios</a>
        <a href="../pages/expedientes.php" class="nav-link <?php echo $current_page == 'expedientes.php' ? 'active' : ''; ?>"><i class="fa-solid fa-folder"></i> Expedientes</a>
        <a href="../pages/historial.php" class="nav-link <?php echo $current_page == 'historial.php' ? 'active' : ''; ?>"><i class="fa-solid fa-history"></i> Historial de Documentos</a>
        <a href="../logout.php" class="nav-link <?php echo $current_page == 'logout.php' ? 'active' : ''; ?>"><i class="fa-solid fa-sign-out-alt"></i> Cerrar Sesión</a>
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
    <!-- Content -->
    <div class="content">

