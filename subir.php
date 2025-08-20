<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $fecha = date("Y-m-d H:i:s");

    // Manejo de imagen
    $imagenNombre = "";
    if (!empty($_FILES["imagen"]["name"])) {
        $imagenNombre = basename($_FILES["imagen"]["name"]);
        $rutaDestino = "img/" . $imagenNombre;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);
    }

    // Insertar en la base de datos
    $sql = "INSERT INTO articulos (titulo, contenido, imagen, fecha) VALUES ('$titulo', '$contenido', '$imagenNombre', '$fecha')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Artículo publicado correctamente. <a href='index.php'>Ver artículos</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>