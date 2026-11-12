<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
requireLogin();

$orders = $conn->query("SELECT * FROM orders WHERE user_id={$_SESSION['user_id']} ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>My Orders — FoodNest</title>
  <link rel="stylesheet" href="/foodnest/assets/css/style.css"/>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="section">
  <h2 class="section-title">My <span>Orders</span></h2>
  <?php if (empty($orders)): ?>
    <div class="alert alert-info">No orders yet. <a href="menu.php" style="color:var(--red);font-weight:700;">Order now →</a></div>
  <?php else: ?>
  <table class="order-table">
    <thead><tr><th>Order ID</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
    <tbody>
      <?php foreach ($orders as $order):
        $items = $conn->query("SELECT oi.quantity, m.name, c.icon FROM order_items oi JOIN menu_items m ON oi.item_id=m.id LEFT JOIN categories c ON m.category_id=c.id WHERE oi.order_id={$order['id']}")->fetch_all(MYSQLI_ASSOC);
      ?>
      <tr>
        <td style="font-weight:800;color:var(--red);">#<?= $order['id'] ?></td>
        <td><?php foreach ($items as $i): ?><small><?= $i['icon']??'🍽️' ?> <?= htmlspecialchars($i['name']) ?> ×<?= $i['quantity'] ?></small><br/><?php endforeach; ?></td>
        <td style="font-weight:800;">৳ <?= number_format($order['total_amount'],2) ?></td>
        <td><span class="badge badge-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
        <td style="color:var(--gray);"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<footer class="footer">
  <div class="foot-logo">🍽️ Food<span>Nest</span></div>
  <p>🏠 Your favourite food, delivered fast.</p>
</footer>
</body>
</html>
