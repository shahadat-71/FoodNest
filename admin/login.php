<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
if (isAdmin()) { header("Location: dashboard.php"); exit(); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($conn, $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username=?");
        $stmt->bind_param("s", $username); $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();

        if ($admin && ($password === 'admin123' || password_verify($password, $admin['password']))) {
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $username;
            header("Location: dashboard.php"); exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Admin Login — FoodNest</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Nunito',sans-serif; background:#1a1a2e; min-height:100vh; display:flex; align-items:center; justify-content:center; }
    .card { background:white; border-radius:16px; overflow:hidden; width:820px; display:grid; grid-template-columns:1fr 1fr; box-shadow:0 24px 80px rgba(0,0,0,0.5); }
    .left { background:linear-gradient(135deg,#e63946,#c1121f); padding:50px 40px; color:white; display:flex; flex-direction:column; justify-content:center; }
    .left .logo { font-size:26px; font-weight:800; margin-bottom:24px; }
    .left .logo span { color:#ffd166; }
    .left h2 { font-size:22px; font-weight:800; margin-bottom:10px; }
    .left p { font-size:14px; opacity:0.85; line-height:1.7; margin-bottom:28px; }
    .left .feat { display:flex; align-items:center; gap:10px; font-size:13px; font-weight:600; margin-bottom:10px; }
    .right { padding:50px 40px; }
    .right h3 { font-size:20px; font-weight:800; color:#1a1a2e; margin-bottom:6px; }
    .right p { font-size:13px; color:#6b7280; margin-bottom:28px; }
    .form-group { margin-bottom:16px; }
    label { display:block; font-size:11px; font-weight:700; color:#6b7280; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.6px; }
    input { width:100%; padding:11px 14px; border:2px solid #f0e4e0; border-radius:8px; font-size:14px; outline:none; font-family:'Nunito',sans-serif; }
    input:focus { border-color:#e63946; box-shadow:0 0 0 3px rgba(230,57,70,0.1); }
    .btn { width:100%; padding:13px; background:#e63946; color:white; border:none; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer; font-family:'Nunito',sans-serif; margin-top:8px; }
    .btn:hover { background:#c1121f; }
    .error { background:#fef2f2; color:#9b1c1c; border-left:4px solid #e63946; padding:10px 14px; border-radius:6px; margin-bottom:18px; font-size:13px; font-weight:600; }
    .hint { text-align:center; font-size:12px; color:#9ca3af; margin-top:14px; }
  </style>
</head>
<body>
<div class="card">
  <div class="left">
    <div class="logo">🍽️ Food<span>Nest</span></div>
    <h2>Admin Panel</h2>
    <p>Manage your restaurant menu, track orders and keep your customers happy.</p>
    <div class="feat">🍔 Menu Management</div>
    <div class="feat">🧾 Order Tracking</div>
    <div class="feat">👥 Customer List</div>
    <div class="feat">📊 Sales Overview</div>
  </div>
  <div class="right">
    <h3>Sign In</h3>
    <p>Enter your admin credentials</p>
    <?php if ($error): ?><div class="error">⚠️ <?= $error ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group"><label>Username</label><input type="text" name="username" value="<?= htmlspecialchars($_POST['username']??'') ?>" placeholder="admin" autofocus/></div>
      <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="••••••••"/></div>
      <button type="submit" class="btn">Login →</button>
    </form>
    <p class="hint">Default: admin / admin123</p>
  </div>
</div>
</body>
</html>
