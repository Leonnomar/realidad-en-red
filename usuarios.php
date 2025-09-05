<?php
session_start();
if ($_SESSION['rol'] !== 'admin') {
    header("Location: panel.php");
    exit;
}
include 'conxion.php';

// Lista de usuarios
$resultado = $conn->query("SELECT id, usuario, rol FROM usuarios");
?>

<h1>Gestión de Usuarios</h1>
<a href="nuevo_usuario.php">➕ Nuevo Usuario</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
    <?php while($fila = $resultado->fetch_assoc()): ?>
    <tr>
        <td><?= $fila['id'] ?></td>
        <td><?= $fila['usuario'] ?></td>
        <td><?= $fila['rol'] ?></td>
        <td>
            <a href="editar_usuario.php?id=<?= $fila['id'] ?>">Editar</a>
            <a href="eliminar_usuario.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>