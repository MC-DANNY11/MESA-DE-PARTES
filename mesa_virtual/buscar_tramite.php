<?php
require '../config/db_connection.php';

$expediente = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['codigo_seguridad']) && !empty($_POST['codigo_seguridad'])) {
        $codigo_seguridad = $_POST['codigo_seguridad'];
        $stmt = $pdo->prepare("SELECT * FROM expedientes WHERE codigo_seguridad = ?");
        $stmt->execute([$codigo_seguridad]);
        $expediente = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<script>alert('Por favor, ingresa un código de seguridad.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Trámite</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 90%;
            max-width: 700px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #2c6da3;
            color: white;
            padding: 10px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header img {
            height: 50px;
            margin-left: 10px;
        }
        .header h1 {
            font-size: 1.5em;
            flex-grow: 1;
            margin: 0;
            text-align: center;
        }
        .header .back-button img {
            height: 30px; /* Ajusta el tamaño del ícono de volver */
            margin-right: 10px;
        }
        .search-section {
            padding: 20px;
        }
        .search-section h2 {
            background-color: #3ba99c;
            color: white;
            padding: 10px;
            margin: 0 -20px 20px;
            font-size: 1.5em;
            text-align: center;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .form-group input[type="text"] {
            width: 48%;
            padding: 10px;
            font-size: 1.1em;
            border-radius: 5px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            font-size: 1.1em;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .result-section {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
        .result-section h3 {
            margin-top: 0;
            font-size: 1.2em;
            color: #333;
        }
        .result-section p {
            font-size: 1em;
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="Imagenes/escudo.png" alt="Escudo">
        <h1>MESA DE PARTES VIRTUAL</h1>
        <a href="../index.php" class="back-button">
            <img src="Imagenes/Volver.png" alt="Volver"> <!-- Aquí va el ícono de volver -->
        </a>
    </div>
    <div class="search-section">
        <h2>Buscar Trámite</h2>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="numero_expediente" placeholder="Ingrese N° expediente" required>
                <input type="text" name="codigo_seguridad" placeholder="Ingrese Código de Seguridad" required>
            </div>
            <div class="form-group">
                <button type="submit">🔍 Buscar</button>
            </div>
        </form>

        <?php if ($expediente): ?>
            <div class="result-section">
                <h3>Detalles del Trámite</h3>
                <p><strong>Remitente:</strong> <?php echo $expediente['remitente']; ?></p>
                <p><strong>Asunto:</strong> <?php echo $expediente['asunto']; ?></p>
                <p><strong>Estado:</strong> <?php echo $expediente['estado']; ?></p>
                <p><strong>Fecha:</strong> <?php echo $expediente['fecha_hora']; ?></p>
                
                <!-- Mostrar respuesta -->
                <?php if (!empty($expediente['respuesta'])): ?>
                    <p><strong>Respuesta:</strong> <?php echo $expediente['respuesta']; ?></p>
                <?php else: ?>
                    <p><strong>Respuesta:</strong> No hay respuesta registrada.</p>
                <?php endif; ?>

                <!-- Mostrar archivo adjunto -->
                <?php if (!empty($expediente['archivo_respuesta'])): ?>
                    <p><strong>Archivo Adjunto:</strong>
                        <a href="../respuesta_archivos/<?php echo htmlspecialchars($expediente['archivo_respuesta']); ?>" target="_blank">
                            Ver PDF
                        </a>
                    </p>
                <?php else: ?>
                    <p><strong>Archivo Adjunto:</strong> No hay archivo adjunto.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
