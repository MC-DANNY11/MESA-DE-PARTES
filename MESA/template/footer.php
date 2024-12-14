        

<script>
    // Resaltar enlace activo según la URL actual
    const currentUrl = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(link => {
        if (currentUrl.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });

    // Función para mostrar/ocultar el menú lateral (opcional si deseas hacerlo responsivo)
    function toggleMenu() {
        document.querySelector('.sidebar').classList.toggle('active');
    }
</script>

        <!-- Font Awesome para los íconos -->
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous">
        < /scrip> <!--Bootstrap JS-- > <
        script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" >
        </script>
        <script src="scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
            integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
        </script>
        </body>

</html>