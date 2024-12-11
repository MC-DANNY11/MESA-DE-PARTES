<?php
session_start();
require "../config/db_connection.php";

// Verificar si el usuario está autenticado y tiene el rol de admin

// Definir el número de usuarios por página
$usuarios_por_pagina = 5;
$pagina_actual = isset($_GET["pagina"]) ? (int) $_GET["pagina"] : 1; // Página actual
$inicio = ($pagina_actual - 1) * $usuarios_por_pagina; // Calcular el inicio de la consulta
// Consulta para obtener los usuarios con la paginación
$stmt = $pdo->prepare("SELECT u.*, COALESCE(a.nombre, 'No asignado') AS area_nombre
                       FROM usuarios u
                       LEFT JOIN areas a ON u.id_area = a.id_area
                       LIMIT :inicio, :usuarios_por_pagina");

$stmt->bindValue(":inicio", $inicio, PDO::PARAM_INT);
$stmt->bindValue(":usuarios_por_pagina", $usuarios_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$usuarios = $stmt->fetchAll();
// Consulta para contar el total de usuarios (sin búsqueda)
$stmtTotal = $pdo->prepare(
    "SELECT COUNT(*) FROM usuarios u LEFT JOIN areas a ON u.id_area = a.id_area"
);
$stmtTotal->execute();
$totalUsuarios = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalUsuarios / $usuarios_por_pagina);
// Consultar las áreas disponibles para el formulario de actualización
$areasStmt = $pdo->prepare("SELECT * FROM areas");
$areasStmt->execute();
$areas = $areasStmt->fetchAll();
// Lógica para actualizar el usuario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_usuario"])) {
    $id_usuario = $_POST["id_usuario"];
    $nombre = $_POST["nombre"];
    $nombre_usuario = $_POST["nombre_usuario"];
    $correo = $_POST["correo"];
    $rol = $_POST["rol"];
    $id_area = $_POST["id_area"];
    // Actualizar datos del usuario
    $stmt = $pdo->prepare(
        "UPDATE usuarios SET nombre = ?, nombre_usuario = ?, correo = ?, rol = ?, id_area = ? WHERE id_usuario = ?"
    );
    $stmt->execute([
        $nombre,
        $nombre_usuario,
        $correo,
        $rol,
        $id_area,
        $id_usuario,
    ]);
    // Redirigir después de la actualización
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
    <h1>GESTION DE USUARIOS</h1>
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
    </header>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <img src="../imagenes/ESCUDO DISTRITO DE PALCA.png" alt="Logo">
            <h3>Municipalidad Distrital De Palca</h3>
            <a href="../dashboard.php"><i class="fa-solid fa-home"></i> Inicio</a>
            <a href="../areas/index.php"><i class="fa-solid fa-layer-group"></i>Áreas</a>
            <a href="index.php"><i class="fa-solid fa-users"></i>Usuarios</a>
            <a href="../expedientes/index.php"><i class="fa-solid fa-folder"></i>Expedientes</a>
            <a href="../historial/index.php"><i class="fa-solid fa-history"></i> Historial de Documentos</a>
            <a href="../logout.php"><i class="fa-solid fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>
    <!-- Modal para agregar/editar usuario -->
<div id="user-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="user-modal-title"></h2>
            <button class="close-btn" onclick="closeUserModal()">×</button>
        </div>
        <form id="user-form" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="nombre_usuario">Usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>

            <label>Rol:</label>
            <select name="rol" required>
                <option value="admin">Admin</option>
                <option value="empleado">Empleado</option>
            </select>

            <label for="id_area">Área:</label>
            <select id="id_area" name="id_area" required>
                <option value="">Seleccionar Área</option>
                <?php foreach ($areas as $area): ?>
                    <option value="<?php echo $area[
                        "id_area"
                    ]; ?>"><?php echo htmlspecialchars(
    $area["nombre"]
); ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Campo para la contraseña (opcional) -->
            <label for="contraseña">Contraseña:</label>
            <div class="password-container">
                <input type="password" id="contraseña" name="contraseña" placeholder="Ingrese su contraseña">
                <i class="fa-solid fa-eye" id="toggle-password" onclick="togglePasswordVisibility()"></i> <!-- Ícono para mostrar/ocultar contraseña -->
            </div>

            <button type="submit" id="user-form-submit">Enviar</button>
        </form>
    </div>
