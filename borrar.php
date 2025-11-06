<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Buscar artículo para eliminar su imagen
    $stmt = $conn->prepare("SELECT imagen FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $articulo = $resultado->fetch_assoc();

    if ($articulo) {
        // Eliminar imagen del servidor
        if (!empty($articulo['imagen']) && file_exists("img/" . $articulo['imagen'])) {
            unlink("img/" . $articulo['imagen']);
        }

        // Eliminar de la BD
        $stmt_delete = $conn->prepare("DELETE FROM articulos WHERE id = ?");
        $stmt_delete->bind_param("i", $id);
        if ($stmt_delete->execute()) {
            $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => '✅ Artículo eliminado correctamente'];
        } else {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => '❌ Error al eliminar el artículo'];
        }
    } else {
        $_SESSION['alerta'] = ['tipo' => 'warning', 'mensaje' => '⚠️ El artículo no existe'];
    }

    header("Location: panel.php");
    exit;
} else {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => '❌ Solicitud inválida'];
    header("Location: panel.php");
    exit;
}
?>