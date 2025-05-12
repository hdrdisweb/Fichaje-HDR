<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
include 'menu.php';
include 'config.php';
?>
<!-- Mensaje de si se borró o no un usuario --> 
<?php
if (isset($_GET['mensaje'])) {
    if ($_GET['mensaje'] == 'usuario_borrado') {
        echo "<div id='mensaje' class='alert alert-success'>El usuario se ha eliminado correctamente.</div>";
    } elseif ($_GET['mensaje'] == 'error_borrando') {
        echo "<div id='mensaje' class='alert alert-danger'>Hubo un error al intentar eliminar el usuario.</div>";
    } elseif ($_GET['mensaje'] == 'id_invalido') {
        echo "<div id='mensaje' class='alert alert-warning'>El ID proporcionado no es válido.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Listado de Usuarios</title>
        <!-- Bootstrap core CSS -->
        <link href="bootstrap_theme/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="css/mio.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.1/css/all.css">
        <!-- Datatables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.bootstrap5.min.css">
        <!-- Datatables JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
        <!-- Datatables Buttons -->
        <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>
        <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                dom: 'frBltip',
                pageLength: 25,
                language: {"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"},
                responsive: true,
                autoWidth: false,
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis']
            });

            table.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    </script>
    </head>
    <body>
        <div class="container my-5">
            <div class="card rounded-4 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h3 class="text-muted" style="font-size: 20px;">Listado de Usuarios</h3><a href="crear-usuario.php"> <button type="button" class="btn btn-primary btn-sm" style="font-size: 12px;"><i class="fa-lg fa-plus fas me-1"></i> Agregar nuevo usuario
                            </button> </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-4">
            <div class="card rounded-4 shadow">
                <div class="card-body">
                    <table id="example" class="display responsive nowrap table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        $sql = "SELECT id, nombre, apellido, email, rol, estado FROM usuarios";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['nombre']}</td>
                                        <td>{$row['apellido']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['rol']}</td>
                                        <td>" . ucfirst($row['estado']) . "</td>
                                        <td>
                                            <a href=\"editar-usuario.php?id={$row['id']}\" class=\"btn btn-sm text-warning\">
                                                <i class=\"fa-edit fa-fw far\" title=\"Editar\"></i>
                                            </a>
                                            <a href=\"eliminar-usuario.php?id={$row['id']}\" class=\"btn btn-sm text-danger\" onclick=\"return confirm('¿Estás seguro de que deseas eliminar este usuario?');\">
                                                <i class=\"fa-trash-alt fa-fw far\" title=\"Borrar\"></i>
                                            </a>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No hay usuarios disponibles.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
        setTimeout(() => {
            const mensaje = document.getElementById('mensaje');
            if (mensaje) {
                mensaje.style.transition = 'opacity 0.5s ease';
                mensaje.style.opacity = '0';
                setTimeout(() => mensaje.remove(), 500);
            }
        }, 8000);
    </script>
        <script src="assets/js/popper.min.js"></script>         
        <script src="bootstrap/js/bootstrap.min.js"></script>         
    </body>
</html>
<?php include 'footer.php'; ?>
