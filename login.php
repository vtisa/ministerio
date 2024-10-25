<?php
session_start();
include "conexion.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT `id`, `nombre_usuario`, `contraseña` FROM `usuarios` WHERE `nombre_usuario` = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $hashed_password = $user['contraseña'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['usuario'] = $user['nombre_usuario'];
            $_SESSION['user_id'] = $user['id'];
            
            if ($user['nombre_usuario'] === 'admin') {
                $_SESSION['nivel'] = 'superadmin';
            } else {
                $_SESSION['nivel'] = 'usuario_general';
            }
            
            header('Location: index.php');
            exit();
        } else {
            $error_message = "Contraseña incorrecta";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('https://img.freepik.com/foto-gratis/fondo-pantalla-negocios-digital-degradado-bokeh_53876-110796.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow: hidden;
        }

        .navbar {
            background-color: #009688;
            color: white;
        }

        .navbar h1 {
            color: white;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20%; 
        }

        .form-container label {
            color: #333;
            font-weight: bold;
            font-size: 18px;
        }

        .form-container .form-control {
            padding: 0.75rem 0.75rem;
            height: auto;
            font-size: 18px;
            resize: none;
        }

        .form-container .btn-primary {
            background-color: #009688;
            border-color: #009688;
        }

        .form-container .btn-primary:hover {
            background-color: #FF0000;
            border-color: #FF0000;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5">
        <h1>Iniciar Sesión</h1>
    </nav>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="form-container">
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label for "password" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="login">Iniciar Sesión</button>
            </form>
            <?php
            if (isset($error_message)) {
                echo '<div class="alert alert-danger mt-3">' . $error_message . '</div>';
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
