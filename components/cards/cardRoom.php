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
        'client_age' => ''   // Nuevo campo para la edad del cliente
    ];

    $data = array_merge($defaultData, $roomData);

    // Ajustamos la lógica del badge para 'Ocupada' y 'Vacía'
    $badgeColor = ($data['status'] === 'ocupada') ? 'bg-danger' : 'bg-success';
    $badgeText = ucfirst($data['status']); // Capitaliza "ocupada" o "vacía"

    // Personalizar la descripción
    $descriptionHtml = '';
    if ($data['status'] === 'ocupada' && !empty($data['client_name'])) {
        $descriptionHtml = '<p class="text-sm text-secondary mb-1">Ocupada por: <strong>' . htmlspecialchars($data['client_name']) . '</strong></p>';
        if (!empty($data['client_age'])) {
            $descriptionHtml .= '<p class="text-sm text-secondary mb-2">Edad: ' . htmlspecialchars($data['client_age']) . ' años</p>';
        } else {
            $descriptionHtml .= '<p class="text-sm text-secondary mb-2"></p>'; // Espacio para consistencia
        }
    } else {
        $descriptionHtml = '<p class="text-sm text-secondary mb-2">Número: ' . htmlspecialchars($data['numero_habitacion']) . '</p>';
        // Puedes añadir aquí otras descripciones por defecto si la habitación está vacía
    }


    return '
        <div class="col-md-4 col-sm-6 mb-4"> <div class="card h-100"> <div class="card-header p-0 position-relative">
                    <img src="' . htmlspecialchars($data['image']) . '" alt="' . htmlspecialchars($data['title']) . '" class="w-100 border-radius-lg">
                    <span class="badge ' . $badgeColor . ' position-absolute top-0 end-0 m-2">' . $badgeText . '</span>
                </div>
                <div class="card-body p-3">
                    <h6 class="mb-1">' . htmlspecialchars($data['title']) . '</h6>
                    ' . $descriptionHtml . '
                    <a href="' . htmlspecialchars($data['edit_url']) . '" class="btn btn-sm btn-outline-primary">Ver detalles</a>
                </div>
            </div>
        </div>
    ';
}
?>