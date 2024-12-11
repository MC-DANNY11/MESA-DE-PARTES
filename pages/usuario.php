<?php include"../template/header.php"; 
 $stmt ="SELECT * FROM Areas";
 $stmt = $pdo->prepare($stmt);
 $stmt->execute();
 $areas = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

        <!-- Main Content -->
        <div class="content">
            <!-- Botones de exportación -->
            <a href="reporte_excel.php" class="add-area-btn">
                <i class="fa-solid fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="reporte_pdf.php" class="add-area-btn">
                <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
            </a>

            <a href="javascript:void(0);" class="add-area-btn" onclick="showUserModal('add')">
                <i class="fa-solid fa-plus"></i> Agregar Usuario
            </a>
            <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Área</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody> <!-- Corregido de <<tbody> a <tbody> -->
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
                    <?php endforeach; ?>
                </tbody> <!-- Cerrado correctamente -->
            </table>
            </div>
            <!-- Paginación -->
            <div class="pagination">
                <ul>
                    <?php if ($pagina_actual > 1): ?>
                        <li><a href="index.php?pagina=<?php echo $pagina_actual -
                            1; ?>" class="prev">Anterior</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li><a href="index.php?pagina=<?php echo $i; ?>" class="<?php echo $i ==
$pagina_actual
    ? "active"
    : ""; ?>">
                            <?php echo $i; ?>
                        </a></li>
                    <?php endfor; ?>

                    <?php if ($pagina_actual < $totalPaginas): ?>
                        <li><a href="index.php?pagina=<?php echo $pagina_actual +
                            1; ?>" class="next">Siguiente</a></li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>
 
        <?php include"../template/footer.php"; ?>