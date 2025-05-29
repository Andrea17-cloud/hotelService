<?php

function cardRoom(array $roomData = []) {
    $defaultData = [
        'image' => '../../assets/img/hotelRooms/room.png',
        'title' => 'Habitación Ejemplo',
        'description' => 'Descripción de la habitación de ejemplo.',
        'status' => 'vacía',
        'edit_url' => '#',
        'numero_habitacion' => '',
        'client_name' => '',
        'client_age' => '',
        'reservation_id' => null, // ID de la reservación activa, si la hay
        'edit_url_id' => null, // ID de la habitación para URLs
        'total_cargos' => null // Nuevo campo para el total de cargos
    ];

    $data = array_merge($defaultData, $roomData);

    $badgeColor = ($data['status'] === 'ocupada') ? 'bg-danger' : 'bg-success';
    $badgeText = ucfirst($data['status']);

    // --- INICIO: Personalizar la descripción ---
    $descriptionHtml = '<p class="text-sm text-secondary mb-1">Número: <strong>' . htmlspecialchars($data['numero_habitacion']) . '</strong></p>';

    if ($data['status'] === 'ocupada' && !empty($data['client_name'])) {
        $descriptionHtml .= '<p class="text-sm text-secondary mb-1">Ocupada por: <strong>' . htmlspecialchars($data['client_name']) . '</strong></p>';
        if (is_numeric($data['client_age']) || ($data['client_age'] !== null && $data['client_age'] !== '')) {
            $descriptionHtml .= '<p class="text-sm text-secondary mb-1">Edad: ' . htmlspecialchars($data['client_age']) . ' años</p>';
        }

        // Mostrar total de cargos si la habitación está ocupada y tenemos el dato
        $totalCargosDisplay = (is_numeric($data['total_cargos'])) ? number_format($data['total_cargos'], 2) : '0.00';
        $descriptionHtml .= '<p class="text-sm text-secondary mb-2">Cargos acumulados: Q<strong>' . $totalCargosDisplay . '</strong></p>';

    } else {
        // Si está vacía, o no hay cliente
        $descriptionHtml .= '<p class="text-sm text-secondary mb-2"></p>'; // Espacio en blanco si no hay más info relevante
    }
    // --- FIN: Personalizar la descripción ---

    // Botones de acción
    $actionButtonsHtml = '';
    if ($data['status'] === 'ocupada') {
        $actionButtonsHtml .= '
            <a href="' . htmlspecialchars($data['edit_url']) . '" class="btn btn-sm btn-outline-primary me-2">Ver detalles</a>
            <a href="add_charge.php?reservation_id=' . htmlspecialchars($data['reservation_id']) . '" class="btn btn-sm btn-info">Hacer Cargo</a>
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
                    <div class="mt-2">
                    </div>
                </div>
            </div>
        </div>
    ';
}
?>