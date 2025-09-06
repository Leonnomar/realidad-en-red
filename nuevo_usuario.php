<?php
session_start();
if ($_SESSION['rol'] !== 'admin') {
    header("Location: panel.php");
    exit;
}
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password, rol) VALUE (?, ?, ?)");
    $stmt->bind_param("sss", $usuario, $password, $rol);
    
    if($stmt->execute()) {
        $_SESSION['mensaje'] = '✅ Usuario creado con éxito';
        $_SESSION['tipo_mensaje'] = "success";
        header("Location: usuarios.php");
        exit;
    } else {
        $_SESSION['mensaje'] = "❌ Error al registrar usuario";
        $_SESSION['tipo_mensaje'] = "danger";
    }
}
?>

<!DOCTYPE>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Nuevo Usuario</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">➕ Nuevo Usuario</h4>
                        </div>
                        <div class="card-body">

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="mb-3">
                                    <label for="usuario" class="form-label">Usuario:</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="rol" class="form-label">Rol:</label>
                                    <select name="rol" id="rol" class="form-select">
                                        <option value="editor">Editor</option>
                                        <option value="admin">Administrador</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Crear usuario</button>
                                <a href="usuarios.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>