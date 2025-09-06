<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Sesión cerrada</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="d-flex align-items-center justify-content-center vh-100 bg-light">

        <div class="card shadow p-4 text-center" style="max-width: 400px;">
            <h3 class="mb-3">Sesión cerrada</h3>
            <p>Has cerrado sesión correctamente.</p>
            <a href="index.php" class="btn btn-primary w-100">Volver al inicio</a>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
