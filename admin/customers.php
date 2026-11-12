<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();

$customers = $conn->query("SELECT u.*, COUNT(o.id) AS order_count, COALESCE(SUM(o.total_amount),0) AS total_spent FROM users u LEFT JOIN orders o ON u.id=o.user_id GROUP BY u.id ORDER BY u.created_at DESC")->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Customers';
include 'header.php';
?>

<div class="section-header">
  <h2>All Customers (<?= count($customers) ?>)</h2>
</div>

<table class="admin-table">
  <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Total Spent</th><th>Joined</th></tr></thead>
  <tbody>
    <?php foreach ($customers as $u): ?>
    <tr>
      <td style="font-weight:700;color:#e63946;">#<?= $u['id'] ?></td>
      <td style="font-weight:700;"><?= htmlspecialchars($u['full_name']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
      <td style="text-align:center;font-weight:700;"><?= $u['order_count'] ?></td>
      <td style="font-weight:700;color:#e63946;">৳ <?= number_format($u['total_spent'],2) ?></td>
      <td style="color:#6b7280;"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
