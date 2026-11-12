<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT m.*, c.name AS cat_name, c.icon FROM menu_items m LEFT JOIN categories c ON m.category_id = c.id WHERE m.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
if (!$item) { header("Location: menu.php"); exit(); }

$msg = ''; $msgType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $qty = max(1, min(20, (int)$_POST['quantity']));
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] = min($_SESSION['cart'][$id]['qty'] + $qty, 20);
    } else {
        $_SESSION['cart'][$id] = ['id'=>$id,'name'=>$item['name'],'price'=>$item['price'],'qty'=>$qty,'icon'=>$item['icon']];
    }
    $msg = "Added to cart!"; $msgType = 'success';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= htmlspecialchars($item['name']) ?> — FoodNest</title>
  <link rel="stylesheet" href="/foodnest/assets/css/style.css"/>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="section">
  <div style="font-size:13px;color:var(--gray);margin-bottom:20px;font-weight:600;">
    <a href="index.php" style="color:var(--red);">Home</a> › 
    <a href="menu.php" style="color:var(--red);">Menu</a> › 
    <?= htmlspecialchars($item['name']) ?>
  </div>

  <?php if ($msg): ?><div class="alert alert-<?= $msgType ?>">✅ <?= $msg ?> <a href="cart.php" style="color:var(--red);font-weight:700;">View Cart →</a></div><?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:44px;background:white;border-radius:16px;padding:40px;box-shadow:var(--shadow);">
    <div>
      <?php if ($item['image'] && file_exists(__DIR__ . '/assets/uploads/' . $item['image'])): ?>
        <img src="/foodnest/assets/uploads/<?= htmlspecialchars($item['image']) ?>" style="width:100%;border-radius:12px;max-height:360px;object-fit:cover;"/>
      <?php else: ?>
        <div style="width:100%;height:360px;background:linear-gradient(135deg,#fff0e8,#ffe4d6);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:100px;"><?= $item['icon'] ?? '🍽️' ?></div>
      <?php endif; ?>
    </div>
    <div>
      <p style="font-size:12px;color:var(--red);font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;"><?= htmlspecialchars($item['cat_name']) ?></p>
      <h2 style="font-size:30px;font-weight:800;margin-bottom:12px;letter-spacing:-0.5px;"><?= htmlspecialchars($item['name']) ?></h2>
      <p style="color:var(--gray);line-height:1.8;margin-bottom:18px;font-size:15px;"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
      <div style="font-size:36px;font-weight:800;color:var(--red);margin-bottom:20px;letter-spacing:-1px;">৳ <?= number_format($item['price'],2) ?></div>

      <?php if ($item['is_available']): ?>
      <form method="POST">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
          <span style="font-weight:700;font-size:14px;">Quantity:</span>
          <button type="button" class="btn btn-dark btn-sm" onclick="changeQty(-1)">−</button>
          <input type="number" id="quantity" name="quantity" value="1" min="1" max="20" style="width:65px;text-align:center;padding:8px;border:2px solid var(--border);border-radius:8px;font-size:16px;font-family:'Nunito',sans-serif;"/>
          <button type="button" class="btn btn-dark btn-sm" onclick="changeQty(1)">+</button>
        </div>
        <button type="submit" name="add_to_cart" class="btn btn-primary" style="width:100%;padding:14px;font-size:16px;">🛒 Add to Cart</button>
      </form>
      <a href="cart.php" class="btn btn-outline" style="width:100%;text-align:center;display:block;margin-top:12px;padding:13px;">View Cart</a>
      <?php else: ?>
        <div class="alert alert-error">❌ This item is currently unavailable.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="foot-logo">🍽️ Food<span>Nest</span></div>
  <p>🏠 Your favourite food, delivered fast.</p>
</footer>

<script src="/foodnest/assets/js/main.js"></script>
</body>
</html>
