<?php
// Archivo editar-usuario.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
// Iniciar sesión y conectar a la base de datos
include 'menu.php';
include 'conexion.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar los datos del usuario desde la base de datos
    $query = "SELECT * FROM usuarios WHERE idPrimaria = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        // Datos del usuario disponibles en $usuario
    } else {
        die("Usuario no encontrado.");
    }
} else {
    die("ID de usuario no válido.");
}

// Procesar el formulario de edición si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $dni = trim($_POST['dni']);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);
    $estado = $_POST['estado'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $rol = $_POST['rol'];
    $nota = trim($_POST['nota']);
    $avatar = $usuario['avatar']; // Mantener el avatar actual si no se sube uno nuevo

    // Manejo de subida de avatar si se envía uno nuevo
    if (!empty($_FILES['avatar']['name'])) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $fileExtension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $avatarPath = 'uploads/' . uniqid() . '.' . $fileExtension;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath)) {
                $avatar = $avatarPath;
            } else {
                $error = "Error al subir el avatar.";
            }
        } else {
            $error = "El archivo debe ser de tipo .jpg, .png o .webp.";
        }
    }

    if (empty($error)) {
        // Actualizar los datos del usuario
        $query = "UPDATE usuarios SET nombre = ?, apellido = ?, dni = ?, direccion = ?, telefono = ?, estado = ?, avatar = ?, nota = ?, email = ?, rol = ? WHERE idPrimaria = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssssssi", $nombre, $apellido, $dni, $direccion, $telefono, $estado, $avatar, $nota, $email, $rol, $id);
        if ($stmt->execute()) {
            $success = "Usuario actualizado exitosamente.";
        } else {
            $error = "Error al actualizar el usuario: " . $stmt->error;
        }
    }
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Usuario</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>.alert { transition: opacity 1s ease-in-out; } .form-label, .form-control, select, textarea { font-size: 10px; } .password-container { position: relative; } .toggle-password { position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer; }</style>
    </head>
    <body>
        <div class="container my-5">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-end"><a href="listar-usuarios.php"> <button type="button" class="bg-danger-subtle btn btn-light text-secondary" style="font-size: 8px;"><i class="fa-arrow-alt-circle-left far" style="margin-right: 5px;"></i>Volver
                            </button> </a>
                    </div>
                    <h1 class="card-title text-center text-primary" style="margin-top: 26px; margin-bottom: 65px;">Editar Usuario</h1>
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
                    <!-- Formulario -->
                    <form method="POST" enctype="multipart/form-data">
                        <!-- Primera línea: Nombre, Apellido, DNI, Teléfono -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 1;">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required style="font-size: 10px;">
                            </div>
                            <div class="me-2" style="flex: 1;">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required style="font-size: 10px;">
                            </div>
                            <div class="me-2" style="flex: 1;">
                                <label for="dni" class="form-label">DNI/NIF/NIE</label>
                                <input type="text" class="form-control" id="dni" name="dni" value="<?php echo htmlspecialchars($usuario['dni']); ?>" required style="font-size: 10px;">
                            </div>
                            <div style="flex: 1;">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required style="font-size: 10px;">
                            </div>
                        </div>
                        <!-- Segunda línea: Dirección, Estado, Rol, Nota -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 2;">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2" style="font-size: 10px;"><?php echo htmlspecialchars($usuario['direccion']); ?></textarea>
                            </div>
                            <div class="me-2" style="flex: 1;">
                                <label for="estado" class="form-label">Estado</label>
                                <select id="estado" name="estado" class="form-select" style="font-size: 10px;">
                                    <option value="activo" <?php if ($usuario['estado'] === 'activo') echo 'selected'; ?>>Activo</option>
                                    <option value="inactivo" <?php if ($usuario['estado'] === 'inactivo') echo 'selected'; ?>>Inactivo</option>
                                </select>
                            </div>
                            <div class="me-2" style="flex: 1;">
                                <label for="rol" class="form-label">Rol</label>
                                <select id="rol" name="rol" class="form-select" style="font-size: 10px;">
                                    <option value="admin" <?php if ($usuario['rol'] === 'admin') echo 'selected'; ?>>Admin</option>
                                    <option value="jefe" <?php if ($usuario['rol'] === 'jefe') echo 'selected'; ?>>Jefe</option>
                                    <option value="usuario" <?php if ($usuario['rol'] === 'usuario') echo 'selected'; ?>>Usuario</option>
                                </select>
                            </div>
                            <div style="flex: 2;">
                                <label for="nota" class="form-label">Nota</label>
                                <textarea class="form-control" id="nota" name="nota" style="font-size: 10px;"><?php echo htmlspecialchars($usuario['nota'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                        <!-- Tercera línea: Email, Contraseña, Avatar -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 2;">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required style="font-size: 10px;">
                            </div>
                            <div class="me-2 password-container" style="flex: 2; position: relative;">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required><span class="toggle-password" onclick="togglePasswordVisibility()" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"> <i class="fas fa-eye"></i> </span>
                                <div class="text-end mt-2">
                                    <button type="button" id="generatePassword" class="btn btn-secondary btn-sm" style="font-size: 9px;">Generar Contraseña</button>
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <label for="avatar" class="form-label">Avatar</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,.webp" style="font-size: 10px;">
                            </div>
                        </div>
                        <!-- Botones -->
                        <div class="text-center" style="margin-top: 40px; margin-bottom: 20px;">
                            <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 13px; font-size: 10px;">Guardar Cambios</button>
                            <button type="reset" class="btn btn-secondary btn-sm" style="font-size: 10px;">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- FontAwesome JS -->
        <!-- Generar contraseña -->
        <script>
            function togglePasswordVisibility() {
                const passwordField = document.getElementById('password');
                const icon = document.querySelector('.toggle-password i');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
            document.getElementById('generatePassword').addEventListener('click', function () {
    const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*!";
    let password = "";
    for (let i = 0; i < 12; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('password').value = password;
});

        </script>
        <!-- FIN Generar contraseña -->
        <!--// Ocultar mensajes de éxito y error después de 5 segundos -->
        <script>
      setTimeout(() => {
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    if (successMessage) {
        successMessage.style.display = 'none';
        setTimeout(() => {
            window.location.href = 'listar-usuarios.php';
        }, 500); // Redirigir después de ocultar
    }
    if (errorMessage) {
        errorMessage.style.display = 'none';
    }
}, 5000); // 5 segundos
</script>
        <!--FIN Ocultar mensajes de éxito y error después de 5 segundos -->
    </body>
</html>
<?php include 'footer.php'; ?>