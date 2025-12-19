<?php
include 'conexion.php';

$categorias = $conn->query("SELECT nombre FROM categorias ORDER BY nombre ASC");
// nuevo.php
session_start();

// Si no hay sesión, redirigir
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nuevo Artículo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="panel.php">⚙️ Panel de Administración</a>
                <div class="d-flex">
                    <a href="logout.php" class="btn btn-outline-light">Cerrar sesión</a>
                </div>
            </div>
        </nav>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Crear Nuevo Artículo</h5>
                        </div>
                        <div class="card-body">
                            <form action="guardar.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título:</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                                </div>

                                <div class="mb-3">
                                    <label for="categoria" class="form-label">Categoría:</label>
                                    <select class="form-select" id="categoria" name="categoria"required onchange="toggleNuevaCategoria()">
                                        <option value="">Selecciona una categoría</option>

                                        <?php while ($cat = $categorias->fetch_assoc()): ?>
                                            <option value="<?= htmlspecialchars($cat['nombre']) ?>">
                                                <?= htmlspecialchars($cat['nombre']) ?>
                                            </option>
                                        <?php endwhile; ?>

                                        <?php if ($_SESSION['rol'] === 'admin'): ?>
                                            <option value="otra">➕ Otra (crear nueva)</option>
                                        <?php endif; ?>
                                    </select>

                                    <?php if ($_SESSION['rol'] === 'admin'): ?>
                                        <div class="mb-3 d-none" id="nuevaCategoriaBox">
                                            <label class="form-label">Nueva categoría:</label>
                                            <input type="text" class="form-control" name="nueva_categoria" placeholder="Ej: Tecnología">
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="contenido" class="form-label">Contenido:</label>
                                    <textarea class="form-control" id="contenido" name="contenido" rows="6" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="imagen" class="form-label">Imagen (opcional):</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                </div>

                                <button type="submit" class="btn btn-success w-100">Guardar Artículo</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script>
            function toggleNuevaCategoria() {
                const select = document.getElementById('categoria');
                const box = document.getElementById('nuevaCategoriaBox');

                if (select.value === 'otra') {
                    box.classList.remove('d-none');
                } else {
                    box.classList.add('d-none');
                }
            }
        </script>
    </body>
</html>