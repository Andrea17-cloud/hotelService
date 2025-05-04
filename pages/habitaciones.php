<?php
include "../components/header/head.php";
include "../components/cards/cardRoom.php";

?>

<body class="g-sidenav-show  bg-gray-200">
    <?php
    $controllerActive = "Rooms";
    include "../components/menu/sideMenu.php";

    ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <?php
        include "../components/header/header.php";
        ?>
        <!-- End Navbar -->
        <div class="container-fluid py-4">

            <div class="row g-4">
                <?php 
                    echo cardRoom();
                ?>
                
            </div>
            <div class="row mt-4">

            </div>
            <div class="row mb-4">

            </div>

        </div>
        <?php
        include "../components/footer/footer.php";
        ?>
    </main>

    <?php
    include "../components/config.php";
    include "../components/footer/footerDependence.php";
    ?>

</body>

</html>