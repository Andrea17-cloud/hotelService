<?php

function cardRoom(array $roomData = []) {
    $defaultData = [
        'image' => '../assets/img/hotelRooms/room.png',
        'title' => 'Habitación Ejemplo',
        'description' => 'Descripción de la habitación de ejemplo.',
        'price' => 'Consultar precio',
        'location' => 'Ubicación Ejemplo',
        'refresh_url' => '#',
        'edit_url' => '#'
    ];

    $data = array_merge($defaultData, $roomData);

    return "
        <div class=\"card col-12 col-md-6 col-lg-4 col-xl-3 mb-4\" data-animation=\"true\">
            <div class=\"card-header p-0 position-relative mt-n4 mx-3 z-index-2\">
                <a class=\"d-block blur-shadow-image\" href=\"{$data['refresh_url']}\">
                    <img src=\"{$data['image']}\" alt=\"{$data['title']}\" class=\"img-fluid shadow border-radius-lg\">
                </a>
                <div class=\"colored-shadow\" style=\"background-image: url('{$data['image']}');\"></div>
            </div>
            <div class=\"card-body text-center\">
                <div class=\"d-flex mt-n6 mx-auto\">
                    <a class=\"btn btn-link text-primary ms-auto border-0\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Refresh\" href=\"{$data['refresh_url']}\">
                        <i class=\"material-symbols-rounded text-lg\" translate=\"no\">refresh</i>
                    </a>
                    <a class=\"btn btn-link text-info me-auto border-0\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Edit\" href=\"{$data['edit_url']}\">
                        <i class=\"material-symbols-rounded text-lg\" translate=\"no\">edit</i>
                    </a>
                </div>
                <h5 class=\"font-weight-normal mt-3\">
                    <a href=\"#\">{$data['title']}</a>
                </h5>
                <p class=\"mb-0\">
                    {$data['description']}
                </p>
            </div>
            <hr class=\"dark horizontal my-0\">
            <div class=\"card-footer d-flex\">
                <p class=\"font-weight-normal my-auto\">{$data['price']}</p>
                <i class=\"material-symbols-rounded position-relative ms-auto text-lg me-1 my-auto\">place</i>
                <p class=\"text-sm my-auto\">{$data['location']}</p>
            </div>
        </div>
    ";
}
?>