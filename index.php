<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesa de Partes Virtual</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
            padding: 10px 30px;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            z-index: 1000;
        }
        .header img {
            max-height: 60px;
        }
        .login {
            font-size: 1em;
            font-weight: bold;
            color: #00796b; /* Updated color */
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }
        .login img {
            height: 24px;
            width: 24px;
            margin-left: 8px;
        }
        .welcome-text {
            font-size: 1.8em;
            font-weight: bold;
            color: #00796b; /* Updated color */
            text-align: center;
            margin: 80px 0 20px; /* Adjusted for fixed header */
        }
        .welcome-container {
            text-align: center;
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 20px;
            width: 90%;
            max-width: 800px;
        }
        .header-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #00796b;
            text-align: center;
            margin: 10px 0;
        }
        .header-subtitle {
            font-size: 1.2em;
            color: #555;
            text-align: center;
            margin: 5px 0 20px;
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
            border-radius: 8px;
            object-fit: cover;
        }
        .footer {
            background-color:rgb(6, 140, 140); /* Updated color */
            color:rgb(251, 251, 251);
            text-align: center;
            padding: 10px;
            box-sizing: border-box;
            width: 100%;
            position: relative;
            bottom: 0;
        }
        .footer .welcome-button {
            background-color:rgb(1, 174, 151); /* Updated color */
            color: #ffff;
            padding: 5px 20px;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .footer .welcome-button:hover {
            background-color: #008f7a; /* Darker shade for hover */
        }
        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-wrap: wrap;
                justify-content: center;
            }
            .welcome-text {
                font-size: 1.5em;
                margin: 70px 0 15px; /* Adjusted for fixed header */
            }
            .carousel img {
                max-height: 250px;
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
        <h1 class="header-title">Palca Gestión 2023 - 2026</h1>
        <p class="header-subtitle">Bienvenido a nuestra plataforma virtual de mesa de partes</p>
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