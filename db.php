<?php
try {
    $conn = new PDO("sqlsrv:Server=minhhoa;Database=chatdemo", "sa", "123");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
