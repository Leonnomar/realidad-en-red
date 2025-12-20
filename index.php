<?php 
include 'conexion.php';

// Verificar si hay búsqueda
$busqueda = "";
$fecha = "";
$where = [];
$sql = "SELECT a.*, c.color FROM articulos a LEFT JOIN categorias c ON a.categoria = c.nombre";
$fechasPublicadas = [];

// Obtener fechas con publicaciones
$resFechas = $conn->query("SELECT DISTINCT DATE(fecha) as fecha FROM articulos");
while ($row = $resFechas->fetch_assoc()) {
    $fechasPublicadas[] = $row['fecha'];
}

// Si hay texto
if (isset($_GET['buscar']) && !empty(trim($_GET['buscar']))) {
    $busqueda = trim($_GET['buscar']);
    $busquedaSQL = $conn->real_escape_string($busqueda);
    $where[] = "(titulo LIKE '%$busquedaSQL%' OR contenido LIKE '%$busquedaSQL%')";
}

// Si hay fecha
if (isset($_GET['fecha']) && !empty($_GET['fecha'])) {
    $fecha = $_GET['fecha'];
    $fechaSQL = $conn->real_escape_string($fecha);
    $where[] = "DATE(fecha) = '$fechaSQL'";
}

// Filtro por categoría
if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    $categoria = $conn->real_escape_string($_GET['categoria']);
    $where[] = "categoria = '$categoria'";
}

// Armar query final
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY fecha DESC";

// Ejecutar la consulta
$resultado = $conn->query($sql);
$total = $resultado ? $resultado->num_rows : 0;

//Función para resaltar conicidencias
function resaltar($texto, $busqueda) {
    $textoEsc = htmlspecialchars($texto);
    if (!$busqueda) return $textoEsc;
    // Escapar la  búsqueda para usar en regex (en su forma HTML)
    $busquedaEsc = preg_quote(htmlspecialchars($busqueda), '/');
    return preg_replace("/($busquedaEsc)/i", "<mark>$1</mark>", $textoEsc);
}

