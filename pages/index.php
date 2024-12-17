<?php
require "../template/header.php";

// Database connection and queries would go here
// (Assuming the database queries are uncommented and functional)

// Include styles for the dashboard
?>


<!-- Contenido del dashboard -->
<!-- Main Content -->
<div class="content">
    <h2>DASHBOARD</h2>
    <div class="card-container">
        <a href="expedientes.php?estado=tramitando" class="card">
            <div class="card-icon">
                <i class="fa-solid fa-spinner"></i>
            </div>
            <div class="card-content">
                <h3 >Expedientes</h3>
            </div>
        </a>
        <a class="card">
            <div class="card-icon">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <div class="card-content">
                <a href="../pages/historial.php?estado=atendido">Historial de Documentos</a>
            </div>
        </a>
    </div>
</div>

<script src="../lib/js/chart.js"></script>
<?php include "../template/footer.php"; ?>