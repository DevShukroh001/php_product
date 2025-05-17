<?php
// database.php
$servername = "localhost";
$username = "root";
$password = "";
$db = "php-products";

$connect = new mysqli($servername, $username, $password, $db);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
?>