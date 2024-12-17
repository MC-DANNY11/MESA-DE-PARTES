<?php
require "../template/header.php";

// Verifica si el usuario ha iniciado sesión y tiene el rol de Administrador
if (!isset($_SESSION['usuario']) && !isset($_SESSION['id_usuario']) ) {
    header("Location:../index.php");
    exit; // Asegura que el script se detenga después de la redirección
} 

// Conexión a la base de datos
include "../config/dbase/conexion.php";

// Consulta SQL para contar el total de libros
$libros = "SELECT COUNT(idLibro) AS total_libros FROM tbllibros";
$stmt = $con->prepare($libros);
$stmt->execute();

// Obtener el resultado
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
$total_libros = $resultado['total_libros']; // Guardamos el valor en una variable
// Consulta para contar el total de editoriales
$editoriales_query = "SELECT COUNT(idEditorial) AS total_editoriales FROM tbleditoriales";
$stmt_editoriales = $con->prepare($editoriales_query);
$stmt_editoriales->execute();
$total_editoriales = $stmt_editoriales->fetch(PDO::FETCH_ASSOC)['total_editoriales'];

// Consulta para contar el total de secciones
$secciones_query = "SELECT COUNT(idCaja) AS total_secciones FROM tblcajas";
$stmt_secciones = $con->prepare($secciones_query);
$stmt_secciones->execute();
$total_secciones = $stmt_secciones->fetch(PDO::FETCH_ASSOC)['total_secciones'];

// Consulta para contar el total de materiales
$materiales_query = "SELECT COUNT(idMaterial) AS total_materiales FROM tblmateriales";
$stmt_materiales = $con->prepare($materiales_query);
$stmt_materiales->execute();
$total_materiales = $stmt_materiales->fetch(PDO::FETCH_ASSOC)['total_materiales'];

// Consulta SQL para contar el total de libros
$bienes = "SELECT COUNT(idBienes) AS total_bienes FROM tblbienes";
$stmt = $con->prepare($bienes);
$stmt->execute();

// Obtener el resultado
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
$total_bienes = $resultado['total_bienes']; // Guardamos el valor en una variable


// Consulta para contar el total de editoriales
$usuarios = "SELECT COUNT(idUsuario) AS total_usuarios FROM tblusuarios";
$stmt = $con->prepare($usuarios);
$stmt->execute();
$total_usuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total_usuarios'];

// Consulta para contar el total de secciones
$secciones_query = "SELECT COUNT(idCaja) AS total_secciones FROM tblcajas";
$stmt_secciones = $con->prepare($secciones_query);
$stmt_secciones->execute();
$total_secciones = $stmt_secciones->fetch(PDO::FETCH_ASSOC)['total_secciones'];

