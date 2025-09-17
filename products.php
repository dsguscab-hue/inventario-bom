<?php
require 'db.php';

// Crear producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
    $stmt = $pdo->prepare("INSERT INTO productos (nombre) VALUES (:nombre)");
    $stmt->execute([':nombre' => $nombre]);
    header('Location: products.php');
    exit;
}

// Eliminar producto (y sus asociaciones)
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    // borrar asociaciones
    $stmt = $pdo->prepare("DELETE FROM producto_material WHERE producto_id = :id");
    $stmt->execute([':id' => $id]);
    // borrar producto
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: products.php');
    exit;
}

// Obtener productos
$productos = $pdo->query("SELECT * FROM productos ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Productos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>Productos</h2>
  <a href="index.php" class="btn btn-sm btn-secondary mb-3">Volver</a>

  <form method="post" class="row g-2 mb-4">
    <div class="col-auto">
      <input name="nombre" class="form-control" placeholder="Nuevo producto" required>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Agregar</button>
    </div>
  </form>

  <table class="table table-striped">
    <thead><tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr></thead>
    <tbody>
      <?php foreach($productos as $p): ?>
        <tr>
          <td><?=htmlspecialchars($p['id'])?></td>
          <td><?=htmlspecialchars($p['nombre'])?></td>
          <td>
            <a class="btn btn-sm btn-danger" href="products.php?delete_id=<?= $p['id'] ?>" onclick="return confirm('Borrar producto y sus asociaciones?');">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
