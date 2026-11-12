<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM menu_items WHERE id=?");
$stmt->bind_param("i", $id); $stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
if (!$item) { header("Location: menu_items.php"); exit(); }

$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = sanitize($conn, $_POST['name'] ?? '');
    $description = sanitize($conn, $_POST['description'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $available   = isset($_POST['is_available']) ? 1 : 0;

    if (strlen($name) < 2) $errors[] = "Item name required.";
    if ($price <= 0) $errors[] = "Valid price required.";

    $imageName = $item['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
        if (!in_array($_FILES['image']['type'], $allowed)) $errors[] = "Only images allowed.";
        elseif ($_FILES['image']['size'] > 2*1024*1024) $errors[] = "Max 2MB.";
        else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $newImg = uniqid('food_') . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/$newImg")) {
                if ($imageName && file_exists("../assets/uploads/$imageName")) unlink("../assets/uploads/$imageName");
                $imageName = $newImg;
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE menu_items SET category_id=?,name=?,description=?,price=?,image=?,is_available=? WHERE id=?");
        $stmt->bind_param("issdsis", $category_id, $name, $description, $price, $imageName, $available, $id);
        if ($stmt->execute()) { header("Location: menu_items.php?msg=updated"); exit(); }
    }
    $item = array_merge($item, $_POST, ['image' => $imageName]);
}

$pageTitle = 'Edit Item';
include 'header.php';
?>

<?php foreach ($errors as $e): ?><div class="alert alert-error">⚠️ <?= $e ?></div><?php endforeach; ?>

<div class="section-header">
  <h2>Edit: <?= htmlspecialchars($item['name']) ?></h2>
  <a href="menu_items.php" class="btn btn-secondary">← Back</a>
</div>

<div class="admin-form">
  <form method="POST" enctype="multipart/form-data">
    <div class="form-group"><label>Item Name *</label><input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>"/></div>
    <div class="form-group"><label>Category</label>
      <select name="category_id">
        <option value="">-- Select --</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $item['category_id']==$c['id']?'selected':'' ?>><?= $c['icon'].' '.htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?= htmlspecialchars($item['description']) ?></textarea></div>
    <div class="form-group"><label>Price (৳) *</label><input type="number" name="price" step="0.01" min="0" value="<?= $item['price'] ?>"/></div>
    <div class="form-group">
      <?php if ($item['image'] && file_exists("../assets/uploads/{$item['image']}")): ?>
        <label>Current Image</label>
        <img src="../assets/uploads/<?= htmlspecialchars($item['image']) ?>" style="height:90px;border-radius:8px;margin-bottom:10px;display:block;"/>
      <?php endif; ?>
      <label>Replace Image</label>
      <input type="file" name="image" accept="image/*"/>
    </div>
    <div class="form-group" style="display:flex;align-items:center;gap:10px;">
      <input type="checkbox" name="is_available" id="avail" <?= $item['is_available']?'checked':'' ?> style="width:auto;"/>
      <label for="avail" style="margin:0;text-transform:none;font-size:14px;">Available for ordering</label>
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;padding:13px;">💾 Save Changes</button>
  </form>
</div>

<?php include 'footer.php'; ?>
