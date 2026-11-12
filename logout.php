<?php
session_start();
session_destroy();
header("Location: /foodnest/index.php");
exit();
?>
