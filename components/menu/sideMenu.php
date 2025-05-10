<?php 
include "../../components/menu/dataMenu.php";
$url_base = "http://localhost:3000/admin/";

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: " . $url_base . "sign-in.php");
}

?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="" target="_blank">
            <span class="ms-1 font-weight-bold text-white">A.N.J</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <?php foreach( $sideMenu as $route ) {?>
                <?php if ($route["type"] == "page"){?>
                    <li class="nav-item">
                        <a class="nav-link text-white <?php echo ($controllerActive == $route["name"]) ? "active bg-gradient-primary" : ""; ?> " href="<?php echo $url_base .$route["url"]?>">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10" translate="no"><?php echo $route["icon"]?></i>
                            </div>
                            <span class="nav-link-text ms-1"><?php echo $route["name"]?></span>
                        </a>
                    </li>
                <?php }
                else if ($route["type"] == "section"){
                ?>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8"><?php  echo $route["content"]?></h6>
                </li>
                <?php }?>

            <?php }?>
        </ul>
    </div>
</aside>