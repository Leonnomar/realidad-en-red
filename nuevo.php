<?php
// nuevo.php
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Nuevo Artículo</title>
        <link rel="stylesheet" href="style.css"> <!-- tu CSS -->
    </head>
    <body>
        <h1>Crear nuevo artículo</h1>

        <form action="guardar.php" method="POST" enctype="multipart/form-data">
            <label for="titulo">Título:</label><br>
            <input type="text" name="titulo" id="titulo" required><br><br>

            <label for="contenido">Contenido:</label><br>
            <textarea name="contenido" id="contenido" rows="6" required></textarea><br><br>

            <label for="imagen">Imagen (opcional):</label><br>
            <input type="file" name="imagen" id="imagen" accept="image/*"><br><br>

            <button type="submit">Guardar Artículo</button>
        </form>

        <p><a href="panel.php">⬅ Volver al panel</a></p>
    </body>
</html>