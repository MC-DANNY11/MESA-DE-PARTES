<?php
session_start();

if (isset($_SESSION['registro'])) {
    $registro = $_SESSION['registro'];
    
    // Modificar los 3 últimos dígitos del código de seguridad
    $codigo_seguridad_oculto = substr($registro['codigo_seguridad'], 0, -3) . str_repeat('*', 3);
    
    // Mostrar los datos
    echo "<html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Detalles del Expediente</title>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        background-color: #f8f9fa;
                        margin-top: 30px;
                    }
                    .container {
                        background-color: white;
                        padding: 30px;
                        border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    }
                    h1 {
                        color: #28a745;
                        text-align: center;
                    }
                    .btn-custom {
                        border-radius: 5px;
                        padding: 10px 20px;
                        text-decoration: none;
                        display: block;
                        width: 100%;
                        text-align: center;
                    }
                    .btn-generar {
                        background-color: #28a745;
                        color: white;
                    }
                    .btn-generar:hover {
                        background-color: #218838;
                    }
                    .btn-volver {
                        background-color: #dc3545; /* Color rojo */
                        color: white;
                    }
                    .btn-volver:hover {
                        background-color: #c82333;
                    }
                    .row {
                        margin-bottom: 20px;
                    }
                    .col-md-6 {
                        margin-bottom: 15px;
                    }
                    .col-md-6 p {
                        background-color: #e9f7e7;
                        padding: 10px;
                        border-radius: 5px;
                        border-left: 5px solid #28a745;
                    }
                    .recommendations {
                        margin-top: 20px;
                        background-color: #f0f8f0;
                        padding: 15px;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h1>Detalles De la Constancia de Envio</h1>
                    <div class='row'>
                        <div class='col-md-6'>
                            <p><strong>Fecha y Hora:</strong> " . $registro['fecha_hora'] . "</p>
                            <p><strong>Nombre:</strong> " . $registro['nombre'] . "</p>
                            <p><strong>Tipo de Identificación:</strong> " . $registro['tipo_identificacion'] . "</p>
                            <p><strong>Asunto:</strong> " . $registro['asunto'] . "</p>
                            <p><strong>Folio:</strong> " . $registro['folio'] . "</p>
                            <p><strong>Teléfono:</strong> " . $registro['telefono'] . "</p>
                            <p><strong>" . $registro['tipo_identificacion'] . "</strong> " . $registro['dni_ruc'] . "</p>
                            <p><strong>Correo:</strong> " . $registro['correo'] . "</p>
                        </div>
                        <div class='col-md-6'>
                            <p><strong>Dirección:</strong> " . $registro['direccion'] . "</p>
                            <p><strong>Estado:</strong> " . $registro['estado'] . "</p>
                            <p><strong>Notas:</strong> " . $registro['notas_referencias'] . "</p>
                            <p><strong>Código de Seguridad:</strong> " . $codigo_seguridad_oculto . "</p>
                            <p><strong>Tipo de Documento:</strong> " . $registro['tipo_documento'] . "</p>
                            <p><strong>Número de Expediente:</strong> " . $registro['numero_expediente'] . "</p>
                            <p><strong>Área:</strong> " . $registro['area'] . "</p>";
                            if ($registro['archivo']) {
                                echo "<p><strong>Archivo adjunto:</strong> <a href='uploads/" . $registro['archivo'] . "' target='_blank' class='btn-custom btn-generar'>Ver archivo</a></p>";
                            }
                        echo "</div>
                    </div>
                    <form action='../TCPDF/examples/detalle.php' method='POST' target='_blank'>
                        <input type='hidden' name='fecha_hora' value='" . $registro['fecha_hora'] . "'>
                        <input type='hidden' name='nombre' value='" . $registro['nombre'] . "'>
                        <input type='hidden' name='tipo_identificacion' value='" . $registro['tipo_identificacion'] . "'>
                        <input type='hidden' name='asunto' value='" . $registro['asunto'] . "'>
                        <input type='hidden' name='folio' value='" . $registro['folio'] . "'>
                        <input type='hidden' name='dni_ruc' value='" . $registro['dni_ruc'] . "'>
                        <input type='hidden' name='correo' value='" . $registro['correo'] . "'>
                        <input type='hidden' name='telefono' value='" . $registro['telefono'] . "'>
                        <input type='hidden' name='direccion' value='" . $registro['direccion'] . "'>
                        <input type='hidden' name='estado' value='" . $registro['estado'] . "'>
                        <input type='hidden' name='notas_referencias' value='" . $registro['notas_referencias'] . "'>
                        <input type='hidden' name='codigo_seguridad' value='" . $registro['codigo_seguridad'] . "'>
                        <input type='hidden' name='tipo_documento' value='" . $registro['tipo_documento'] . "'>
                        <input type='hidden' name='numero_expediente' value='" . $registro['numero_expediente'] . "'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <button type='submit' class='btn-custom btn-generar'>Generar PDF</button>
                            </div>
                            <div class='col-md-6'>
                                <a href='registrar_tramite.php' class='btn-custom btn-volver' onclick='limpiarSesion()'>Volver</a>
                            </div>
                        </div>
                    </form>
                    
                    <div class='recommendations'>
                        <h5>Recomendaciones:</h5>
                        <p><strong>Generar PDF:</strong> Al generar, podrás obtener una ver tu codigo de Seguimiento.</p>
                        <p><strong>Código de Seguimiento:</strong> Después de generar el PDF, asegúrate de guardar el código de seguridad para poder seguir el estado de tu documento en la mesa de partes. No compartas este código con nadie, ya que es único para tu expediente.</p>
                    </div>
                </div>
                <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
                <script>
                    function limpiarSesion() {
                        <?php
                            // Eliminar los datos de la sesión
                            session_unset();
                            session_destroy();
                        ?>
                    }
                </script>
            </body>
        </html>";
} else {
    echo "<p>No se encontraron datos del expediente.</p>";
}  
?>