// Consulta para contar el total de materiales
$socios = "SELECT COUNT(idSocio) AS total_socios FROM tblsocios";
$stmt = $con->prepare($socios);
$stmt->execute();
$total_socios = $stmt->fetch(PDO::FETCH_ASSOC)['total_socios'];
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
                    <h3 class="custom-number"><?= $total_libros; ?></h3> <!-- Mostramos el total de libros -->
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
                    <h3 class="custom-number"><?= $total_editoriales; ?></h3>
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
                    <h3 class="custom-number"><?= $total_secciones; ?></h3>
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
                    <h3 class="custom-number"><?= $total_materiales; ?></h3>
                    <!-- Texto centrado abajo -->
                    <div class="custom-text">Materiales</div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">

        <div class="col-md-3">
            <a href="libros.php" class="text-decoration-none">
                <div class="custom-card">
                    <!-- Ícono en la parte superior izquierda -->
                    <div class="custom-icon">
                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                    </div>
                    <!-- Número en la parte superior derecha -->
                    <h3 class="custom-number"><?= $total_libros; ?></h3> <!-- Mostramos el total de libros -->
                    <!-- Texto centrado abajo -->
                    <div class="custom-text">Prestamos</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="editorial.php" class="text-decoration-none">
                <div class="custom-card">
                    <!-- Ícono en la parte superior izquierda -->
                    <div class="custom-icon">
                        <i class="fa fa-television" aria-hidden="true"></i>
                    </div>
                    <!-- Número en la parte superior derecha -->
                    <h3 class="custom-number"><?= $total_bienes; ?></h3>
                    <!-- Texto centrado abajo -->
                    <div class="custom-text">Bienes</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="usuarios.php" class="text-decoration-none">
                <div class="custom-card">
                    <!-- Ícono en la parte superior izquierda -->
                    <div class="custom-icon">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                    </div>
                    <!-- Número en la parte superior derecha -->
                    <h3 class="custom-number"><?= $total_usuarios; ?></h3>
                    <!-- Texto centrado abajo -->
                    <div class="custom-text">Usuarios</div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="socios.php" class="text-decoration-none">
                <div class="custom-card">
                    <!-- Ícono en la parte superior izquierda -->
                    <div class="custom-icon">
                        <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                    </div>
                    <!-- Número en la parte superior derecha -->
                    <h3 class="custom-number"><?= $total_socios; ?></h3>
                    <!-- Texto centrado abajo -->
                    <div class="custom-text">Socios</div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="card custom-card">
                <div id="chart2"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php
include "../config/dbase/conexion.php";

// Gráfico 1: Cantidad de Libros por Editorial
$sql = "SELECT e.nombre AS Editorial, COUNT(l.idLibro) AS Cantidad_Libros
        FROM tbllibros l
        JOIN tbleditoriales e ON l.editorial_id = e.idEditorial
        GROUP BY e.nombre
        ORDER BY Cantidad_Libros DESC";
$result = $con->query($sql);

$labels1 = [];
$series1 = [];

while($row = $result->fetch(PDO::FETCH_OBJ)) {
    $labels1[] = $row->Editorial;
    $series1[] = (int)$row->Cantidad_Libros;
}
?>
        <div class="col-md-6 mb-2">
            <div class="card custom-card">
                <div id="chart1"></div>
            </div>
        </div>
        <script>
        var labels1 = <?php echo json_encode($labels1); ?>;
        var series1 = [{
            name: 'Cantidad de Libros',
            data: <?php echo json_encode($series1); ?>
        }];
        var options1 = {
            series: series1,
            chart: {
                height: 350,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: labels1,
                position: 'bottom',
                axisBorder: {
                    show: true
                },
                axisTicks: {
                    show: true
                }
            },
            title: {
                text: 'Cantidad de Libros por Editorial',
                align: 'center',
                style: {
                    color: '#444',
                    fontSize: '20px',
                    fontWeight: 'bold'
                }
            }
        };
        new ApexCharts(document.querySelector("#chart1"), options1).render();
        </script>
        <?php

// Gráfico 2: Cambios en Cada Tabla del Historial
$fecha_inicio = '2024-01-01';
$fecha_fin = '2024-12-31';
$sql = "SELECT tabla_afectada, COUNT(*) AS cantidad_cambios FROM tblhistorialcambios
        WHERE fecha_cambio BETWEEN :fecha_inicio AND :fecha_fin
        GROUP BY tabla_afectada ORDER BY cantidad_cambios DESC";
$stmt = $con->prepare($sql);
$stmt->bindParam(':fecha_inicio', $fecha_inicio);
$stmt->bindParam(':fecha_fin', $fecha_fin);
$stmt->execute();

$labels2 = [];
$series2 = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $labels2[] = $row['tabla_afectada'];
    $series2[] = (int)$row['cantidad_cambios'];
}
?>
        <div class="col-md-6 mb-2">
            <div class="card custom-card">
                <div id="chart5"></div>
            </div>
        </div>


        <?php

