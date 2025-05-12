<?php
include 'conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Selecciona todos los usuarios
$query = "SELECT idPrimaria, password FROM usuarios";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Verifica si la contraseña ya está cifrada
        if (!password_verify($row['password'], $row['password'])) {
            // Cifra la contraseña si no está cifrada
            $hashedPassword = password_hash($row['password'], PASSWORD_DEFAULT);
            
            // Actualiza la contraseña cifrada en la base de datos
            $updateQuery = "UPDATE usuarios SET password = ? WHERE idPrimaria = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $hashedPassword, $row['idPrimaria']);
            $stmt->execute();
        }
    }
    echo "Contraseñas cifradas y actualizadas correctamente.";
} else {
    echo "No se encontraron usuarios.";
}

// Cierra la conexión
$conn->close();
?>
