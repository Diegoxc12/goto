<?php
// bodegas_crud.php
// CRUD completo para la tabla `bodegas` usando PDO ($pdo) desde conexion.php
// Requiere PHP 8+
// Autor: ChatGPT

// --- Config ---
$TITLE = "Administración de Bodegas";
$PER_PAGE = 10; // paginación

// --- Conexión ---
require_once __DIR__ . '/includes/conexion.php'; // crea $pdo (PDO)

// --- Helpers ---
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function redirect_self(array $params = []){
    $base = strtok($_SERVER['REQUEST_URI'], '?');
    $query = http_build_query(array_merge($_GET, $params));
    header("Location: " . $base . ($query ? "?$query" : ""));
    exit;
}

// --- Crear / Editar / Eliminar ---
$errors = [];
$messages = [];

// Crear
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']==='create') {
    $nombre = trim($_POST['nombre_bodega'] ?? '');
    $creado_por = (int)($_POST['creado_por'] ?? 1);
    if ($nombre === '') $errors[] = "El nombre de la bodega es obligatorio.";
    if (!$errors){
        $stmt = $pdo->prepare("INSERT INTO bodegas (nombre_bodega, creado_por) VALUES (?, ?)");
        $stmt->execute([$nombre, $creado_por]);
        $messages[] = "Bodega creada correctamente.";
        redirect_self(['msg'=>'created']);
    }
}

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']==='update') {
    $id = (int)($_POST['id_bodega'] ?? 0);
    $nombre = trim($_POST['nombre_bodega'] ?? '');
    if ($id <= 0) $errors[] = "ID inválido.";
    if ($nombre === '') $errors[] = "El nombre de la bodega es obligatorio.";
    if (!$errors){
        $stmt = $pdo->prepare("UPDATE bodegas SET nombre_bodega = ? WHERE id_bodega = ?");
        $stmt->execute([$nombre, $id]);
        $messages[] = "Bodega actualizada.";
        redirect_self(['msg'=>'updated']);
    }
}

// Delete (soft: real delete de esta tabla)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action']==='delete') {
    $id = (int)($_POST['id_bodega'] ?? 0);
    if ($id <= 0) $errors[] = "ID inválido.";
    if (!$errors){
        // Validar llaves foráneas: evitar borrar si tiene ingresos/egresos/saldos
        $hasRelations = false;
        foreach (['ingresos'=>'bodega','egresos'=>'bodega','inventario_saldos'=>'codigo_bodega'] as $tbl=>$col){
            $q = $pdo->prepare("SELECT 1 FROM $tbl WHERE $col = ? LIMIT 1");
            $q->execute([$id]);
            if ($q->fetchColumn()){ $hasRelations = true; break; }
        }
        if ($hasRelations){
            $errors[] = "No se puede eliminar: la bodega tiene movimientos o saldos asociados.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM bodegas WHERE id_bodega = ?");
            $stmt->execute([$id]);
            $messages[] = "Bodega eliminada.";
            redirect_self(['msg'=>'deleted']);
        }
    }
}

// --- Lectura (lista con búsqueda + paginación) ---
$search = trim($_GET['s'] ?? '');
$wheres = [];
$params = [];
if ($search !== ''){
    $wheres[] = "b.nombre_bodega LIKE ?";
    $params[] = "%$search%";
}
$whereSql = $wheres ? "WHERE " . implode(" AND ", $wheres) : "";

$total = (function() use($pdo, $whereSql, $params){
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bodegas b $whereSql");
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
})();

$page = max(1, (int)($_GET['page'] ?? 1));
$pages = max(1, (int)ceil($total / $PER_PAGE));
if ($page > $pages) $page = $pages;
$offset = ($page - 1) * $PER_PAGE;

