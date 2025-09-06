<?php
session_start();
if ($_SESSION['rol'] !== 'admin') {
    header("Location: panel.php");
    exit;
}
include 'conexion.php';

// Lista de usuarios
$resultado = $conn->query("SELECT id, usuario, rol FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Gestión de Usuarios</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="bg-light">

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand fw-bold" href="panel.php">⬅ Volver al panel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link active" href="usuarios.php">Usuarios</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- CONTENIDO -->
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-primary">Gestión de usuarios</h1>
                <a href="nuevo_usuario.php" class="btn btn_success">➕ Nuevo Usuario</a>
            </div>

            <!-- ALERTAS -->
            <?php if (isset($_SESSION['mensajes'])): ?>
                <div class="alert alert-<?= $_SESSION['tipo_mensaje'] ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['mensaje'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
                <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?= $fila['id'] ?></td>
                            <td><?= $fila['usuario'] ?></td>
                            <td>
                                <span class="badge <?= $fila['rol'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                    <?= ucfirst($fila['rol']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="editar_usuario.php?id=<?= $fila['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                                <a href="eliminar_usuario.php?id=<?= $fila['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">🗑 Eliminar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="bg-dark text-white text-center py-3 mt-5">
            <p class="mb-0">© <?= date("Y") ?> Realidad en Red - Panel de Administación</p>
        </footer>
    </body>
</html>