<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost","root","","realidadenred");
if ($conexion->connect_error) {
    die("Error de conexión". $conexion->connect_error);
}

// Si se recibe in id por GET para borrar
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM articulos WHERE id = $id");
    header("Location: panel.php"); // Recarga la página después de borrar
    exit;
}

// Obtener todos los artículos
$resultado = $conexion->query("SELECT * FROM articulos ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Panel de Administración</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
            th { background-color: #f4f4f4; }
            a { text-decoration: none; padding: 5px 10px; border-radius: 4px; }
            .borrar { background-color: red; color: white; }
            .editar { background-color: orange; color: white; }
        </style>
    </head>
    <body>
        <h1>Panel de Administración</h1>
        <a href="nuevo.php">➕ Nuevo Artículo</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Contenido</th>
                <th>Imagen</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= $fila['titulo'] ?></td>
                    <td><?= $fila['contenido'] ?></td>
                    <td><img src="<?= $fila['imagen'] ?>" width="80"></td>
                    <td><?= $fila['fecha'] ?></td>
                    <td>
                        <a class="editar" href="editar.php?id=<?= $fila['id'] ?>">Editar</a>
                        <a class="borrar" href="panel.php?eliminar=<?= $fila['id'] ?>" onclick="return confirm('¿Seguro que deseas borrar este artículo?')">Borrar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
        </table>
    </body>
</html>