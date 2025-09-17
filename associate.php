<?php
require 'db.php';

// Agregar asociación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = (int) $_POST['producto_id'];
    $material_id = (int) $_POST['material_id'];
    $cantidad = (int) $_POST['cantidad'];
    if ($cantidad > 0) {
        $stmt = $pdo->prepare("SELECT id FROM producto_material WHERE producto_id=:p AND material_id=:m");
        $stmt->execute([':p'=>$producto_id, ':m'=>$material_id]);
        $row = $stmt->fetch();
        if ($row) {
            $pdo->prepare("UPDATE producto_material SET cantidad=cantidad+:c WHERE id=:id")
                ->execute([':c'=>$cantidad, ':id'=>$row['id']]);
        } else {
            $pdo->prepare("INSERT INTO producto_material (producto_id,material_id,cantidad) VALUES (:p,:m,:c)")
                ->execute([':p'=>$producto_id, ':m'=>$material_id, ':c'=>$cantidad]);
        }
    }
    header("Location: associate.php?producto_id=$producto_id");
    exit;
}

// Eliminar asociación
if (isset($_GET['delete_assoc'])) {
    $id = (int) $_GET['delete_assoc'];
    $pdo->prepare("DELETE FROM producto_material WHERE id=:id")->execute([':id'=>$id]);
    $producto_id = $_GET['producto_id'] ?? '';
    header("Location: associate.php?producto_id=$producto_id");
    exit;
}

// Listar productos y materiales
$productos = $pdo->query("SELECT * FROM productos ORDER BY nombre")->fetchAll();
$materiales = $pdo->query("SELECT * FROM materiales ORDER BY nombre")->fetchAll();

$selected = $_GET['producto_id'] ?? ($productos[0]['id'] ?? null);
$asociados = [];
if ($selected) {
    $stmt = $pdo->prepare("SELECT pm.id,m.nombre,pm.cantidad FROM producto_material pm JOIN materiales m ON pm.material_id=m.id WHERE pm.producto_id=:p");
    $stmt->execute([':p'=>$selected]);
    $asociados = $stmt->fetchAll();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Asignar Materiales</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>Asignar Materiales a Productos</h2>
  <a href="index.php" class="btn btn-sm btn-secondary mb-3">Volver</a>

  <form method="get" class="mb-3">
    <select name="producto_id" class="form-select" onchange="this.form.submit()">
      <?php foreach($productos as $p): ?>
        <option value="<?= $p['id'] ?>" <?= $p['id']==$selected?'selected':'' ?>><?= htmlspecialchars($p['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
  </form>

  <form method="post" class="row g-2 mb-4">
    <input type="hidden" name="producto_id" value="<?= $selected ?>">
    <div class="col-md-5">
      <select name="material_id" class="form-select" required>
        <option value="">Selecciona material</option>
        <?php foreach($materiales as $m): ?>
          <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <input type="number" name="cantidad" min="1" class="form-control" placeholder="Cantidad" required>
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary">Agregar</button>
    </div>
  </form>

  <h5>Materiales asignados</h5>
  <table class="table table-striped">
    <thead><tr><th>Material</th><th>Cantidad</th><th>Acción</th></tr></thead>
    <tbody>
      <?php foreach($asociados as $a): ?>
        <tr>
          <td><?= htmlspecialchars($a['nombre']) ?></td>
          <td><?= $a['cantidad'] ?></td>
          <td><a href="associate.php?delete_assoc=<?= $a['id'] ?>&producto_id=<?= $selected ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?');">Eliminar</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if(empty($asociados)): ?>
        <tr><td colspan="3" class="text-muted">No hay materiales asignados</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>

