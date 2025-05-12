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
        <div class="container mt-5">
            <!-- Título -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">Bienvenido al Panel de Administración</h1>
                <p class="lead">Administra usuarios, jornadas y productividad de manera eficiente.</p>
            </div>
            <!-- Tarjetas con enlaces -->
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6"><a href="agregar_usuario.php" class="text-decoration-none"> <div class="card shadow-sm border-0 animate__animated animate__fadeInUp">
                            <div class="card-body shadow-lg text-center"><i class="fa fa-user-plus text-primary fa-3x mb-3"></i>
                                <h5 class="card-title">Agregar Usuario</h5>
                                <p class="text-muted">Crea nuevos usuarios en el sistema.</p>
                            </div>
                        </div> </a>
                </div>
                <div class="col-lg-3 col-sm-6"><a href="listar_usuarios.php" class="text-decoration-none"> <div class="card shadow-sm border-0 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                            <div class="card-body text-center"><i class="fa fa-users text-success fa-3x mb-3"></i>
                                <h5 class="card-title">Listar Usuarios</h5>
                                <p class="text-muted">Consulta y gestiona usuarios.</p>
                            </div>
                        </div> </a>
                </div>
                <div class="col-lg-3 col-sm-6"><a href="gestionar_jornadas.php" class="text-decoration-none"> <div class="card shadow-sm border-0 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                            <div class="card-body text-center"><i class="fa fa-clock text-warning fa-3x mb-3"></i>
                                <h5 class="card-title">Gestionar Jornadas</h5>
                                <p class="text-muted">Administra jornadas laborales.</p>
                            </div>
                        </div> </a>
                </div>
                <div class="col-lg-3 col-sm-6"><a href="consultar_productividad.php" class="text-decoration-none"> <div class="card shadow-sm border-0 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                            <div class="card-body text-center"><i class="fa fa-chart-line text-danger fa-3x mb-3"></i>
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
