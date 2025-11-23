<?php
include 'conexion.php';

// Obtener categorías dinámicamente para el menú
$resCat = $conn->query("SELECT DISTINCT categoria FROM articulos ORDER BY categoria ASC");
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contacto - Realidad en Red</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    </head>

    <body class="bg-light">
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php">🌐 Realidad en Red</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">

                    <ul class="navbar-nav ms-auto">

                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Inicio</a>
                        </li>

                        <!-- MENÚ DE CATEGORÍAS -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Categorías</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <?php while ($cat = $resCat->fetch_assoc()): ?>
                                    <li><a class="dropdown-item" href="index.php?categoria=<?= urlencode($cat['categoria']) ?>">
                                        <?= htmlspecialchars($cat['categoria']) ?>
                                    </a></li>
                                <?php endwhile; ?>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="contacto.php">Contacto</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Iniciar Sesión</a>
                        </li>

                    </ul>

                </div>
            </div>
        </nav>

        <!-- CONTENIDO -->
        <div class="container my-5">
            <h1 class="text-center text-primary mb-4">Contáctanos</h1>

            <div class="row g-4">

                <!-- INFORMACIÓN DEL ADMINISTRADOR -->
                <div class="col-md-5">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Información de contacto</h5>
                        </div>

                        <div class="card-body">
                            <p><strong>Administrador:</strong> <br> *Sabás Espinoza*</p>
                            <p><strong>Correo:</strong> <br> <a href="mailto:realidad2punto0@hotmail.com">realidad2punto0@hotmail.com</a></p>
                            <p><strong>Teléfono:</strong> <br> +52 612 137 8974</p>

                            <hr>

                            <p class="text-muted">
                                Puedes ponerte en contacto para consultas, sugerencias, reportes o colaboraciones.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FORMULARIO DE CONTACTO -->
                <div class="col-md-7">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-envelope-fill"></i> Enviar mensaje</h5>
                        </div>

                        <div class="card-body">
                            <form action="enviar_contacto.php" method="POST">

                                <div class="mb-3">
                                    <label class="form-label">Tu nombre:</label>
                                    <input type="text" name="nombre" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Correo electrónico:</label>
                                    <input type="email" name="correo" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mensaje:</label>
                                    <textarea name="mensaje" class="form-control" rows="5" required></textarea>
                                </div>

                                <button class="btn btn-success w-100" type="submit">
                                    <i class="bi bi-send-fill"></i> Enviar mensaje
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAPA -->
            <div class="mt-5">
                <h4 class="text-center">Nuestra ubicación</h4>
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57639.660852378925!2d-108.11479766936694!3d25.455682827382923!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x86bb87d8e10c89a1%3A0xece24bc159d1c8b0!2sGuam%C3%BAchil%2C%20Sin.!5e0!3m2!1ses!2smx!4v1763769086140!5m2!1ses!2smx" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="bg-dark text-white text-center py-3 mt-5">
            <p class="mb-0">© <?= date("Y") ?> Realidad en Red - Todos los derechos reservados</p>
        </footer>
    </body>
</html>