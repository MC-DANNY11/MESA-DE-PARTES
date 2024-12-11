<?php
session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <!-- Enlace a Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Estilos generales */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #e0f7fa;
    }

    header {
        background-color: #004d40;
        color: #fff;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
    }

    header .menu-toggle {
        display: none; /* Por defecto, el ícono de menú no se muestra */
        background-color: #004d40;
        padding: 10px;
        font-size: 20px;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    /* Contenedor principal */
    .dashboard-container {
        display: flex;
    }

    .sidebar {
        width: 200px;
        background-color: #00796b;
        min-height: 100vh;
        padding: 20px;
        color: #fff;
    }

    .sidebar img {
        width: 100px;
        display: block;
        margin: 0 auto 20px;
    }

    .sidebar h3 {
        text-align: center;
        margin: 0 0 20px;
        font-size: 20px;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        border-radius: 5px;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .sidebar a i {
        margin-right: 10px;
        font-size: 20px;
    }

    .sidebar a:hover {
        background-color: #004d40;
    }

    .sidebar a:last-child {
        margin-top: 20px;
        background-color: #c62828;
    }

    .sidebar a:last-child:hover {
        background-color: #b71c1c;
    }

    .content {
        flex-grow: 1;
        padding: 20px;
    }

    .content h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #004d40;
    }

    .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .card {
        flex: 1 1 calc(25% - 20px);
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .card i {
        font-size: 30px;
        color: #00796b;
        margin-bottom: 10px;
    }

    .card a {
        text-decoration: none;
        color: #00796b;
        font-size: 16px;
        font-weight: bold;
    }
    @media (max-width: 1024px) {
        .dashboard-container {
            flex-direction: row;
            justify-content: flex-start;
        }

        .sidebar {
            width: 250px; /* Aumentar el ancho de la barra lateral */
        }

        .content {
            margin-left: 250px; /* Ajusta el margen izquierdo para que no se solapen con el menú */
        }

        .card-container {
            gap: 15px;
        }

        .card {
            flex: 1 1 calc(33.33% - 15px); /* Aumentar el tamaño de las tarjetas para pantallas medianas */
        }
    }

    /* Estilos responsivos */
    @media (max-width: 768px) {
        header .menu-toggle {
            display: block; /* Muestra el ícono del menú solo en pantallas pequeñas */
        }

        .sidebar {
            position: absolute;
            left: -100%; /* Oculta el menú lateral fuera de la pantalla */
            top: 0;
            z-index: 1000;
            width: 200px;
            transition: left 0.3s ease; /* Agrega una transición para el efecto */
        }

        .sidebar.active {
            left: 0; /* Cuando se activa, el menú lateral se muestra */
        }

        .dashboard-container {
            flex-direction: column; /* En pantallas pequeñas, el contenido se muestra en columna */
        }

        .content {
            margin-left: 0; /* Sin margen extra cuando el menú está oculto */
        }

        .sidebar.active + .content {
            margin-left: 200px; /* Cuando el menú está visible, mueve el contenido hacia la derecha */
        }
        .card-container {
        gap: 10px;
    }

    .card {
        flex: 1 1 100%; /* Las tarjetas ocupan el 100% del ancho en dispositivos pequeños */
    }
    }
</style>
</head>
<body>
    <header>
        <h1>BIENVENIDO: <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
    </header>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <img src="imagenes/ESCUDO DISTRITO DE PALCA.png" alt="Logo">
            <h3>Municipalidad Distrital De Palca</h3>
            <a href="dashboard.php"><i class="fa-solid fa-home"></i> Inicio</a>
            
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="areas/index.php"><i class="fa-solid fa-layer-group"></i>Áreas</a>
                <a href="usuarios/index.php"><i class="fa-solid fa-users"></i>Usuarios</a>
            <?php endif; ?>

            <a href="expedientes/index.php"><i class="fa-solid fa-folder"></i>Expedientes</a>
            <a href="historial/index.php"><i class="fa-solid fa-history"></i> Historial de Documentos</a>
            <a href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>

    <!-- Main Content -->
    <div class="content">
        <h2>DASHBOARD</h2>
        <div class="card-container">
            <div class="card">
                <i class="fa-solid fa-clock"></i>
                <a href="expedientes/index.php?estado=pendiente">Documentos Pendientes</a>
            </div>
            <div class="card">
                <i class="fa-solid fa-spinner"></i>
                <a href="expedientes/index.php?estado=tramitando">Documentos en Trámite</a>
            </div>
            <div class="card">
                <i class="fa-solid fa-check-circle"></i>
                <a href="expedientes/index.php?estado=atendido">Documentos Atendidos</a>
            </div>
            <div class="card">
                <i class="fa-solid fa-ban"></i>
                <a href="expedientes/index.php?estado=denegado">Documentos Denegados</a>
            </div>
        </div>
    </div>
</div>
<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active'); // Alterna la clase 'active' para mostrar/ocultar el menú
}
</script>
</body>
</html>