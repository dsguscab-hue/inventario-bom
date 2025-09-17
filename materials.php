<?php
require 'db.php';

// Crear material
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
    $stmt = $pdo->prepare("INSERT INTO materiales (nombre) VALUES (:nombre)");
    $stmt->execute([':nombre' => $nombre]);
    header('Location: materials.php');
    exit;
}

// Eliminar material
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $pdo->prepare("DELETE FROM producto_material WHERE material_id = :id")->execute([':id' => $id]);
    $pdo->prepare("DELETE FROM materiales WHERE id = :id")->execute([':id' => $id]);
    header('Location: materials.php');
    exit;
}

// Listar materiales
$materiales = $pdo->query("SELECT * FROM materiales ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Materiales</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>Gestión de Materiales</h2>
  <a href="index.php" class="btn btn-sm btn-secondary mb-3">Volver</a>

  <form method="post" class="row g-2 mb-4">
    <div class="col-auto">
      <input name="nombre" class="form-control" placeholder="Nuevo material" required>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Agregar</button>
    </div>
  </form>

  <table class="table table-striped">
    <thead><tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr></thead>
    <tbody>
      <?php foreach($materiales as $m): ?>
        <tr>
          <td><?= $m['id'] ?></td>
          <td><?= htmlspecialchars($m['nombre']) ?></td>
          <td>
            <a href="materials.php?delete_id=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar material?');">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
