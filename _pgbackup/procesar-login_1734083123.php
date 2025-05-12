<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['idPrimaria'] = $usuario['idPrimaria'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirigir según el rol del usuario
            if ($usuario['rol'] === 'admin') {
                header("Location: escritorio.php");
            } elseif ($usuario['rol'] === 'usuario') {
                header("Location: fichaje.php");
            } elseif ($usuario['rol'] === 'jefe') {
                header("Location: jefe_panel.php");
            } else {
                // Redirigir a login si el rol no es reconocido
                header("Location: login.php?error=unknown_role");
            }
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: login.php?error=1");
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: login.php?error=1");
        exit();
    }
}
?>
