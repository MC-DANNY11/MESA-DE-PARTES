<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Area</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../areas/estilos.css">
</head>
<body>
    <header>
    <h1>GESTION DE AREAS</h1>
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
    </header>
    <div class="dashboard-container" style="width: 100%;">
        <!-- Sidebar -->
        <div class="sidebar">
            <img src="../imagenes/ESCUDO DISTRITO DE PALCA.png" alt="Logo">
            <h3><strong>Municipalidad Distrital De Palca</strong></h3>
            <a href="../pages/index.php"><i class="fa-solid fa-home"></i> Inicio</a>
            <a href="../pages/areas.php"><i class="fa-solid fa-layer-group"></i>Áreas</a>
            <a href="../pages/usuarios.php"><i class="fa-solid fa-users"></i>Usuarios</a>
            <a href="../pages/expedientes.php"><i class="fa-solid fa-folder"></i>Expedientes</a>
            <a href="../pages/historial.php"><i class="fa-solid fa-history"></i> Historial de Documentos</a>
            <a href="../logout.php"><i class="fa-solid fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>        
        <!-- Main Content -->
        <div class="content">
