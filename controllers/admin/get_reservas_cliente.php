<?php
session_start();
include "../../backend/data/db.conexion.php";

header('Content-Type: application/json');


if (!isset($_GET['dpi']) || empty($_GET['dpi'])) {
    echo json_encode(['status' => 'error', 'message' => 'DPI o Pasaporte no proporcionado.']);
    exit();
}

$dpi = $_GET['dpi'];

try {
    $stmt = $conexion->prepare("
        SELECT
            r.ID_Reservacion,
            r.Fecha_Entrada,
            r.Fecha_Salida,
            r.Estado AS EstadoReservacion,
            h.ID_Habitacion, -- Asegura que el ID de la habitación también esté disponible
            h.Numero_Habitacion,
            h.Nombre_Habitacion,
            c.Nombre AS NombreCliente,
            c.Apellido AS ApellidoCliente,
            c.Fecha_Nacimiento AS FechaNacimientoCliente
        FROM Reservacion r
        JOIN Cliente c ON r.FK_ID_Cliente = c.ID_Cliente
        JOIN Habitacion h ON r.FK_ID_Habitacion = h.ID_Habitacion
        WHERE c.DPI_Pasaporte = :dpi
          AND r.Estado = 'en curso'
        ORDER BY r.Fecha_Entrada ASC
    ");
    $stmt->bindParam(':dpi', $dpi, PDO::PARAM_STR);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($reservations) {
        echo json_encode(['status' => 'success', 'reservations' => $reservations]);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No se encontraron reservaciones en curso para este DPI/Pasaporte.', 'reservations' => []]);
    }

} catch (PDOException $e) {
    error_log("Error en get_reservas_cliente.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos al buscar reservaciones.']);
} catch (Exception $e) {
    error_log("Error general en get_reservas_cliente.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error inesperado al buscar reservaciones.']);
}
?>