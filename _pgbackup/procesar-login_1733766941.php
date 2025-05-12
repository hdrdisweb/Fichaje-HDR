<?php
// Inicia la sesión
session_start();

// Si el usuario ya está logueado, redirigir al escritorio o a la página correspondiente según el rol
if (isset($_SESSION['idPrimaria'])) {
    // Conexión a la base de datos
    include 'conexion.php';

    // Recuperar el ID del usuario desde la sesión
    $idPrimaria = $_SESSION['idPrimaria'];

    // Preparar la consulta para obtener el rol del usuario
    $query = "SELECT rol FROM usuarios WHERE idPrimaria = ?";
    $stmt = $conn->prepare($query);

    // Verificar que la conexión y la preparación de la consulta sean correctas
    if ($stmt) {
        $stmt->bind_param("i", $idPrimaria);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            // Redirigir según el rol del usuario
            if ($usuario['rol'] === 'admin') {
                header("Location: escritorio.php");
            } elseif ($usuario['rol'] === 'usuario') {
                header("Location: fichaje.php");
            } elseif ($usuario['rol'] === 'jefe') {
                header("Location: jefe_panel.php");
            } else {
                header("Location: login.php?error=unknown_role");
            }
        } else {
            // Si el usuario no se encuentra, destruir la sesión y redirigir
            session_destroy();
            header("Location: login.php?error=invalid_user");
        }
    } else {
        // Si falla la preparación del statement, mostrar error
        die("Error al preparar la consulta: " . $conn->error);
    }
    exit();
} else {
    // Si no hay sesión activa, redirigir al login
    header("Location: login.php");
    exit();
}
?>
