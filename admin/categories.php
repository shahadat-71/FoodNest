<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();

$msg = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = sanitize($conn, $_POST['name'] ?? '');
        $icon = sanitize($conn, $_POST['icon'] ?? '🍽️');
        if (strlen($name) >= 2) {
            $stmt = $conn->prepare("INSERT INTO categories (name, icon) VALUES (?,?)");
            $stmt->bind_param("ss", $name, $icon);
            $stmt->execute() ? $msg = "Category added!" : $error = "Failed.";
        } else $error = "Name must be at least 2 characters.";
    } elseif (isset($_POST['delete'])) {
        $did = (int)$_POST['delete'];
        $conn->query("DELETE FROM categories WHERE id=$did");
        $msg = "Category deleted.";
    }
}

$categories = $conn->query("SELECT c.id, c.name, c.icon, (SELECT COUNT(*) FROM menu_items WHERE category_id=c.id) AS item_count FROM categories c")->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Categories';
include 'header.php';
?>

<?php if ($msg): ?><div class="alert alert-success">✅ <?= $msg ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error">⚠️ <?= $error ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
  <div>
    <div class="section-header"><h2>All Categories</h2></div>
    <table class="admin-table">
      <thead><tr><th>Icon</th><th>Name</th><th>Items</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach ($categories as $c): ?>
        <tr>
          <td style="font-size:22px;"><?= $c['icon'] ?></td>
          <td style="font-weight:700;"><?= htmlspecialchars($c['name']) ?></td>
          <td><?= $c['item_count'] ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="delete" value="<?= $c['id'] ?>"/>
              <button class="btn btn-danger btn-sm" onclick="return confirmDelete('Delete this category?')">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div>
    <div class="section-header"><h2>Add Category</h2></div>
    <div class="admin-form">
      <form method="POST">
        <div class="form-group"><label>Category Name *</label><input type="text" name="name" placeholder="e.g. Burgers"/></div>
        <div class="form-group"><label>Icon (Emoji)</label><input type="text" name="icon" placeholder="🍔" maxlength="5"/></div>
        <button type="submit" name="add" class="btn btn-primary" style="width:100%;padding:12px;">Add Category</button>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
