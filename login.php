<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
if (isLoggedIn()) { header("Location: index.php"); exit(); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($conn, $_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if (empty($pass)) $errors[] = "Password is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email=?");
        $stmt->bind_param("s", $email); $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            header("Location: index.php"); exit();
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Login — FoodNest</title>
  <link rel="stylesheet" href="/foodnest/assets/css/style.css"/>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="form-card">
  <h2>🔐 Login</h2>
  <?php foreach ($errors as $e): ?><div class="alert alert-error">⚠️ <?= $e ?></div><?php endforeach; ?>
  <form method="POST" onsubmit="return validateLogin()">
    <div class="form-group"><label>Email</label><input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email']??'') ?>" placeholder="your@email.com"/><span class="error" id="emailError"></span></div>
    <div class="form-group"><label>Password</label><input type="password" id="password" name="password" placeholder="Your password"/><span class="error" id="passwordError"></span></div>
    <button type="submit" class="btn btn-primary">Login</button>
  </form>
  <div class="form-footer">Don't have an account? <a href="register.php">Register</a></div>
</div>
<script src="/foodnest/assets/js/main.js"></script>
</body>
</html>
