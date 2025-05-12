<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php'; // Archivo de conexión a la base de datos

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener ID de la actividad desde la URL
$actividad_id = $_GET['id'] ?? null;

if (!$actividad_id) {
    die("Error: ID de la actividad no especificado.");
}

// Consultar si la actividad existe
$query_check = "SELECT id FROM actividades WHERE id = '$actividad_id'";
$result_check = $conn->query($query_check);

if ($result_check->num_rows == 0) {
    die("Error: La actividad no existe.");
}

// Eliminar actividad
$query_delete = "DELETE FROM actividades WHERE id = '$actividad_id'";

if ($conn->query($query_delete)) {
    // Redirigir con éxito
    header("Location: listar-actividades.php?success=Actividad eliminada correctamente.");
    exit();
} else {
    die("Error al eliminar actividad: " . $conn->error);
}
?>
