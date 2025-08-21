<?php include 'conexion.php';

$sql = "SELECT id, titulo, contenido, imagen, fecha FROM articulos ORDER BY fecha DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Realidad en Red</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <?php  if ($resultado->num_rows > 0): ?>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <div class="articulo">
                    <h2><?php echo htmlspecialchars($fila['titulo']); ?></h2>
                    <small>Publica el: <?php echo date("d/m/Y H:i", strtotime($fila['fecha'])); ?></small>
                    <p><?php echo nl2br(htmlspecialchars($fila['contenido'])); ?></p>
                    <?php if (!empty($fila['imagen'])): ?>
                        <img src="<?php echo $fila['imagen']; ?>" alt="Imagen del artículo" width="300">
                    <?php endif; ?>
                </div>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay artículos publicados todavía.</p>
        <?php endif; ?>
    </body>
</html>