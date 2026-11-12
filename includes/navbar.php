<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/foodnest/includes/functions.php';
$cartCount = getCartCount();
$cur = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
  <a href="/foodnest/index.php" class="logo">🍽️ Food<span>Nest</span></a>
  <div class="nav-links">
    <a href="/foodnest/index.php"    <?= $cur==='index.php'   ?'class="active"':'' ?>>Home</a>
    <a href="/foodnest/menu.php"     <?= $cur==='menu.php'    ?'class="active"':'' ?>>Menu</a>
    <?php if (isLoggedIn()): ?>
      <a href="/foodnest/my_orders.php" <?= $cur==='my_orders.php'?'class="active"':'' ?>>My Orders</a>
      <a href="/foodnest/logout.php">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
    <?php else: ?>
      <a href="/foodnest/login.php"    <?= $cur==='login.php'   ?'class="active"':'' ?>>Login</a>
      <a href="/foodnest/register.php" <?= $cur==='register.php'?'class="active"':'' ?>>Register</a>
    <?php endif; ?>
    <a href="/foodnest/cart.php" class="cart-btn">🛒 <span class="cart-count"><?= $cartCount ?></span></a>
  </div>
</nav>
