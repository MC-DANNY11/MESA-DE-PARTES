
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registrar Trámite</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            /* Estilo personalizado para los labels e inputs */
            .form-label {
                color: #28a745;
            }

            .form-control {
                border-color: #28a745;
            }

            .form-control:focus {
                border-color: #218838;
                box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
            }

            .form-select {
                border-color: #28a745;
            }

            .form-select:focus {
                border-color: #218838;
                box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
            }
        </style>
    </head>

    <body>
        <div class="container my-5">
            <div class="card">
                <div class="card-header bg-primary text-white position-relative">
                    <h1 class="card-title text-center position-relative">
                        Registrar Trámite
                        <a href="../mesa_virtual/index.php" class="back-button position-absolute top-50 end-0 translate-middle-y" style="width: 40px; height: 40px; margin-right: 10px;">
                            <img src="Imagenes/Volver.png" alt="Volver" style="max-width: 100%; max-height: 100%; object-fit: contain;"> 
                        </a>
                    </h1>
                </div>
                <div class="card-body">
                <form action="c_expediente.php" method="POST" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="tipo_persona" class="form-label">Tipo de Persona</label>
                                <select name="tipo_persona" id="tipo_persona" class="form-select" required>
                                    <option selected disabled value="">Seleccionar</option>
                                    <option value="Persona Natural">Persona Natural</option>
                                    <option value="Persona Jurídica">Persona Jurídica</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                                <select name="tipo_identificacion" id="tipo_identificacion" class="form-select" required>
                                    <option selected disabled value="">Seleccionar</option>
                                    <option value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="dni_ruc" class="form-label">DNI / RUC</label>
                                <input type="number" name="dni_ruc" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                <input type="text" name="apellido_paterno" class="form-control" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                <input type="text" name="apellido_materno" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="number" name="telefono" class="form-control" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" name="correo" class="form-control" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" name="direccion" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="asunto" class="form-label">Asunto</label>
                                <input type="text" name="asunto" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                                <select name="tipo_documento" class="form-select" required>
                                    <option selected disabled value="">Seleccionar</option>
                                    <option value="solicitud">Solicitud</option>
                                    <option value="oficio">Oficio</option>
                                    <option value="queja">Queja</option>
                                    <option value="reclamo">Reclamo</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="folio" class="form-label">Folio</label>
                                <input type="number" name="folio" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="notas_referencias" class="form-label">Notas/Referencias</label>
                            <textarea name="notas_referencias" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="archivo" class="form-label">Adjuntar Archivo</label>
                            <input type="file" name="archivo" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrar Trámite</button>
                    </form>
                </div>
            </div>
        </div>

      
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
  
