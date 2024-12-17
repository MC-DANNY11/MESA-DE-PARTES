<?php
require "../template/header.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
} 

// Conexión a la base de datos
include "../config/db_connection.php";

// Consultas SQL para contar totales
try {
    // Total de áreas
    $areas_query = "SELECT COUNT(id_area) AS total_areas FROM areas";
    $stmt = $pdo->prepare($areas_query);
    $stmt->execute();
    $total_areas = $stmt->fetch(PDO::FETCH_ASSOC)['total_areas'];

    // Total de usuarios
    $usuarios_query = "SELECT COUNT(id_usuario) AS total_usuarios FROM usuarios";
    $stmt_usuarios = $pdo->prepare($usuarios_query);
    $stmt_usuarios->execute();
    $total_usuarios = $stmt_usuarios->fetch(PDO::FETCH_ASSOC)['total_usuarios'];

    // Total de expedientes
    $expedientes_query = "SELECT COUNT(id_expediente) AS total_expedientes FROM expedientes";
    $stmt_expedientes = $pdo->prepare($expedientes_query);
    $stmt_expedientes->execute();
    $total_expedientes = $stmt_expedientes->fetch(PDO::FETCH_ASSOC)['total_expedientes'];

    // Total de historial de expedientes
    $historial_expedientes_query = "SELECT COUNT(id_historial) AS total_historial_expedientes FROM historial_expedientes";
    $stmt_historial_expedientes = $pdo->prepare($historial_expedientes_query);
    $stmt_historial_expedientes->execute();
    $total_historial_expedientes = $stmt_historial_expedientes->fetch(PDO::FETCH_ASSOC)['total_historial_expedientes'];
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit;
}
?>

<!-- Estilos para las tarjetas -->
<link rel="stylesheet" href="../css/index.css">

<!-- Contenido del dashboard -->
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3">
            <a href="../pages/areas.php" class="text-decoration-none">
                <div class="custom-card">
                    <div class="custom-icon">
                        <img src="../imagenes/areas.png" alt="Áreas" class="icon"> <!-- Replace with your icon -->
                    </div>
                    <h3 class="custom-number"><?= $total_areas; ?></h3>
                    <div class="custom-text">Áreas</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="../pages/usuarios.php" class="text-decoration-none">
                <div class="custom-card">
                    <div class="custom-icon">
                        <img src="../imagenes/usuarios.png" alt="Usuarios" class="icon"> <!-- Replace with your icon -->
                    </div>
                    <h3 class="custom-number"><?= $total_usuarios; ?></h3>
                    <div class="custom-text">Usuarios</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="../pages/expedientes.php" class="text-decoration-none">
                <div class="custom-card">
                    <div class="custom-icon">
                        <img src="../imagenes/expedien.png" alt="Expedientes" class="icon"> <!-- Replace with your icon -->
                    </div>
                    <h3 class="custom-number"><?= $total_expedientes; ?></h3>
                    <div class="custom-text">Expedientes</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="../pages/historial.php" class="text-decoration-none">
                <div class="custom-card">
                    <div class="custom-icon">
                        <img src="../imagenes/historial.png" alt="Historial" class="icon"> <!-- Replace with your icon -->
                    </div> 
                    <h3 class="custom-number"><?= $total_historial_expedientes; ?></h3>
                    <div class="custom-text">Historial de Expedientes</div>
                </div>
            </a>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="card ```php
custom-card">
                <div id="chart2"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php
        // Gráfico: Cantidad de Expedientes por Estado
        $sql = "SELECT estado, COUNT(id_expediente) AS cantidad FROM expedientes GROUP BY estado";
        $result = $pdo->query($sql);

        $labels5 = [];
        $series5 = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $labels5[] = $row['estado']; // Guardar el estado
            $series5[] = (int) $row['cantidad']; // Guardar la cantidad como entero
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var labels5 = <?php echo json_encode($labels5); ?>;
        var series5 = [{
            name: 'Cantidad de Expedientes',
            data: <?php echo json_encode($series5); ?>
        }];
        var options5 = {
            series: series5,
            chart: {
                height: 400,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    columnWidth: '50%'
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                labels: {
                    rotate: -45
                },
                categories: labels5
            },
            yaxis: {
                title: {
                    text: 'Cantidad'
                }
            },
            title: {
                text: 'Cantidad de Expedientes por Estado',
                align: 'center',
                style: {
                    fontSize: '20px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            }
        };
        new ApexCharts(document.querySelector("#chart2"), options5).render();
    </script>

    <?php include "../template/footer.php"; ?>