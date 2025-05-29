<?php
include "../../components/header/head.php"; 
include "../../backend/data/db.conexion.php";

$foundReservations = [];
$searchDpi = '';
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_dpi'])) {
    $searchDpi = trim($_POST['dpi_pasaporte']);

    if (!empty($searchDpi)) {
        try {
            $stmtCliente = $conexion->prepare("SELECT ID_Cliente FROM Cliente WHERE DPI_Pasaporte = :dpi");
            $stmtCliente->bindParam(':dpi', $searchDpi);
            $stmtCliente->execute();
            $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                $clienteId = $cliente['ID_Cliente'];

                $stmtReservaciones = $conexion->prepare("
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
                        r.FK_ID_Cliente = :cliente_id
                    ORDER BY
                        r.Fecha_Entrada DESC
                ");
                $stmtReservaciones->bindParam(':cliente_id', $clienteId);
                $stmtReservaciones->execute();
                $foundReservations = $stmtReservaciones->fetchAll(PDO::FETCH_ASSOC);

                if (empty($foundReservations)) {
                    $message = "No se encontraron reservaciones para el DPI/Pasaporte proporcionado.";
                    $messageType = "info";
                } else {
                    $message = "Reservaciones encontradas para DPI/Pasaporte: " . htmlspecialchars($searchDpi);
                    $messageType = "success";
                }

            } else {
                $message = "No se encontró ningún cliente con el DPI/Pasaporte proporcionado.";
                $messageType = "warning";
            }

        } catch (PDOException $e) {
            error_log("Error al buscar reservaciones: " . $e->getMessage());
            $message = "Error al realizar la búsqueda: " . $e->getMessage();
            $messageType = "danger";
        }
    } else {
        $message = "Por favor, ingrese un número de DPI/Pasaporte.";
        $messageType = "danger";
    }
}
?>


<body class="g-sidenav-show bg-gray-200">
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
                                <h6 class="text-white text-capitalize ps-3">Gestión de Cargos por Reservación</h6>
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

                                <form method="POST" action="">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label" for="dpi_pasaporte_search">Buscar Reservaciones por DPI/Pasaporte</label>
                                        <input type="text" class="form-control" id="dpi_pasaporte_search" name="dpi_pasaporte" value="<?php echo htmlspecialchars($searchDpi); ?>" required>
                                        <button type="submit" name="search_dpi" class="btn btn-primary ms-2">Buscar Reservaciones</button>
                                    </div>
                                </form>

                                <?php if (!empty($foundReservations)): ?>
                                    <h5 class="mt-4 mb-3">Selecciona una Reservación:</h5>
                                    <form id="selectReservationForm" method="POST" action="add_charge.php">
                                        <div class="form-group">
                                            <?php foreach ($foundReservations as $reserva): ?>
                                                <div class="form-check mb-2 border rounded p-3">
                                                    <input class="form-check-input" type="radio" name="selected_reservation_id" id="reservation_<?php echo htmlspecialchars($reserva['ID_Reservacion']); ?>" value="<?php echo htmlspecialchars($reserva['ID_Reservacion']); ?>" required>
                                                    <label class="form-check-label d-block" for="reservation_<?php echo htmlspecialchars($reserva['ID_Reservacion']); ?>">
                                                        <strong>ID Reserva:</strong> <?php echo htmlspecialchars($reserva['ID_Reservacion']); ?><br>
                                                        <strong>Cliente:</strong> <?php echo htmlspecialchars($reserva['NombreCliente'] . ' ' . $reserva['ApellidoCliente']); ?><br>
                                                        <strong>Habitación:</strong> <?php echo htmlspecialchars($reserva['Nombre_Habitacion']); ?> (No. <?php echo htmlspecialchars($reserva['Numero_Habitacion']); ?>)<br>
                                                        <strong>Entrada:</strong> <?php echo htmlspecialchars($reserva['Fecha_Entrada']); ?><br>
                                                        <strong>Salida:</strong> <?php echo htmlspecialchars($reserva['Fecha_Salida']); ?><br>
                                                        <strong>Estado:</strong> <span class="badge bg-<?php echo ($reserva['Estado'] == 'en curso' ? 'success' : ($reserva['Estado'] == 'reservada' ? 'primary' : 'secondary')); ?>"><?php echo htmlspecialchars(ucfirst($reserva['Estado'])); ?></span>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="submit" class="btn btn-success mt-3">Seleccionar Reservación y Añadir Cargos</button>
                                    </form>
                                <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_dpi'])): ?>
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