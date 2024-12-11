<?php
session_start();
require_once "../config/db_connection.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

// Definir la cantidad de registros por página
$registros_por_pagina = 10;

// Verificar la página actual, si no está definida, usar la página 1
$pagina_actual = isset($_GET["pagina"]) ? (int) $_GET["pagina"] : 1;

// Calcular el índice de inicio para la consulta SQL (OFFSET)
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

try {
    // Obtener los registros del historial con paginación, ahora con más información
    $stmt = $pdo->prepare("
        SELECT h.*, e.id_expediente, e.remitente, e.estado AS estado_documento, e.categoria AS categoria_documento,
               u.nombre AS usuario_nombre, h.tipo_accion
        FROM historial_expedientes h
        JOIN expedientes e ON h.id_expediente = e.id_expediente
        JOIN usuarios u ON h.id_usuario = u.id_usuario
        ORDER BY h.fecha_hora DESC
        LIMIT :inicio, :registros_por_pagina
    ");
    $stmt->bindParam(":inicio", $inicio, PDO::PARAM_INT);
    $stmt->bindParam(
        ":registros_por_pagina",
        $registros_por_pagina,
        PDO::PARAM_INT
    );
    $stmt->execute();
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Contar el total de registros para calcular las páginas
    $stmt_total = $pdo->prepare("SELECT COUNT(*) FROM historial_expedientes");
    $stmt_total->execute();
    $total_registros = $stmt_total->fetchColumn();

    // Calcular el número total de páginas
    $total_paginas = ceil($total_registros / $registros_por_pagina);
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Documentos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<header>
    <h1>GESTION DE HISTORIAL</h1>
</header>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../imagenes/ESCUDO DISTRITO DE PALCA.png" alt="Logo">
        <h3>Municipalidad Distrital De Palca</h3>
        <a href="../dashboard.php"><i class="fas fa-home"></i> Inicio</a>
        <a href="../areas/index.php"><i class="fas fa-layer-group"></i> Áreas</a>
        <a href="index.php"><i class="fas fa-users"></i> Usuarios</a>
        <a href="../expedientes/index.php"><i class="fas fa-folder"></i> Expedientes</a>
        <a href="../historial/index.php"><i class="fas fa-history"></i> Historial de Documentos</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Historial de Acciones</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Expediente</th>
                    <th>Remitente</th>
                    <th>Tipo de Acción</th>
                    <th>Estado</th>
                    <th>Categoría</th>
                    <th>Usuario</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($historial)): ?>
                    <?php foreach ($historial as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(
                                $item["id_expediente"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $item["remitente"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $item["tipo_accion"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $item["estado_documento"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $item["categoria_documento"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $item["usuario_nombre"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $item["fecha_hora"]
                            ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron registros en el historial.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($pagina_actual > 1): ?>
                <a href="?pagina=<?php echo $pagina_actual -
                    1; ?>">« Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?php echo $i; ?>" class="<?php echo $i ==
$pagina_actual
    ? "active"
    : ""; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="?pagina=<?php echo $pagina_actual +
                    1; ?>">Siguiente »</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
