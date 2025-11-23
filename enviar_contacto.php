<?php
// Datos enviados desde el formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$mensaje = $_POST['mensaje'];

// Validación básica
if (empty($nombre) || empty($correo) || empty($mensaje)) {
    die("Todos los campos son obligatorios.");
}

// Email a enviar
$destinatario = "realidad2punto0@hotmail.com";
$asunto = "📩 Nuevo mensaje desde Realidad en Red";

// Encabezados para enviar en HTML
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: $nombre <$correo>\r\n";
$headers .= "Reply-To: $correo\r\n";

// Cuerpo del mensaje en HTML
$contenido = "
<html>
<body style='font-family: Arial, sans-serif; background:#f7f7f7; padding:20px;'>

    <div style='max-width:600px; margin:0 auto; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px #ccc;'>

        <h2 style='color:#007bff; text-align:center;'>
            📧 Nuevo mensaje desde Realidad en Red
        </h2>

        <p style='font-size:16px;'>
            <strong>👤 Nombre:</strong> {$nombre}
        </p>

        <p style='font-size:16px;'>
            <strong>📨 Correo:</strong> {$correo}
        </p>

        <p style='font-size:16px; margin-top:20px;'>
            <strong>💬 Mensaje:</strong><br>
            <div style='background:#f1f1f1; padding:15px; border-radius:5px; margin-top:10px;'>
                ". nl2br(htmlspecialchars($mensaje)) ."
            </div>
        </p>

        <hr>

        <p style='text-align:center; color:#777; font-size:14px;'>
            Enviado el ".date("d/m/Y H:i")."<br>
            Sitio: <strong>Realidad en Red</strong>
        </p>

    </div>

</body>
</html>";
    
// Enviar correo
if (mail($destinatario, $asunto, $contenido, $headers)) {
    header("Location: contacto.php?enviado=1");
    exit;
} else {
    echo "Hubo un error al enviar el mensaje.";
}
?>
