<?php
session_start();
if (!isset($_SESSION['idPrimaria'])) {
    header("Location: login.php");
    exit();
}
?>
<?php


// Iniciar sesión y conectar a la base de datos
include 'menu.php'; 
include 'conexion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar variables
$success = "";
$error = "";
$email_error = "";

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $estado = $_POST['estado'];
    $avatar = isset($_FILES['avatar']) ? $_FILES['avatar']['name'] : NULL;
    $nota = $_POST['nota'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $rol = $_POST['rol'];

    // Verificar si el correo electrónico ya existe en la base de datos
    $query = "SELECT idPrimaria FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // El correo electrónico ya existe
        $email_error = "Error: El correo electrónico ya está en uso. Por favor, usa otro.";
    } else {
        // Cerrar la declaración de la verificación
        $stmt->close();

        // Insertar en la base de datos
        $query = "INSERT INTO usuarios (nombre, apellido, dni, direccion, telefono, estado, avatar, nota, email, password, rol)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssss", $nombre, $apellido, $dni, $direccion, $telefono, $estado, $avatar, $nota, $email, $password, $rol);

        if ($stmt->execute()) {
            $success = "Usuario creado exitosamente.";
        } else {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }

        // Cerrar la declaración
        $stmt->close();
    }
}

// Cerrar la conexión
$conn->close();
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
        <style>.alert { transition: opacity 1s ease-in-out; } .form-label, .form-control, select, textarea { font-size: 10px; }</style>
    </head>
    <body>
        <div class="container my-5">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="card-title text-center text-primary" style="margin-top: 15px; margin-bottom: 50px;">Crear Usuario</h1>
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
                        <!-- Primera línea: Nombre, Apellido, DNI -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 1;">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="me-2" style="flex: 1;">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required>
                            </div>
                            <div style="flex: 1;">
                                <label for="dni" class="form-label">DNI/NIF/NIE</label>
                                <input type="text" class="form-control" id="dni" name="dni" pattern="^\d{8}[A-Za-z]$|^[XxTtYyZz]\d{7}[A-Za-z]$" title="Introduce un DNI, NIF o NIE válido." required>
                            </div>
                        </div>
                        <!-- Segunda línea: Dirección, Teléfono, Estado -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 2;">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                            </div>
                            <div class="me-2" style="flex: 1;">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" pattern="^[6-9]\d{8}$" title="Introduce un teléfono válido de 9 dígitos." required>
                            </div>
                            <div style="flex: 1;">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required style="font-size: 10px;">
                                    <option value="activo" selected>Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <!-- Tercera línea: Email, Contraseña, Rol -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 1;">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <?php if (!empty($email_error)): ?>
                                    <div class="text-danger" style="font-size: 10px;">
                                        <?php echo $email_error; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Popover de Bootstrap -->
                            <script>
            var emailInput = document.getElementById('email');
            var popover = new bootstrap.Popover(emailInput, {
                trigger: 'focus'
            });

            <?php if (!empty($email_error)): ?>
                emailInput.focus();
                popover.show();
            <?php endif; ?>
        </script>
                            <div class="password-container" style="flex: 2; position: relative;">
                                <label for="password" class="form-label" style="font-size: 10px;">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required style="font-size: 10px;"><span class="toggle-password" onclick="togglePasswordVisibility()" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"> <i class="fas fa-eye"></i> </span>
                                <div class="text-end mt-2">
                                    <button type="button" id="generatePassword" class="btn btn-secondary btn-sm" style="font-size: 10px;">Generar Contraseña</button>
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <label for="rol" class="form-label">Rol</label>
                                <select class="form-select" id="rol" name="rol" style="font-size: 10px;">
                                    <option value="admin">Admin</option>
                                    <option value="jefe">Jefe</option>
                                    <option value="usuario" selected>Usuario</option>
                                </select>
                            </div>
                        </div>
                        <!-- Cuarta línea: Nota y Avatar -->
                        <div class="d-flex mb-3">
                            <div class="me-2" style="flex: 2;">
                                <label for="nota" class="form-label">Nota</label>
                                <textarea class="form-control" id="nota" name="nota" rows="5"></textarea>
                            </div>
                            <div style="flex: 1;">
                                <label for="avatar" class="form-label">Avatar</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,.webp" style="font-size: 10px;">
                            </div>
                        </div>
                        <div class="text-center" style="margin-top: 40px; margin-bottom: 20px;">
                            <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 13px;">Crear Usuario</button>
                            <button type="reset" class="btn btn-secondary btn-sm">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        <script>
        // Ocultar mensajes de éxito y error después de 5 segundos
        setTimeout(() => {
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            if (successMessage) successMessage.style.display = 'none';
            if (errorMessage) errorMessage.style.display = 'none';
        }, 5000);
    </script>
    </body>
</html>
<?php include 'footer.php'; ?>