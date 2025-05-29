<?php

function cardRoom(array $roomData = []) {
    $defaultData = [
        'image' => '../../assets/img/hotelRooms/room.png',
        'title' => 'Habitación Ejemplo', // Esto será el Nombre_Habitacion
        'description' => 'Descripción de la habitación de ejemplo.',
        'status' => 'vacía', // Cambiado a 'vacía' por defecto
        'edit_url' => '#',
        'numero_habitacion' => '',
        'client_name' => '', // Nuevo campo para el nombre del cliente
        'client_age' => '',  // Nuevo campo para la edad del cliente
        'reservation_id' => null, // Nuevo campo para el ID de la reservación, si es necesario para el cargo
        'edit_url_id' => null // Aseguramos que el ID de la habitación se pase explícitamente para URLs
    ];

    $data = array_merge($defaultData, $roomData);

    // Ajustamos la lógica del badge para 'Ocupada' y 'Vacía'
    $badgeColor = ($data['status'] === 'ocupada') ? 'bg-danger' : 'bg-success';
    $badgeText = ucfirst($data['status']); // Capitaliza "ocupada" o "vacía"

    // --- INICIO: Personalizar la descripción para mostrar el número siempre ---
    $descriptionHtml = '<p class="text-sm text-secondary mb-1">Número: <strong>' . htmlspecialchars($data['numero_habitacion']) . '</strong></p>';

    if ($data['status'] === 'ocupada' && !empty($data['client_name'])) {
        $descriptionHtml .= '<p class="text-sm text-secondary mb-1">Ocupada por: <strong>' . htmlspecialchars($data['client_name']) . '</strong></p>';
        // Verificación para la edad: si es numérico (incluido 0) o no es null/cadena vacía
        if (is_numeric($data['client_age']) || ($data['client_age'] !== null && $data['client_age'] !== '')) {
            $descriptionHtml .= '<p class="text-sm text-secondary mb-2">Edad: ' . htmlspecialchars($data['client_age']) . ' años</p>';
        } else {
            $descriptionHtml .= '<p class="text-sm text-secondary mb-2"></p>'; // Espacio para consistencia si la edad no está disponible
        }
    } else {
        // Si está vacía o no ocupada, solo el número ya fue añadido arriba.
        // Podrías añadir un espacio extra si no hay más info
        $descriptionHtml .= '<p class="text-sm text-secondary mb-2"></p>';
    }
    // --- FIN: Personalizar la descripción ---


    // Botones de acción
    $actionButtonsHtml = '';
    if ($data['status'] === 'ocupada') {
        $actionButtonsHtml .= '
            <a href="' . htmlspecialchars($data['edit_url']) . '" class="btn btn-sm btn-outline-primary me-2">Ver detalles</a>
            <a href="hacer_cargo.php?id_habitacion=' . htmlspecialchars($data['edit_url_id']) . '&id_reservacion=' . htmlspecialchars($data['reservation_id']) . '" class="btn btn-sm btn-info">Hacer Cargo</a>
        ';
    } else {
        $actionButtonsHtml .= '
            <a href="' . htmlspecialchars($data['edit_url']) . '" class="btn btn-sm btn-outline-secondary">Ver detalles</a>
            ';
    }

    return '
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-header p-0 position-relative">
                    <img src="' . htmlspecialchars($data['image']) . '" alt="' . htmlspecialchars($data['title']) . '" class="w-100 border-radius-lg">
                    <span class="badge ' . $badgeColor . ' position-absolute top-0 end-0 m-2">' . $badgeText . '</span>
                </div>
                <div class="card-body p-3">
                    <h6 class="mb-1">' . htmlspecialchars($data['title']) . '</h6>
                    ' . $descriptionHtml . '

                </div>
            </div>
        </div>
    ';
}
?>