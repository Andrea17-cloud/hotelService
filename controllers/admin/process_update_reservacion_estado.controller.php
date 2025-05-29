<?php

session_start(); // Inicia la sesión

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../backend/data/db.conexion.php";

// Función para guardar el mensaje en la sesión y redirigir
function setStatusMessageAndRedirect($message, $type, $redirectTo) {
    $_SESSION['status_message'] = $message;
    $_SESSION['status_type'] = $type;
    header("Location: " . $redirectTo);
    exit();
}

$reservationsPage = '../../admin/secciones/reservaciones.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservationId = $_POST['reservation_id'] ?? null;
    $newStatus = $_POST['new_status'] ?? null;

    if (empty($reservationId) || empty($newStatus)) {
        setStatusMessageAndRedirect("Error: ID de reserva o nuevo estado no proporcionado.", "danger", $reservationsPage);
    }

    $allowedStatuses = ['activa', 'finalizada', 'cancelada', 'pendiente'];
    if (!in_array($newStatus, $allowedStatuses)) {
        setStatusMessageAndRedirect("Error: Estado inválido.", "danger", $reservationsPage);
    }

    try {
        $stmt = $conexion->prepare("UPDATE Reservacion SET Estado = :newStatus WHERE ID_Reservacion = :id");
        $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
        $stmt->bindParam(':id', $reservationId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            setStatusMessageAndRedirect("Estado de la reserva ID " . htmlspecialchars($reservationId) . " actualizado a '" . htmlspecialchars(ucfirst($newStatus)) . "'.", "success", $reservationsPage);
        } else {
            setStatusMessageAndRedirect("Error al actualizar el estado de la reserva. (DB Error)", "danger", $reservationsPage);
        }

    } catch (PDOException $e) {
        error_log("Error en la actualización de estado de reserva: " . $e->getMessage());
        setStatusMessageAndRedirect("Ha ocurrido un error inesperado al actualizar la reserva: " . $e->getMessage(), "danger", $reservationsPage);
    }

} else {
    // Si la solicitud no es POST, redirigir
    setStatusMessageAndRedirect("Acceso no autorizado.", "danger", $reservationsPage);
}
?>