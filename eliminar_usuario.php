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
        die("⚠️ No puedes eliminar tu propio usuario.");
    }

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: usuarios.php");
    exit;
}
?>