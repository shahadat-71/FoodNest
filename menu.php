<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$catId  = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$search = isset($_GET['search']) ? sanitize($conn, $_GET['search']) : '';

$where = ["m.is_available = 1"];
if ($catId)  $where[] = "m.category_id = $catId";
if ($search) $where[] = "m.name LIKE '%$search%'";
$whereSQL = 'WHERE ' . implode(' AND ', $where);

$items = $conn->query("SELECT m.*, c.name AS cat_name, c.icon FROM menu_items m LEFT JOIN categories c ON m.category_id = c.id $whereSQL ORDER BY m.category_id, m.name")->fetch_all(MYSQLI_ASSOC);
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Menu — FoodNest</title>
  <link rel="stylesheet" href="/foodnest/assets/css/style.css"/>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="section">
  <h2 class="section-title">Our <span>Menu</span></h2>

  <!-- Search -->
  <form method="GET" style="margin-bottom:20px;display:flex;gap:10px;">
    <?php if ($catId): ?><input type="hidden" name="cat" value="<?= $catId ?>"/><?php endif; ?>
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search menu items..." style="flex:1;padding:11px 16px;border:2px solid #f0e4e0;border-radius:8px;font-size:14px;outline:none;font-family:'Nunito',sans-serif;"/>
    <button type="submit" class="btn btn-primary">Search</button>
    <?php if ($search || $catId): ?><a href="menu.php" class="btn btn-dark">Clear</a><?php endif; ?>
  </form>

  <!-- Category Tabs -->
  <div class="cat-tabs">
    <a href="menu.php" <?= !$catId ? 'class="active"' : '' ?>>🍽️ All</a>
    <?php foreach ($categories as $c): ?>
      <a href="menu.php?cat=<?= $c['id'] ?>" <?= $catId==$c['id'] ? 'class="active"' : '' ?>><?= $c['icon'] . ' ' . htmlspecialchars($c['name']) ?></a>
    <?php endforeach; ?>
  </div>

  <!-- Menu Grid -->
  <?php if (empty($items)): ?>
    <div class="alert alert-info">No items found.</div>
  <?php else: ?>
  <div class="menu-grid">
    <?php foreach ($items as $item): ?>
    <div class="menu-card">
      <?php if ($item['image'] && file_exists(__DIR__ . '/assets/uploads/' . $item['image'])): ?>
        <div class="img-box" style="padding:0;">
          <img src="/foodnest/assets/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="height:175px;"/>
          <?php if (!$item['is_available']): ?><div class="unavailable-tag">Unavailable</div><?php endif; ?>
        </div>
      <?php else: ?>
        <div class="img-box"><?= $item['icon'] ?? '🍽️' ?></div>
      <?php endif; ?>
      <div class="info">
        <p style="font-size:11px;color:var(--red);font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;"><?= htmlspecialchars($item['cat_name']) ?></p>
        <h3><?= htmlspecialchars($item['name']) ?></h3>
        <p><?= htmlspecialchars(substr($item['description'],0,65)) ?>...</p>
        <div class="price"><?= number_format($item['price'],2) ?></div>
        <?php if ($item['is_available']): ?>
          <a href="item.php?id=<?= $item['id'] ?>" class="btn btn-primary btn-sm" style="width:100%;text-align:center;display:block;">Add to Cart 🛒</a>
        <?php else: ?>
          <button class="btn btn-sm" disabled style="width:100%;background:#e5e7eb;color:#9ca3af;cursor:not-allowed;">Unavailable</button>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<footer class="footer">
  <div class="foot-logo">🍽️ Food<span>Nest</span></div>
  <p>🏠 Your favourite food, delivered fast.</p>
</footer>

<script src="/foodnest/assets/js/main.js"></script>
</body>
</html>
