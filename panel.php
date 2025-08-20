<?php
// Incluimos la conexión
include 'conexion.php';

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];

    // Guardar imagen si se sube
    $imagen = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $carpeta = "img/";
        $imagen = $carpeta . basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen);
    }

    // Insertar en la base de datos
    $sql = "INSERT INTO articulos (titulo, contenido, imagen, fecha) 
            VALUES ('$titulo', '$contenido', '$imagen', NOW())";

    if ($conn->query($sql) === TRUE) {
        header("Location: panel.php?success=1");
        exit();
    } else {
        echo "<p style='color:red'>❌ Error: " . $conn->error . "</p>";
    }
}
?>

<?php if (isset($_GET['success'])): ?>
    <p style="color:green">✅ Artículo publicado correctamente.</p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel de Publicación</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h1>📢 Publicar nuevo artículo</h1>


        <form action="panel.php" method="POST" enctype="multipart/form-data">
            <label>Título:</label><br>
            <input type="text" name="titulo" required><br><br>

            <label>Contenido:</label><br>
            <textarea name="contenido" rows="6" required></textarea><br><br>

            <label>Imagen:</label>
            <input type="file" name="imagen"><br><br>

            <button type="submit">📌 Publicar</button>
        </form>
    </body>
</html>