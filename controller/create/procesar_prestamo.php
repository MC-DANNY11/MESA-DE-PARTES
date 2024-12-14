<?php
session_start();
include '../../config/dbase/conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Manejo de la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['libros'])) {
        try {
            // Decodificar los libros seleccionados
            $libros = json_decode($_POST['libros'], true);
            $cantidades = $_POST['cantidad'];

            // Obtener datos del formulario
            $dni = $_POST['dni'] ?? null;
            $fechaDesde = $_POST['fecha'] ?? null;
            $fechaHasta = $_POST['hasta'] ?? null;

            if ($dni) {
                // Verificar si el socio existe
                $socio = "SELECT idSocio FROM tblsocios WHERE dni = ?";
                $stmt_socio = $con->prepare($socio);
                $stmt_socio->execute([$dni]);
                $idsocio = $stmt_socio->fetch(PDO::FETCH_OBJ);

                if ($idsocio) {
                    $idSocioGuardado = $idsocio->idSocio;

                    // Insertar préstamo
                    $prestamo = "INSERT INTO tblprestamos (socio_id, fecha_prestamo, fecha_devolucion) VALUES (?, ?, ?)";
                    $stmt_prestamo = $con->prepare($prestamo);
                    $stmt_prestamo->execute([$idSocioGuardado, $fechaDesde, $fechaHasta]);

                    // Obtener el ID del préstamo insertado
                    $id_prestamo = $con->lastInsertId();

                    // Insertar detalles del préstamo
                    $detalle = "INSERT INTO tbldetalleprestamos (prestamo_id, libro_id, cantidad) VALUES (?, ?, ?)";
                    $stmt_detalle = $con->prepare($detalle);

                    // Actualizar el inventario
                    foreach ($libros as $idLibro => $libro) {
                        // Obtener la disponibilidad actual
                        $disponible = "SELECT disponible FROM tblinventarios WHERE libro_id = ?";
                        $stmt_disponible = $con->prepare($disponible);
                        $stmt_disponible->execute([$idLibro]);
                        $resultado = $stmt_disponible->fetch(PDO::FETCH_OBJ);

                        if ($resultado) {
                            $nuevoDisponible = $resultado->disponible - intval($cantidades[$idLibro]);

                            if ($nuevoDisponible >= 0) { // Solo actualizar si hay suficientes libros
                                // Actualizar inventario
                                $u_inventario = "UPDATE tblinventarios SET disponible = ? WHERE libro_id = ?";
                                $stmt_inv = $con->prepare($u_inventario);
                                $stmt_inv->execute([$nuevoDisponible, $idLibro]);

                                // Insertar detalle del préstamo
                                $stmt_detalle->execute([$id_prestamo, $idLibro, intval($cantidades[$idLibro])]);
                            } else {
                                $_SESSION['alerta'] = "No hay suficientes copias del libro ID: $idLibro.";
                                header("Location: " . $_SERVER['HTTP_REFERER']);
                                exit();
                            }
                        } else {
                            $_SESSION['alerta'] = "No se encontró el libro ID: $idLibro.";
                            header("Location: " . $_SERVER['HTTP_REFERER']);
                            exit();
                        }
                    }

                    // Registrar en el historial
                    $tblafectada = "Prestamos";
                    $t_cambio = "Se registró un nuevo préstamo con ID: $id_prestamo";
                    $u_responsable = $_SESSION['id_usuario'];
                    date_default_timezone_set("America/Lima");
                    $fecha_cambio = date('Y-m-d H:i:s');

                    $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) 
                                    VALUES (?, ?, ?, ?, ?)";
                    $smt_historial = $con->prepare($a_historial);
                    $smt_historial->execute([$tblafectada, $id_prestamo, $t_cambio, $u_responsable, $fecha_cambio]);

                   
                    header("Location: " . $_SERVER['HTTP_REFERER'] . "?id_prestamo=" . $id_prestamo);

                    unset($_SESSION['librosSeleccionados']);
                    exit();
                } else {
                    $_SESSION['alerta'] = "No se encontró un socio con el DNI proporcionado.";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            } else {
                $_SESSION['alerta'] = "DNI no proporcionado.";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['alerta'] = "Error al procesar el préstamo: " . $e->getMessage();
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } catch (Exception $e) {
            $_SESSION['alerta'] = $e->getMessage();
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        $_SESSION['alerta'] = "No se han seleccionado libros.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    $_SESSION['alerta'] = "No se han enviado datos.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
