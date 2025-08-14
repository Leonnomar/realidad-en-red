<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel de Publicación</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <header>
            <h1>Panel de Publicación</h1>
        </header>

        <main>
            <form action="subir.php" method="POST" enctype="multipart/form-data">
                <label for="titulo">Título:</label>
                <input type="text" name="titulo" id="titulo" required>

                <label for="contenido">Contenido:</label>
                <textarea name="contenido" id="contenido" rows="6" required></textarea>

                <label for="imagen">Imagen:</label>
                <input type="file" name="imagen" id="imagen" accept="image/*">

                <button type="submit">Publicar</button>
            </form>
        </main>
    </body>
</html>