<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel de Administración</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- DataTables CSS -->
        <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
        <!-- Animate.css -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container" style="margin-top: 7px;"> 
            <div class="mt-3 row"> 
                <div class="card shadow"> 
                    <div class="card-body"> 
                        <section class="bg-light pb-0 pe-3 ps-3 pt-0 text-center"> 
                            <div class="container pb-4 pt-4"> 
                                <div class="align-items-center justify-content-center row"> 
                                    <div class="col-lg-3 col-md-6 py-3" id="columna_1" data-pg-ia='{"l":[{"name":"animacion","trg":"mouseenter_notouch","t":"this","a":"pulse"}]}'> 
                                        <div class="bg-white pb-5 pe-4 ps-4 pt-5 rounded-3 shadow text-secondary" data-pg-ia-apply> <i class="fa-5x fa-user-plus fas mb-4 text-primary"></i> 
                                            <h3 class="fst-normal fw-bold h3 mb-3 text-uppercase">Usuarios</h3><span class="form-text">Listado, crear, modificar y/o eliminar</span>
                                            <div class="row">
                                                <a href="factura_list.html" class="btn btn-primary btn-sm mt-4 text-uppercase">Ir </a>
                                            </div>                                             
                                        </div>                                         
                                    </div>
                                    <div class="col-lg-3 col-md-6 py-3" id="columna_1" data-pg-ia='{"l":[{"name":"animacion","trg":"mouseenter_notouch","t":"this","a":"pulse"}]}'> 
                                        <div class="bg-white pb-5 pe-4 ps-4 pt-5 rounded-3 shadow text-secondary" data-pg-ia-apply> <i class="fa-5x fa-clock fas mb-4 text-warning"></i> 
                                            <h3 class="fst-normal fw-bold h3 mb-3 text-uppercase">Jornadas</h3><span class="form-text">Administra Jornadas Laborables</span>
                                            <div class="row">
                                                <a href="#" class="btn btn-primary btn-sm mt-4 text-uppercase">Ir </a>
                                            </div>                                             
                                        </div>                                         
                                    </div>
                                    <div class="col-lg-3 col-md-6 py-3" id="columna_1" data-pg-ia='{"l":[{"name":"animacion","trg":"mouseenter_notouch","t":"this","a":"pulse"}]}'> 
                                        <div class="bg-white pb-5 pe-4 ps-4 pt-5 rounded-3 shadow text-secondary" data-pg-ia-apply> <i class="fa-5x fa-chart-pie fas mb-4 text-success"></i> 
                                            <h3 class="fst-normal fw-bold h3 mb-3 text-uppercase">Productividad</h3><span class="form-text">Analiza métricas de productividad.</span>
                                            <div class="row">
                                                <a href="clientes_list.html" class="btn btn-primary btn-sm mt-4 text-uppercase">Ir </a>
                                            </div>                                             
                                        </div>                                         
                                    </div>
                                    <div class="col-lg-3 col-md-6 py-3" id="columna_1" data-pg-ia='{"l":[{"name":"animacion","trg":"mouseenter_notouch","t":"this","a":"pulse"}]}'> 
                                        <div class="bg-white pb-5 pe-4 ps-4 pt-5 rounded-3 shadow text-secondary" data-pg-ia-apply> <i class="fa-5x fa-id-card-alt fas mb-4"></i> 
                                            <h3 class="fst-normal fw-bold h3 mb-3 text-uppercase">Proveedores</h3><span class="form-text">Listado, agregar nueva facturas, modificar y/o eliminar&nbsp;</span>
                                            <div class="row">
                                                <a href="#" class="btn btn-primary btn-sm mt-4 text-uppercase">Ir </a>
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
        <div class="container mt-5">
            <!-- Título -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">Bienvenido al Panel de Administración</h1>
                <p class="lead">Administra usuarios, jornadas y productividad de manera eficiente.</p>
            </div>
            <!-- Tarjetas con enlaces -->
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6">
                    <a href="agregar_usuario.php" class="text-decoration-none"> <div class="card shadow-sm border-0 animate__animated animate__fadeInUp">
                            <div class="card-body shadow-lg text-center">
                                <i class="fa fa-user-plus text-primary fa-3x mb-3"></i>
                                <h5 class="card-title">Agregar Usuario</h5>
                                <p class="text-muted">Crea nuevos usuarios en el sistema.</p>
                            </div>
                        </div> </a>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <a href="listar_usuarios.php" class="text-decoration-none"> <div class="card shadow-sm border-0 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                            <div class="card-body text-center">
                                <i class="fa fa-users text-success fa-3x mb-3"></i>
                                <h5 class="card-title">Listar Usuarios</h5>
                                <p class="text-muted">Consulta y gestiona usuarios.</p>
                            </div>
                        </div> </a>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <a href="gestionar_jornadas.php" class="text-decoration-none"> <div class="card shadow-sm border-0 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                            <div class="card-body text-center">
                                <i class="fa fa-clock text-warning fa-3x mb-3"></i>
                                <h5 class="card-title">Gestionar Jornadas</h5>
                                <p class="text-muted">Administra jornadas laborales.</p>
                            </div>
                        </div> </a>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <a href="consultar_productividad.php" class="text-decoration-none"> <div class="card shadow-sm border-0 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                            <div class="card-body text-center">
                                <i class="fa fa-chart-line text-danger fa-3x mb-3"></i>
                                <h5 class="card-title">Consultar Productividad</h5>
                                <p class="text-muted">Analiza métricas de productividad.</p>
                            </div>
                        </div> </a>
                </div>
            </div>
            <!-- Tabla de usuarios -->
            <div class="mt-5">
                <h3 class="text-secondary">Listado de Usuarios</h3>
                <div class="table-responsive">
                    <table id="tablaUsuarios" class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se llenarán los datos dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script>
        $(document).ready(function() {
            $('#tablaUsuarios').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });
    </script>
    </body>
</html>
