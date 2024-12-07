<?php
// Thông tin kết nối
$serverName = "localhost"; // Tên server hoặc IP
$database = "llll"; // Tên cơ sở dữ liệu

try {
    // Kết nối với SQL Server sử dụng Windows Authentication
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database;TrustServerCertificate=YES;Authentication=ActiveDirectoryIntegrated;");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
    exit;
}
