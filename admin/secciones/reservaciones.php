<?php
include "../../components/header/head.php";
include "../../backend/data/db.conexion.php";


$message = null;
$type = null;
if (isset($_SESSION['status_message'])) {
    $message = $_SESSION['status_message'];
    $type = $_SESSION['status_type'];
    unset($_SESSION['status_message']);
    unset($_SESSION['status_type']);
}

$reservations = [];

try {
    $stmt = $conexion->prepare("
        SELECT
            r.ID_Reservacion,
            c.Nombre AS NombreCliente,
            c.Apellido AS ApellidoCliente,
            h.Numero_Habitacion,
            h.Nombre_Habitacion AS TipoHabitacion,
            r.Fecha_Entrada,
            r.Fecha_Salida,
            r.Estado AS EstadoReservacion
        FROM
            Reservacion r
        JOIN
            Cliente c ON r.FK_ID_Cliente = c.ID_Cliente
        JOIN
            Habitacion h ON r.FK_ID_Habitacion = h.ID_Habitacion
        ORDER BY
            FIELD(r.Estado, 'en curso', 'reservada', 'finalizada', 'cancelada'),
            r.Fecha_Entrada DESC,
            r.ID_Reservacion DESC
    ");
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error al cargar reservas: " . $e->getMessage());
    $message = "Error al cargar las reservaciones: " . $e->getMessage();
    $type = "danger";
}
?>

<body class="g-sidenav-show bg-gray-200 dark-version">
    <?php
    $controllerActive = "Reservaciones";
    include "../../components/menu/sideMenu.php";
    ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php
        include "../../components/header/header.php";
        ?>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Gestión de Reservaciones</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <?php
                            if ($message !== null) {
                                echo '<div class="alert alert-' . htmlspecialchars($type) . ' alert-dismissible fade show mx-3" role="alert">';
                                echo htmlspecialchars($message);
                                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                                echo '</div>';
                            }

                            if (isset($_SESSION['status_message'])) {
                                $message = $_SESSION['status_message'];
                                $type = $_SESSION['status_type'];

                                echo '<div class="container mt-4">';
                                echo '<div class="alert alert-' . htmlspecialchars($type) . ' alert-dismissible fade show" role="alert">';
                                echo htmlspecialchars($message);
                                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                                echo '</div>';
                                echo '</div>';

                                unset($_SESSION['status_message']);
                                unset($_SESSION['status_type']);
                            }
                            ?>

                            <div class="px-3 pb-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#makeReservationModal">
                                    Hacer Reservación
                                </button>
                            </div>

                            <?php if (!empty($reservations)): ?>
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID Reserva</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cliente</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Habitación</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipo de Habitación</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha Entrada</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha Salida</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                                                <th class="text-secondary opacity-7">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($reservations as $reserva): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($reserva['ID_Reservacion']); ?></td>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($reserva['NombreCliente'] . ' ' . $reserva['ApellidoCliente']); ?></h6>
                                                                </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($reserva['Numero_Habitacion']); ?></td>
                                                    <td><?php echo htmlspecialchars($reserva['TipoHabitacion']); ?></td>
                                                    <td class="align-middle text-center">
                                                        <span class="text-secondary text-xs font-weight-bold"><?php echo htmlspecialchars($reserva['Fecha_Entrada']); ?></span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="text-secondary text-xs font-weight-bold"><?php echo htmlspecialchars($reserva['Fecha_Salida']); ?></span>
                                                    </td>
                                                    <td class="align-middle text-center text-sm">
                                                        <?php
                                                        $estadoClass = '';
                                                        switch ($reserva['EstadoReservacion']) {
                                                            case 'en curso':
                                                                $estadoClass = 'badge bg-gradient-success';
                                                                break;
                                                            case 'finalizada':
                                                                $estadoClass = 'badge bg-gradient-info';
                                                                break;
                                                            case 'cancelada':
                                                                $estadoClass = 'badge bg-gradient-danger';
                                                                break;
                                                            case 'reservada':
                                                                $estadoClass = 'badge bg-gradient-success';
                                                                break;
                                                            default:
                                                                $estadoClass = 'badge bg-gradient-secondary';
                                                                break;
                                                        }
                                                        echo '<span class="' . $estadoClass . '">' . htmlspecialchars(ucfirst($reserva['EstadoReservacion'])) . '</span>';
                                                        ?>
                                                    </td>
                                                    <td class="align-middle">
                                                        <?php if ($reserva['EstadoReservacion'] != 'cancelada' && $reserva['EstadoReservacion'] != 'finalizada'): ?>
                                                            <button type="button" class="btn btn-link text-primary p-0"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editStatusModal"
                                                                data-id="<?php echo htmlspecialchars($reserva['ID_Reservacion']); ?>"
                                                                data-estado="<?php echo htmlspecialchars($reserva['EstadoReservacion']); ?>">
                                                                <i class="material-icons text-sm">edit</i>
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-secondary text-xs font-weight-bold">No editable</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mx-3" role="alert">
                                    No hay reservaciones registradas.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include "../../components/footer/footer.php";
        ?>
    </main>

    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel">Cambiar Estado de Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateStatusForm" method="POST" action="../../controllers/admin/process_update_reservacion_estado.controller.php">
                    <div class="modal-body">
                        <input type="hidden" name="reservation_id" id="modalReservationId">
                        <div class="mb-3">
                            <label for="newStatus" class="form-label">Nuevo Estado:</label>
                            <select class="form-select" id="newStatus" name="new_status" required>
                                <option value="en curso">En curso</option>
                                <option value="finalizada">Finalizada</option>
                                <option value="cancelada">Cancelada</option>
                                <option value="reservada">Reservada</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="makeReservationModal" tabindex="-1" aria-labelledby="makeReservationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="makeReservationModalLabel">Hacer Nueva Reservación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="newReservationForm" method="POST" action="../../controllers/client/process_client_reservation.controller.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombreCliente" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellidoCliente" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellidoCliente" name="apellidoCliente" required>
                        </div>
                        <div class="mb-3">
                            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" placeholder="mm/dd/yyyy" required>
                        </div>
                        <div class="mb-3">
                            <label for="dpiPasaporte" class="form-label">DPI / Pasaporte</label>
                            <input type="text" class="form-control" id="dpiPasaporte" name="dpiPasaporte" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefonoCliente" required>
                        </div>
                        <div class="mb-3">
                            <label for="genero" class="form-label">Género</label>
                            <select class="form-select" id="generoCliente" name="generoCliente" required>
                                <option value="">Seleccione Género</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fechaEntrada" class="form-label">Fecha de Entrada</label>
                            <input type="date" class="form-control" id="fechaRegistroHabitacion" name="fechaRegistroHabitacion" placeholder="mm/dd/yyyy" required>
                        </div>
                        <div class="mb-3">
                            <label for="fechaSalida" class="form-label">Fecha de Salida</label>
                            <input type="date" class="form-control" id="fechaSalida" name="fechaSalida" placeholder="mm/dd/yyyy" required>
                        </div>

                        <input type="text" value="../../admin/secciones/reservaciones.php" name="url" style="display: none;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear Reservación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php
    include "../../components/config.php";
    include "../../components/footer/footerDependence.php";
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var editStatusModal = document.getElementById('editStatusModal');
        editStatusModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var reservationId = button.getAttribute('data-id');
            var currentStatus = button.getAttribute('data-estado');
            var modalReservationIdInput = editStatusModal.querySelector('#modalReservationId');
            var newStatusSelect = editStatusModal.querySelector('#newStatus');

            modalReservationIdInput.value = reservationId;
            newStatusSelect.value = currentStatus; 
        });
    </script>
</body>
</html>