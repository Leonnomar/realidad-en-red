<?php
include 'conexion.php';

// Categorías para Navbar
$sqlCat = "SELECT nombre FROM categorias ORDER BY nombre ASC";
$resCat = $conn->query($sqlCat);

// Verificar si se pasó un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// Consultar el artículo
$sql = "SELECT a.*, c.color FROM articulos a LEFT JOIN categorias c ON a.categoria = c.nombre WHERE a.id = $id LIMIT 1";
$resultado = $conn->query($sql);

if (!$resultado || $resultado->num_rows === 0) {
    $error = "El artículo no existe o fue eliminado.";
} else {
    $articulo = $resultado->fetch_assoc();
}

// ==================================
// ARTÍCULOS RELACIONADOS
// ==================================
$relacionados = [];

if (isset($articulo)) {
    $categoria = $conn->real_escape_string($articulo['categoria']);
    $fecha = date("Y-m-d", strtotime($articulo['fecha']));

    // Buscar artículos de la misma categoría y día (sin incluir el actual)
    $sqlRelacionados = "
        SELECT a.id, a.titulo, a.imagen, a.categoria, a.fecha, c.color
        FROM articulos a
        LEFT JOIN categorias c ON a.categoria = c.nombre
        WHERE a.id != $id
        AND (a.categoria = '$categoria' OR DATE(a.fecha) = '$fecha')
        ORDER BY a.fecha DESC
        LIMIT 4
    ";

    $resultadoRel = $conn->query($sqlRelacionados);
    if ($resultadoRel && $resultadoRel->num_rows > 0) {
        $relacionados = $resultadoRel->fetch_all(MYSQLI_ASSOC);
    }
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
        <style>
            .bg-realidad {
               background: #080924; 
            }
        </style>
    </head>

    <body class="bg-light">
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg bg-realidad">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="img/logo_barra.png" alt="Realidad en Red" height="45">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">

                        <li class="nav-item">
                            <a class="nav-link text-white" href="index.php">Inicio</a>
                        </li>

                        <!-- CATEGORÍAS DINÁMICAS -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">Categorías</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <?php while ($cat = $resCat->fetch_assoc()): ?>
                                <li>
                                    <a class="dropdown-item" href="index.php?categoria=<?= urlencode($cat['nombre']) ?>">
                                        <?= htmlspecialchars($cat['nombre']) ?>
                                    </a>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="contacto.php">Contacto</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active text-white" href="acercade.php">Nosotros</a>
                        </li>

                        <li class="nav-item text-white">
                            <a class="nav-link" href="login.php">Iniciar Sesión</a>
                        </li>
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
                            <span class="badge bg-<?= $articulo['color'] ?? 'secondary' ?>">
                                <?= htmlspecialchars($articulo['categoria']) ?>
                            </span>
                        </p>
                        <p class="text-muted">
                            Publicado por <strong><?= htmlspecialchars($articulo['autor']) ?></strong>
                            | Fecha: <?= date("d/m/Y H:i", strtotime($articulo['fecha'])) ?>
                        </p>
                        <div class="card-text" style="white-space: pre-line;"><?= nl2br(htmlspecialchars($articulo['contenido'])) ?></div>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <a href="index.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Volver</a>
                    </div>
                </div>

                <!-- ARTÍCULOS RELACIONADOS -->
                <?php if (!empty($relacionados)): ?>
                    <h4 class="text-center mb-4">📰 Artículos relacionados</h4>
                    <div class="row">
                        <?php foreach ($relacionados as $rel): ?>
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <?php if (!empty($rel['imagen'])): ?>
                                        <img src="<?= htmlspecialchars($rel['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($rel['titulo']) ?>" style="height: 160px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <span class="badge bg-<?= $rel['color'] ?? 'secondary' ?> mb-2">
                                            <?= htmlspecialchars($rel['categoria']) ?>
                                        </span>
                                        <h6 class="card-title">
                                            <a href="articulo.php?id=<?= $rel['id'] ?>" class="text-decoration-none text-dark">
                                                <?= htmlspecialchars($rel['titulo']) ?>
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-calendar"></i> <?= date("d/m/Y", strtotime($rel['fecha'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- FOOTER -->
        <footer class="bg-dark text-white text-center py-3 mt-5">
            <p class="mb-0">© <?= date("Y") ?> Realidad en Red - Todos los derechos reservados</p>
        </footer>
    </body>
</html>