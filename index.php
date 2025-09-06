<?php include 'conexion.php';

// Obtener todos los artículos
$resultado = $conn->query("SELECT * FROM articulos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Realidad en Red</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="bg-light">

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php">🌐 Realidad en Red</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Iniciar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- CONTENIDO -->
        <div class="container my-5">
            <h1 class="mb-4 text-center text-primary">Últimos Artículos</h1>

            <div class="row g-4">
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            <?php if (!empty($fila['imagen'])): ?>
                                <img src="<?= $fila['imagen'] ?>" class="card-img-top" alt="<?= $fila['titulo'] ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= $fila['titulo'] ?></h5>
                                <p class="card-text"><?= substr($fila['contenido'], 0, 100) ?>...</p>
                                <p class="text-muted"><small><?= $fila['fecha'] ?></small></p>
                                <a href="articulo.php?id=<?= $fila['id'] ?>" class="btn btn-primary btn-sm">Leer más</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="bg-dark text-white text-center py-3 mt-5">
            <p class="mb-0">© <?= date("Y") ?> Realidad en Red - Todos los derechos reservados</p>
        </footer>
    </body>
</html>