<?php
session_start();
include 'conexion.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === ''|| $password === '') {
        $error = 'Completa usuario y contraseña.';
    } else {
        $stmt = $conn->prepare("SELECT id, usuario, password, rol FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows === 1) {
            $fila = $resultado->fetch_assoc();
            if (password_verify($password, $fila['password'])) {
                // Guardar datos en la sesión
                $_SESSION['usuario'] = $fila['usuario'];
                $_SESSION['id'] = $fila['id'];
                $_SESSION['rol'] = $fila['rol'];
                header('Location: panel.php');
                exit;
            } else {
                $error = '❌ Contraseña incorrecta.';
            }
        } else {
            $error = '⚠️ Usuario no encontrado.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Login - Realidad en Red</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="bg-light d-flex align-items-center justify-content-center vh-100">

        <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
            <h2 class="text-center text-primary mb-4">🔐 Iniciar Sesión</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Usuario:</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>

            <p class="text-center mt-3 mb-0">
                <small class="text-muted">© <?= date("Y") ?> Realidad en Red</small>
            </p>
        </div>
    </body>
</html>