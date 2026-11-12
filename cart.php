<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        unset($_SESSION['cart'][(int)$_POST['remove']]);
    } elseif (isset($_POST['update'])) {
        foreach ($_POST['qty'] as $pid => $qty) {
            $pid = (int)$pid; $qty = max(1, (int)$qty);
            if (isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid]['qty'] = $qty;
        }
    } elseif (isset($_POST['clear'])) {
        $_SESSION['cart'] = [];
    }
    header("Location: cart.php"); exit();
}

$cart  = $_SESSION['cart'] ?? [];
$total = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Cart — FoodNest</title>
  <link rel="stylesheet" href="/foodnest/assets/css/style.css"/>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="section">
  <h2 class="section-title">Your <span>Cart</span></h2>

  <?php if (empty($cart)): ?>
    <div class="alert alert-info">Your cart is empty. <a href="menu.php" style="color:var(--red);font-weight:700;">Browse Menu →</a></div>
  <?php else: ?>

  <form method="POST">
    <table class="cart-table">
      <thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Remove</th></tr></thead>
      <tbody>
        <?php foreach ($cart as $pid => $item): ?>
        <tr>
          <td style="font-weight:700;"><?= $item['icon'] ?? '🍽️' ?> <?= htmlspecialchars($item['name']) ?></td>
          <td>৳ <?= number_format($item['price'],2) ?></td>
          <td><input type="number" name="qty[<?= $pid ?>]" value="<?= $item['qty'] ?>" min="1" max="20" style="width:60px;padding:6px;border:2px solid var(--border);border-radius:6px;text-align:center;font-family:'Nunito',sans-serif;"/></td>
          <td style="font-weight:800;color:var(--red);">৳ <?= number_format($item['price']*$item['qty'],2) ?></td>
          <td><button type="submit" name="remove" value="<?= $pid ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete('Remove item?')">✕</button></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="cart-actions">
      <div style="display:flex;gap:10px;">
        <button type="submit" name="update" class="btn btn-dark">Update Cart</button>
        <button type="submit" name="clear" class="btn btn-danger" onclick="return confirmDelete('Clear entire cart?')">Clear Cart</button>
      </div>
      <a href="menu.php" class="btn btn-outline">← Continue Ordering</a>
    </div>
  </form>

  <div class="cart-total">Total: ৳ <?= number_format($total,2) ?></div>

  <div style="text-align:right;margin-top:18px;">
    <?php if (isLoggedIn()): ?>
      <a href="checkout.php" class="btn btn-success" style="padding:14px 36px;font-size:16px;">Proceed to Checkout →</a>
    <?php else: ?>
      <a href="login.php" class="btn btn-primary" style="padding:14px 36px;font-size:16px;">Login to Checkout →</a>
    <?php endif; ?>
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
