<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$featured = $conn->query("SELECT m.*, c.icon FROM menu_items m LEFT JOIN categories c ON m.category_id = c.id WHERE m.is_available=1 ORDER BY RAND() LIMIT 8")->fetch_all(MYSQLI_ASSOC);
$totalItems = $conn->query("SELECT COUNT(*) FROM menu_items WHERE is_available=1")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>FoodNest — Order Fresh Food Online</title>
  <link rel="stylesheet" href="/foodnest/assets/css/style.css"/>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- HERO -->
<div class="hero">
  <div class="hero-text">
    <div class="hero-badge">🔥 Fresh & Hot Delivery</div>
    <h1>Hungry? We've<br/>Got You <span>Covered.</span></h1>
    <p>Order from our wide selection of burgers, pizza, rice and more. Fresh food delivered fast to your door.</p>
    <div class="hero-btns">
      <a href="menu.php" class="btn btn-primary" style="padding:13px 30px;font-size:15px;">Order Now 🛒</a>
      <?php if (!isLoggedIn()): ?>
        <a href="register.php" class="btn btn-secondary">Join Free</a>
      <?php endif; ?>
    </div>
  </div>
  <div class="hero-emoji">🍔</div>
</div>

<!-- FEATURES STRIP -->
<div class="features-strip">
  <div class="feat-item">
    <div class="icon">⚡</div>
    <div class="title">Fast Delivery</div>
    <div class="sub">30 mins or less</div>
  </div>
  <div class="feat-item">
    <div class="icon">👨‍🍳</div>
    <div class="title">Fresh Cooked</div>
    <div class="sub">Made to order</div>
  </div>
  <div class="feat-item">
    <div class="icon">💳</div>
    <div class="title">Easy Payment</div>
    <div class="sub">Cash on delivery</div>
  </div>
  <div class="feat-item">
    <div class="icon">📍</div>
    <div class="title">Track Order</div>
    <div class="sub">Live status update</div>
  </div>
</div>

<!-- FEATURED MENU -->
<div class="section">
  <h2 class="section-title">Popular <span>Items</span></h2>
  <div class="menu-grid">
    <?php foreach ($featured as $item): ?>
    <div class="menu-card">
      <?php if ($item['image'] && file_exists(__DIR__ . '/assets/uploads/' . $item['image'])): ?>
        <img src="/foodnest/assets/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"/>
      <?php else: ?>
        <div class="img-box"><?= $item['icon'] ?? '🍽️' ?></div>
      <?php endif; ?>
      <div class="info">
        <h3><?= htmlspecialchars($item['name']) ?></h3>
        <p><?= htmlspecialchars(substr($item['description'],0,60)) ?>...</p>
        <div class="price"><?= number_format($item['price'],2) ?></div>
        <a href="item.php?id=<?= $item['id'] ?>" class="btn btn-primary btn-sm" style="width:100%;text-align:center;display:block;">Add to Cart</a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div style="text-align:center;margin-top:32px;">
    <a href="menu.php" class="btn btn-outline" style="padding:12px 36px;">View Full Menu →</a>
  </div>
</div>

<footer class="footer">
  <div class="foot-logo">🍽️ Food<span>Nest</span></div>
  <p>🏠 Your favourite food, delivered fast.</p>
</footer>

<script src="/foodnest/assets/js/main.js"></script>
</body>
</html>
