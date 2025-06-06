<?php 
include "../backend/data/db.conexion.php";
include "../components/header/head.php";
include "../backend/data/admin.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["user"]) && isset($_POST["password"])){
        $userLogin = $_POST["user"];
        $password = $_POST["password"];

        try {
            $stmt = $conexion->prepare("SELECT ID_Trabajador, Nombre, Contrasenia FROM Trabajador WHERE Nombre = :userLogin");
            $stmt->bindParam(':userLogin', $userLogin, PDO::PARAM_STR);
            $stmt->execute();

            $trabajador = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($trabajador) {
                if ($password == $trabajador['Contrasenia']) { 
                    session_start();
                    $_SESSION['usuario'] = $trabajador['Nombre'];
                    $_SESSION['id_trabajador'] = $trabajador['ID_Trabajador'];

                    header("location: ./secciones/dashboard.php");
                    exit;
                } else {
                    $errorMessage = "Usuario o contraseña incorrectos.";
                }
            } else {
                $errorMessage = "Usuario o contraseña incorrectos.";
            }

        } catch (PDOException $e) {
            error_log("Error de autenticación desde DB: " . $e->getMessage());
            $errorMessage = "Ha ocurrido un error en el servidor. Por favor, inténtelo de nuevo más tarde.";
        }
    }
}
?>

<body class="bg-gray-200 dark-version">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
    </div>
  </div>
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h4>
                  <div class="row mt-3">
                    <div class="col-12 text-center px-1">
                      <a class="btn btn-link px-3" href="https://github.com/KEVIN01306">
                        <i class="fa fa-github text-white text-lg"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form role="form" class="text-start" method="post">
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">User</label>
                    <input type="text" class="form-control" name="user">
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password">
                  </div>
                  <div class="form-check form-switch d-flex align-items-center mb-3">
                    <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                    <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2" >Sign in</button>
                  </div>
                  <p class="mt-4 text-sm text-center">
                    Only 
                    <a class="text-primary text-gradient font-weight-bold">Admin</a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-6 my-auto">
              <div class="copyright text-center text-sm text-white text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart" aria-hidden="true"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold text-white" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-12 col-md-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="../index.php" class="nav-link pe-0 text-white" target="_blank">Landing Pages</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <?php
    include "../components/footer/footerDependence.php";
  ?>

  <?php
    if (!empty($errorMessage)) {
        echo "<script type='text/javascript'>";
        echo "alert('" . addslashes($errorMessage) . "');";
        echo "</script>";
    }
    ?>
</body>

</html>