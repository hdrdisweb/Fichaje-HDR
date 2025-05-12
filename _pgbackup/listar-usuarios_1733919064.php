<?php
include 'menu.php';
include 'conexion.php';

// Obtener usuarios desde la base de datos
$query = "SELECT idPrimaria, nombre, apellido, dni, email, rol, estado, telefono FROM usuarios";
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
        <style>thead { background-color: #e3f2fd; } .btn-action { font-size: 12px; margin-right: 5px; }</style>
    </head>
    <body>
        <div class="container mt-5">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center text-primary" style="margin-top: 20px; margin-bottom: 51px;">Listado de Usuarios</h2>
                    <table id="usuariosTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
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
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dni']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['rol']); ?></td>
                                    <td><?php echo htmlspecialchars($row['estado']); ?></td>
                                    <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                    <td> <a href="editar-usuario.php?id=<?php echo $row['idPrimaria']; ?>" class="btn btn-warning btn-sm btn-action"> <i class="fas fa-edit"></i> Editar </a> <a href="borrar-usuario.php?id=<?php echo $row['idPrimaria']; ?>" class="btn btn-danger btn-sm btn-action" onclick="return confirm('¿Estás seguro de que deseas borrar este usuario?')"> <i class="fas fa-trash"></i> Borrar </a> </td>
                                </tr>
                            <?php endwhile; ?>
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
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });
        });
    </script>
    </body>
</html>
<?php
include 'footer.php';
?>