$stmt = $pdo->prepare("
    SELECT b.id_bodega, b.nombre_bodega, b.fecha_creacion, u.nombre AS nombre_usuario
    FROM bodegas b
    LEFT JOIN usuarios u ON u.id_usuario = b.creado_por
    $whereSql
    ORDER BY b.id_bodega DESC
    LIMIT $PER_PAGE OFFSET $offset
");
$stmt->execute($params);
$rows = $stmt->fetchAll();

// Cargar bodega a editar si corresponde
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editRow = null;
if ($editId){
    $e = $pdo->prepare("SELECT id_bodega, nombre_bodega FROM bodegas WHERE id_bodega = ?");
    $e->execute([$editId]);
    $editRow = $e->fetch();
    if (!$editRow){ $editId = null; }
}

// Mensajes de redirección
if (isset($_GET['msg'])){
    $map = [
        'created' => 'Bodega creada correctamente.',
        'updated' => 'Bodega actualizada correctamente.',
        'deleted' => 'Bodega eliminada correctamente.',
    ];
    if (isset($map[$_GET['msg']])) $messages[] = $map[$_GET['msg']];
}

?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= h($TITLE) ?></title>
  <link rel="stylesheet" href="/assets/css/bodegas.css">
</head>
<body>
<div class="container">
  <header class="header">
    <h1><?= h($TITLE) ?></h1>
    <form class="search" method="get" action="">
      <input type="text" name="s" placeholder="Buscar bodega..." value="<?= h($search) ?>">
      <button class="bnt bnt-primary" type="submit">Buscar</button>
      <?php if ($search): ?><a class="bnt bnt-light" href="?">Limpiar</a><?php endif; ?>
    </form>
  </header>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul><?php foreach($errors as $er): ?><li><?= h($er) ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>
  <?php if ($messages): ?>
    <div class="alert alert-success">
        <ul><?php foreach($messages as $ms): ?><li><?= h($ms) ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <section class="grid">
    <div class="card">
      <h2><?= $editId ? "Editar bodega #".h($editRow['id_bodega']) : "Nueva bodega" ?></h2>
      <form method="post" class="form">
        <?php if ($editId): ?>
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="id_bodega" value="<?= h($editRow['id_bodega']) ?>">
        <?php else: ?>
          <input type="hidden" name="action" value="create">
          <input type="hidden" name="creado_por" value="1">
        <?php endif; ?>
        <label class="form-row">
          <span>Nombre de bodega</span>
          <input type="text" name="nombre_bodega" value="<?= h($editRow['nombre_bodega'] ?? '') ?>" maxlength="150" required>
        </label>
        <div class="form-actions">
          <button class="bnt bnt-success" type="submit"><?= $editId ? "Guardar cambios" : "Crear bodega" ?></button>
          <?php if ($editId): ?><a class="bnt bnt-light" href="?">Cancelar</a><?php endif; ?>
        </div>
      </form>
    </div>

    <div class="card">
      <h2>Listado (<?= $total ?>)</h2>
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Creado por</th>
              <th>Fecha creación</th>
              <th style="width:160px">Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php if(!$rows): ?>
            <tr><td colspan="5" class="muted">Sin resultados.</td></tr>
          <?php else: foreach($rows as $r): ?>
            <tr>
              <td><?= h($r['id_bodega']) ?></td>
              <td><?= h($r['nombre_bodega']) ?></td>
              <td><?= h($r['nombre_usuario'] ?? '—') ?></td>
              <td><?= h($r['fecha_creacion']) ?></td>
              <td class="actions">
                <a class="bnt bnt-sm bnt-primary" href="?edit=<?= h($r['id_bodega']) ?><?= $search ? '&s='.urlencode($search):'' ?>">Editar</a>
                <form method="post" onsubmit="return confirm('¿Eliminar bodega? Esta acción no se puede deshacer.');" style="display:inline">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id_bodega" value="<?= h($r['id_bodega']) ?>">
                  <button class="bnt bnt-sm bnt-danger" type="submit">Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($pages > 1): ?>
      <nav class="pagination">
        <?php for($i=1;$i<=$pages;$i++): 
          $qs = $_GET; $qs['page']=$i; $href='?'.http_build_query($qs);
        ?>
          <a class="page <?= $i===$page?'active':'' ?>" href="<?= h($href) ?>"><?= $i ?></a>
        <?php endfor; ?>
      </nav>
      <?php endif; ?>
    </div>
  </section>

  <footer class="footer">
    <small>CRUD de bodegas • <?= date('Y') ?></small>
  </footer>
</div>
</body>
</html>
<?php include "./includes/header_menu.php";?>

