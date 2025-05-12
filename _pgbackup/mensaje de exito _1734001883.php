1. Declarar las variables al inicio del script
Al inicio del archivo PHP, antes de procesar el formulario, inicializa las variables:
<!-- Inicializar variables -->
<?php
$success = '';
$error = '';
?>
<!-- FIN Inicializar variables -->
// 2. Asignar mensajes durante el procesamiento
En el bloque donde procesas el formulario, asigna mensajes a $success o $error según el resultado de la operación.
Ejemplo del código de procesamiento ajustado:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$usuario_id = $_POST['usuario_id'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$pausa_id = $_POST['pausa_id'];
// Validar datos obligatorios
if (!$usuario_id || !$fecha_inicio || !$fecha_fin || !$pausa_id) {
$error = "Todos los campos obligatorios deben estar completos.";
} elseif (strtotime($fecha_inicio) > strtotime($fecha_fin)) {
$error = "La fecha de inicio no puede ser mayor que la fecha de fin.";
} else {
// Generar jornadas
$fecha_actual = $fecha_inicio;
while (strtotime($fecha_actual) 
<= strtotime($fecha_fin)) { $sql="INSERT INTO jornadas (usuario_id, fecha, hora_inicio, hora_fin, pausa_id, fecha_inicio, fecha_fin)
                    VALUES ('$usuario_id', '$fecha_actual', '$hora_inicio', '$hora_fin', '$pausa_id', '$fecha_inicio', '$fecha_fin')" if (!$conn->query($sql)) {
    $error = "Error al insertar jornada: " . $conn->error;
    break;
    }
    $fecha_actual = date('Y-m-d', strtotime($fecha_actual . ' +1 day'));
    }
    if (empty($error)) {
    $success = "Jornadas creadas exitosamente.";
    }
    }
    }
    3. Mostrar los mensajes en el HTML
    El bloque que compartiste ya está configurado para mostrar los mensajes si las variables $success o $error contienen contenido. Solo asegúrate de incluirlo donde quieras mostrar los mensajes en tu página:
    <!-- Mensajes de éxito o error -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success" id="successMessage">
            <?php echo $success; ?>
        </div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger" id="errorMessage">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <!-- FIN Mensajes de éxito o error -->
    4. Opcional: Ocultar el mensaje después de unos segundos
    Si quieres que el mensaje desaparezca automáticamente después de unos segundos, agrega este script al final del HTML:
    <!-- Desaparecer Mensajes de éxito o error -->
    <script>
    setTimeout(() => {
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        if (successMessage) successMessage.style.display = 'none';
        if (errorMessage) errorMessage.style.display = 'none';
    }, 5000); // 5000 ms = 5 segundos
</script>
    <!-- FIN Desaparecer Mensajes de éxito o error -->
