<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();

$totalItems    = $conn->query("SELECT COUNT(*) FROM menu_items")->fetch_row()[0];
$totalOrders   = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$totalUsers    = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$pendingOrders = $conn->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetch_row()[0];
$totalRevenue  = $conn->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status != 'cancelled'")->fetch_row()[0];

$recentOrders = $conn->query("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC LIMIT 8")->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Dashboard';
include 'header.php';
?>

<div class="stats-grid">
  <div class="stat-card" style="--c:#e63946;"><div class="label">🍔 Menu Items</div><div class="value"><?= $totalItems ?></div></div>
  <div class="stat-card" style="--c:#f59e0b;"><div class="label">🧾 Total Orders</div><div class="value"><?= $totalOrders ?></div></div>
  <div class="stat-card" style="--c:#dc2626;"><div class="label">⏳ Pending</div><div class="value"><?= $pendingOrders ?></div></div>
  <div class="stat-card" style="--c:#2dc653;"><div class="label">👥 Customers</div><div class="value"><?= $totalUsers ?></div></div>
  <div class="stat-card" style="--c:#7c3aed;"><div class="label">💰 Revenue</div><div class="value" style="font-size:20px;">৳<?= number_format($totalRevenue,0) ?></div></div>
</div>

<div class="section-header">
  <h2>Recent Orders</h2>
  <a href="orders.php" class="btn btn-primary btn-sm">View All</a>
</div>

<table class="admin-table">
  <thead><tr><th>#</th><th>Customer</th><th>Amount</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
  <tbody>
    <?php foreach ($recentOrders as $o): ?>
    <tr>
      <td style="font-weight:800;color:#e63946;">#<?= $o['id'] ?></td>
      <td style="font-weight:600;"><?= htmlspecialchars($o['full_name']) ?></td>
      <td style="font-weight:700;">৳ <?= number_format($o['total_amount'],2) ?></td>
      <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
      <td style="color:#6b7280;"><?= date('d M, h:i A', strtotime($o['created_at'])) ?></td>
      <td><a href="orders.php" class="btn btn-warning btn-sm">Manage</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
