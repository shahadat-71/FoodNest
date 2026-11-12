<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
requireLogin();

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { header("Location: cart.php"); exit(); }
$total = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize($conn, $_POST['address'] ?? '');
    $phone   = sanitize($conn, $_POST['phone'] ?? '');
    $note    = sanitize($conn, $_POST['note'] ?? '');

    if (strlen($address) < 8) $errors[] = "Please enter a valid delivery address.";
    if (!preg_match('/^[0-9]{11}$/', $phone)) $errors[] = "Phone must be 11 digits.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, delivery_address, phone, note) VALUES (?,?,?,?,?)");
        $stmt->bind_param("idsss", $_SESSION['user_id'], $total, $address, $phone, $note);
        $stmt->execute();
        $orderId = $conn->insert_id;

        $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?,?,?,?)");
        foreach ($cart as $pid => $item) {
            $stmt2->bind_param("iiid", $orderId, $pid, $item['qty'], $item['price']);
            $stmt2->execute();
        }
        $_SESSION['cart'] = [];
        header("Location: order_placed.php?id=$orderId"); exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Checkout — FoodNest</title>
  <link rel="stylesheet" href="/foodnest/assets/css/style.css"/>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="section" style="display:grid;grid-template-columns:1fr 1fr;gap:30px;max-width:960px;">

  <div>
    <h2 class="section-title">Order <span>Summary</span></h2>
    <div style="background:white;border-radius:14px;padding:24px;box-shadow:var(--shadow);">
      <?php foreach ($cart as $item): ?>
      <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);font-size:14px;">
        <span><?= $item['icon']??'🍽️' ?> <?= htmlspecialchars($item['name']) ?> ×<?= $item['qty'] ?></span>
        <span style="font-weight:800;color:var(--red);">৳ <?= number_format($item['price']*$item['qty'],2) ?></span>
      </div>
      <?php endforeach; ?>
      <div style="display:flex;justify-content:space-between;padding:16px 0;font-size:20px;font-weight:800;">
        <span>Total</span>
        <span style="color:var(--red);">৳ <?= number_format($total,2) ?></span>
      </div>
      <div style="background:#fff5f0;border-radius:8px;padding:12px;font-size:13px;font-weight:600;color:var(--gray);">
        🚗 Delivery: <strong style="color:var(--dark);">Cash on Delivery</strong>
      </div>
    </div>
  </div>

  <div>
    <h2 class="section-title">Delivery <span>Details</span></h2>
    <div style="background:white;border-radius:14px;padding:24px;box-shadow:var(--shadow);">
      <?php foreach ($errors as $e): ?><div class="alert alert-error">⚠️ <?= $e ?></div><?php endforeach; ?>
      <form method="POST">
        <div class="form-group">
          <label>Name</label>
          <input type="text" value="<?= htmlspecialchars($user['full_name']) ?>" disabled style="background:#f5f5f5;"/>
        </div>
        <div class="form-group">
          <label>Phone *</label>
          <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? $user['phone'] ?? '') ?>" placeholder="01XXXXXXXXX"/>
        </div>
        <div class="form-group">
          <label>Delivery Address *</label>
          <textarea name="address" rows="3" placeholder="House, Road, Area, City..."><?= htmlspecialchars($_POST['address'] ?? $user['address'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label>Special Note (optional)</label>
          <input type="text" name="note" value="<?= htmlspecialchars($_POST['note'] ?? '') ?>" placeholder="e.g. Extra sauce, no onion..."/>
        </div>
        <button type="submit" class="btn btn-success" style="width:100%;padding:14px;font-size:16px;">✅ Place Order</button>
      </form>
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
