<?php
$servername = "localhost";
$username = "root"; // Cambia si tienes usuario distinto
$password = ""; // Vacío si no se tiene contraseña
$dbname = "realidadenred"; //Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>