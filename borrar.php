<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Buscar artículo para eliminar su imagen
    $sql = "SELECT imagen FROM articulos WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $articulo = mysqli_fetch_assoc($result);

    if ($articulo) {
        // Eliminar imagen del servidor
        if (!empty($articulo['imagen']) && file_exists("img/" . $articulo['imagen'])) {
            unlink("img/" . $articulo['imagen']);
        }

        // Eliminar de la BD
        $sql_delete = "DELETE FROM articulos WHERE id = $id";
        if (mysqli_query($conn, $sql_delete)) {
            $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Artículo eliminado correctamente'];
        } else {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Error al eliminar el artículo'];
        }
    } else {
        $_SESSION['alerta'] = ['tipo' => 'warning', 'mensaje' => 'El artículo no existe'];
    }

    header("Location: panel.php");
    exit;
}
?>