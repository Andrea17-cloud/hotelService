<?php
include "../../components/header/head.php";
include "../../backend/data/db.conexion.php"; 

include "../../components/cards/cardRoom.php";

function calculateAge($birthdate) {
    if (empty($birthdate) || $birthdate === '0000-00-00') {
        return null;
    }
    $dob = new DateTime($birthdate);
    $now = new DateTime();
    $age = $now->diff($dob)->y;
    return $age;
}

?>

<body class="g-sidenav-show bg-gray-200 dark-version">
    <?php
    $controllerActive = "Rooms";
    include "../../components/menu/sideMenu.php";
    ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php
        include "../../components/header/header.php";
        ?>
        <div class="container-fluid py-4">

            <div class="row g-4">
                <?php
                $allRoomsData = [];
                $today = date('Y-m-d'); // Obtiene la fecha actual

                try {
                    $stmt = $conexion->prepare("
                        SELECT
                            h.ID_Habitacion,
                            h.Nombre_Habitacion,
                            h.Numero_Habitacion,
                            r.ID_Reservacion,
                            r.Estado AS EstadoReservacion,
                            c.Nombre AS NombreCliente,
                            c.Apellido AS ApellidoCliente,
                            c.Fecha_Nacimiento AS FechaNacimientoCliente,
                            fe.Total AS TotalCargos -- NUEVO: Traemos el total de cargos del Factura_Encabezado
                        FROM
                            Habitacion h
                        LEFT JOIN
                            Reservacion r ON h.ID_Habitacion = r.FK_ID_Habitacion
                            AND r.Estado = 'en curso' -- Fíjate que aquí usamos 'en curso' con espacio
                            AND :today BETWEEN r.Fecha_Entrada AND r.Fecha_Salida
                        LEFT JOIN
                            Cliente c ON r.FK_ID_Cliente = c.ID_Cliente
                        LEFT JOIN
                            Factura_Encabezado fe ON r.ID_Reservacion = fe.FK_ID_Reservacion -- NUEVO: Unimos con Factura_Encabezado
                        ORDER BY
                            CASE
                                WHEN r.Estado = 'en curso' AND :today BETWEEN r.Fecha_Entrada AND r.Fecha_Salida THEN 0
                                ELSE 1
                            END,
                            h.Numero_Habitacion ASC;
                    ");
                    $stmt->bindParam(':today', $today);
                    $stmt->execute();
                    $allRoomsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                } catch (PDOException $e) {
                    error_log("Error al cargar habitaciones y reservas: " . $e->getMessage());
                    echo '<div class="alert alert-danger mx-3" role="alert">Error al cargar la información de las habitaciones: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    $allRoomsData = [];
                }

                if (!empty($allRoomsData)) {
                    foreach ($allRoomsData as $room) {
                        $currentStatus = 'vacía';
                        $clientName = '';
                        $clientAge = '';
                        $totalCargos = 0; // Inicializamos en 0

                        if (!empty($room['ID_Reservacion']) && $room['EstadoReservacion'] === 'en curso') {
                            $currentStatus = 'ocupada';
                            $clientName = htmlspecialchars($room['NombreCliente'] . ' ' . $room['ApellidoCliente']);
                            $clientAge = calculateAge($room['FechaNacimientoCliente']);
                            $totalCargos = $room['TotalCargos'] ?? 0; // Asignar el total, si es null, se queda en 0
                        }

                        echo cardRoom([
                            'image' => '../../assets/img/hotelRooms/room.png',
                            'title' => htmlspecialchars($room['Nombre_Habitacion']),
                            'numero_habitacion' => htmlspecialchars($room['Numero_Habitacion']),
                            'status' => $currentStatus,
                            'client_name' => $clientName,
                            'client_age' => $clientAge,
                            'reservation_id' => $room['ID_Reservacion'], // Pasamos el ID de la reserva
                            'edit_url_id' => htmlspecialchars($room['ID_Habitacion']), // El ID de la habitación para detalles
                            'edit_url' => 'detalle_habitacion.php?id=' . htmlspecialchars($room['ID_Habitacion']), // URL para ver detalles generales de la habitación
                            'total_cargos' => $totalCargos // Pasamos el total de cargos
                        ]);
                    }
                } else {
                    echo '<div class="col-12"><div class="alert alert-info mx-3" role="alert">No hay habitaciones registradas o un error impidió cargarlas.</div></div>';
                }
                ?>

            </div>
            <div class="row mt-4"></div>
            <div class="row mb-4"></div>

        </div>
        <?php
        include "../../components/footer/footer.php";
        ?>
    </main>

    <?php
    include "../../components/config.php";
    include "../../components/footer/footerDependence.php";
    ?>

</body>

</html>