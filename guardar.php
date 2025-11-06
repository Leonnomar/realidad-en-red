<?php
include 'conexion.php';

$titulo = $_POST['titulo'];
$categoria = $_POST['categoria'];
$contenido = $_POST['contenido'];
$fecha = date("Y-m-d H:i:s");

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

$sql = "INSERT INTO articulos (titulo, categoria, contenido, imagen, fecha) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $titulo, $categoria, $contenido, $imagen, $fecha);
$stmt->execute();

header("Location: index.php");
exit;
?>