</div>

    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmar eliminación</h2>
                <button class="close-btn" onclick="closeDeleteModal()">×</button>
            </div>
            <p>¿Estás seguro de que deseas eliminar este usuario?</p>
            <div style="text-align: right;">
                <button onclick="confirmDelete()" style="background-color: #d32f2f; color: white;">Eliminar</button>
                <button onclick="closeDeleteModal()" style="background-color: #00796b; color: white;">Cancelar</button>
            </div>
        </div>
    </div>
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
                        <td><?php echo $usuario["id_usuario"]; ?></td>
                        <td><?php echo htmlspecialchars(
                            $usuario["nombre"]
                        ); ?></td>
                        <td><?php echo htmlspecialchars(
                            $usuario["nombre_usuario"]
                        ); ?></td>
                        <td><?php echo htmlspecialchars(
                            $usuario["correo"]
                        ); ?></td>
                        <td><?php echo htmlspecialchars(
                            $usuario["rol"]
                        ); ?></td>
                        <td><?php echo htmlspecialchars(
                            $usuario["area_nombre"]
                        ); ?></td>
                        <td class="action-links">
                            <!-- Botón de editar con fondo verde -->
                            <button class="edit-btn" onclick="showUserModal('edit', <?php echo htmlspecialchars(
                                json_encode([
                                    "id_usuario" => $usuario["id_usuario"],
                                    "nombre" => $usuario["nombre"],
                                    "nombre_usuario" =>
                                        $usuario["nombre_usuario"],
                                    "correo" => $usuario["correo"],
                                    "rol" => $usuario["rol"],
                                    "area_nombre" => $usuario["area_nombre"],
                                    "contraseña" => $usuario["contraseña"],
                                ])
                            ); ?>)">
                                <i class="fa-solid fa-edit"></i>
                            </button>

                            <!-- Botón de eliminar con fondo rojo (solo si no es el Área 1) -->
                            <?php if ($usuario["rol"] !== "admin"): ?>
                                <button class="delete-btn" onclick="showDeleteModal(<?php echo $usuario[
                                    "id_usuario"
                                ]; ?>)">
                                    <i class="fa-solid fa-trash-alt"></i>
                                </button>
                            <?php endif; ?>
                        </td>
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
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }
        function showUserModal(type, userData = null) {
            const modal = document.getElementById('user-modal');
            const title = document.getElementById('user-modal-title');
            const form = document.getElementById('user-form');
            const submitButton = document.getElementById('user-form-submit');

            modal.style.display = 'flex'; // Muestra el modal

            if (type === 'add') {
                title.textContent = 'Agregar Usuario';
                form.action = 'agregar.php'; // Define la acción para enviar datos al servidor
                submitButton.textContent = 'Agregar';
                form.reset(); // Limpia los campos del formulario
            } else if (type === 'edit') {
                title.textContent = 'Editar Usuario';
                form.action = `editar.php?id=${userData.id_usuario}`; // Define la acción para editar
                submitButton.textContent = 'Actualizar';

                // Rellenar los campos con los datos del usuario para editar
                document.getElementById('nombre').value = userData.nombre;
                document.getElementById('nombre_usuario').value = userData.nombre_usuario;
                document.getElementById('correo').value = userData.correo;
                document.getElementById('rol').value = userData.rol;

                // Asignar el nombre del área al campo
                document.getElementById('id_area').value = userData.area_nombre; // Asignamos el nombre del área
            }
        }
        function closeUserModal() {
            const modal = document.getElementById('user-modal');
            modal.style.display = 'none';
        }
        let deleteUserId = null;

        function showDeleteModal(id) {
            deleteUserId = id;
            const deleteModal = document.getElementById('delete-modal');
            deleteModal.style.display = 'flex';
        }

        function closeDeleteModal() {
            deleteUserId = null;
            const deleteModal = document.getElementById('delete-modal');
            deleteModal.style.display = 'none';
        }

        function confirmDelete() {
            if (deleteUserId !== null) {
                window.location.href = `eliminar.php?id=${deleteUserId}`;
            }
        }
    </script>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('contraseña');
            const passwordIcon = document.getElementById('toggle-password');

            // Cambiar tipo de input entre "password" y "text"
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash"); // Cambiar ícono a "ojo cerrado"
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye"); // Cambiar ícono a "ojo abierto"
            }
        }
    </script>


</body>
</html>
