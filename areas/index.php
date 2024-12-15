<?php
// areas/index.php
session_start();
require '../config/db_connection.php';

// Número de elementos por página
$items_per_page = 5;
// Calcular la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// Calcular el índice de inicio
$start = ($page - 1) * $items_per_page;
// Realizar la consulta SQL con paginación
$query = "SELECT * FROM areas LIMIT :start, :items_per_page";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$areas = $stmt->fetchAll();
// Contar el número total de registros para generar las páginas
$total_query = "SELECT COUNT(*) AS total FROM areas";
$total_result = $pdo->query($total_query);
$total_row = $total_result->fetch(PDO::FETCH_ASSOC);
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Area</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
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
            <a href="../dashboard.php"><i class="fa-solid fa-home"></i> Inicio</a>
            <a href="index.php"><i class="fa-solid fa-layer-group"></i>Áreas</a>
            <a href="../usuarios/index.php"><i class="fa-solid fa-users"></i>Usuarios</a>
            <a href="../expedientes/index.php"><i class="fa-solid fa-folder"></i>Expedientes</a>
            <a href="../historial/index.php"><i class="fa-solid fa-history"></i> Historial de Documentos</a>
            <a href="../logout.php"><i class="fa-solid fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>        
        <!-- Main Content -->
        <div class="content">
            <a href="../EXCEL/reporteareasEXCEL.php" class="add-area-btn">
                <i class="fa-solid fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="../PDF/reporteareasPDF.php" class="add-area-btn">
                <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
            </a>
            <a href="javascript:void(0);" class="add-area-btn" onclick="showModal('add')">
                <i class="fa-solid fa-plus"></i> Agregar Área
            </a>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($areas as $area): ?>
                        <tr>
                            <td><?php echo $area['id_area']; ?></td>
                            <td><?php echo htmlspecialchars($area['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($area['descripcion']); ?></td>
                            <td class="action-links">
                                <!-- Botón de editar con ícono verde -->
                                <button class="icon-btn edit-btn" onclick="showModal('edit', <?php echo $area['id_area']; ?>)">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                <!-- Botón de eliminar con ícono rojo (solo si no es el Área 1) -->
                                <?php if ($area['id_area'] !== 1): ?>
                                    <a href="javascript:void(0);" class="icon-btn delete-btn" onclick="showDeleteModal(<?php echo $area['id_area']; ?>)">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title"></h2>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>
            <form id="modal-form" method="post">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
                
                <button type="submit" id="modal-submit"></button>
            </form>
        </div>
    </div>
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmar eliminación</h2>
                <button class="close-btn" onclick="closeDeleteModal()">×</button>
            </div>
            <p>¿Estás seguro de que deseas eliminar esta área?</p>
            <div style="text-align: right;">
                <button onclick="confirmDelete()" style="background-color: #d32f2f; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">Eliminar</button>
                <button onclick="closeDeleteModal()" style="background-color: #00796b; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">Cancelar</button>
            </div>
        </div>
    </div>
    <script>
        function showModal(type, id = null) {
            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modal-title');
            const modalForm = document.getElementById('modal-form');
            const modalSubmit = document.getElementById('modal-submit');
            const nombreField = document.getElementById('nombre');
            const descripcionField = document.getElementById('descripcion');

            modal.style.display = 'flex';

            if (type === 'add') {
                modalTitle.textContent = 'Agregar Área';
                modalForm.action = 'agregar.php';
                modalSubmit.textContent = 'Guardar';
                nombreField.value = '';
                descripcionField.value = '';
            } else if (type === 'edit') {
                modalTitle.textContent = 'Editar Área';
                modalForm.action = `editar.php?id=${id}`;
                modalSubmit.textContent = 'Actualizar';

                // Cargar datos de la base de datos mediante fetch
                fetch(`get_area.php?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        nombreField.value = data.nombre;
                        descripcionField.value = data.descripcion;
                    })
                    .catch(error => {
                        console.error('Error al cargar los datos:', error);
                    });
            }
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }
        let deleteAreaId = null;

            function showDeleteModal(id) {
                deleteAreaId = id;
                const deleteModal = document.getElementById('delete-modal');
                deleteModal.style.display = 'flex';
            }

            function closeDeleteModal() {
                deleteAreaId = null;
                const deleteModal = document.getElementById('delete-modal');
                deleteModal.style.display = 'none';
            }

            function confirmDelete() {
                if (deleteAreaId !== null) {
                    // Redirigir a la URL de eliminación
                    window.location.href = `eliminar.php?id=${deleteAreaId}`;
                }
            }
    </script>
</body>
</html>
