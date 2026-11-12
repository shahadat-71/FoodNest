<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'foodnest_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("<div style='font-family:sans-serif;color:red;padding:20px;'>
        <h3>❌ Database Connection Failed</h3>
        <p>" . $conn->connect_error . "</p>
        <p>Make sure XAMPP MySQL is running and you have imported <strong>database.sql</strong></p>
    </div>");
}
$conn->set_charset("utf8mb4");
?>
