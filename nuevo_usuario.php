<?php
session_start();
if ($_SESSION['rol'] !== 'admin') {
    header("Location: panel.php");
    exit;
}
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password, rol) VALUE (?, ?, ?)");
    $stmt->bind_param("sss", $usuario, $password, $rol);
    $stmt->execute();

    header("Location: usuarios.php");
    exit;
}
?>

<form method="POST">
    <label>Usuario:</label>
    <input type="text" name="usuario" required><br>
    <label>Contraseña:</label>
    <input type="password" name="password" required><br>
    <label>Rol:</label><br>
    <select name="rol">
        <option value="editor">Editor</option>
        <option value="admin">Admin</option>
    </select><br><br>
    <button type="submit">Crear Usuario</button>
</form>