// Gráfico 3: Estado de Libros en Inventario
$sql = "SELECT i.estado AS Estado, COUNT(l.idLibro) AS Cantidad
        FROM tblInventarios i
        JOIN tbllibros l ON i.libro_id = l.idLibro
        WHERE i.estado IN ('B', 'R', 'M')
        GROUP BY i.estado ORDER BY i.estado ASC";
$result = $con->query($sql);

$labels3 = [];
$series3 = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $labels3[] ="Estado " . $row['Estado'];
    $series3[] = (int) $row['Cantidad'];
}
?>
        <div class="col-md-6 mb-2">
            <div class="card custom-card">
                <div id="chart3"></div>
            </div>
        </div>
        <script>
        var labels3 = <?php echo json_encode($labels3); ?>;
        var series3 = <?php echo json_encode($series3); ?>;
        var options3 = {
            series: series3,
            chart: {
                width: 380,
                type: 'pie'
            },
            labels: labels3,
            title: {
                text: 'Número de Libros por Estado (B, R, M)',
                align: 'center',
                style: {
                    fontSize: '20px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            }
        };
        new ApexCharts(document.querySelector("#chart3"), options3).render();
        </script>
        <?php

// Gráfico 4: Estado de Materiales
$sql = "SELECT estado AS Estado, COUNT(idMaterial) AS Cantidad
        FROM tblmateriales
        WHERE estado IN ('B', 'R', 'M')
        GROUP BY estado ORDER BY estado ASC";
$result = $con->query($sql);

$labels4 = [];
$series4 = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $labels4[] = "Estado " . $row['Estado'];
    $series4[] = (int) $row['Cantidad'];
}
?>
        <div class="col-md-6 mb-2">
            <div class="card custom-card">
                <div id="chart4"></div>
            </div>
        </div>
        <script>
        var labels4 = <?php echo json_encode($labels4); ?>;
        var series4 = <?php echo json_encode($series4); ?>;
        var options4 = {
            series: series4,
            chart: {
                width: 380,
                type: 'pie'
            },
            labels: labels4,
            title: {
                text: 'Estado de Materiales (B, R, M)',
                align: 'center',
                style: {
                    fontSize: '20px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            }
        };
        new ApexCharts(document.querySelector("#chart4"), options4).render();
        </script>
        <?php

// Gráfico 5: Cantidad de Libros por Título
$sql = "SELECT titulo AS Titulo, COUNT(titulo) AS cantidad FROM tbllibros GROUP BY titulo";

$result = $con->query($sql);

$labels5 = [];
$series5 = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $labels5[] = $row['Titulo']; // Guardar el título
    $series5[] = (int) $row['cantidad']; // Guardar la cantidad como entero
}

?>

    </div>


    <script>
    var labels5 = <?php echo json_encode($labels5); ?>;
    var series5 = [{
        name: 'Cantidad de Libros',
        data: <?php echo json_encode($series5); ?>
    }];
    var options5 = {
        series: series5,
        chart: {
            height: 350,
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
            text: 'Cantidad de Libros por Título',
            align: 'center',
            style: {
                fontSize: '20px',
                fontWeight: 'bold',
                color: '#333'
            }
        }
    };
    new ApexCharts(document.querySelector("#chart5"), options5).render();
    </script>
    <script>
    var labels2 = <?php echo json_encode($labels2); ?>;
    var series2 = [{
        data: <?php echo json_encode($series2); ?>
    }];
    var options2 = {
        series: series2,
        chart: {
            type: 'bar',
            height: 350
        },
        title: {
            text: 'Número de Cambios en Cada Tabla',
            align: 'center',
            style: {
                fontSize: '20px',
                fontWeight: 'bold',
                color: '#333'
            }
        },
        xaxis: {
            categories: labels2
        },
        yaxis: {
            title: {
                text: 'Número de Cambios'
            }
        }
    };
    new ApexCharts(document.querySelector("#chart2"), options2).render();
    </script>

    <script src="../lib/js/chart.js"></script>
    <?php include "../template/footer.php"; ?>