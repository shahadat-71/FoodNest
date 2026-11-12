<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $oid    = (int)$_POST['order_id'];
    $status = sanitize($conn, $_POST['status']);
    $allowed = ['pending','confirmed','preparing','delivered','cancelled'];
    if (in_array($status, $allowed)) $conn->query("UPDATE orders SET status='$status' WHERE id=$oid");
    header("Location: orders.php?msg=updated"); exit();
}

$filter = $_GET['status'] ?? '';
$where  = $filter ? "WHERE o.status='$filter'" : '';
$orders = $conn->query("SELECT o.*, u.full_name, u.email FROM orders o JOIN users u ON o.user_id=u.id $where ORDER BY o.created_at DESC")->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Orders';
include 'header.php';
?>

<?php if (isset($_GET['msg'])): ?><div class="alert alert-success">✅ Order status updated!</div><?php endif; ?>

<div class="section-header">
  <h2>All Orders (<?= count($orders) ?>)</h2>
  <div style="display:flex;gap:6px;flex-wrap:wrap;">
    <?php foreach ([''=>'All','pending'=>'Pending','confirmed'=>'Confirmed','preparing'=>'Preparing','delivered'=>'Delivered','cancelled'=>'Cancelled'] as $val=>$lbl): ?>
      <a href="orders.php<?= $val?"?status=$val":'' ?>" style="padding:6px 14px;border-radius:6px;font-size:12px;font-weight:700;background:<?= $filter===$val?'#1a1a2e':'#f0f0f0' ?>;color:<?= $filter===$val?'white':'#555' ?>;text-decoration:none;"><?= $lbl ?></a>
    <?php endforeach; ?>
  </div>
</div>

<table class="admin-table">
  <thead><tr><th>#</th><th>Customer</th><th>Items</th><th>Total</th><th>Address</th><th>Status</th><th>Date</th><th>Update</th></tr></thead>
  <tbody>
    <?php foreach ($orders as $o):
      $items = $conn->query("SELECT oi.quantity, m.name, c.icon FROM order_items oi JOIN menu_items m ON oi.item_id=m.id LEFT JOIN categories c ON m.category_id=c.id WHERE oi.order_id={$o['id']}")->fetch_all(MYSQLI_ASSOC);
    ?>
    <tr>
      <td style="font-weight:800;color:#e63946;">#<?= $o['id'] ?></td>
      <td><strong><?= htmlspecialchars($o['full_name']) ?></strong><br/><small style="color:#6b7280;"><?= htmlspecialchars($o['email']) ?></small></td>
      <td><?php foreach ($items as $i): ?><small><?= $i['icon']??'🍽️' ?> <?= htmlspecialchars($i['name']) ?> ×<?= $i['quantity'] ?></small><br/><?php endforeach; ?></td>
      <td style="font-weight:800;">৳ <?= number_format($o['total_amount'],2) ?></td>
      <td style="max-width:150px;font-size:12px;"><?= htmlspecialchars($o['delivery_address']) ?><?= $o['note']?'<br/><em style="color:#f59e0b;">Note: '.htmlspecialchars($o['note']).'</em>':'' ?></td>
      <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
      <td style="color:#6b7280;font-size:12px;"><?= date('d M Y<br/>h:i A', strtotime($o['created_at'])) ?></td>
      <td>
        <form method="POST" style="display:flex;gap:6px;align-items:center;">
          <input type="hidden" name="order_id" value="<?= $o['id'] ?>"/>
          <select name="status" style="padding:5px;border:2px solid #fde8e0;border-radius:6px;font-size:12px;font-family:'Nunito',sans-serif;">
            <?php foreach (['pending','confirmed','preparing','delivered','cancelled'] as $s): ?>
              <option value="<?= $s ?>" <?= $o['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
          </select>
          <button type="submit" class="btn btn-primary btn-sm">✓</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
