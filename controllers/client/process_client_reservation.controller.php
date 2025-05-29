<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../backend/data/db.conexion.php";

function setStatusMessageAndRedirect($message, $type, $redirectTo) {
    $_SESSION['status_message'] = $message;
    $_SESSION['status_type'] = $type;
    header("Location: " . $redirectTo);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $urlRequest = $_POST['url'] ?? '';
    $nombre = $_POST['nombreCliente'] ?? '';
    $apellido = $_POST['apellidoCliente'] ?? '';
    $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';
    $dpiPasaporte = $_POST['dpiPasaporte'] ?? '';
    $telefono = $_POST['telefonoCliente'] ?? '';
    $genero = $_POST['generoCliente'] ?? '';
    $fechaEntrada = $_POST['fechaRegistroHabitacion'] ?? '';
    $fechaSalida = $_POST['fechaSalida'] ?? '';

    $formPage = $urlRequest;

    if (empty($nombre) || empty($fechaNacimiento) || empty($dpiPasaporte) || empty($telefono) || empty($genero) || empty($fechaEntrada) || empty($fechaSalida)) {
        setStatusMessageAndRedirect("Todos los campos marcados como requeridos deben ser completados.", "danger", $formPage);
    }

    if (!strtotime($fechaNacimiento) || !strtotime($fechaEntrada) || !strtotime($fechaSalida)) {
        setStatusMessageAndRedirect("Formato de fecha inválido.", "danger", $formPage);
    }

    if (new DateTime($fechaSalida) <= new DateTime($fechaEntrada)) {
        setStatusMessageAndRedirect("La fecha de salida debe ser posterior a la fecha de entrada de la habitación.", "danger", $formPage);
    }
    
    if (new DateTime($fechaEntrada) < new DateTime(date('Y-m-d'))) {
        setStatusMessageAndRedirect("La fecha de entrada no puede ser en el pasado.", "danger", $formPage);
    }

    try {
        if (!isset($conexion) || !$conexion) {
            setStatusMessageAndRedirect("Error: No se pudo establecer conexión a la base de datos.", "danger", $formPage);
        }

        $conexion->beginTransaction();

        $stmt = $conexion->prepare("
            SELECT h.ID_Habitacion, h.Numero_Habitacion, h.Nombre_Habitacion
            FROM Habitacion h
            LEFT JOIN Reservacion r ON h.ID_Habitacion = r.FK_ID_Habitacion
                AND (
                    (r.Fecha_Entrada < :fechaSalida AND r.Fecha_Salida > :fechaEntrada)
                )
            WHERE r.ID_Reservacion IS NULL
            GROUP BY h.ID_Habitacion
            ORDER BY h.Numero_Habitacion ASC
        ");
        $stmt->bindParam(':fechaEntrada', $fechaEntrada, PDO::PARAM_STR);
        $stmt->bindParam(':fechaSalida', $fechaSalida, PDO::PARAM_STR);
        $stmt->execute();
        $habitacionesDisponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($habitacionesDisponibles)) {
            $conexion->rollBack();
            setStatusMessageAndRedirect("Lo sentimos, no hay habitaciones disponibles para las fechas seleccionadas.", "danger", $formPage);
        }

        $fechaNacimientoObj = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacimientoObj)->y;


        $edadMinima = 18; 
        if ($edad < $edadMinima) {
            $conexion->rollBack();
            setStatusMessageAndRedirect("El cliente debe tener al menos " . $edadMinima . " años de edad para realizar una reserva.", "danger", $formPage);
        }

        $stmt = $conexion->prepare("SELECT ID_Cliente FROM Cliente WHERE DPI_Pasaporte = :dpiPasaporte AND Fecha_Nacimiento = :fechaNacimiento");
        $stmt->bindParam(':dpiPasaporte', $dpiPasaporte, PDO::PARAM_STR);
        $stmt->bindParam(':fechaNacimiento', $fechaNacimiento, PDO::PARAM_STR);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        $ID_Cliente = null;

        if ($cliente) {
            $ID_Cliente = $cliente['ID_Cliente'];
        } else {
            $stmt = $conexion->prepare("INSERT INTO Cliente (Nombre, Apellido, Fecha_Nacimiento, DPI_Pasaporte, Telefono, Genero) VALUES (:nombre, :apellido, :fechaNacimiento, :dpiPasaporte, :telefono, :genero)");
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
            $stmt->bindParam(':fechaNacimiento', $fechaNacimiento, PDO::PARAM_STR);
            $stmt->bindParam(':dpiPasaporte', $dpiPasaporte, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
            $stmt->execute();
            $ID_Cliente = $conexion->lastInsertId();
        }

        if (!$ID_Cliente) {
            $conexion->rollBack();
            setStatusMessageAndRedirect("No se pudo obtener o crear el ID del cliente.", "danger", $formPage);
        }

        $ID_Habitacion_Asignada = null;
        $numeroHabitacionAsignada = '';

        if ($edad < 40) {
            $lastRoom = end($habitacionesDisponibles);
            $ID_Habitacion_Asignada = $lastRoom['ID_Habitacion'];
            $numeroHabitacionAsignada = $lastRoom['Numero_Habitacion'];
        } else {
            $firstRoom = $habitacionesDisponibles[0];
            $ID_Habitacion_Asignada = $firstRoom['ID_Habitacion'];
            $numeroHabitacionAsignada = $firstRoom['Numero_Habitacion'];
        }
        $estadoReservacion = 'reservada';

        $stmt = $conexion->prepare("INSERT INTO Reservacion (FK_ID_Cliente, FK_ID_Habitacion, Fecha_Entrada, Fecha_Salida, Estado) VALUES (:idCliente, :idHabitacion, :fechaEntrada, :fechaSalida, :estado)");
        $stmt->bindParam(':idCliente', $ID_Cliente, PDO::PARAM_INT);
        $stmt->bindParam(':idHabitacion', $ID_Habitacion_Asignada, PDO::PARAM_INT);
        $stmt->bindParam(':fechaEntrada', $fechaEntrada, PDO::PARAM_STR);
        $stmt->bindParam(':fechaSalida', $fechaSalida, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estadoReservacion, PDO::PARAM_STR);
        $stmt->execute();

        $conexion->commit();
        setStatusMessageAndRedirect("¡Reserva realizada con éxito! Habitación asignada: " . $numeroHabitacionAsignada, "success", $formPage); 

    } catch (PDOException $e) {
        $conexion->rollBack();
        error_log("Error en el procesamiento de reserva: " . $e->getMessage());
        setStatusMessageAndRedirect("Ha ocurrido un error inesperado al procesar la reserva. Por favor, inténtelo de nuevo más tarde.", "danger", $formPage);
    }

} else {
    setStatusMessageAndRedirect("Acceso no autorizado. La solicitud no es POST.", "danger", $formPage);
}
?>