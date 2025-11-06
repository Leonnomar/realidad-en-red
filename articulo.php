<?php
include 'conexion.php';

// Verificar si se pasó un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// Consultar el artículo
$sql = "SELECT * FROM articulos WHERE id = $id LIMIT 1";
$resultado = $conn->query($sql);

if (!$resultado || $resultado->num_rows === 0) {
    $error = "El artículo no existe o fue eliminado.";
} else {
    $articulo = $resultado->fetch_assoc();
}

function colorCategoria($categoria) {
    return match (strtolower($categoria)) {
        'policiaca' => 'danger', // rojo
        'deporte' => 'success',  // verde
        'sinaloa' => 'info',     // celeste
        default => 'secondary',  // gris
    };
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= isset($articulo) ? htmlspecialchars($articulo['titulo']) . " - Realidad en Red" : "Artículo no encontrado" ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
                        <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Iniciar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- CONTENIDO -->
        <div class="container my-5">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($error) ?><br>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm mt-2">Volver al inicio</a>
                </div>
            <?php else: ?>
                <div class="card shadow-sm">
                    <?php if (!empty($articulo['imagen'])): ?>
                        <img src="<?= htmlspecialchars($articulo['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($articulo['titulo']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h1 class="card-title mb-3 text-primary"><?= htmlspecialchars($articulo['titulo']) ?></h1>
                        <p class="mb-1">
                            <span class="badge bg-<?= colorCategoria($fila['categoria']) ?>">
                                <?= htmlspecialchars($articulo['categoria']) ?>
                            </span>
                        </p>
                        <p class="text-muted mb-3">
                            <i class="bi bi-calendar"></i> Publicado el <?= date("d/m/Y H:i", strtotime($articulo['fecha'])) ?>
                        </p>
                        <div class="card-text" style="white-space: pre-line;"><?= nl2br(htmlspecialchars($articulo['contenido'])) ?></div>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <a href="index.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Volver</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- FOOTER -->
        <footer class="bg-dark text-white text-center py-3 mt-5">
            <p class="mb-0">© <?= date("Y") ?> Realidad en Red - Todos los derechos reservados</p>
        </footer>
    </body>
</html>