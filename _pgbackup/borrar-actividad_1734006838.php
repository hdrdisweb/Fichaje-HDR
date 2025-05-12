<?php
// Inicia la sesión
session_start();

// Conexión a la base de datos
include 'conexion.php';

// Verificar si el ID del usuario está presente en el parámetro GET
if (isset($_GET['id'])) {
    $idPrimaria = intval($_GET['id']);

    // Consulta para eliminar el usuario
    $query = "DELETE FROM usuarios WHERE idPrimaria = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idPrimaria);

    if ($stmt->execute()) {
        // Redirigir con mensaje de éxito
        header("Location: listar-usuarios.php?success=Usuario%20eliminado%20correctamente");
    } else {
        // Redirigir con mensaje de error
        header("Location: listar-usuarios.php?error=No%20se%20pudo%20eliminar%20el%20usuario");
    }

    $stmt->close();
} else {
    // Redirigir si no hay un ID presente
    header("Location: listar-usuarios.php?error=ID%20no%20válido");
}
exit();