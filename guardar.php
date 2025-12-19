<?php
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    die("Error: No hay usuario en sesión.");
}

$titulo = $_POST['titulo'];
$categoria = $_POST['categoria'];
$nuevaCategoria = trim($_POST['nueva_categoria'] ?? '');
$contenido = $_POST['contenido'];
$fecha = date("Y-m-d H:i:s");
$autor = $_SESSION['usuario'];

// Validación
if (empty($titulo) || empty($contenido)) {
    die("El título y el contenido son obligatorios.");
}

// Manejo de imagen
$imagen = null;
if (!empty($_FILES['imagen']['name'])) {
    $carpeta = "img/";
    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    $imagen = $carpeta . uniqid() . "_" . basename($_FILES["imagen"]["name"]);
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen);
}

if ($categoria === 'otra' && $_SESSION['rol'] === 'admin') {
    if ($nuevaCategoria === '') {
        die("Debes escribir el nombre de la nueva categoría.");
    }

    // Asignar color automático (puede ser random)
    $colores = ['primary', 'success', 'danger', 'warning', 'info', 'dark'];
    $color = $colores[array_rand($colores)];

    // Insertar categoría si no existe
    $stmt = $conn->prepare("INSERT IGNORE INTO categorias (nombre, color) VALUES (?, ?)");
    $stmt->bind_param("ss", $nuevaCategoria, $color);
    $stmt->execute();

    $categoria = $nuevaCategoria;
}

// Guardar en la base de datos
$sql = "INSERT INTO articulos (titulo, categoria, contenido, imagen, fecha, autor) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $titulo, $categoria, $contenido, $imagen, $fecha, $autor);
$stmt->execute();

header("Location: index.php");
exit;
?>