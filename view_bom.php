<?php
require 'db.php';

$productos = $pdo->query("SELECT * FROM productos ORDER BY nombre")->fetchAll();
$selected = $_GET['producto_id'] ?? ($productos[0]['id'] ?? null);
$items = [];
if ($selected) {
    $stmt = $pdo->prepare("SELECT m.nombre,pm.cantidad FROM producto_material pm JOIN materiales m ON pm.material_id=m.id WHERE pm.producto_id=:p");
    $stmt->execute([':p'=>$selected]);
    $items = $stmt->fetchAll();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Consulta BOM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2>Consulta de Lista de Materiales</h2>
  <a href="index.php" class="btn btn-sm btn-secondary mb-3">Volver</a>

  <form method="get" class="mb-3">
    <select name="producto_id" class="form-select" onchange="this.form.submit()">
      <?php foreach($productos as $p): ?>
        <option value="<?= $p['id'] ?>" <?= $p['id']==$selected?'selected':'' ?>><?= htmlspecialchars($p['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
  </form>

  <table class="table table-striped">
    <thead><tr><th>Material</th><th>Cantidad</th></tr></thead>
    <tbody>
      <?php foreach($items as $it): ?>
        <tr>
          <td><?= htmlspecialchars($it['nombre']) ?></td>
          <td><?= $it['cantidad'] ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if(empty($items)): ?>
        <tr><td colspan="2" class="text-muted">No hay materiales asignados</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
