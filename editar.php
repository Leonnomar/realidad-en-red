<?php
// editar.php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

include 'conexion.php';

// 1. Verificar si se pasa un ID por GET
if (!isset($_GET['id'])) {
    die("ID no propocionado.");
}

$id = intval($_GET['id']);

// 2. Obtener datos del artículo actual
$stmt = $conn->prepare("SELECT * FROM articulos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$articulo = $resultado->fetch_assoc();

if (!$articulo) {
    die("Artículo no encontrado.");
}

// 3. Si se envía el formulario, actualizar datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];

    // Manejo de imagen
    $imagen = $articulo['imagen']; // Imagen actual
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = basename($_FILES['imagen']['name']);
        $rutaDestino = "img/" . $imagen;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);

        //Borrar imagen anterior si existe
        if (!empty($articulo['imagen']) && file_exists("img/" . $articulo['imagen'])) {
            unlink("img/" . $articulo['imagen']);
        }
    }

    // Actualizar en la base de datos
    $stmt = $conexion->prepare("UPDATE articulos SET titulo = ?, contenido = ?, imagen = ?, WHERE id = ?");
    $stmt->bind_param("sssi", $titulo, $contenido, $imagen, $id);
    $stmt->execute();

    header("Location: panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Editar Artículo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="bg-light">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="panel.php">⚙️ Panel de Administración</a>
                <div class="d-flex">
                    <a href="logout.php" class="btn btn-outline-light">Cerrar sesión</a>
                </div>
            </div>
        </nav>
        
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    
                    <div class="card shadow-lg">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">Editar Artículo</h5>
                        </div>
                        <div class="card-body">
                            <form action="actualizar.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $articulo['id'] ?>">

                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título:</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" value="<?= $articulo['titulo'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="contenido" class="form-label">Contenido:</label>
                                    <textarea class="form-control" id="contenido" name="contenido" rows="5" required><?= $articulo['contenido'] ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="imagen" class="form-label">Imagen:</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                    <small class="text-muted">Si no seleccionas una, se mantendrá la actual</small>
                                    <div class="mt-2">
                                        <img src="img/<?= $articulo['imagen'] ?>" alt="Imagen actual" width="150" class="rounded shadow">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>