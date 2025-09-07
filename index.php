<?php 
include 'conexion.php';

// Verificar si hay búsqueda
$busqueda = "";
$resultado = null;

if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda = trim($_GET['buscar']);
    $busquedaSQL = $conn->real_escape_string($busqueda);

    $sql = "SELECT * FROM articulos WHERE titulo LIKE '%$busqueda%' OR contenido LIKE '%$busqueda%' ORDER BY fecha DESC";
    $resultado = mysqli_query($conn, $sql);

    $total = mysqli_num_rows($resultado);
} else {
    $sql = "SELECT * FROM articulos ORDER BY fecha DESC";
}

// Ejecutar la consulta
$resultado = $conn->query($sql);
$total = $resultado->num_rows;

//Función para resaltar conicidencias
function resaltar($texto, $busqueda) {
    if (!$busqueda) return htmlspecialchars($texto);
    return preg_replace("/(" . preg_quote($busqueda, '/') . ")/i", "<mark>$1</mark>", htmlspecialchars($texto));
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Realidad en Red</title>
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
                        <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Iniciar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- CONTENIDO -->
        <div class="container my-5">
            <h1 class="mb-4 text-center text-primary">Últimos Artículos</h1>

            <!-- FORMULARIO DE BUSQUEDA -->
            <div class="container my-3">
                <form method="GET" action="index.php" class="d-flex justify-content-end">
                    <div class="input-group" style="max-width: 250px;">
                        <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar">
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- MENSAJE DE RESULTADOS -->
            <?php if (!empty($busqueda)): ?>
                <?php if ($total > 0): ?>
                    <div class="alert alert-info text-center">
                    Se encontraron <strong><?= $total ?></strong> artículo(s) con: <strong><?= htmlspecialchars($busqueda) ?></strong>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center">
                        No se encontraron artículos con: <strong><?= htmlspecialchars($busqueda) ?></strong>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- LISTA DE ARTÍCULOS -->
            <div class="row g-4">
                <?php if ($resultado->num_rows > 0): ?>
                    <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <?php 
                            $titulo = resaltar($fila['titulo'], $busqueda);
                            $contenido = resaltar(substr($fila['contenido'], 0, 100), $busqueda);
                        ?>
                        <div class="col-md-4">
                            <div class="card shadow-sm h-100">
                                <?php if (!empty($fila['imagen'])): ?>
                                    <img src="<?= $fila['imagen'] ?>" class="card-img-top" alt="<?= htmlspecialchars($fila['titulo']) ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= $titulo ?></h5>
                                    <p class="card-text"><?= $contenido ?>...</p>
                                    <p class="text-muted"><small><?= $fila['fecha'] ?></small></p>
                                    <a href="articulo.php?id=<?= $fila['id'] ?>" class="btn btn-primary btn-sm">Leer más</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center text-muted">No se encontraron artículos.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="bg-dark text-white text-center py-3 mt-5">
            <p class="mb-0">© <?= date("Y") ?> Realidad en Red - Todos los derechos reservados</p>
        </footer>
    </body>
</html>