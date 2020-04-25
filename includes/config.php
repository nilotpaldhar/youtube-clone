<?php
ob_start(); // Turns on output buffering
date_default_timezone_set('Asia/Kolkata');
// Database Credentials
define('DB_TYPE', 'mysql'); // Database Type
define('DB_NAME', 'php_youtube'); // Database Name
define('DB_HOST', 'localhost'); // Database host
define('DB_USER', 'root'); // Database User
define('DB_PASSWORD', ''); // Database Password

try {
  $con = new PDO(DB_TYPE . ':dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASSWORD);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

?>