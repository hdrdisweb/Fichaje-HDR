<?php
session_start();
include 'conexion.php';

// Verificar si se recibió un POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "Por favor, ingresa email y contraseña.";
        exit();
    }

    // Consulta para validar las credenciales
  $query = "SELECT idPrimaria, rol, password FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($password, $usuario['password'])) {
        $_SESSION['idPrimaria'] = $usuario['idPrimaria'];
        $_SESSION['rol'] = $usuario['rol'];
        // Redirigir según el rol...
    } else {
        echo "Contraseña incorrecta.";
        header("Location: login.php?error=invalid_credentials");
    }
}


        // Configurar la sesión con el ID del usuario
        $_SESSION['idPrimaria'] = $usuario['idPrimaria'];
        $_SESSION['rol'] = $usuario['rol'];

        // Redirigir según el rol del usuario
        if ($usuario['rol'] === 'admin') {
            echo "Redirigiendo al panel de administración...";
            header("Location: escritorio.php");
        } elseif ($usuario['rol'] === 'usuario') {
            echo "Redirigiendo al panel de usuario...";
            header("Location: fichaje.php");
        } elseif ($usuario['rol'] === 'jefe') {
            echo "Redirigiendo al panel de jefe...";
            header("Location: jefe_panel.php");
        } else {
            echo "Error: rol desconocido.";
            header("Location: login.php?error=unknown_role");
        }
        exit();
    } else {
        // Credenciales inválidas
        echo "Credenciales inválidas. Revisa tu email y contraseña.";
        header("Location: login.php?error=invalid_credentials");
        exit();
    }
} else {
    echo "Acceso no autorizado.";
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html> 
<html lang="es"> 
    <head> 
        <meta charset="utf-8"> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <meta name="description" content="Login"> 
        <meta name="author" content="HDR Diseño Web"> 
        <title>Login HDR</title>         
        <!-- Bootstrap core CSS -->         
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <!-- Custom styles for this template -->         
        <link rel="stylesheet" href="assets/css/style_login.css"> 
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.1/css/all.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    </head>     
    <body> 
        <div class="container d-flex justify-content-around"> 
            <div class="card"> 
                <div class="bg-white card-header mb-2 text-center"> 
                    <img src="assets/img/hdr_new_logo_350.png" data-pg-name="logo" width="160"> 
                </div>                 
                <div class="card-body"> 
                    <form action="procesar-login.php" method="post"> 
                        <div class="form-group input-group mb-3 mt-3"> 
                            <div class="input-group-prepend pe-2 pt-2"><i class="fa-user fas" style="font-size: 20px; color: #e38922;"></i> 
                            </div>                             
                            <input type="email" name="email" class="form-control ms-1 rounded" placeholder="email" style="font-size: 15px; font-family: 'Ubuntu', sans-serif;" id="email" required="true"> 
                        </div>                         
                        <div class="input-group form-group"> 
                            <div class="input-group-prepend pe-2 pt-2"><i class="fa-key fas" style="font-size: 20px; color: #e38922;"></i> 
                            </div>                             
                            <input type="password" name="password" class="form-control ms-1 rounded" placeholder="contraseña" style="font-size: 15px; font-family: 'Ubuntu', sans-serif;" id="password">
                            <button type="button" class="btn ms-1 p-0" style="height: 36px;" name="wf" onclick="mostrar()">
                                <i class="fa-2x fa-eye far ms-1" style="font-size: 20px; color: #e38922;"></i>
                            </button>                             
                        </div>                         
                        <div class="align-items-center d-flex form-group justify-content-between mb-3 mt-4"> 
                            <button type="submit" class="btn btn-sm placeholder-wave text-white vw-100" style="background-color: #e38922;">Ingresar</button>                             
                        </div>                         
                    </form>                     
                    <!-- Mostrar mensajes según el estado -->
                    <?php
                        if (isset($_GET['error'])) {
                            echo '<div class="alert alert-danger mt-3">Credenciales incorrectas. Inténtalo de nuevo.</div>';
                        }
                        if (isset($_GET['logout'])) {
                            echo '<div class="alert alert-success mt-3">Has cerrado sesión correctamente.</div>';
                        }
                        ?>
                </div>                 
                <div class="card-footer"> 
                    <div class="d-flex justify-content-center links" style="font-size: 12px; font-family: 'Ubuntu', sans-serif; color: #686666;">¿No tienes una cuenta?<a href="registro.php" class="ms-2">Regístrate</a> 
                    </div>                     
                    <div class="d-flex justify-content-center"> <a href="perdidapass.php" style="font-size: 12px; font-family: 'Ubuntu', sans-serif;">¿Olvidaste tu contraseña?</a> 
                    </div>                     
                </div>                 
            </div>             
        </div>         
        <!-- Bootstrap core JavaScript
    ================================================== -->         
        <!-- Placed at the end of the document so the pages load faster -->         
        <script src="assets/js/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>         
        <!--Mostrar contraseña -->
        <script type="text/javascript"> 
            function mostrar() {
                var tipo = document.getElementById("password");
                if(tipo.type == 'password') {
                    tipo.type = 'text';

                } else {
                    tipo.type = 'password';
                }
            }
        </script>         
        <!--FIN Mostrar contraseña -->         
    </body>     
</html>
