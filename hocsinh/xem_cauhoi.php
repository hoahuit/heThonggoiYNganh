<?php
include '../includes/db.php';
session_start();

// Kiểm tra nếu học sinh đã đăng nhập
if (!isset($_SESSION['MaHocSinh'])) {
    header("Location: login.php");
    exit();
}

$maHocSinh = $_SESSION['MaHocSinh'];

// Lấy danh sách câu hỏi của học sinh
$stmt = $conn->prepare("SELECT * FROM CauHoi WHERE MaHocSinh = ? ORDER BY NgayGui DESC");
$stmt->execute([$maHocSinh]);
$cauHoiList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nội dung chính của trang
$content = '
<div class="dashboard-container">
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-history"></i>
            Lịch sử câu hỏi
        </h2>
        <p class="page-description">Xem lại các câu hỏi bạn đã gửi và phản hồi từ giáo viên</p>
    </div>

    <div class="content-card">
        <div class="questions-list">';

if (empty($cauHoiList)) {
    $content .= '
        <div class="empty-state">
            <i class="fas fa-inbox fa-3x"></i>
            <p>Bạn chưa gửi câu hỏi nào</p>
            <a href="gui_cauhoi.php" class="primary-btn">
                <i class="fas fa-plus"></i>
                Gửi câu hỏi mới
            </a>
        </div>';
} else {
    foreach ($cauHoiList as $cauHoi) {
        $trangThai = $cauHoi['TrangThai'];
        $statusClass = '';
        $statusIcon = '';

        switch ($trangThai) {
            case 'Đã trả lời':
                $statusClass = 'status-answered';
                $statusIcon = 'fas fa-check-circle';
                break;
            case 'Đang xử lý':
                $statusClass = 'status-processing';
                $statusIcon = 'fas fa-clock';
                break;
            default:
                $statusClass = 'status-pending';
                $statusIcon = 'fas fa-hourglass-half';
        }

        $content .= '
        <div class="question-card">
            <div class="question-header">
                <div class="question-meta">
                    <span class="question-id">#' . $cauHoi['MaCauHoi'] . '</span>
                    <span class="question-date"><i class="far fa-calendar-alt"></i> ' . date('d/m/Y H:i', strtotime($cauHoi['NgayGui'])) . '</span>
                </div>
                <span class="status-badge ' . $statusClass . '">
                    <i class="' . $statusIcon . '"></i>
                    ' . $trangThai . '
                </span>
            </div>
            <div class="question-content">
                <h3>Câu hỏi:</h3>
                <p>' . htmlspecialchars($cauHoi['NoiDung']) . '</p>
            </div>';

        if (!empty($cauHoi['TraLoi'])) {
            $content .= '
            <div class="answer-content">
                <h3><i class="fas fa-comment-dots"></i> Phản hồi:</h3>
                <p>' . htmlspecialchars($cauHoi['TraLoi']) . '</p>
                <div class="answer-meta">
                    <span><i class="far fa-clock"></i> ' . date('d/m/Y H:i', strtotime($cauHoi['NgayTraLoi'])) . '</span>
                </div>
            </div>';
        }

        $content .= '
        </div>';
    }
}

$content .= '
        </div>
    </div>
    
    <div class="form-actions">
        <a href="gui_cauhoi.php" class="primary-btn">
            <i class="fas fa-plus"></i>
            Gửi câu hỏi mới
        </a>
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
    margin-bottom: 2rem;
}

.questions-list {
    padding: 2rem;
}

.question-card {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.question-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.question-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: #6c757d;
}

.question-id {
    font-weight: 600;
    color: #2575fc;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9em;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-answered {
    background: #d4edda;
    color: #155724;
}

.status-processing {
    background: #fff3cd;
    color: #856404;
}

.status-pending {
    background: #f8d7da;
    color: #721c24;
}

.question-content, .answer-content {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.question-content h3, .answer-content h3 {
    color: #2c3e50;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.answer-content {
    background: #f1f8ff;
    border-left: 4px solid #2575fc;
}

.answer-meta {
    margin-top: 1rem;
    color: #6c757d;
    font-size: 0.9em;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}

.empty-state i {
    margin-bottom: 1rem;
    color: #dee2e6;
}

.empty-state p {
    margin-bottom: 1.5rem;
}

.primary-btn {
    padding: 1rem 2rem;
    border-radius: 30px;
    font-size: 1.1em;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.primary-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(37, 117, 252, 0.2);
}

.form-actions {
    text-align: center;
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
    
    .question-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>';

// Gọi layout chung
include '../includes/layouthocsinh.php';
