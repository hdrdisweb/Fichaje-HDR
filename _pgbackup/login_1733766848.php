<?php
// Inicia la sesión
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['idPrimaria'])) {
    // Redirige al login si no hay sesión activa
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
include 'conexion.php';

// Recupera el ID del usuario desde la sesión
$idPrimaria = $_SESSION['idPrimaria'];

// Prepara la consulta para obtener el rol del usuario
$query = "SELECT rol FROM usuarios WHERE idPrimaria = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $idPrimaria);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        // Redirige según el rol del usuario
        if ($usuario['rol'] === 'admin') {
            header("Location: escritorio.php");
        } elseif ($usuario['rol'] === 'usuario') {
            header("Location: fichaje.php");
        } elseif ($usuario['rol'] === 'jefe') {
            header("Location: jefe_panel.php");
        } else {
            // Si el rol es desconocido, redirige al login con un error
            header("Location: login.php?error=unknown_role");
        }
        exit();
    } else {
        // Si no se encuentra el usuario, cerrar sesión y redirigir al login
        session_destroy();
        header("Location: login.php?error=invalid_user");
        exit();
    }
} else {
    // Si hay un error en la preparación de la consulta, redirige con un mensaje de error
    header("Location: login.php?error=db_error");
    exit();
}
?>
