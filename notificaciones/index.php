<?php
// notificaciones/index.php
session_start();
require "../config/db_connection.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->query("SELECT n.id_notificacion, n.mensaje, n.fecha, u.nombre AS usuario
                     FROM notificaciones n
                     JOIN usuarios u ON n.id_usuario = u.id_usuario
                     ORDER BY n.fecha DESC");
$notificaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Notificaciones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            display: flex;
            flex-direction: column; /* Esto hace que todo se apile verticalmente */
            height: 100vh;
        }

        header {
            background-color: #004d40; /* Verde oscuro */
            color: #fff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            width: 100%;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        header .menu-toggle {
            display: none;
            background-color: #004d40;
            padding: 10px;
            font-size: 20px;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .dashboard-container {
            display: flex;
            width: 100%;
            margin-top: 80px; /* Espacio para la cabecera */
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
            padding: 30px;
            margin-left: 240px; /* Espacio para la barra lateral */
            padding-top: 30px;
        }

        .content h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #004d40;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 15px;
            transition: transform 0.3s ease;
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }

        .card h3 {
            font-size: 18px;
            color: #004d40;
            margin-top: 15px;
        }

        .card p {
            font-size: 14px;
            color: #666;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .button-add {
            display: inline-block;
            background-color: #00796b;
            color: #fff;
            padding: 12px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 16px;
            float: right;
        }

        .button-add:hover {
            background-color: #004d40;
        }

        @media (max-width: 768px) {
            header .menu-toggle {
                display: block;
            }

            .sidebar {
                position: absolute;
                left: -100%;
                top: 0;
                width: 100%;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.active {
                left: 0;
            }

            .dashboard-container {
                flex-direction: column;
            }

            .content {
                margin-left: 0;
            }

            .card-container {
                grid-template-columns: 1fr;
            }

            .button-add {
                width: 100%;
                text-align: center;
                display: block;
            }
        }
        /* Reseteo de márgenes y padding */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e0f7fa;
            display: flex;
        }

        /* Estilo del encabezado */
        .header {
            background-color: #004d40;
            color: #fff;
            padding: 20px;
            flex-grow: 1; /* Para que ocupe todo el espacio disponible */
            position: relative;
            z-index: 1;
        }

        /* Estilo de la barra lateral (menú) */
        .sidebar {
            width: 250px; /* Puedes ajustar el tamaño del menú */
            background-color: #00796b;
            min-height: 100vh;
            padding: 20px;
            color: #fff;
            position: fixed; /* Esto asegura que el menú esté fijo */
            top: 0;
            left: 0;
            z-index: 2; /* Asegura que el menú quede encima del contenido */
        }

        /* Estilo para el contenido principal */
        .content {
            margin-left: 250px; /* Espacio para la barra lateral */
            padding: 20px;
            flex-grow: 1;
        }

        /* Estilo para el botón de menú (solo en móviles) */
        .menu-toggle {
            display: none;
            background-color: #004d40;
            padding: 10px;
            font-size: 20px;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            /* Mostrar el botón de menú solo en móviles */
            .menu-toggle {
                display: block;
            }

            /* Cambiar el layout cuando es móvil */
            .sidebar {
                position: absolute;
                left: -250px;
                transition: left 0.3s ease;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0;
            }

            /* Ajustar tamaños de texto en pantallas más pequeñas */
            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="../imagenes/ESCUDO DISTRITO DE PALCA.png" alt="Logo">
        <h3>Municipalidad Distrital De Palca</h3>
        <a href="../dashboard.php"><i class="fa-solid fa-home"></i> Inicio</a>
        <a href="../areas/index.php"><i class="fa-solid fa-layer-group"></i> Gestión de Áreas</a>
        <a href="index.php"><i class="fa-solid fa-users"></i> Gestión de Usuarios</a>
        <a href="../expedientes/index.php"><i class="fa-solid fa-folder"></i> Gestión de Expedientes</a>
        <a href="../notificaciones/index.php"><i class="fa-solid fa-bell"></i> Notificaciones</a>
        <a href="../reportes/index.php"><i class="fa-solid fa-chart-bar"></i> Reportes</a>
        <a href="../logout.php"><i class="fa-solid fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <div class="content">
        <div class="header">
            <h1>Gestión de Notificaciones</h1>
            <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        </div>

        <a href="agregar.php" class="add-btn"><i class="fa-solid fa-plus"></i> Agregar Notificación</a>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mensaje</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notificaciones as $notificacion): ?>
                        <tr>
                            <td><?php echo $notificacion[
                                "id_notificacion"
                            ]; ?></td>
                            <td><?php echo htmlspecialchars(
                                $notificacion["mensaje"]
                            ); ?></td>
                            <td><?php echo $notificacion["fecha"]; ?></td>
                            <td><?php echo htmlspecialchars(
                                $notificacion["usuario"]
                            ); ?></td>
                            <td class="action-links">
                                <a href="eliminar.php?id=<?php echo $notificacion[
                                    "id_notificacion"
                                ]; ?>"><i class="fa-solid fa-trash-alt"></i> Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }
    </script>
</body>
