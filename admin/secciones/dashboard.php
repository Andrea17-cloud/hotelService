<?php 
include "../../components/header/head.php";
include "../../components/cards/cardInfo.php"
?>
<body class="g-sidenav-show  bg-gray-200">
  <?php 
  $controllerActive = "Dashboard";
  include "../../components/menu/sideMenu.php";

  ?>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?php
      include "../../components/header/header.php";
    ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
    
      <div class="row">
        <?php
          echo cardInfo(candidad: "200", color: "dark");
          echo cardInfo(candidad: "500", color: "success");
        ?>
      </div>
      <div class="row mt-4">
        
      </div>
      <div class="row mb-4">
        
      </div>

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