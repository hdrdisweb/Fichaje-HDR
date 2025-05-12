<?php
session_start();
if (!isset($_SESSION['idPrimaria'])) {
    header("Location: login.php");
    exit();
}
?>
<?php include 'menu.php'; 
      include 'conexion.php';
?>
<!DOCTYPE html> 
<html lang="es"> 
    <head> 
        <meta charset="utf-8"> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
        <meta name="description" content=""> 
        <meta name="author" content=""> 
        <!-- Bootstrap core CSS -->         
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"> 
        <link href="css/mio.css" rel="stylesheet"> 
        <link href="bootstrap_theme/bootstrap.css" rel="stylesheet" type="text/css"> 
        <!-- Custom styles for this template -->         
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.1/css/all.css"> 
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap">
        <link rel="stylesheet" href="blocks.css">
        <script>/* Pinegrow Interactions, do not remove */ (function(){try{if(!document.documentElement.hasAttribute('data-pg-ia-disabled')) { window.pgia_small_mq=typeof pgia_small_mq=='string'?pgia_small_mq:'(max-width:767px)';window.pgia_large_mq=typeof pgia_large_mq=='string'?pgia_large_mq:'(min-width:768px)';var style = document.createElement('style');var pgcss='html:not(.pg-ia-no-preview) [data-pg-ia-hide=""] {opacity:0;visibility:hidden;}html:not(.pg-ia-no-preview) [data-pg-ia-show=""] {opacity:1;visibility:visible;display:block;}';if(document.documentElement.hasAttribute('data-pg-id') && document.documentElement.hasAttribute('data-pg-mobile')) {pgia_small_mq='(min-width:0)';pgia_large_mq='(min-width:99999px)'} pgcss+='@media ' + pgia_small_mq + '{ html:not(.pg-ia-no-preview) [data-pg-ia-hide="mobile"] {opacity:0;visibility:hidden;}html:not(.pg-ia-no-preview) [data-pg-ia-show="mobile"] {opacity:1;visibility:visible;display:block;}}';pgcss+='@media ' + pgia_large_mq + '{html:not(.pg-ia-no-preview) [data-pg-ia-hide="desktop"] {opacity:0;visibility:hidden;}html:not(.pg-ia-no-preview) [data-pg-ia-show="desktop"] {opacity:1;visibility:visible;display:block;}}';style.innerHTML=pgcss;document.querySelector('head').appendChild(style);}}catch(e){console&&console.log(e);}})()</script>
        <link rel="stylesheet" href="pgia/components/tooltips.css">
        <link rel="stylesheet" href="pgia/components/controls.css">
        <link rel="stylesheet" href="pgia/components/presentation.css">
        <link rel="stylesheet" href="pgia/components/gallery.css">
    </head>     
    <body> 
        <!-- Bootstrap core JavaScript
    ================================================== -->         
        <!-- Placed at the end of the document so the pages load faster -->         
        <div class="container" style="margin-top: 7px;"> 
            <div class="mt-3 row"> 
                <div class="card shadow"> 
                    <div class="card-body"> 
                        <section class="bg-light pb-0 pe-3 ps-3 pt-0 text-center"> 
                            <div class="container pb-4 pt-4"> 
                                <div class="align-items-center justify-content-center row"> 
                                    <div class="col-lg-3 col-md-6 py-3" id="columna_1" data-pg-ia='{"l":[{"name":"animacion","trg":"mouseenter_notouch","t":"this","a":"pulse"}]}'> 
                                        <div class="bg-white pb-5 pe-4 ps-4 pt-5 rounded-3 shadow text-secondary" data-pg-ia-apply> <i class="fa-5x fa-user-plus fas mb-4 text-primary"></i> 
                                            <h3 class="fst-normal fw-bold h3 mb-3 text-uppercase">Usuarios</h3>
                                            <span class="form-text">Listado, crear, modificar y/o eliminar</span>
                                            <div class="row"><a href="listar-usuarios.php" class="btn btn-sm mt-4 text-uppercase text-white" style="background-color: #e38922;">Ir </a>
                                            </div>                                             
                                        </div>                                         
                                    </div>
                                    <div class="col-lg-3 col-md-6 py-3" id="columna_1" data-pg-ia='{"l":[{"name":"animacion","trg":"mouseenter_notouch","t":"this","a":"pulse"}]}'> 
                                        <div class="bg-white pb-5 pe-4 ps-4 pt-5 rounded-3 shadow text-secondary" data-pg-ia-apply> <i class="fa-5x fa-clock fas mb-4 text-warning"></i> 
                                            <h3 class="fst-normal fw-bold h3 mb-3 text-uppercase">Jornadas</h3>
                                            <span class="form-text">Administra Jornadas Laborables</span>
                                            <div class="row"><a href="listar-jornadas.php" class="btn btn-sm mt-4 text-uppercase text-white" style="background-color: #e38922;">Ir </a>
                                            </div>                                             
                                        </div>                                         
                                    </div>
                                    <div class="col-lg-3 col-md-6 py-3" id="columna_1" data-pg-ia='{"l":[{"name":"animacion","trg":"mouseenter_notouch","t":"this","a":"pulse"}]}'> 
                                        <div class="bg-white pb-5 pe-4 ps-4 pt-5 rounded-3 shadow text-secondary" data-pg-ia-apply> <i class="fa-5x fa-chart-pie fas mb-4 text-success"></i> 
                                            <h3 class="fst-normal fw-bold h3 mb-3 text-uppercase">Productividad</h3>
                                            <span class="form-text">Analiza métricas de productividad.</span>
                                            <div class="row"><a href="clientes_list.html" class="btn btn-sm mt-4 text-uppercase text-white" style="background-color: #e38922;">Ir </a>
                                            </div>                                             
                                        </div>                                         
                                    </div>
                                    <div class="col-lg-3 col-md-6 py-3" id="columna_1" data-pg-ia='{"l":[{"name":"animacion","trg":"mouseenter_notouch","t":"this","a":"pulse"}]}'> 
                                        <div class="bg-white pb-5 pe-4 ps-4 pt-5 rounded-3 shadow text-secondary" data-pg-ia-apply> <i class="fa-5x fa-network-wired fas mb-4"></i> 
                                            <h3 class="fst-normal fw-bold h3 mb-3 text-uppercase">actividades</h3>
                                            <span class="form-text">Listado, agregar, modificar, y/o eliminar&nbsp;</span>
                                            <div class="row"><a href="#" class="btn btn-sm mt-4 text-uppercase text-white" style="background-color: #e38922;">Ir </a>
                                            </div>                                             
                                        </div>                                         
                                    </div>                                     
                                </div>                                 
                            </div>                             
                        </section>
                    </div>                     
                </div>                 
            </div>             
        </div>         
        <div class="mb-5 mt-5 row" data-empty-placeholder></div>
        <script src="assets/js/popper.min.js"></script>         
        <script src="bootstrap/js/bootstrap.min.js"></script>         
        <script src="pgia/lib/pgia.js"></script>
    </body>     
</html>
<?php include 'footer.php'; ?>