//Obtener categorías automáticamente
$categorias = [];
$resCat = $conn->query("SELECT nombre, color FROM categorias ORDER BY nombre ASC");
while ($row = $resCat->fetch_assoc()) {
    $categorias[] = $row;
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

                <!-- LOGO -->
                <a class="navbar-brand" href="index.php">
                    <img src="img/logo_barra.png" alt="Realidad en Red" height="45">
                </a>

                <!-- BOTON RESPOSIVE -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav ms-auto">

                        <!-- INICIO -->
                        <li class="nav-item">
                            <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF'])=='index.php' ? 'active' : '' ?>"
                            href="index.php">Inicio</a>
                        </li>

                        <!-- CATEGORIAS DESPEGABLES -->
                        <li class="nav-item dropdown">
                            <a class="nav-link text-white dropdown-toggle" href="#" id="catDropdown" role="button" data-bs-toggle="dropdown">
                                Categorías
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <?php foreach ($categorias as $cat): ?>
                                    <li>
                                        <a class="dropdown-item" href="index.php?categoria=<?= urlencode($cat['nombre']) ?>">
                                            <span class="badge bg-<? $cat['color'] ?> me-2"></span>
                                            <?= htmlspecialchars($cat['nombre']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>

                        <!-- CONTACTO -->
                        <li class="nav-item">
                            <a class="nav-link text-white" href="contacto.php">Contacto</a>
                        </li>

                        <!-- ACERCA DE -->
                        <li class="nav-item">
                            <a class="nav-link text-white" href="acercade.php">Nosotros</a>
                        </li>

                        <!-- LOGIN -->
                        <li class="nav-item">
                            <a class="nav-link text-white" href="login.php">Iniciar Sesión</a>
                        </li>
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
                    <div class="col-auto me-2">
                        <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar" value="<?= htmlspecialchars($busqueda) ?>">
                    </div>
                    <div class="col-auto me-2">
                        <input type="text" id="fecha" name="fecha" class="form-control form-control-sm" placeholder="Selecciona fecha" value="<?= htmlspecialchars($fecha) ?>">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- MENSAJE DE RESULTADOS -->
            <?php if (!empty($busqueda) || !empty($fecha)): ?>
                <?php if ($total > 0): ?>
                    <div class="alert alert-info text-center">
                        <?php if (!empty($busqueda) && !empty($fecha)): ?>
                            Se encontraron <strong><?= $total ?></strong> artículo(s) con la palabra
                            "<strong><?= htmlspecialchars($busqueda) ?></strong>" en la fecha
                            <strong><?= date("d/m/Y", strtotime($fecha)) ?></strong>.
                        <?php elseif (!empty($busqueda)): ?>
                            Se encontraron <strong><?= $total ?></strong> artículo(s) con la palabra:
                            "<strong><?= htmlspecialchars($busqueda) ?></strong>".
                        <?php else: /* sólo fecha */ ?>
                            Estos son los artículos del día <strong><?= date("d/m/Y", strtotime($fecha)) ?></strong>
                            (<?= $total ?> encontrado<?= $total > 1 ? "s" : "" ?>).
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center">
                        <?php if (!empty($busqueda) && !empty($fecha)): ?>
                            No se encontraron artículos con la palabra "<strong><?= htmlspecialchars($busqueda) ?></strong>"
                            en la fecha <strong><?= date("d/m/Y", strtotime($fecha)) ?></strong>.
                        <?php elseif (!empty($busqueda)): ?>
                            No se encontraron artículos con la palabra: "<strong><?= htmlspecialchars($busqueda) ?></strong>".
                        <?php else: ?> 
                            No hay artículos en la fecha <strong><?= date("d/m/Y", strtotime($fecha)) ?></strong>.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- LISTA DE ARTÍCULOS -->
            <div class="row g-4">
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <?php 
                            $titulo = resaltar($fila['titulo'], $busqueda);
                            $contenido = resaltar(substr($fila['contenido'], 0, 100), $busqueda);
                        ?>
                        <div class="col-md-4">
                            <div class="card shadow-sm h-100">
                                <?php if (!empty($fila['imagen'])): ?>
                                    <img src="<?= htmlspecialchars($fila['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($fila['titulo']) ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <span class="badge bg-<?= $fila['color'] ?? 'secondary' ?> mb-2">
                                        <?= htmlspecialchars($fila['categoria']) ?>
                                    </span>
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

        <script>
            window.onload = function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('buscar') || urlParams.has('fecha')) {
                    document.querySelector('input[name="buscar"]').value = "";
                    document.querySelector('input[name="fecha"]').value = "";
                }
            }
            document.addEventListener("DOMContentLoaded", function() {
                let fechasPublicadas = <?= json_encode($fechasPublicadas) ?>;

                flatpickr("#fecha", {
                    dateFormat: "Y-m-d",
                    defaultDate: "<?= $fecha ? htmlspecialchars($fecha) : '' ?>",
                    disable: [
                        function(date) {
                            // Deshabilita días que NO estén en publicaciones
                            let d = date.toISOString().split('T')[0];
                            return !fechasPublicadas.includes(d);
                        }
                    ],
                    onDayCreate: function(dObj, dStr, fp, dayElem) {
                        let d = dayElem.dateObj.toISOString().split('T')[0];
                        if (fechasPublicadas.includes(d)) {
                            dayElem.style.backgroundColor = "#0d6efd"; // azul bootstrap
                            dayElem.style.color = "white";
                            dayElem.style.borderRadius = "50%";
                        }
                    },
                    onChange: function(selectedDates, dateStr, instance) {
                        if (dateStr) {
                            document.querySelector('form').submit();
                        }
                    }
                });
            });
        </script>
    </body>
</html>