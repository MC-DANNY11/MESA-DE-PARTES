<?php 
include "../template/header.php";
// Verifica si el usuario ha iniciado sesión y NO tiene el rol de administrador
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_usuario']) ) {
    header("Location: ../index.php");
    exit; // Asegura que el script se detenga después de la redirección
}

// Verifica si el usuario tiene el rol de 'Bibliotecario' para mostrar un mensaje específico
if (isset($_SESSION['usuario']) && isset($_SESSION['id_usuario']) && $_SESSION['rol'] === 'Bibliotecario') {
    echo '<div class="container-fluid"><h1 class="text text-center">Esta página no está disponible</h1></div>';
    exit; // Asegura que el script se detenga después de mostrar el mensaje
}

?>



<div class="container-fluid mt-4">
    <button style="border-radius:10px" type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Agregar
    </button>

    <?php
    include("../config/dbase/conexion.php");
    $sql = "SELECT * FROM tblUsuarios";
    $datos = $con->prepare($sql);
    $datos->execute();
    $usuarios = $datos->fetchall(PDO::FETCH_OBJ)
        ?>

    <div class="card">
        <table class="table alter mb-0" id="example" style="width:100%; max-width:100%;">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Nombres y Apellidos</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($usuarios as $dato) {
                    ?>
                    <tr>
                        <th scope="row"><?= $dato->idUsuario; ?></th>
                        <td><?= $dato->usuario; ?></td>
                        <td><?= $dato->nombres_apellidos; ?></td>
                        <td><?= $dato->correo; ?></td>
                        <td><?= $dato->rol; ?></td>
                        <td><?= $dato->estado; ?></td>
                        <td><a href="" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdropL<?= $dato->idUsuario; ?>"
                                style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"
                                    style="margin-right: 5px; font-size: 16px;"></i></a>|
                                    <a href="#" data-href="../controller/delete/d_usuario.php?id=<?= $dato->idUsuario; ?>"
                                    class="btn btn-danger btn-sm"
                                    style="display: inline-flex; justify-content: center; align-items: center; padding: 5px 10px; text-align: center; width: auto;"
                                    onclick="EliminarUsuario(event)">
                                    <i class="fa fa-trash" aria-hidden="true"
                                        style="margin-right: 5px; font-size: 16px;"></i>
                                </a>

                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdropL<?= $dato->idUsuario; ?>" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Editar Datos de Usuario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="../controller/update/u_Usuario.php" method="POST">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <input hidden type="text" class="form-control" name="codigo"
                                                    value="<?= $dato->idUsuario; ?>" required>
                                                <div class="col-md-6">
                                                    <label for="validationCustom01" class="form-label">Usuario</label>
                                                    <input type="text" class="form-control" name="usuario"
                                                        value="<?= $dato->usuario; ?>" oninput="capitalizeWords(this)" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom02" class="form-label">Nombres y Apellidos</label>
                                                    <input type="text" class="form-control" name="nombres"
                                                        value="<?= $dato->nombres_apellidos; ?>" oninput="capitalizeWords(this)" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-12">
                                                    <label for="validationCustom02" class="form-label">Correo
                                                        Electronico</label>
                                                    <input type="text" class="form-control" id="validationCustom02"
                                                        name="correo" value="<?= $dato->correo; ?>" required>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="validationCustom04" class="form-label">Rol Usuario</label>
                                                    <select class="form-select" id="validationCustom04" name="rol" required>
                                                        <option value="<?= $dato->rol; ?>"><?= $dato->rol; ?></option>
                                                        <option value="Bibliotecario">Bibliotecario</option>
                                                        <option value="Administrador">Administrador</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="validationCustom04" class="form-label">Estado
                                                        Usuario</label>
                                                    <select class="form-select" id="validationCustom04" name="estado"
                                                        required>
                                                        <option  value="<?= $dato->estado; ?>"><?= $dato->estado; ?>
                                                        </option>
                                                        <option value="Activo">Activo</option>
                                                        <option value="Inactivo">Inactivo</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button style="border-radius:15px" type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button style="border-radius:15px" type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Modal para agregar -->

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Agregar Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../controller/create/c_usuario.php" method="POST">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">Usuario</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)" name="usuario" value=""
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom02" class="form-label">Nombres y Apellidos</label>
                                <input type="text" class="form-control" oninput="capitalizeWords(this)" name="nombres" value=""
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="validationCustom01" name="contrasena"
                                    value="" required> <!-- Corrige el nombre del campo -->
                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom02" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="validationCustom02" name="correo" value=""
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="validationCustom04" class="form-label">Rol Usuario</label>
                                <select class="form-select" id="validationCustom04" name="rol" required>
                                    <option selected disabled value="">Seleccionar Rol Usuario</option>
                                    <option value="Bibliotecario">Bibliotecario</option> <!-- Agrega valores a los option -->
                                    <option value="Administrador">Administrador</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="validationCustom04" class="form-label">Estado Usuario</label>
                                <select class="form-select" id="validationCustom04" name="estado" required>
                                    <option selected disabled value="">Seleccionar Estado Usuario</option>
                                    <option value="Activo">Activo</option> <!-- Agrega valores a los option -->
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button style="border-radius:15px" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button style="border-radius:15px" type="submit" class="btn btn-primary">Agregar</button>
            </div>
            </form>
        </div>
    </div>
</div>

</div>
<?php include "../template/footer.php"; ?>