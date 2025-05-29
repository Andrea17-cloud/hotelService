<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>HEP</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap Icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- SimpleLightbox plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="assets/css/styles.css" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="#page-top">HOTEL EL PARAISO</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link" href="#about">Acerca de</a></li>
                        <li class="nav-item"><a class="nav-link" href="#services">Servicios</a></li>
                        <li class="nav-item"><a class="nav-link" href="#portfolio">Portafolio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Reservar</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container px-4 px-lg-5 h-100">
                <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-8 align-self-end">
                        <h1 class="text-white font-weight-bold">EL mejor hotel del mundo, somos los mas especializados</h1>
                        <hr class="divider" />
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p class="text-white-75 mb-5">Empieza a reservar, para saber que es estar vivo!</p>
                        <a class="btn btn-primary btn-xl" href="#about">Un poco de nosotros</a>
                    </div>
                </div>
            </div>
        </header>
        <!-- About-->
        <section class="page-section bg-primary" id="about">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="text-white mt-0">Algo sobre nosotros!</h2>
                        <hr class="divider divider-light" />
                        <p class="text-white-75 mb-4">En el Hotel Paraíso, te abrimos las puertas a un oasis de tranquilidad y confort. Somos más que un lugar para dormir; somos tu hogar lejos de casa. Cada detalle está pensado para que tu estancia sea una experiencia inolvidable, llena de calidez y momentos especiales. ¡Esperamos darte la bienvenida pronto a nuestro paraíso!</p>
                        <a class="btn btn-light btn-xl" href="#services">Empecemos!</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container px-4 px-lg-5">
                <h2 class="text-center mt-0">Nuestros servivios</h2>
                <hr class="divider" />
                <div class="row gx-4 gx-lg-5 d-flex justify-content-center align-items-center">
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <div class="mb-2"><i class="bi bi-cup fs-1 text-primary"></i></div>
                            <h3 class="h4 mb-2">Desayuno</h3>
                            <p class="text-muted mb-0">Comienza tu día con energía gracias a nuestro delicioso desayuno, preparado con ingredientes frescos y locales.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <div class="mb-2"><i class="bi bi-cup-straw fs-1 text-primary"></i></div>
                            <h3 class="h4 mb-2">Almuerzo</h3>
                            <p class="text-muted mb-0">Disfruta de una pausa deliciosa con opciones variadas que satisfarán tu paladar en nuestro acogedor ambiente.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <div class="mb-2"><i class="bi bi-egg-fried fs-1 text-primary"></i></div>
                            <h3 class="h4 mb-2">Cena</h3>
                            <p class="text-muted mb-0">Termina tu jornada con una exquisita cena, donde los sabores y la tranquilidad se unen para una experiencia culinaria memorable.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Portfolio-->
        <div id="portfolio">
            <div class="container-fluid p-0">
                <div class="row g-0">
                
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/hotelRooms/room.png" title="Habitacion Simple">
                            <img class="img-fluid" src="assets/img/hotelRooms/room.png" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Habitacion</div>
                                <div class="project-name">Simple</div>
                            </div>
                        </a>
                    </div>
                                        <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/hotelRooms/room.png" title="Habitacion Doble">
                            <img class="img-fluid" src="assets/img/hotelRooms/room.png" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Habitacion</div>
                                <div class="project-name">Doble</div>
                            </div>
                        </a>
                    </div>
                                        <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/hotelRooms/room.png" title="Habitacion Suite">
                            <img class="img-fluid" src="assets/img/hotelRooms/room.png" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Habitacion</div>
                                <div class="project-name">Suite</div>
                            </div>
                        </a>
                    </div>
                                        <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/hotelRooms/room.png" title="Habitacion Familiar">
                            <img class="img-fluid" src="assets/img/hotelRooms/room.png" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Habitacion</div>
                                <div class="project-name">Familiar</div>
                            </div>
                        </a>
                    </div>
                                        <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/hotelRooms/room.png" title="Habitacion Deluxe">
                            <img class="img-fluid" src="assets/img/hotelRooms/room.png" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Habitacion</div>
                                <div class="project-name">Deluxe</div>
                            </div>
                        </a>
                    </div>
                                        <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="assets/img/hotelRooms/room.png" title="Habitacion Estandar">
                            <img class="img-fluid" src="assets/img/hotelRooms/room.png" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Habitacion</div>
                                <div class="project-name">Estandar</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Call to action-->
        <section class="page-section bg-dark text-white">
            <div class="container px-4 px-lg-5 text-center">
                <h2 class="mb-4">Hotel El Paraiso</h2>
            </div>
        </section>
        <!-- Contact-->
        <section class="page-section" id="contact">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-8 col-xl-6 text-center">
                        <h2 class="mt-0">Reservar!</h2>
                        <hr class="divider" />
                        <p class="text-muted mb-5">¿Listo para desconectar y vivir una experiencia inolvidable? En el Hotel Paraíso, tu descanso y confort son nuestra prioridad. Reserva ahora y asegura tu lugar en este oasis de tranquilidad. </p>
                    </div>
                </div>
                <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
                    <div class="col-lg-6">
                        <?php
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
                        <form id="clientForm" method="POST" action="./controllers/client/process_client_reservation.controller.php">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="nombreCliente" name="nombreCliente" type="text" placeholder="Ingrese el nombre del cliente..." data-sb-validations="required" required />
                                <label for="nombreCliente">Nombre</label>
                                <div class="invalid-feedback" data-sb-feedback="nombreCliente:required">El nombre es requerido.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input class="form-control" id="apellidoCliente" name="apellidoCliente" type="text" placeholder="Ingrese el apellido del cliente..." data-sb-validations="required" required />
                                <label for="apellidoCliente">Apellido</label>
                                <div class="invalid-feedback" data-sb-feedback="apellidoCliente:required">El apellido es requerido.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input class="form-control" id="fechaNacimiento" name="fechaNacimiento" type="date" placeholder="Fecha de Nacimiento..." data-sb-validations="required" required />
                                <label for="fechaNacimiento">Fecha de Nacimiento</label>
                                <div class="invalid-feedback" data-sb-feedback="fechaNacimiento:required">La fecha de nacimiento es requerida.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input class="form-control" id="dpiPasaporte" name="dpiPasaporte" type="text" placeholder="DPI o Pasaporte..." data-sb-validations="required" required />
                                <label for="dpiPasaporte">DPI / Pasaporte</label>
                                <div class="invalid-feedback" data-sb-feedback="dpiPasaporte:required">El DPI o Pasaporte es requerido.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input class="form-control" id="telefonoCliente" name="telefonoCliente" type="tel" placeholder="(123) 456-7890" data-sb-validations="required" required />
                                <label for="telefonoCliente">Teléfono</label>
                                <div class="invalid-feedback" data-sb-feedback="telefonoCliente:required">El teléfono es requerido.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="generoCliente" name="generoCliente" data-sb-validations="required" required>
                                    <option value="">Seleccione Género</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                <label for="generoCliente">Género</label>
                                <div class="invalid-feedback" data-sb-feedback="generoCliente:required">El género es requerido.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input class="form-control" id="fechaRegistroHabitacion" name="fechaRegistroHabitacion" type="date" data-sb-validations="required" required />
                                <label for="fechaRegistroHabitacion">Fecha de Entrada</label>
                                <div class="invalid-feedback" data-sb-feedback="fechaRegistroHabitacion:required">La fecha de entrada es requerida.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input class="form-control" id="fechaSalida" name="fechaSalida" type="date" data-sb-validations="required" required />
                                <label for="fechaSalida">Fecha de Salida</label>
                                <div class="invalid-feedback" data-sb-feedback="fechaSalida:required">La fecha de salida es requerida.</div>
                            </div>

                            <div class="d-none" id="submitSuccessMessage">
                                <div class="text-center mb-3">
                                    <div class="fw-bolder">¡Formulario enviado exitosamente!</div>
                                    <br />
                                </div>
                            </div>

                            <input type="text" value="../../index.php#contact" name="url" style="display: none;">

                            <div class="d-none" id="submitErrorMessage"><div class="text-center text-danger mb-3">¡Error al enviar el mensaje!</div></div>

                            <div class="d-grid"><button class="btn btn-primary btn-xl" id="submitButton" type="submit">Registrar Cliente y Reservar</button></div>
                        </form>
                </div>
                </div>
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-4 text-center mb-5 mb-lg-0">
                        <i class="bi-phone fs-2 mb-3 text-muted"></i>
                        <div>+1 (555) 123-4567</div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="bg-light py-5">
            <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Copyright &copy; 2023 - HEP</div></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SimpleLightbox plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
        <!-- Core theme JS-->
        <script src="assets/js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>
</html>
