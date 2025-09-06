<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
// Conexión a la base de datos
$conn = new mysqli("localhost","root","","realidadenred");
if ($conn->connect_error) {
    die("Error de conexión". $conn->connect_error);
}

// Si se recibe in id por GET para borrar
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);

    //1. Buscar la imagen asociada
    $stmt = $conn->prepare("SELECT imagen FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $articulo = $resultado->fetch_assoc();

    // 2. Eliminar la imagen del servidor si existe
    if (!empty($articulo['imagen']) && file_exists("img/" . $articulo['imagen'])) {
        unlink("img/". $articulo['imagen']);
    }

    // 3. Borrar el artículo de la base de datos
    $stmt = $conn->prepare("DELETE FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // 4. Redirigir al panel
    header("Location: panel.php");
    exit;
}

// Obtener todos los artículos
$resultado = $conn->query("SELECT * FROM articulos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Panel de Administración</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="bg-light">

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand fw-bold" href="panel.php">⚙️ Panel de Administración</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php if ($_SESSION['rol'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="usuarios.php">👤 Usuarios</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- CONTENIDO -->
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-primary">Artículos</h1>
                <a href="nuevo.php" class="btn btn-success">➕ Nuevo Artículo</a>
            </div>

            <?php if (isset($_SESSION['alerta'])): ?>
                <div class="alert alert-<?= $_SESSION['alerta']['tipo'] ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['alerta']['mensaje'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['alerta']); ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Contenido</th>
                            <th>Imagen</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= $fila['id'] ?></td>
                                <td><?= $fila['titulo'] ?></td>
                                <td><?= substr($fila['contenido'], 0, 50) ?>...</td>
                                <td>
                                    <?php if (!empty($fila['imagen'])): ?>
                                        <img src="<?= $fila['imagen'] ?>" width="80" class="img-thumbnail">
                                    <?php endif; ?>
                                </td>
                                <td><?= $fila['fecha'] ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="editar.php?id=<?= $fila['id'] ?>">✏️ Editar</a>
                                    <form action="borrar.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este artículo?')">
                                            🗑 Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="bg-dark text-white text-center py-3 mt-5">
            <p class="mb-0">© <?= date("Y") ?> Realidad en Red - Panel de Administración</p>
        </footer>
    </body>
</html>