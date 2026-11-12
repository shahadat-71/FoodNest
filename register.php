<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
if (isLoggedIn()) { header("Location: index.php"); exit(); }

$errors = []; $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($conn, $_POST['full_name'] ?? '');
    $email   = sanitize($conn, $_POST['email'] ?? '');
    $pass    = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $phone   = sanitize($conn, $_POST['phone'] ?? '');
    $address = sanitize($conn, $_POST['address'] ?? '');

    if (strlen($name) < 3) $errors[] = "Name must be at least 3 characters.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
    if (strlen($pass) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($pass !== $confirm) $errors[] = "Passwords do not match.";
    if ($phone && !preg_match('/^[0-9]{11}$/', $phone)) $errors[] = "Phone must be 11 digits.";

    $chk = $conn->prepare("SELECT id FROM users WHERE email=?");
    $chk->bind_param("s", $email); $chk->execute();
    if ($chk->get_result()->num_rows > 0) $errors[] = "Email already registered.";

    if (empty($errors)) {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (full_name,email,password,phone,address) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $name, $email, $hashed, $phone, $address);
        $stmt->execute() ? $success = "Account created! <a href='login.php'>Login now →</a>" : $errors[] = "Registration failed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Register — FoodNest</title>
  <link rel="stylesheet" href="/foodnest/assets/css/style.css"/>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="form-card" style="max-width:520px;">
  <h2>🍽️ Create Account</h2>
  <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
  <?php foreach ($errors as $e): ?><div class="alert alert-error">⚠️ <?= $e ?></div><?php endforeach; ?>
  <form method="POST" onsubmit="return validateRegister()">
    <div class="form-group"><label>Full Name *</label><input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($_POST['full_name']??'') ?>" placeholder="Your full name"/><span class="error" id="nameError"></span></div>
    <div class="form-group"><label>Email *</label><input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email']??'') ?>" placeholder="your@email.com"/><span class="error" id="emailError"></span></div>
    <div class="form-group"><label>Password *</label><input type="password" id="password" name="password" placeholder="Min 6 characters"/><span class="error" id="passwordError"></span></div>
    <div class="form-group"><label>Confirm Password *</label><input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password"/><span class="error" id="confirmError"></span></div>
    <div class="form-group"><label>Phone</label><input type="text" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone']??'') ?>" placeholder="01XXXXXXXXX"/><span class="error" id="phoneError"></span></div>
    <div class="form-group"><label>Address</label><textarea name="address" rows="2" placeholder="Your delivery address"><?= htmlspecialchars($_POST['address']??'') ?></textarea></div>
    <button type="submit" class="btn btn-primary">Create Account</button>
  </form>
  <div class="form-footer">Already have an account? <a href="login.php">Login</a></div>
</div>
<script src="/foodnest/assets/js/main.js"></script>
</body>
</html>
