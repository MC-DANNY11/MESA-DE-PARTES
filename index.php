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
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #e0e0e0;
            padding: 10px 20px;
            width: 100%;
            box-sizing: border-box;
        }
        .header img {
            max-height: 60px;
        }
        .login {
            font-size: 1em;
            font-weight: bold;
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .login img {
            height: 24px;
            width: 24px;
            margin-left: 8px;
        }
        .welcome-text {
            font-size: 1.5em;
            font-weight: bold;
            color: #0056b3;
            text-align: center;
            margin: 10px 0;
        }
        .welcome-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .carousel-container {
            width: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
        }
        .carousel {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            gap: 10px;
        }
        .carousel::-webkit-scrollbar {
            display: none;
        }
        .carousel img {
            width: 100%;
            height: auto;
            max-height: 400px;
            scroll-snap-align: center;
            flex-shrink: 0;
            margin-right: 10px;
            object-fit: cover;
        }
        .footer {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
            width: 100%;
        }
        .footer .welcome-button {
            background-color: #0056b3;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .footer .welcome-button:hover {
            background-color: #00bfa5;
        }
        /* Responsivo */
        @media (max-width: 768px) {
            .header {
                flex-wrap: wrap;
                justify-content: center;
            }
            .login {
                margin-top: 10px;
                justify-content: center;
            }
            .carousel img {
                max-height: 200px;
            }
            .welcome-text {
                font-size: 1.2em;
                margin: 10px 0;
            }
        }
        @media (min-width: 1024px) {
            .welcome-container {
                width: 80%;
            }
        }
    </style>
</head>
<body>

<!-- Encabezado -->
<div class="header">
    <div>
        <img src="imagenes/ESCUDO DISTRITO DE PALCA.png" alt="Escudo">
    </div>
    <div>
        <img src="imagenes/gestion.jpg" alt="Gestión 2023-2026">
    </div>
    <a href="login.php" class="login">
        Login <img src="imagenes/login.png" alt="Icono de Login">
    </a>
</div>

<p class="welcome-text">Bienvenido a nuestra plataforma virtual de mesa de partes</p>

<!-- Contenedor principal del carrusel -->
<div class="welcome-container">
    <div class="carousel-container">
        <div class="carousel" id="carousel">
            <img src="imagenes/img1.jpg" alt="Imagen 1">
            <img src="imagenes/img2.jpg" alt="Imagen 2">
            <img src="imagenes/img3.jpg" alt="Imagen 3">
            <img src="imagenes/img4.jpg" alt="Imagen 4">
        </div>
    </div>
</div>

<!-- Pie de página -->
<div class="footer">
    <p>Realiza tus trámites <strong>sin salir de casa</strong></p>
    <a href="mesa_virtual/index.php" class="welcome-button">Ir a la Mesa De Partes Virtual</a>
</div>

<script>
    const carousel = document.getElementById('carousel');
    let scrollAmount = 0;
    let autoScrollInterval;

    function startAutoScroll() {
        autoScrollInterval = setInterval(() => {
            if (scrollAmount >= carousel.scrollWidth - carousel.clientWidth) {
                scrollAmount = 0;
            } else {
                scrollAmount += carousel.clientWidth;
            }
            carousel.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }, 3000);
    }

    function stopAutoScroll() {
        clearInterval(autoScrollInterval);
    }

    startAutoScroll();

    carousel.addEventListener('mousedown', stopAutoScroll);
    carousel.addEventListener('touchstart', stopAutoScroll);
    carousel.addEventListener('mouseup', startAutoScroll);
    carousel.addEventListener('touchend', startAutoScroll);
    carousel.addEventListener('scroll', () => {
        clearTimeout(autoScrollInterval);
        autoScrollInterval = setTimeout(startAutoScroll, 3000);
    });
</script>

</body>
</html>
