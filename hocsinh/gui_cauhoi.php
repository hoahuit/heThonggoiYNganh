<?php
include '../includes/db.php';
session_start();

// Kiểm tra nếu học sinh đã đăng nhập
if (!isset($_SESSION['MaHocSinh'])) {
    header("Location: login.php");
    exit();
}

$maHocSinh = $_SESSION['MaHocSinh'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noiDung = $_POST['noiDung'];

    // Thêm câu hỏi vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO CauHoi (MaHocSinh, NoiDung) VALUES (?, ?)");
    $stmt->execute([$maHocSinh, $noiDung]);
    $message = "Câu hỏi của bạn đã được gửi thành công!";
}

// Nội dung chính của trang
$content = '
<div class="dashboard-container">
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-question-circle"></i>
            Gửi câu hỏi
        </h2>
        <p class="page-description">Đặt câu hỏi của bạn và nhận được hướng dẫn từ đội ngũ giáo viên</p>
    </div>

    ' . ($message ? '<div class="alert alert-success">
        <i class="fas fa-check-circle bounce"></i> 
        <div class="alert-content">
            <h4>Gửi câu hỏi thành công!</h4>
            <p>' . $message . '</p>
            <a href="xem_cauhoi.php" class="view-questions-link">
                <i class="fas fa-eye"></i> Xem câu hỏi đã gửi
            </a>
        </div>
    </div>' : '') . '

    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-pen"></i> Form gửi câu hỏi</h3>
        </div>
        <form method="POST" class="modern-form">
            <div class="form-group">
                <label for="noiDung">
                    <i class="fas fa-comment-dots"></i>
                    Nội dung câu hỏi
                </label>
                <div class="input-container">
                    <textarea 
                        id="noiDung" 
                        name="noiDung" 
                        rows="8" 
                        required 
                        placeholder="Ví dụ: Em muốn hỏi về..."
                    ></textarea>
                    <div class="focus-border">
                        <i></i>
                    </div>
                </div>
                <div class="form-tips">
                    <div class="tip-item">
                        <i class="fas fa-lightbulb"></i>
                        <span>Hãy mô tả chi tiết vấn đề bạn đang gặp phải</span>
                    </div>
                    <div class="tip-item">
                        <i class="fas fa-info-circle"></i>
                        <span>Câu hỏi rõ ràng sẽ nhận được câu trả lời chính xác hơn</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="primary-btn">
                    <i class="fas fa-paper-plane"></i>
                    <span>Gửi câu hỏi</span>
                </button>
                <a href="xem_cauhoi.php" class="secondary-btn">
                    <i class="fas fa-history"></i>
                    <span>Xem câu hỏi đã gửi</span>
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
}

.page-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    border-radius: 15px;
    color: white;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.page-title {
    font-size: 2.8em;
    font-weight: 800;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.page-description {
    font-size: 1.2em;
    opacity: 0.9;
}

.content-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.content-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.card-header {
    background: #f8f9fa;
    padding: 1.5rem 2rem;
    border-bottom: 2px solid #e9ecef;
}

.card-header h3 {
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modern-form {
    padding: 2rem;
}

.form-group {
    margin-bottom: 2rem;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.2em;
    color: #2c3e50;
    margin-bottom: 1rem;
    font-weight: 600;
}

.input-container {
    position: relative;
    margin-bottom: 1rem;
}

textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 1.1em;
    transition: all 0.3s ease;
    background: #f8f9fa;
    resize: vertical;
}

textarea:focus {
    outline: none;
    border-color: #2575fc;
    background: white;
    box-shadow: 0 0 0 4px rgba(37, 117, 252, 0.1);
}

.form-tips {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 10px;
    margin-top: 1rem;
}

.tip-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 0.5rem;
    color: #6c757d;
}

.tip-item i {
    color: #2575fc;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.primary-btn, .secondary-btn {
    padding: 1rem 2rem;
    border-radius: 30px;
    font-size: 1.1em;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.primary-btn {
    background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
    color: white;
    border: none;
    flex: 1;
    justify-content: center;
}

.secondary-btn {
    background: #f8f9fa;
    color: #2c3e50;
    border: 2px solid #e9ecef;
}

.primary-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(37, 117, 252, 0.2);
}

.secondary-btn:hover {
    background: #e9ecef;
}

.alert {
    background: #d1ecf1;
    border-left: 5px solid #0dcaf0;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border-radius: 10px;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.alert-success {
    background: #d4edda;
    border-left-color: #28a745;
}

.alert i {
    font-size: 1.5em;
    color: #28a745;
}

.alert-content h4 {
    margin: 0 0 0.5rem 0;
    color: #155724;
}

.alert-content p {
    margin: 0 0 1rem 0;
    color: #1e7e34;
}

.view-questions-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #155724;
    text-decoration: none;
    font-weight: 600;
}

.view-questions-link:hover {
    text-decoration: underline;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
    40% {transform: translateY(-10px);}
    60% {transform: translateY(-5px);}
}

.bounce {
    animation: bounce 2s infinite;
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 2em;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .primary-btn, .secondary-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
';

// Gọi layout chung
include '../includes/layouthocsinh.php';
