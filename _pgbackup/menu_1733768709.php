<?php
// Inicia la sesión
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['idPrimaria'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
include 'conexion.php';

$idPrimaria = $_SESSION['idPrimaria'];

// Consulta para obtener los datos del usuario
$query = "SELECT avatar, nombre FROM usuarios WHERE idPrimaria = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idPrimaria);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    $avatar = $usuario['avatar'] ?: 'uploads/avatar.webp'; // Avatar por defecto si no hay uno
    $nombre = $usuario['nombre'];
} else {
    // Si el usuario no existe, cerrar sesión y redirigir
    session_destroy();
    header("Location: login.php?error=user_not_found");
    exit();
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
        <title>HDR Diseño Web</title>
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.1/css/all.css">
        <!-- Custom Styles -->
        <link href="blocks.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-dark navbar-expand-lg py-4 py-lg-2 text-uppercase" style="background-color: #e38922;">
            <div class="container">
                <!-- Logo --><a class="navbar-brand" href="index.php"> <img src="assets/img/hdr_new_logo_350.png" alt="HDR Logo" width="130"> </a>
                <!-- Toggle Button for Mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto" style="padding-right: 81px;">
                        <li class="nav-item"><a class="nav-link text-light" href="admin-panel.php"><i class="fas fa-home me-2"></i>Inicio</a>
                        </li>
                        <li class="nav-item"><a class="nav-link text-light" href="listar-usuarios.php"><i class="fas fa-users me-2"></i>Usuarios</a>
                        </li>
                        <li class="nav-item"><a class="nav-link text-light" href="listar-jornadas.php"><i class="fas fa-calendar-alt me-2"></i>Jornadas</a>
                        </li>
                        <li class="nav-item"><a class="nav-link text-light" href="listar-productividad.php"><i class="fas fa-chart-line me-2"></i>Productividad</a>
                        </li>
                    </ul>
                    <!-- Inserta esto en el menú donde desees mostrar el avatar y el nombre -->
                    <div class="d-flex align-items-center">
                        <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="rounded-circle" style="width: 50px; height: 50px;"><span class="ms-3 text-white fw-bold"><?php echo htmlspecialchars($nombre); ?></span>
                    </div>
                    <!-- Logout Button -->
                    <div class="ms-lg-3"><a href="logout.php"> <button class="btn btn-outline-light" type="button"><i class="fas fa-sign-out-alt me-2"></i>Salir
                            </button> </a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Main Content Placeholder -->
        <div class="container mt-5">
            <!-- Aquí va el contenido de las páginas -->
        </div>
        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
