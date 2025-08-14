<?php include 'conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Realidad en Red</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <header>
            <h1>Realidad en Red</h1>
        </header>

        <main>
            <?php
            $sql = "SELECT titulo, contenido, imagen, fecha FROM articulos ORDER BY fecha DESC";
            $resultado = $conn->query($sql);

            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<article>";
                    echo "<h2>" . htmlspecialchars($fila['titulo']) . "</h2>";
                    if (!empty($fila['imagen'])) {
                        echo "<img src='img/" . htmlspecialchars($fila['imagen']) . "' alt='Imagen del artículo'>";
                    }
                    echo "<p>" . nl2br(htmlspecialchars($fila['contenido'])) . "</p>";
                    echo "<small>Publicado el " . $fila['fecha'] . "</small>";
                    echo "</article>"
                }
            } else {
                echo "<p>No hay artículos publicados.</p>"
            }
            ?>
        </main>
    </body>
</html>