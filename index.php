<?php ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Inventario - Inicio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h1 class="mb-4">Sistema Inventario (BOM)</h1>

    <div class="row">
      <div class="col-md-6">
        <h4>Módulo Administrativo</h4>
        <ul>
          <li><a href="products.php">Gestionar Productos</a></li>
          <li><a href="materials.php">Gestionar Materiales</a></li>
          <li><a href="associate.php">Asignar Materiales a Productos</a></li>
        </ul>
      </div>
      <div class="col-md-6">
        <h4>Módulo Operario</h4>
        <ul>
          <li><a href="view_bom.php">Consultar Lista de Materiales</a></li>
        </ul>
      </div>
    </div>
  </div>
</body>
</html>

