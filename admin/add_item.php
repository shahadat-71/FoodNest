<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();

$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = sanitize($conn, $_POST['name'] ?? '');
    $description = sanitize($conn, $_POST['description'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $available   = isset($_POST['is_available']) ? 1 : 0;

    if (strlen($name) < 2) $errors[] = "Item name is required.";
    if ($price <= 0)        $errors[] = "Price must be greater than 0.";

    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
        if (!in_array($_FILES['image']['type'], $allowed)) $errors[] = "Only image files allowed.";
        elseif ($_FILES['image']['size'] > 2*1024*1024) $errors[] = "Image must be under 2MB.";
        else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('food_') . '.' . $ext;
            $uploadDir = '../assets/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
                $errors[] = "Upload failed."; $imageName = '';
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO menu_items (category_id, name, description, price, image, is_available) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("issdsi", $category_id, $name, $description, $price, $imageName, $available);
        if ($stmt->execute()) { header("Location: menu_items.php?msg=added"); exit(); }
        else $errors[] = "Failed to save.";
    }
}

$pageTitle = 'Add Menu Item';
include 'header.php';
?>

<?php foreach ($errors as $e): ?><div class="alert alert-error">⚠️ <?= $e ?></div><?php endforeach; ?>

<div class="section-header">
  <h2>Add New Menu Item</h2>
  <a href="menu_items.php" class="btn btn-secondary">← Back</a>
</div>

<div class="admin-form">
  <form method="POST" enctype="multipart/form-data">
    <div class="form-group"><label>Item Name *</label><input type="text" name="name" value="<?= htmlspecialchars($_POST['name']??'') ?>" placeholder="e.g. Classic Beef Burger"/></div>
    <div class="form-group"><label>Category</label>
      <select name="category_id">
        <option value="">-- Select Category --</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>" <?= (($_POST['category_id']??'')==$c['id'])?'selected':'' ?>><?= $c['icon'] . ' ' . htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group"><label>Description</label><textarea name="description" rows="3" placeholder="Describe the item..."><?= htmlspecialchars($_POST['description']??'') ?></textarea></div>
    <div class="form-group"><label>Price (৳) *</label><input type="number" name="price" step="0.01" min="0" value="<?= htmlspecialchars($_POST['price']??'') ?>" placeholder="0.00"/></div>
    <div class="form-group"><label>Item Image (Max 2MB)</label><input type="file" name="image" accept="image/*"/></div>
    <div class="form-group" style="display:flex;align-items:center;gap:10px;">
      <input type="checkbox" name="is_available" id="avail" checked style="width:auto;"/>
      <label for="avail" style="margin:0;text-transform:none;font-size:14px;">Available for ordering</label>
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;padding:13px;">➕ Add Item</button>
  </form>
</div>

<?php include 'footer.php'; ?>
