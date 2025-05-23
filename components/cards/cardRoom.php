<?php

function cardRoom(array $roomData = []) {
    $defaultData = [
        'image' => '../../assets/img/hotelRooms/room.png',
        'title' => 'Habitación Ejemplo',
        'description' => 'Descripción de la habitación de ejemplo.',
        'status' => 'disponible',
        'edit_url' => '#',
    ];

    $data = array_merge($defaultData, $roomData);

    $badgeColor = $data['status'] === 'reservado' ? 'bg-danger' : 'bg-success';
    $badgeText = ucfirst($data['status']);

    return '
        <div class="card my-4" style="max-width: 400px;">
            <div class="card-header p-0 position-relative">
                <img src="' . htmlspecialchars($data['image']) . '" alt="' . htmlspecialchars($data['title']) . '" class="w-100 border-radius-lg">
                <span class="badge ' . $badgeColor . ' position-absolute top-0 end-0 m-2">' . $badgeText . '</span>
            </div>
            <div class="card-body p-3">
                <h6 class="mb-1">' . htmlspecialchars($data['title']) . '</h6>
                <p class="text-sm text-secondary mb-2">' . htmlspecialchars($data['description']) . '</p>
                <a href="' . htmlspecialchars($data['edit_url']) . '" class="btn btn-sm btn-outline-primary">Ver detalles</a>
            </div>
        </div>
    ';
}
?>
