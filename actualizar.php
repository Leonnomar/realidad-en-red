<?php
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = mysql_real_escape_string($conn, $_POST['titulo']);
    $contenido = mysql_real_escape_string($conn, $_POST['contenido']);

    // Buscar el artículo actual para saber si tiene imagen
    $sql = "SELECT imagen FROM articulos WHERE id = $id";
    $resultado = mysql_query($conn,$sql);
    $articulo = mysql_fetch_array($resultado);
    $imagen_actual = $articulo['imagen'];

    // Manejo de nueva imagen
    if (!empty($_FILES['imagen']['name'])) {
        $nombre_imagen = time() . "_" . basename($_FILES['imagen']['name']);
        $rutaDestino = "img/" . $nombre_imagen;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            // Eliminar imagen anterior si existía
            if (!empty($imagen_actual) && file_exists("img/" . $imagen_actual)) {
                unlink("img/" . $imagen_actual);
            }
            $imagen_final = $nombre_imagen;
        } else {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Error al subir la imagen'];
            header("Location: usuarios.php");
            exit;
        }
    } else {
        $imagen_final = $imagen_actual; //Mantener imagen actual
    }

    // Actualizar en BD
    $sql_update = "UPDATE articulos SET titulo='$titulo', contenido='$contenido', imagen='$imagen_final' WHERE id=$id";
    if (mysql_query($conn, $sql_update)) {
        $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Artículo actualizado correctamente'];
    } else {
        $_SESSION['alerta'] = ['tipo'=> 'danger', 'mensaje' => 'Error al actualizar el artículo'];
    }
    header("Location: panel.php");
    exit;
}
?>