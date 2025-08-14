<?php
include 'conexion.php';

$titulo = $_POST['titulo'];
$contenido = $_POST['contenido'];

// Manejo de imagen
$imagen = null;
if (!empty($_FILES['imagen']['name'])) {
    $carpeta = "uploads/";
    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    $imagen = $carpeta . basename($_FILES["imagen"]["name"]);
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen)
}

$sql = "INSERT INTO articulos (titulo, contenido, imagen) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $titulo, $contenido, $imagen);
$stmt->execute();

header("Location: index.php");
?>