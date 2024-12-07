<?php
include '../includes/db.php';

// Lấy thông tin học sinh
session_start();
$maHocSinh = $_SESSION['MaHocSinh'];
$stmt = $conn->prepare("SELECT * FROM HocSinh WHERE MaHocSinh = ?");
$stmt->execute([$maHocSinh]);
$hocSinh = $stmt->fetch(PDO::FETCH_ASSOC);

$content = '
<style>
.profile-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.profile-header {
    text-align: center;
    margin-bottom: 3rem;
}

.profile-header h2 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.profile-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.profile-info {
    display: grid;
    gap: 1.5rem;
}

.info-item {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #6c757d;
    font-size: 0.9em;
    margin-bottom: 0.5rem;
}

.info-value {
    color: #2c3e50;
    font-size: 1.1em;
    font-weight: 500;
}

.edit-button {
    display: inline-block;
    margin-top: 2rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.edit-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(37, 117, 252, 0.2);
}
</style>

<div class="profile-container">
    <div class="profile-header">
        <h2>Thông tin cá nhân</h2>
    </div>
    
    <div class="profile-card">
        <div class="profile-info">
            <div class="info-item">
                <div class="info-label">Họ và tên</div>
                <div class="info-value">' . $hocSinh['HoTen'] . '</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">' . $hocSinh['Email'] . '</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Số điện thoại</div>
                <div class="info-value">' . $hocSinh['SoDienThoai'] . '</div>
            </div>
        </div>
        
        <div style="text-align: center;">
            <a href="edit.php" class="edit-button">
                <i class="fas fa-edit"></i> Chỉnh sửa thông tin
            </a>
        </div>
    </div>
</div>';

include '../includes/layouthocsinh.php';
