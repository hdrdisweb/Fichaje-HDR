<?php
// Archivo de conexión a la base de datos
include 'conexion.php';

// Prueba la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
    echo "Conexión exitosa a la base de datos.";
}

// Consulta de prueba
$query = "SHOW TABLES";
$result = $conn->query($query);

if ($result) {
    echo "<br>Tablas en la base de datos:<br>";
    while ($row = $result->fetch_array()) {
        echo $row[0] . "<br>";
    }
} else {
    echo "<br>Error al obtener tablas: " . $conn->error;
}

// Cerrar conexión
$conn->close();
?>
