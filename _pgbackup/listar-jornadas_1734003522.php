<?php
include 'menu.php';
include 'conexion.php';

// Obtener jornadas desde la base de datos
$query = "SELECT j.id, u.nombre AS usuario_nombre, u.apellido AS usuario_apellido, j.fecha, j.hora_inicio, j.hora_fin, p.nombre AS pausa
          FROM jornadas j
          JOIN usuarios u ON j.usuario_id = u.idPrimaria
          JOIN pausas p ON j.pausa_id = p.id";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Listado de Jornadas</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- DataTables CSS -->
        <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <!-- Buttons CSS -->
        <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <style>thead { background-color: #e3f2fd; } .btn-action { font-size: 12px; margin-right: 5px; }</style>
    </head>
    <body>
        <div class="container mt-5">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center text-primary" style="margin-top: 20px; margin-bottom: 51px;">Listado de Jornadas</h2>
                    <table id="jornadasTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Pausa</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['usuario_nombre'] . ' ' . $row['usuario_apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                                    <td><?php echo htmlspecialchars($row['hora_inicio']); ?></td>
                                    <td><?php echo htmlspecialchars($row['hora_fin']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pausa']); ?></td>
                                    <td> <a href="editar-jornada.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm btn-action"> <i class="fas fa-edit"></i> Editar </a> <a href="borrar-jornada.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm btn-action" onclick="return confirm('¿Estás seguro de que deseas borrar esta jornada? Esta acción no se puede deshacer')"> <i class="fas fa-trash"></i> Borrar </a> </td>
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
        <!-- Buttons JS -->
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script>
        $(document).ready(function() {
            $('#jornadasTable').DataTable({
                responsive: true,
                dom: 'Bfrtip', // Activa la disposición de botones
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Exportar a Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> Exportar a PDF',
                        className: 'btn btn-danger btn-sm',
                        orientation: 'landscape', // Orientación horizontal
                        pageSize: 'A4' // Tamaño de página
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-info btn-sm'
                    }
                ],
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
