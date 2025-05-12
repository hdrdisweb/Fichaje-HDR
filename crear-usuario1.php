<?php
include 'menu.php'; // Incluir el menú si es parte del diseño
include 'conexion.php'; // Conexión a la base de datos


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar y sanitizar los datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $dni = trim($_POST['dni']);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);
    $estado = $_POST['estado'];
    $rol = $_POST['rol'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $avatar = null;
    $notas = trim($_POST['nota']);

    // Validar Avatar (opcional)
    $avatar = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileInfo = pathinfo($_FILES['avatar']['name']);
        $fileExt = strtolower($fileInfo['extension']);
        $allowedExts = ['jpg', 'bmp', 'webp', 'png'];
        if (in_array($fileExt, $allowedExts) && $_FILES['avatar']['size'] <= 500 * 1024) {
            $avatar = 'uploads/' . uniqid() . '.' . $fileExt;
            move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar);
        } else {
            $error = "El archivo debe ser una imagen JPG, BMP, WEBP o PNG de hasta 500 KB.";
        }
    }

    // Evitar valores vacíos en campos opcionales
    $telefono = !empty($telefono) ? $telefono : null;
    $direccion = !empty($direccion) ? $direccion : null;
    $avatar = !empty($avatar) ? $avatar : null;
    $notas = !empty($notas) ? $notas : null; // Notas opcional

    // Validar campos obligatorios
    if (empty($nombre) || empty($apellido) || empty($dni) || empty($email) || empty($password)) {
        $error = "Por favor, rellene todos los campos obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ingrese un email válido.";
    } elseif (!preg_match('/^\d{8}[A-Za-z]$|^[XxTtYyZz]\d{7}[A-Za-z]$/', $dni)) {
        $error = "Ingrese un DNI/NIF/NIE válido.";
    } elseif (!empty($telefono) && !preg_match('/^[6-9]\d{8}$/', $telefono)) {
        $error = "Ingrese un teléfono válido de 9 dígitos.";
    }

    // Insertar en la base de datos
        $query = "INSERT INTO usuarios (nombre, apellido, dni, direccion, telefono, estado, avatar, nota, email, password, rol)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssss", $nombre, $apellido, $dni, $direccion, $telefono, $estado, $avatar, $notas, $email, $password, $rol);

        if ($stmt->execute()) {
            $message = "Usuario creado exitosamente.";
        } else {
            $message = "Error al crear el usuario: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Usuario</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>.password-container { position: relative; } .toggle-password { position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer; } .card { box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding-left: 1px; left: 1px; } .form-label, .form-control, select, textarea,  { font-size: 10px; }</style>
    </head>
    <body>
        <div class="container my-5">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Mensajes de éxito o error -->
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success" id="successMessage">
                            <?php echo $success; ?>
                        </div>
                    <?php elseif (isset($error)): ?>
                        <div class="alert alert-danger" id="errorMessage">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <!-- Formulario -->
                    <form method="POST" enctype="multipart/form-data">
                        <!-- Primera línea: Nombre, Apellido, DNI -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 1;">
                                <label for="nombre" class="form-label" style="font-size: 10px;">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required style="font-size: 10px;">
                            </div>
                            <div class="me-2" style="flex: 1;">
                                <label for="apellido" class="form-label" style="font-size: 10px;">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required style="font-size: 10px;">
                            </div>
                            <div style="flex: 1;">
                                <label for="dni" class="form-label" style="font-size: 10px;">DNI/NIF/NIE</label>
                                <input type="text" class="form-control" id="dni" name="dni" pattern="^\d{8}[A-Za-z]$|^[XxTtYyZz]\d{7}[A-Za-z]$" title="Introduce un DNI, NIF o NIE válido." required style="font-size: 10px;">
                            </div>
                        </div>
                        <!-- Segunda línea: Dirección, Teléfono, Estado -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 2;">
                                <label for="direccion" class="form-label" style="font-size: 10px;">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="1" style="font-size: 10px;"></textarea>
                            </div>
                            <div class="me-2" style="flex: 1;">
                                <label for="telefono" class="form-label" style="font-size: 10px;">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" pattern="^[6-9]\d{8}$" title="Introduce un teléfono válido de 9 dígitos." required style="font-size: 10px;">
                            </div>
                            <div style="flex: 1;">
                                <label for="estado" class="form-label" style="font-size: 10px;">Estado</label>
                                <select class="form-select" id="estado" name="estado" style="font-size: 10px;">
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <!-- Tercera línea: Rol, Email, Contraseña -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 1;">
                                <label for="rol" class="form-label" style="font-size: 10px;">Rol</label>
                                <select class="form-select" id="rol" name="rol" style="font-size: 10px;">
                                    <option value="admin">Administrador</option>
                                    <option value="editor">Editor</option>
                                    <option value="usuario">Usuario</option>
                                </select>
                            </div>
                            <div class="me-2" style="flex: 2;">
                                <label for="email" class="form-label" style="font-size: 10px;">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required style="font-size: 10px;">
                            </div>
                            <div class="password-container" style="flex: 2; position: relative;">
                                <label for="password" class="form-label" style="font-size: 10px;">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required style="font-size: 10px;"><span class="toggle-password" onclick="togglePasswordVisibility()" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"> <i class="fas fa-eye"></i> </span>
                                <div class="text-end mt-2">
                                    <button type="button" id="generatePassword" class="btn btn-secondary btn-sm" style="font-size: 10px;">Generar Contraseña</button>
                                </div>
                            </div>
                        </div>
                        <!-- Cuarta línea: Avatar, Notas -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 1;">
                                <label for="avatar" class="form-label" style="font-size: 10px;">Avatar</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept=".jpg,.bmp,.webp,.png" style="font-size: 10px;"><small class="form-text text-muted" style="font-size: 9px;">Solo se permiten imágenes JPG, BMP, WEBP, PNG de hasta 500 KB.</small>
                            </div>
                            <div style="flex: 2;">
                                <label for="notas" class="form-label" style="font-size: 10px;">Notas</label>
                                <textarea class="form-control" id="notas" name="notas" rows="2" placeholder="Añade comentarios o notas (opcional)" style="font-size: 10px;"></textarea>
                            </div>
                        </div>
                        <!-- Botón de envío -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="font-size: 10px;">Crear Usuario</button>
                            <button type="reset" class="btn btn-secondary" style="font-size: 10px;">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Scripts -->
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
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*!?";
            let password = "";
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('password').value = password;
        });
    </script>
        <!-- FIN Generar contraseña -->
        <!-- Mensaje de éxito -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success" id="successMessage">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        <!-- Mensaje de error -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" id="errorMessage">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <!-- Script para ocultar los mensajes -->
        <script>
    // Ocultar los mensajes de éxito y error después de 5 segundos
    setTimeout(() => {
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 5000); // 5000ms = 5 segundos
</script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </body>
</html>
<?php include 'footer.php'; ?>