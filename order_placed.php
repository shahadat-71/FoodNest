<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
requireLogin();

$orderId = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id=u.id WHERE o.id=? AND o.user_id=?");
$stmt->bind_param("ii", $orderId, $_SESSION['user_id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) { header("Location: index.php"); exit(); }

$items = $conn->query("SELECT oi.*, m.name, c.icon FROM order_items oi JOIN menu_items m ON oi.item_id=m.id LEFT JOIN categories c ON m.category_id=c.id WHERE oi.order_id=$orderId")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Order Placed — FoodNest</title>
  <link rel="stylesheet" href="/foodnest/assets/css/style.css"/>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="section" style="max-width:620px;">
  <div style="background:white;border-radius:16px;padding:44px;box-shadow:var(--shadow);text-align:center;">
    <div style="font-size:70px;margin-bottom:16px;animation:float 2s ease-in-out infinite;">🎉</div>
    <h2 style="font-size:26px;font-weight:800;margin-bottom:8px;color:var(--dark);">Order Placed!</h2>
    <p style="color:var(--gray);margin-bottom:28px;font-size:15px;">Thanks <strong><?= htmlspecialchars($order['full_name']) ?></strong>! We're preparing your food. 👨‍🍳</p>

    <div style="background:#fff5f0;border-radius:10px;padding:18px;text-align:left;margin-bottom:24px;font-size:14px;">
      <div style="display:flex;justify-content:space-between;padding:6px 0;"><span style="color:var(--gray);">Order ID</span><strong>#<?= $order['id'] ?></strong></div>
      <div style="display:flex;justify-content:space-between;padding:6px 0;"><span style="color:var(--gray);">Status</span><span class="badge badge-pending">Pending</span></div>
      <div style="display:flex;justify-content:space-between;padding:6px 0;"><span style="color:var(--gray);">Total</span><strong style="color:var(--red);">৳ <?= number_format($order['total_amount'],2) ?></strong></div>
      <div style="display:flex;justify-content:space-between;padding:6px 0;"><span style="color:var(--gray);">Payment</span><strong>Cash on Delivery</strong></div>
      <?php if ($order['note']): ?>
      <div style="display:flex;justify-content:space-between;padding:6px 0;"><span style="color:var(--gray);">Note</span><span><?= htmlspecialchars($order['note']) ?></span></div>
      <?php endif; ?>
    </div>

    <table style="width:100%;border-collapse:collapse;margin-bottom:24px;text-align:left;">
      <tr style="background:var(--dark);color:white;">
        <th style="padding:10px 12px;border-radius:8px 0 0 0;">Item</th>
        <th style="padding:10px 12px;">Qty</th>
        <th style="padding:10px 12px;border-radius:0 8px 0 0;">Price</th>
      </tr>
      <?php foreach ($items as $item): ?>
      <tr style="border-bottom:1px solid var(--border);">
        <td style="padding:10px 12px;"><?= $item['icon']??'🍽️' ?> <?= htmlspecialchars($item['name']) ?></td>
        <td style="padding:10px 12px;">×<?= $item['quantity'] ?></td>
        <td style="padding:10px 12px;font-weight:800;color:var(--red);">৳ <?= number_format($item['price']*$item['quantity'],2) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>

    <div style="display:flex;gap:12px;justify-content:center;">
      <a href="my_orders.php" class="btn btn-primary">My Orders</a>
      <a href="menu.php" class="btn btn-outline">Order More</a>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="foot-logo">🍽️ Food<span>Nest</span></div>
  <p>🏠 Your favourite food, delivered fast.</p>
</footer>

<style>
@keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
</style>
</body>
</html>
