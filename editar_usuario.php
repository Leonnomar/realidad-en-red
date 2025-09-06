<?php
session_start();
if ($_SESSION['rol'] !== 'admin') {
    header("Location: panel.php");
    exit;
}

include 'conexion.php';

//Obtener usuario por ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    if (!$usuario) {
        die("Usuario no encontrado");
    }
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);
    $usuarioNuevo = $_POST['usuario'];
    $rol = $_POST['rol'];

    // Si el admin deja vacío el campo de contraseña, no se actualiza
    if (!empty($_POST['password'])) {
        if (empty($usuarioNuevo)) {
            die("El campo usuario no puede estar vacío.");
        }
        if (!in_array($rol, ['admin', 'editor'])) {
            die("Rol inválido.");
        }
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET usuario = ?, password = ?, rol = ? WHERE id = ?");
        $stmt->bind_param("sssi", $usuarioNuevo, $password, $rol, $id);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET usuario = ?, rol = ? WHERE id = ?");
        $stmt->bind_param("ssi", $usuarioNuevo, $rol, $id);
    }
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Usuario actualizado correctamente.";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "❌ Error al actualizar el usuario.";
        $_SESSION['tipo_mensaje'] = "danger";
    }

    header("Location: usuarios.php");
    exit;
}
?>

<h1>Editar Usuario</h1>
<form method="POST">
    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

    <label>Usuario:</label><br>
    <input type="text" name="usuario" value="<?= $usuario['usuario'] ?>" required><br><br>

    <label>Nueva Contraseña (Opcional):</label><br>
    <input type="password" name="password"><br><br>

    <label>Rol:</label><br>
    <select name="rol">
        <option value="editor" <?= $usuario['rol'] === 'editor' ? 'selected' : '' ?>>Editor</option>
        <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select><br><br>

    <button type="submit">Guardar cambios</button>
</form>