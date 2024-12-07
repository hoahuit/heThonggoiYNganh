<?php
session_start();
include '../includes/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['MaGiaoVien'])) {
    header("Location: login.php");
    exit();
}

$maGiaoVien = $_SESSION['MaGiaoVien'];

// Truy vấn thông tin giáo viên cùng với ngành nghề
$stmt = $conn->prepare("SELECT G.*, N.TenNganh 
                        FROM GiaoVien G
                        LEFT JOIN Nganhhoc N ON G.MaNganh = N.MaNganh
                        WHERE G.MaGiaoVien = ?");
$stmt->execute([$maGiaoVien]);
$giaoVien = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra nếu không có thông tin giáo viên
if (!$giaoVien) {
    die('Không tìm thấy thông tin giáo viên.');
}

// Tạo nội dung trang
$content = '
<style>
.welcome-container {
    padding: 3rem;
    max-width: 1200px;
    margin: 0 auto;
    background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.8));
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.welcome-header {
    text-align: center;
    margin-bottom: 3rem;
    animation: fadeInDown 1s ease;
}

.welcome-header h2 {
    font-size: 2.8em;
    background: linear-gradient(135deg, #2575fc, #6a11cb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1.5rem;
}

.teacher-info {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 3rem;
    animation: fadeInUp 0.8s ease;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0.8rem;
    border-bottom: 1px solid #eee;
}

.info-item i {
    font-size: 1.5em;
    margin-right: 1rem;
    color: #6a11cb;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.feature-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    text-align: center;
    transition: transform 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.feature-card:hover {
    transform: translateY(-10px);
}

.feature-card i {
    font-size: 2.5em;
    color: #6a11cb;
    margin-bottom: 1rem;
}

.feature-card h3 {
    color: #2d3748;
    margin-bottom: 1rem;
}

.feature-card p {
    color: #4a5568;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<div class="welcome-container">
    <div class="welcome-header">
        <h2>Chào mừng, ' . $giaoVien['HoTen'] . '!</h2>
        <p>Chào mừng bạn đến với Hệ thống Quản lý Giáo viên</p>
    </div>

    <div class="teacher-info">
        <div class="info-item">
            <i class="fas fa-envelope"></i>
            <span>Email: ' . $giaoVien['Email'] . '</span>
        </div>
        <div class="info-item">
            <i class="fas fa-graduation-cap"></i>
            <span>Ngành nghề: ' . ($giaoVien['TenNganh'] ?? 'Chưa có ngành nghề') . '</span>
        </div>
        <div class="info-item">
            <i class="fas fa-phone"></i>
            <span>Số điện thoại: ' . $giaoVien['SoDienThoai'] . '</span>
        </div>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <i class="fas fa-question-circle"></i>
            <h3>Danh sách câu hỏi</h3>
            <p>Quản lý và trả lời các câu hỏi từ học sinh</p>
            <a href="danhSachCauHoi.php" class="btn btn-primary mt-3">Xem chi tiết</a>
        </div>

        <div class="feature-card">
            <i class="fas fa-comments"></i>
            <h3>Quản lý phòng chat</h3>
            <p>Tương tác trực tiếp với học sinh qua phòng chat</p>
            <a href="quanLyPhongChat.php" class="btn btn-primary mt-3">Truy cập</a>
        </div>

        <div class="feature-card">
            <i class="fas fa-tasks"></i>
            <h3>Quản lý bài kiểm tra</h3>
            <p>Tạo và quản lý các bài kiểm tra đánh giá</p>
            <a href="quanLyBaiTest.php" class="btn btn-primary mt-3">Quản lý</a>
        </div>
    </div>
</div>
';

// Include layout và hiển thị nội dung
include '../includes/layoutgiaovien.php';
