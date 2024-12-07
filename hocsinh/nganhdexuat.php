<?php
include '../includes/db.php';

session_start();
$maHocSinh = $_SESSION['MaHocSinh'];

// Lấy ngành nghề đề xuất từ lịch sử bài test gần nhất
$stmt = $conn->prepare("SELECT TOP 1 NganhDeXuat, NgayLamBai FROM BaiTest WHERE MaHocSinh = ? ORDER BY NgayLamBai DESC");
$stmt->execute([$maHocSinh]);
$nganhDeXuat = $stmt->fetch(PDO::FETCH_ASSOC);

$content = '
<style>
.suggestion-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.suggestion-header {
    text-align: center;
    margin-bottom: 3rem;
}

.suggestion-header h2 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.suggestion-card {
    background: white;
    border-radius: 15px;
    padding: 2.5rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.suggestion-result {
    background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
    color: white;
    padding: 2rem;
    border-radius: 10px;
    margin: 2rem 0;
}

.suggestion-title {
    font-size: 1.3em;
    margin-bottom: 1rem;
    font-weight: 500;
}

.suggestion-value {
    font-size: 1.8em;
    font-weight: bold;
    margin-bottom: 1rem;
}

.suggestion-date {
    font-size: 0.9em;
    color: rgba(255,255,255,0.8);
}

.empty-message {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
    font-style: italic;
    background: #f8f9fa;
    border-radius: 10px;
}

.cta-button {
    display: inline-block;
    margin-top: 2rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-weight: bold;
    transition: transform 0.3s ease;
}

.cta-button:hover {
    transform: translateY(-2px);
}
</style>

<div class="suggestion-container">
    <div class="suggestion-header">
        <h2>Ngành Đề Xuất</h2>
    </div>
    
    <div class="suggestion-card">';

if ($nganhDeXuat) {
    $ngayLamBai = date('d/m/Y H:i', strtotime($nganhDeXuat['NgayLamBai']));
    $content .= '
        <div class="suggestion-result">
            <div class="suggestion-title">Ngành học phù hợp nhất</div>
            <div class="suggestion-value">' . $nganhDeXuat['NganhDeXuat'] . '</div>
            <div class="suggestion-date">Kết quả từ bài kiểm tra ngày ' . $ngayLamBai . '</div>
        </div>';
} else {
    $content .= '
        <div class="empty-message">
            <p>Bạn chưa làm bài kiểm tra nào!</p>
            <a href="lambai.php" class="cta-button">Làm bài kiểm tra ngay</a>
        </div>';
}

$content .= '
    </div>
</div>';

include '../includes/layouthocsinh.php';
