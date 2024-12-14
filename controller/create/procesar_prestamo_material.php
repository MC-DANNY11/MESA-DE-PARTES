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
    if (isset($_POST['materiales'])) {
        try {
            // Decodificar los materiales seleccionados
            $materiales = json_decode($_POST['materiales'], true);
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
                    $prestamo = "INSERT INTO tbl_p_material (idSocio, fecha_prestamo, fecha_devolucion) VALUES (?, ?, ?)";
                    $stmt_prestamo = $con->prepare($prestamo);
                    $stmt_prestamo->execute([$idSocioGuardado, $fechaDesde, $fechaHasta]);

                    // Obtener el ID del préstamo insertado
                    $id_prestamo = $con->lastInsertId();

                    // Insertar detalles del préstamo
                    $detalle = "INSERT INTO tbl_dp_material (idPMaterial, idMaterial, cantidad) VALUES (?, ?, ?)";
                    $stmt_detalle = $con->prepare($detalle);

                    // Actualizar el inventario
                    foreach ($materiales as $idMaterial => $material) {
                        // Obtener la disponibilidad actual
                        $disponible = "SELECT disponible FROM tblmateriales WHERE idMaterial = ?";
                        $stmt_disponible = $con->prepare($disponible);
                        $stmt_disponible->execute([$idMaterial]);
                        $resultado = $stmt_disponible->fetch(PDO::FETCH_OBJ);

                        if ($resultado) {
                            $nuevoDisponible = $resultado->disponible - intval($cantidades[$idMaterial]);

                            if ($nuevoDisponible >= 0) { // Solo actualizar si hay suficientes materiales
                                // Actualizar inventario
                                $u_material = "UPDATE tblmateriales SET disponible = ? WHERE idMaterial = ?";
                                $stmt_inv = $con->prepare($u_material);
                                $stmt_inv->execute([$nuevoDisponible, $idMaterial]);

                                // Insertar detalle del préstamo
                                $stmt_detalle->execute([$id_prestamo, $idMaterial, intval($cantidades[$idMaterial])]);
                            } else {
                                $_SESSION['alerta'] = "No hay suficientes copias del material ID: $idMaterial.";
                                header("Location: " . $_SERVER['HTTP_REFERER']);
                                exit();
                            }
                        } else {
                            $_SESSION['alerta'] = "No se encontró el material ID: $idMaterial.";
                            header("Location: " . $_SERVER['HTTP_REFERER']);
                            exit();
                        }
                    }

                    // Registrar en el historial
                    $tblafectada = "Prestamo Materiales";
                    $t_cambio = "Se registró un nuevo préstamo con ID: $id_prestamo";
                    $u_responsable = $_SESSION['id_usuario'];
                    date_default_timezone_set("America/Lima");
                    $fecha_cambio = date('Y-m-d H:i:s');

                    $a_historial = "INSERT INTO tblhistorialcambios (tabla_afectada, id_registro_afectado, tipo_cambio, usuario_responsable_id, fecha_cambio) 
                                    VALUES (?, ?, ?, ?, ?)";
                    $smt_historial = $con->prepare($a_historial);
                    $smt_historial->execute([$tblafectada, $id_prestamo, $t_cambio, $u_responsable, $fecha_cambio]);

                    // Redirigir con mensaje de éxito
                    unset($_SESSION['materialesSeleccionados']);
                    header("Location: " . $_SERVER['HTTP_REFERER'] . "?id_prestamo=" . $id_prestamo);
                    $_SESSION['exito'] = "Préstamo registrado exitosamente.";
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
        $_SESSION['alerta'] = "No se han seleccionado materiales.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    $_SESSION['alerta'] = "No se han enviado datos.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
