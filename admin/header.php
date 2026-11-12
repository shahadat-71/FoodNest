<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= $pageTitle ?? 'Admin' ?> — FoodNest Admin</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap');
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Nunito',sans-serif; background:#fff5f0; display:flex; min-height:100vh; color:#1a1a2e; }

    .sidebar { width:230px; background:#1a1a2e; min-height:100vh; flex-shrink:0; position:sticky; top:0; display:flex; flex-direction:column; }
    .sidebar .brand { padding:24px 20px; border-bottom:1px solid #2d2d4e; }
    .sidebar .brand .logo { font-size:20px; font-weight:800; color:white; }
    .sidebar .brand .logo span { color:#ffd166; }
    .sidebar .brand .sub { font-size:11px; color:#4b5563; font-weight:600; text-transform:uppercase; letter-spacing:0.8px; margin-top:3px; }
    .sidebar .nav-label { font-size:10px; font-weight:700; color:#374151; padding:14px 20px 4px; text-transform:uppercase; letter-spacing:1px; }
    .sidebar a { display:flex; align-items:center; gap:10px; padding:10px 20px; color:#6b7280; font-size:13px; font-weight:600; transition:all 0.15s; text-decoration:none; border-left:3px solid transparent; }
    .sidebar a:hover, .sidebar a.active { background:#16213e; color:white; border-left-color:#e63946; }
    .sidebar a.logout { color:#e63946; border-top:1px solid #2d2d4e; margin-top:auto; }
    .sidebar a.logout:hover { background:#16213e; }
    .sidebar-bottom { margin-top:auto; }

    .main { flex:1; padding:28px 32px; }
    .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:26px; padding-bottom:18px; border-bottom:1px solid #fde8e0; }
    .topbar h1 { font-size:22px; font-weight:800; color:#1a1a2e; }
    .topbar .badge-admin { background:#1a1a2e; color:white; padding:7px 16px; border-radius:8px; font-size:13px; font-weight:700; }
    .topbar .badge-admin span { color:#ffd166; }

    .stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:16px; margin-bottom:26px; }
    .stat-card { background:white; border-radius:12px; padding:20px; box-shadow:0 2px 12px rgba(230,57,70,0.08); border-top:3px solid var(--c,#e63946); }
    .stat-card .label { font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px; }
    .stat-card .value { font-size:30px; font-weight:800; color:#1a1a2e; }

    .admin-table { width:100%; border-collapse:collapse; background:white; border-radius:12px; overflow:hidden; box-shadow:0 2px 12px rgba(230,57,70,0.08); }
    .admin-table th { background:#1a1a2e; color:white; padding:12px 16px; text-align:left; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; }
    .admin-table td { padding:12px 16px; border-bottom:1px solid #fff0eb; font-size:13px; vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fff8f6; }

    .admin-form { background:white; border-radius:12px; padding:28px; box-shadow:0 2px 12px rgba(230,57,70,0.08); max-width:580px; border-top:3px solid #e63946; }
    .admin-form .form-group { margin-bottom:15px; }
    .admin-form label { display:block; font-size:11px; font-weight:700; color:#6b7280; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; }
    .admin-form input,.admin-form select,.admin-form textarea { width:100%; padding:10px 13px; border:2px solid #fde8e0; border-radius:8px; font-size:14px; outline:none; font-family:'Nunito',sans-serif; background:#fffaf9; }
    .admin-form input:focus,.admin-form select:focus,.admin-form textarea:focus { border-color:#e63946; background:white; }

    .btn { display:inline-block; padding:9px 18px; border-radius:7px; font-size:13px; font-weight:700; cursor:pointer; border:none; transition:all 0.2s; text-decoration:none; font-family:'Nunito',sans-serif; }
    .btn-primary { background:#e63946; color:white; }
    .btn-primary:hover { background:#c1121f; }
    .btn-warning { background:#f59e0b; color:white; }
    .btn-warning:hover { background:#d97706; }
    .btn-danger { background:#dc2626; color:white; }
    .btn-danger:hover { background:#b91c1c; }
    .btn-secondary { background:#6b7280; color:white; }
    .btn-secondary:hover { background:#4b5563; }
    .btn-success { background:#2dc653; color:white; }
    .btn-success:hover { background:#24a845; }
    .btn-sm { padding:5px 12px; font-size:12px; }

    .alert { padding:11px 14px; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600; border-left:4px solid; }
    .alert-success { background:#e8faf0; color:#1a6e35; border-color:#2dc653; }
    .alert-error   { background:#fef2f2; color:#9b1c1c; border-color:#e63946; }

    .badge { display:inline-block; padding:4px 11px; border-radius:20px; font-size:11px; font-weight:700; }
    .badge-pending   { background:#fff7e6; color:#b45309; }
    .badge-confirmed { background:#e6f0ff; color:#1e40af; }
    .badge-preparing { background:#fef9c3; color:#854d0e; }
    .badge-delivered { background:#e8faf0; color:#1a6e35; }
    .badge-cancelled { background:#fef2f2; color:#9b1c1c; }

    .section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
    .section-header h2 { font-size:17px; font-weight:800; color:#1a1a2e; }
  </style>
</head>
<body>

<?php $cur = basename($_SERVER['PHP_SELF']); ?>

<div class="sidebar">
  <div class="brand">
    <div class="logo">🍽️ Food<span>Nest</span></div>
    <div class="sub">Admin Panel</div>
  </div>
  <div class="nav-label">Overview</div>
  <a href="dashboard.php" <?= $cur==='dashboard.php'?'class="active"':'' ?>>📊 Dashboard</a>
  <div class="nav-label">Menu</div>
  <a href="menu_items.php"  <?= $cur==='menu_items.php' ?'class="active"':'' ?>>🍔 Menu Items</a>
  <a href="add_item.php"    <?= $cur==='add_item.php'   ?'class="active"':'' ?>>➕ Add Item</a>
  <a href="categories.php"  <?= $cur==='categories.php' ?'class="active"':'' ?>>🗂️ Categories</a>
  <div class="nav-label">Orders</div>
  <a href="orders.php"  <?= $cur==='orders.php' ?'class="active"':'' ?>>🧾 All Orders</a>
  <a href="customers.php" <?= $cur==='customers.php'?'class="active"':'' ?>>👥 Customers</a>
  <div class="sidebar-bottom">
    <a href="../index.php" target="_blank">🌐 View Site</a>
    <a href="logout.php" class="logout">🚪 Logout</a>
  </div>
</div>

<div class="main">
<div class="topbar">
  <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
  <div class="badge-admin">👤 <span><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span></div>
</div>
