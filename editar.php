<?php
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
        <title>Editar Artículo</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h1>Editar Artículo</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <label>Título:</label>
            <input type="text" name="titulo" value="<?= htmlspecialchars($articulo['titulo']) ?>" required>

            <label>Contenido:</label>
            <textarea name="contenido" required><?= htmlspecialchars($articulo['contenido']) ?></textarea>

            <label>Imagen:</label>
            <?php if (!empty($articulo['imagen'])): ?>
                <p>Imagen actual: <img src="img/<?= htmlspecialchars($articulo['imagen']) ?>" width="100"></p>
            <?php endif; ?>
            <input type="file" name="imagen">

            <button type="submit">Guardar Cambios</button>
        </form>
    </body>
</html>