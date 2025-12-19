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
        <title>Acerca de - Realidad en Red</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            body {
                background-color: #ffffff;
                color: #212529;
            }
            .logo-crop {
                width: 100%;
                max-width: 1300px;
                height: 275px;
                overflow: hidden;
                margin: 0 auto 30px auto;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .logo-horizontal {
                width: 100%;
                height: auto;
            }
            .text-box {
                font-size: 1.1rem;
                line-height: 1.7;
            }
            .social-icons a {
                transition: transform 0.2s ease, opacity 0.2s ease;
            }
            .social-icons a:hover {
                transform: scale(1.2);
                opacity: 0.8;
            }
        </style>
    </head>

    <body>

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php">🌐 Realidad en Red</a>

                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">

                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Inicio</a>
                        </li>

                        <!-- CATEGORÍAS DINÁMICAS -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Categorías</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <?php while ($cat = $resCat->fetch_assoc()): ?>
                                <li>
                                    <a class="dropdown-item" href="index.php?categoria=<?= urlencode($cat['categoria']) ?>">
                                        <?= htmlspecialchars($cat['categoria']) ?>
                                    </a>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="acercade.php">Quiénes somos</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="contacto.php">Contacto</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Iniciar Sesión</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <!-- CONTENIDO -->
        <div class="container mt-0 mb-3">

            <!-- IMAGEN PRINCIPAL -->
            <div class="logo-crop">
                <img src="img/logo.png" class="logo-horizontal" alt="Logo Realidad en Red">
            </div>

            <h1 class="text-center mb-4">Quiénes Somos</h1>

            <div class="card bg-light text-dark shadow-lg p-4">
                <div class="card-body text-box">

                    <p>En la actualidad, con el crecimiento exponencial de la tecnología, gran parte del tiempo de las personas está dedicado a la conectividad en internet y las diversas redes sociales.</p>

                    <p>Por eso, <strong>Realidad en Red</strong> surge como un medio de comunicación que aspira a brindar información actualizada, precisa y veraz, aprovechando las ventajas de la red.</p>

                    <p>Nuestra visión es convertirnos en un medio que informe de manera clara, con certeza, objetividad y, como lo requieren los tiempos actuales, con inmediatez.</p>

                    <p>Fomentamos la interacción con nuestra audiencia mediante distintas plataformas, donde escuchamos críticas, comentarios y propuestas.</p>

                    <p>Creemos firmemente en una relación franca, cordial y duradera con nuestro público. Este proyecto nace con esa convicción.</p>

                    <hr class="border-dark">

                    <h4 class="mt-4 text-center">Síguenos en redes</h4>
                    <div class="text-center social-icons">
                        <a href="https://www.facebook.com/realidadenred" aria-label="Facebook">
                            <i class="fab fa-facebook fa-2x" style="color:#1877f2;"></i>
                        </a>

                        <a href="https://x.com/realidadenlared" aria-label="X">
                            <i class="fab fa-x-twitter fa-2x" style="color:#000;"></i>
                        </a>

                        <a href="https://www.youtube.com/channel/UCsJaxLZfwhHxJkjmwqwl3qA?view_as=subscriber" aria-label="YouTube">
                            <i class="fab fa-youtube fa-2x" style="color:#ff0000;"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="bg-dark text-white text-center py-3 mt-5">
            <p class="mb-0">© <?= date("Y") ?> Realidad en Red - Todos los derechos reservados</p>
        </footer>
    </body>
</html>