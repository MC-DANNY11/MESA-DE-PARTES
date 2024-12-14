<div class="container mt-4">
                <h2>Lista de Datos</h2>
                <table id="tabla-datos" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí agregarías tus filas de datos -->
                        <tr>
                            <td>1</td>
                            <td>Juan Pérez</td>
                            <td>juan@example.com</td>
                            <td><button class="btn btn-warning">Editar</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Ana Gómez</td>
                            <td>ana@example.com</td>
                            <td><button class="btn btn-warning">Editar</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <script>
            $(document).ready(function() {
                // Inicialización de DataTable
                $('#tabla-datos').DataTable({
                    responsive: true
                });

                // Función para mostrar/ocultar la barra lateral
                $('#custom-menu-toggle').on('click', function() {
                    $('.barra-lateral').toggleClass('active');
                    $('#contenido-de-la-pagina').toggleClass('active');
                });
            });
            </script>