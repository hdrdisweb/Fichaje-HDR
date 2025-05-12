<?php
// Conectar a la base de datos
include 'conexion.php';

// Consulta para obtener los usuarios
$query = "SELECT nombre, apellido, dni, email, rol, estado, telefono FROM usuarios";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Listado de Usuarios</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- DataTables CSS -->
        <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <style>
        thead {
            background-color: #e3f2fd;
        }
    </style>
    </head>
    <body>
        <div class="container mt-5">
            <div class="card shadow-lg border-0">
                <div class="text-center mb-4">
                    <h1 class="display-6 fw-bold text-primary" style="margin-top: 32px;">Listado de Usuarios</h1>
                    <p class="lead">Administra y gestiona los usuarios registrados en el sistema.</p>
                </div>
                <div class="card-body">
                    <table id="usuariosTable" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>DNI</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['nombre']}</td>
                                    <td>{$row['apellido']}</td>
                                    <td>{$row['dni']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['rol']}</td>
                                    <td>{$row['estado']}</td>
                                    <td>{$row['telefono']}</td>
                                    <td>
                                        <a href='#' class='btn btn-sm btn-warning me-1'>
                                            <i class='fa fa-edit'></i> Editar
                                        </a>
                                        <a href='#' class='btn btn-sm btn-danger'>
                                            <i class='fa fa-trash'></i> Borrar
                                        </a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No hay usuarios registrados.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script>
        $(document).ready(function() {
            $('#usuariosTable').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });
        });
    </script>
    </body>
</html>
