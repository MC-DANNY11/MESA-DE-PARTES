<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesa de Partes Virtual</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 700px;
        }
        .header {
            background-color: #2c6da3;
            color: white;
            padding: 10px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .header img {
            height: 60px;
        }
        .header h2 {
            font-size: 1.5em;
            margin: 0;
        }
        .options {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .option {
            width: 160px;
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: background-color 0.3s;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }
        .option:hover {
            background-color: #e2e6ea;
        }
        .option img {
            width: 60px;
            height: 60px;
        }
        .option p {
            margin: 10px 0 0;
            font-weight: bold;
            color: #333;
            font-size: 1.2em;
        }
        .notice {
            background-color: #6fa8dc;
            color: white;
            padding: 10px;
            margin-top: 20px;
            border-radius: 8px;
            font-size: 1.1em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .notice img {
            width: 30px;
            height: 30px;
        }
        .notice p {
            margin: 0;
            text-align: left;
        }
        .back-button-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .back-button {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            background-color: #6fa8dc;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 1.1em;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #5a99c5;
        }
        .back-button img {
            width: 20px;
            margin-right: 5px;
        }
        /* Ajustes responsivos para pantallas pequeñas */
        @media (max-width: 600px) {
            .options {
                flex-direction: column;
                gap: 15px;
            }
            .option {
                width: 100%;
                max-width: 300px;
                padding: 15px;
            }
            .header h2 {
                font-size: 1.2em;
            }
            .notice {
                font-size: 1em;
                padding: 8px;
            }
            .back-button {
                font-size: 1em;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="Imagenes/escudo.png" alt="Escudo">
        <h2>MUNICIPALIDAD DISTRITAL DE PALCA - LAMPA</h2>
    </div>
    <div class="options">
        <a href="registrar_tramite.php" class="option">
            <img src="Imagenes/registrar.png" alt="Registrar Trámite">
            <p>Registrar Trámite</p>
        </a>
        <a href="buscar_tramite.php" class="option">
            <img src="Imagenes/buscar.png" alt="Buscar Trámite">
            <p>Buscar Trámite</p>
        </a>
    </div>
    <div class="notice">
        <img src="Imagenes/notificacion.png" alt="Notificación">
        <p><strong>Estimado usuario:</strong> El horario para envío de documentos es de 08:00 am a 05:30 pm. Fuera de horario de envío, se considera al siguiente día hábil.</p>
    </div>
    <div class="back-button-container">
        <a href="../index.php" class="back-button">
            <img src="Imagenes/Volver.png" alt="Volver">
            VOLVER
        </a>
    </div>
</div>

</body>
</html>
