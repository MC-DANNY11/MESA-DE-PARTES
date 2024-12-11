<?php include"../template/header.php"; ?>
    <!-- Main Content -->
    <div class="content">

    <?php
        include"../config/db_connection.php";
        // Consultar todas las áreas disponibles
        $stmt ="SELECT * FROM Areas";
        $stmt = $pdo->prepare($stmt);
        $stmt->execute();
        $areas = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        ?>
        <a href="Reporte_EXCEL.php" class="add-area-btn">
            <i class="fa-solid fa-file-excel"></i> Exportar a Excel
        </a>
        <a href="Reporte_PDF.php" class="add-area-btn">
            <i class="fa-solid fa-file-pdf"></i> Exportar a PDF
        </a>
        <!-- Campo de búsqueda -->
        <input type="text" id="searchInput" placeholder="Buscar por expediente o remitente..." class="search-input">
        <!-- Botón de búsqueda -->
        <button id="searchButton" class="search-btn">Buscar</button>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Expediente</th>
                    <th>Remitente</th>
                    <th>Código</th>
                    <th>Teléfono</th>
                    <th>Asunto</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Área</th>
                    <th>Acciones</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($expedientes)): ?>
                <?php foreach ($expedientes as $expediente): ?>
                <tr>
                    <td><?php echo htmlspecialchars(
                        $expediente["id_expediente"]
                    ); ?></td>
                    <td><?php echo htmlspecialchars(
                        $expediente["remitente"]
                    ); ?></td>
                    <td><?php echo htmlspecialchars(
                        $expediente["codigo_seguridad"]
                    ); ?></td>
                    <td><?php echo htmlspecialchars(
                        $expediente["telefono"]
                    ); ?></td>
                    <td><?php echo htmlspecialchars(
                        $expediente["asunto"]
                    ); ?></td>
                    <td><?php echo htmlspecialchars(
                        $expediente["fecha_hora"]
                    ); ?></td>
                    <td><?php echo htmlspecialchars(
                        $expediente["estado"]
                    ); ?></td>
                    <td><?php echo isset($expediente["area_destino"])
                        ? htmlspecialchars($expediente["area_destino"])
                        : "Sin asignar"; ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <!-- Botón Atender con solo el ícono -->
                            <a href="javascript:void(0);" onclick="mostrarModal(<?php echo $expediente[
                                "id_expediente"
                            ]; ?>);" class="btn btn-primary btn-sm" title="Atender expediente">
                                <i class="fas fa-check"></i>
                            </a>

                            <!-- Botón Derivar con solo el ícono -->
                            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo htmlspecialchars(
                        $expediente["id_expediente"]
                    ); ?>" class="btn btn-warning btn-sm" title="Derivar expediente">
                                <i class="fas fa-exchange-alt"></i>
                            </a>

                            <!-- Botón Eliminar con solo el ícono -->
                            <a href="javascript:void(0);" onclick="mostrarPopupEliminar(<?php echo $expediente[
                                "id_expediente"
                            ]; ?>);" class="btn btn-danger btn-sm" title="Eliminar expediente">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                    <td class="text-center">
                        <?php if (
                            !empty($expediente["archivo"]) &&
                            file_exists(
                                "../mesa_virtual/uploads/" .
                                    $expediente["archivo"]
                            )
                        ): ?>
                        <a href="../mesa_virtual/uploads/<?php echo htmlspecialchars(
                                basename($expediente["archivo"])
                            ); ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>
                        <?php else: ?>
                        <span class="text-muted">Archivo no disponible</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <!-- Modal para devivar  -->
                <div class="modal fade" id="exampleModal<?php echo htmlspecialchars(
                        $expediente["id_expediente"]
                    ); ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content ">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Derivar Expediente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="../validate/update/u_derivar.php" method="post">
                                    <input type="NUMBER" name="codigo" value="<?php echo htmlspecialchars(
                        $expediente["id_expediente"]
                    ); ?>">
                                    <div class="mb-3">
                                        <label for="disabledSelect" class="form-label">Disabled select menu</label>
                                        <select id="" class="form-select" name="area">
                                            <option>Disabled select</option>
                                            <?php foreach ($areas as $area) { ?>
                                            <option value="<?php echo $area->id_area;?>"><?php echo $area->nombre;?>
                                            </option>
                                            <?php }?>

                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">No se encontraron expedientes.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- Popup para atender expediente -->
        <div id="popupModal" class="popup-modal">
            <div class="popup-content">
                <span id="closePopup" class="close-btn">&times;</span>
                <h3>Atender Expediente</h3>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_expediente" id="id_expediente">
                    <div class="form-group">
                        <label for="respuesta">Respuesta:</label>
                        <textarea name="respuesta" id="respuesta" rows="4" cols="50" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="pdf_file">Seleccione el archivo PDF:</label>
                        <input type="file" name="pdf_file" id="pdf_file" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Atender</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>



        <!-- Popup para Derivar Expediente -->
        <div id="popupDerivarModal" class="popup-modal">
            <div class="popup-content">
                <span id="closeDerivarPopup" class="close-btn">&times;</span>
                <h3>Derivar Expediente</h3>
                <!-- Formulario de derivación -->
                <form method="post">
                    <input type="hidden" name="id_expediente" id="id_expediente_derivar">
                    <div class="form-group">
                        <label for="area_destino">Área destino:</label>
                        <select name="area" required>
                            <?php
            // Consultar todas las áreas disponibles
            $stmt = $pdo->query("SELECT * FROM Areas");
            while ($row = $stmt->fetch()) {
                echo "<option value=\"" .
                    $row["id_area"] .
                    "\">" .
                    htmlspecialchars($row["nombre"]) .
                    "</option>";
            }
            ?>
                        </select>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Derivar</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Popup para Derivar Expediente -->
        <div id="popupDerivarModal" class="popup-modal">
            <div class="popup-content">
                <span id="closeDerivarPopup" class="close-btn">&times;</span>
                <h3>Derivar Expediente</h3>
                <!-- Formulario de derivación -->
                <form method="post" action="derivar.php">
                    <input type="hidden" name="id_expediente" id="id_expediente_derivar">
                    <div class="form-group">
                        <label for="area_destino">Área destino:</label>
                        <input type="text" name="area_destino" id="area_destino" required class="form-control">
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Derivar</button>
                        <a href="javascript:void(0);"
                            onclick="document.getElementById('popupDerivarModal').style.display='none';"
                            class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Popup para Confirmar Eliminación -->
        <div id="popupEliminarModal" class="popup-modal">
            <div class="popup-content">
                <span id="closeEliminarPopup" class="close-btn">&times;</span>
                <h3>Confirmar Eliminación</h3>
                <p>¿Estás seguro de que deseas eliminar este expediente?</p>
                <form method="post" action="eliminar.php">
                    <input type="hidden" name="id_expediente" id="id_expediente_eliminar">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                        <a href="javascript:void(0);"
                            onclick="document.getElementById('popupEliminarModal').style.display='none';"
                            class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Paginacion -->
        <div class="pagination">
            <?php if ($pagina_actual > 1): ?>
            <a href="?pagina=<?php echo $pagina_actual -
                    1; ?>">&laquo; Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>" class="<?php echo $i ==
$pagina_actual
    ? "active"
    : ""; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($pagina_actual < $total_paginas): ?>
            <a href="?pagina=<?php echo $pagina_actual +
                    1; ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
        <!-- Botón de "Ver más" para cargar más filas en dispositivos móviles -->
        <div class="text-center">
            <button class="btn btn-secondary" id="verMasBtn">Ver más</button>
        </div>

        <?php include"../template/footer.php"; ?>