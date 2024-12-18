<?php
require '../config/db_connection.php';

$expediente = null;
$seguimiento = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['codigo_seguridad']) && !empty($_POST['codigo_seguridad'])) {
        $codigo_seguridad = $_POST['codigo_seguridad'];
        
        // Fetch the expediente based on the security code
        $stmt = $pdo->prepare("SELECT * FROM expedientes WHERE codigo_seguridad = ?");
        $stmt->execute([$codigo_seguridad]);
        $expediente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If expediente is found, fetch the latest seguimiento record
        if ($expediente) {
            $id_expediente = $expediente['id_expediente']; // Assuming 'id_expediente' is the primary key
            $seguimiento_stmt = $pdo->prepare("SELECT * FROM seguimiento WHERE id_expediente = ? ORDER BY fecha_hora DESC LIMIT 1");
            $seguimiento_stmt->execute([$id_expediente]);
            $seguimiento = $seguimiento_stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "<script>alert('Código de seguridad no encontrado.');</script>";
        }
    } else {
        echo "<script>alert('Por favor, ingresa un código de seguridad.');</script>";
    }
}

// Display the current date
$current_date = date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Trámite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            background-color: #3ba99c; /* Background color */
            color: white;
            padding: 10px;
            margin: 0 -20px 20px; /* Adjust margin to ensure it aligns with the container */
            font-size: 1.5em;
            text-align: center;
            border-radius: 5px; /* Optional: Add rounded corners */
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .result-section h3 {
            margin-top: 0;
            font-size: 1.5em;
            color: #333;
            border-bottom: 2px solid #3ba99c;
            padding-bottom: 10px;
        }
        .result-section p {
            font-size: 1em;
            margin: 5px 0;
            padding: 10px;
            border-left: 4px solid #3ba99c;
            background-color: #e9ecef;
            border-radius: 5px;
        }
        .result-section .file-link {
            color: #007bff;
            text-decoration: none;
        }
        .result-section .file-link:hover {
            text-decoration: underline;
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
                <p><strong>Fecha:</strong> <?php echo $expediente['fecha_hora']; ?></p>
                
                <!-- Mostrar detalles del seguimiento -->
                <?php if ($seguimiento): ?>
                    <h3>Detalles del Seguimiento</h3>
                    <p><strong>Fecha y Hora:</strong> <?php echo $seguimiento['fecha_hora']; ?></p>
                    <p><strong>Estado:</strong> <?php echo $expediente['estado']; ?></p>
                    <p><strong>Respuesta:</strong> <?php echo !empty($seguimiento['respuesta']) ? $seguimiento['respuesta'] : 'No hay respuesta registrada.'; ?></p>
                    
                    <!-- Mostrar archivo adjunto -->
                    <?php if (!empty($seguimiento['adjunto'])): ?>
                        <p><strong>Archivo Adjunto:</strong>
                            <a class="file-link" href="../respuesta_archivos/<?php echo htmlspecialchars($seguimiento['adjunto']); ?>" target="_blank">
                                <i class="fas fa-file-download"></i> Ver Archivo
                            </a>
                        </p>
                    <?php else: ?>
                        <p><strong>Archivo Adjunto:</strong > No hay archivo adjunto.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>No hay seguimiento registrado para este expediente.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>