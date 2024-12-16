<?php
$user = "root";
$pass = "rDqubbrMkflrKoyMrZQUEZIrmaoMcDCx"; // รหัสผ่านของคุณ
$host = "autorack.proxy.rlwy.net";
$port = "35071";
$dbname = "railway";

session_start();

try {
    // สร้างการเชื่อมต่อ PDO โดยใช้ URL ที่ได้รับจาก Railway
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $conn->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    echo "การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage();
}
?>
