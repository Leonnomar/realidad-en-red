<?php
session_start();
if ($_SESSION['rol'] !== 'admin') {
    header("Location: panel.php");
    exit;
}

include 'conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Evitar que el admin se borre a sí mismo
    if ($id == $_SESSION['id']) {
        $_SESSION['mensaje'] = "⚠️ No puedes eliminar tu propio usuario.";
        $_SESSION['tipo_mensaje'] = "warning";
    }

    // Verificar si el usuario existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("⚠️ Usuario no encontrado.");
    }

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Usuario eliminado correctamente.";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "❌ Error al eliminar el usuario.";
        $_SESSION['tipo_mensaje'] = "danger";
    }

    header("Location: usuarios.php");
    exit;
}
?>