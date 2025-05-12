<?php
include 'conexion.php'; // Archivo que contiene la conexión a tu base de datos

try {
    // Consulta para seleccionar usuarios con contraseñas sin cifrar
    $query = "SELECT id, password FROM usuarios";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $plainPassword = $row['password'];

        // Evita volver a cifrar contraseñas ya cifradas (si sabes que cumplen con bcrypt)
        if (password_get_info($plainPassword)['algo'] === 0) {
            $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

            // Actualiza la contraseña cifrada en la base de datos
            $updateQuery = "UPDATE usuarios SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $hashedPassword, $id);
            $stmt->execute();

            echo "Contraseña del usuario con ID $id cifrada correctamente.<br>";
        } else {
            echo "Contraseña del usuario con ID $id ya está cifrada.<br>";
        }
    }

    echo "Proceso completado.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
