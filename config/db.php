<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "perfume_shop";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8");
} catch(PDOException $e) {
    echo "Kết nối thất bại: " . $e->getMessage();
}
?> 