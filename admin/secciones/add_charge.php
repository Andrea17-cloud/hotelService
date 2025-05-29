<?php
include "../../components/header/head.php";
include "../../backend/data/db.conexion.php";

$selectedReservationId = null;
$reservationDetails = null;
$availableCharges = [];
$message = '';
$messageType = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_reservation_id'])) {
    $selectedReservationId = $_POST['selected_reservation_id'];

    try {
        $stmtReserva = $conexion->prepare("
            SELECT
                r.ID_Reservacion,
                r.Fecha_Entrada,
                r.Fecha_Salida,
                r.Estado,
                h.Nombre_Habitacion,
                h.Numero_Habitacion,
                c.Nombre AS NombreCliente,
                c.Apellido AS ApellidoCliente
            FROM
                Reservacion r
            JOIN
                Habitacion h ON r.FK_ID_Habitacion = h.ID_Habitacion
            JOIN
                Cliente c ON r.FK_ID_Cliente = c.ID_Cliente
            WHERE
                r.ID_Reservacion = :reservation_id
        ");
        $stmtReserva->bindParam(':reservation_id', $selectedReservationId);
        $stmtReserva->execute();
        $reservationDetails = $stmtReserva->fetch(PDO::FETCH_ASSOC);

        if (!$reservationDetails) {
            $message = "Reservación no encontrada.";
            $messageType = "danger";
            $selectedReservationId = null;
        }

        $stmtCargos = $conexion->prepare("SELECT ID_Cargo, Descripcion, Precio FROM Cargo ORDER BY Descripcion ASC");
        $stmtCargos->execute();
        $availableCharges = $stmtCargos->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Error al cargar detalles de reserva o cargos: " . $e->getMessage());
        $message = "Error al cargar los datos: " . $e->getMessage();
        $messageType = "danger";
        $selectedReservationId = null;
    }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_charges'])) {
    $selectedReservationId = $_POST['reservation_id_for_charges'];
    $chargesToAdd = $_POST['charges'] ?? [];

    if (!empty($selectedReservationId) && !empty($chargesToAdd)) {
        try {
            $conexion->beginTransaction();

            $stmtFacturaEnc = $conexion->prepare("SELECT ID_Factura_Encabezado, Total FROM Factura_Encabezado WHERE FK_ID_Reservacion = :reservation_id");
            $stmtFacturaEnc->bindParam(':reservation_id', $selectedReservationId);
            $stmtFacturaEnc->execute();
            $facturaEncabezado = $stmtFacturaEnc->fetch(PDO::FETCH_ASSOC);

            if (!$facturaEncabezado) {

                $stmtInsertEnc = $conexion->prepare("INSERT INTO Factura_Encabezado (FK_ID_Reservacion, Total, Fecha_Factura, Estatus) VALUES (:reservation_id, 0, NOW(), 'pendiente')");
                $stmtInsertEnc->bindParam(':reservation_id', $selectedReservationId);
                $stmtInsertEnc->execute();
                $facturaEncabezadoId = $conexion->lastInsertId();
                $currentTotal = 0;
            } else {
                $facturaEncabezadoId = $facturaEncabezado['ID_Factura_Encabezado'];
                $currentTotal = $facturaEncabezado['Total'];
            }

            $newTotalAmount = $currentTotal;


            foreach ($chargesToAdd as $cargoId => $quantity) {
                $quantity = (int)$quantity; 
                if ($quantity > 0) {
                    $stmtPrecio = $conexion->prepare("SELECT Precio FROM Cargo WHERE ID_Cargo = :cargo_id");
                    $stmtPrecio->bindParam(':cargo_id', $cargoId);
                    $stmtPrecio->execute();
                    $precioUnitario = $stmtPrecio->fetchColumn();

                    if ($precioUnitario !== false) {
                        $subtotal = $precioUnitario * $quantity;
                        $newTotalAmount += $subtotal;

                        $stmtInsertDetalle = $conexion->prepare("
                            INSERT INTO Factura_Detalle (FK_ID_Factura_Encabezado, FK_ID_Cargo, Cantidad, Precio_Unitario, Subtotal)
                            VALUES (:factura_enc_id, :cargo_id, :cantidad, :precio_unitario, :subtotal)
                        ");
                        $stmtInsertDetalle->bindParam(':factura_enc_id', $facturaEncabezadoId);
                        $stmtInsertDetalle->bindParam(':cargo_id', $cargoId);
                        $stmtInsertDetalle->bindParam(':cantidad', $quantity);
                        $stmtInsertDetalle->bindParam(':precio_unitario', $precioUnitario);
                        $stmtInsertDetalle->bindParam(':subtotal', $subtotal);
                        $stmtInsertDetalle->execute();
                    }
                }
            }

            $stmtUpdateEnc = $conexion->prepare("UPDATE Factura_Encabezado SET Total = :new_total WHERE ID_Factura_Encabezado = :factura_enc_id");
            $stmtUpdateEnc->bindParam(':new_total', $newTotalAmount);
            $stmtUpdateEnc->bindParam(':factura_enc_id', $facturaEncabezadoId);
            $stmtUpdateEnc->execute();

            $conexion->commit();
            $message = "Cargos añadidos exitosamente. Total actualizado: Q" . number_format($newTotalAmount, 2);
            $messageType = "success";


            $_SESSION['status_message'] = $message;
            $_SESSION['status_type'] = $messageType;
            header("Location: add_charge.php?reservation_id=" . $selectedReservationId);
            exit();

        } catch (PDOException $e) {
            $conexion->rollBack();
            error_log("Error al añadir cargos: " . $e->getMessage());
            $message = "Error al añadir los cargos: " . $e->getMessage();
            $messageType = "danger";
        }
    } else {
        $message = "Por favor, seleccione al menos un cargo y especifique una cantidad.";
        $messageType = "warning";
    }

    if ($messageType === "danger") {
        try {
            $stmtReserva = $conexion->prepare("
                SELECT
                    r.ID_Reservacion,
                    r.Fecha_Entrada,
                    r.Fecha_Salida,
                    r.Estado,
                    h.Nombre_Habitacion,
                    h.Numero_Habitacion,
                    c.Nombre AS NombreCliente,
                    c.Apellido AS ApellidoCliente
                FROM
                    Reservacion r
                JOIN
                    Habitacion h ON r.FK_ID_Habitacion = h.ID_Habitacion
                JOIN
                    Cliente c ON r.FK_ID_Cliente = c.ID_Cliente
                WHERE
                    r.ID_Reservacion = :reservation_id
            ");
            $stmtReserva->bindParam(':reservation_id', $selectedReservationId);
            $stmtReserva->execute();
            $reservationDetails = $stmtReserva->fetch(PDO::FETCH_ASSOC);

            $stmtCargos = $conexion->prepare("SELECT ID_Cargo, Descripcion, Precio FROM Cargo ORDER BY Descripcion ASC");
            $stmtCargos->execute();
            $availableCharges = $stmtCargos->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al recargar datos después de fallo de adición: " . $e->getMessage());
        }
    }

}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['reservation_id'])) {
    $selectedReservationId = $_GET['reservation_id'];

    if (isset($_SESSION['status_message'])) {
        $message = $_SESSION['status_message'];
        $messageType = $_SESSION['status_type'];
        unset($_SESSION['status_message']);
        unset($_SESSION['status_type']);
    }

    try {
        $stmtReserva = $conexion->prepare("
            SELECT
                r.ID_Reservacion,
                r.Fecha_Entrada,
                r.Fecha_Salida,
                r.Estado,
                h.Nombre_Habitacion,
                h.Numero_Habitacion,
                c.Nombre AS NombreCliente,
                c.Apellido AS ApellidoCliente
            FROM
                Reservacion r
            JOIN
                Habitacion h ON r.FK_ID_Habitacion = h.ID_Habitacion
            JOIN
                Cliente c ON r.FK_ID_Cliente = c.ID_Cliente
            WHERE
                r.ID_Reservacion = :reservation_id
        ");
        $stmtReserva->bindParam(':reservation_id', $selectedReservationId);
        $stmtReserva->execute();
        $reservationDetails = $stmtReserva->fetch(PDO::FETCH_ASSOC);

        if (!$reservationDetails) {
            $message = "Reservación no encontrada.";
            $messageType = "danger";
            $selectedReservationId = null;
        }

        $stmtCargos = $conexion->prepare("SELECT ID_Cargo, Descripcion, Precio FROM Cargo ORDER BY Descripcion ASC");
        $stmtCargos->execute();
        $availableCharges = $stmtCargos->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Error al cargar detalles de reserva o cargos (GET): " . $e->getMessage());
        $message = "Error al cargar los datos: " . $e->getMessage();
        $messageType = "danger";
        $selectedReservationId = null;
    }
}
?>


<body class="g-sidenav-show bg-gray-200 dark-version">
    <?php
    $controllerActive = "Cargos";
    include "../../components/menu/sideMenu.php";
    ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include "../../components/header/header.php"; ?>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Añadir Cargos a Reservación</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="p-3">
                                <?php if ($message): ?>
                                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                        <?php echo htmlspecialchars($message); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if ($selectedReservationId && $reservationDetails): ?>
                                    <h5 class="mb-3">Detalles de la Reservación Seleccionada:</h5>
                                    <div class="card card-body mb-4 p-3 bg-light">
                                        <p class="mb-1"><strong>ID Reserva:</strong> <?php echo htmlspecialchars($reservationDetails['ID_Reservacion']); ?></p>
                                        <p class="mb-1"><strong>Cliente:</strong> <?php echo htmlspecialchars($reservationDetails['NombreCliente'] . ' ' . $reservationDetails['ApellidoCliente']); ?></p>
                                        <p class="mb-1"><strong>Habitación:</strong> <?php echo htmlspecialchars($reservationDetails['Nombre_Habitacion']); ?> (No. <?php echo htmlspecialchars($reservationDetails['Numero_Habitacion']); ?>)</p>
                                        <p class="mb-1"><strong>Período:</strong> <?php echo htmlspecialchars($reservationDetails['Fecha_Entrada']); ?> al <?php echo htmlspecialchars($reservationDetails['Fecha_Salida']); ?></p>
                                        <p class="mb-0"><strong>Estado:</strong> <span class="badge bg-<?php echo ($reservationDetails['Estado'] == 'en curso' ? 'success' : ($reservationDetails['Estado'] == 'reservada' ? 'primary' : 'secondary')); ?>"><?php echo htmlspecialchars(ucfirst($reservationDetails['Estado'])); ?></span></p>
                                    </div>

                                    <h5 class="mt-4 mb-3">Añadir Cargos:</h5>
                                    <form method="POST" action="">
                                        <input type="hidden" name="reservation_id_for_charges" value="<?php echo htmlspecialchars($selectedReservationId); ?>">
                                        <div class="table-responsive">
                                            <table class="table align-items-center mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Cargo</th>
                                                        <th>Precio Unitario</th>
                                                        <th>Cantidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($availableCharges)): ?>
                                                        <?php foreach ($availableCharges as $charge): ?>
                                                            <tr>
                                                                <td>
                                                                    <strong><?php echo htmlspecialchars($charge['Descripcion']); ?></strong>
                                                                </td>
                                                                <td>Q<?php echo number_format($charge['Precio'], 2); ?></td>
                                                                <td>
                                                                    <div class="input-group input-group-outline w-50">
                                                                        <input type="number" class="form-control" name="charges[<?php echo htmlspecialchars($charge['ID_Cargo']); ?>]" value="0" min="0" step="1">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center">No hay cargos disponibles.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="submit" name="add_charges" class="btn btn-primary mt-4">Guardar Cargos</button>
                                        <a href="cargos.php" class="btn btn-secondary mt-4 ms-2">Volver a Buscar Reservación</a>
                                    </form>
                                <?php else: ?>
                                    <div class="alert alert-warning" role="alert">
                                        Por favor, selecciona una reservación desde la página de búsqueda.
                                        <a href="cargos.php" class="alert-link">Volver a Buscar Reservación</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4"></div>
            <div class="row mb-4"></div>

        </div>
        <?php include "../../components/footer/footer.php"; ?>
    </main>

    <?php
    include "../../components/config.php";
    include "../../components/footer/footerDependence.php";
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var inputs = document.querySelectorAll('.input-group-outline .form-control');
            inputs.forEach(function(input) {
                if (input.value.length > 0) {
                    input.parentNode.classList.add('is-filled');
                }
                input.addEventListener('focus', function() {
                    input.parentNode.classList.add('is-focused');
                });
                input.addEventListener('blur', function() {
                    input.parentNode.classList.remove('is-focused');
                    if (input.value.length > 0) {
                        input.parentNode.classList.add('is-filled');
                    } else {
                        input.parentNode.classList.remove('is-filled');
                    }
                });
            });
        });
    </script>
</body>
</html>