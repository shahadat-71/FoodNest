<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();

if (isset($_GET['delete'])) {
    $did = (int)$_GET['delete'];
    $row = $conn->query("SELECT image FROM menu_items WHERE id=$did")->fetch_assoc();
    if ($row && $row['image'] && file_exists("../assets/uploads/{$row['image']}")) unlink("../assets/uploads/{$row['image']}");
    $conn->query("DELETE FROM menu_items WHERE id=$did");
    header("Location: menu_items.php?msg=deleted"); exit();
}

if (isset($_GET['toggle'])) {
    $tid = (int)$_GET['toggle'];
    $conn->query("UPDATE menu_items SET is_available = NOT is_available WHERE id=$tid");
    header("Location: menu_items.php"); exit();
}

$items = $conn->query("SELECT m.*, c.name AS cat, c.icon FROM menu_items m LEFT JOIN categories c ON m.category_id=c.id ORDER BY m.id DESC")->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Menu Items';
include 'header.php';
?>

<?php if (isset($_GET['msg'])): ?>
  <div class="alert alert-success">✅ <?= $_GET['msg']==='added'?'Item added!':($_GET['msg']==='updated'?'Item updated!':'Item deleted.') ?></div>
<?php endif; ?>

<div class="section-header">
  <h2>All Menu Items (<?= count($items) ?>)</h2>
  <a href="add_item.php" class="btn btn-primary">➕ Add New Item</a>
</div>

<table class="admin-table">
  <thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Available</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach ($items as $item): ?>
    <tr>
      <td style="font-weight:700;color:#e63946;">#<?= $item['id'] ?></td>
      <td>
        <?php if ($item['image'] && file_exists("../assets/uploads/{$item['image']}")): ?>
          <img src="../assets/uploads/<?= htmlspecialchars($item['image']) ?>" style="width:50px;height:50px;object-fit:cover;border-radius:8px;"/>
        <?php else: ?>
          <div style="width:50px;height:50px;background:#fff0e8;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:22px;"><?= $item['icon']??'🍽️' ?></div>
        <?php endif; ?>
      </td>
      <td style="font-weight:700;"><?= htmlspecialchars($item['name']) ?></td>
      <td><?= $item['icon']??'' ?> <?= htmlspecialchars($item['cat']??'N/A') ?></td>
      <td style="font-weight:700;">৳ <?= number_format($item['price'],2) ?></td>
      <td>
        <a href="menu_items.php?toggle=<?= $item['id'] ?>" class="btn btn-sm <?= $item['is_available']?'btn-success':'btn-secondary' ?>">
          <?= $item['is_available'] ? '✅ Yes' : '❌ No' ?>
        </a>
      </td>
      <td style="display:flex;gap:6px;">
        <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="menu_items.php?delete=<?= $item['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete('Delete this item?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
