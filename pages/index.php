<?php
require "../template/header.php";

// Database connection and queries would go here
// (Assuming the database queries are uncommented and functional)

// Include styles for the dashboard
?>
<link rel="stylesheet" href="../css/index.css">

<!-- Contenido del dashboard -->
<!-- Main Content -->
<div class="content">
    <h2>DASHBOARD</h2>
    <div class="card-container">
        <div class="card">
            <div class="card-icon">
                <i class="fa-solid fa-spinner"></i>
            </div>
            <div class="card-content">
                <a href="../pages/expedientes.php?estado=tramitando">Expedientes</a>
            </div>
        </div>
        <div class="card">
            <div class="card-icon">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <div class="card-content">
                <a href="../pages/historial.php?estado=atendido">Historial de Documentos</a>
            </div>
        </div>
    </div>
</div>

<script src="../lib/js/chart.js"></script>
<?php include "../template/footer.php"; ?>