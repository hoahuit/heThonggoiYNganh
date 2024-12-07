<?php
include '../includes/db.php';
session_start();

// Kiểm tra nếu giáo viên đã đăng nhập
if (!isset($_SESSION['MaGiaoVien'])) {
    header("Location: login.php");
    exit();
}

$maGiaoVien = $_SESSION['MaGiaoVien'];

// Lấy danh sách câu hỏi chưa được trả lời từ học sinh
$stmt = $conn->prepare("SELECT * FROM CauHoi ");
$stmt->execute();
$cauHoiList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nội dung chính của trang
$content = '
<div class="dashboard-container">
    <div class="page-header">
        <h2><i class="fas fa-question-circle"></i> Danh sách câu hỏi từ học sinh</h2>
      
    </div>
    
    <div class="stats-container">
        <div class="stat-card">
            <i class="fas fa-question"></i>
            <div class="stat-info">
                <h3>Tổng số câu hỏi</h3>
                <p>' . count($cauHoiList) . '</p>
            </div>
        </div>
        <div class="stat-card">
            <i class="fas fa-clock"></i>
            <div class="stat-info">
                <h3>Đang chờ</h3>
                <p>' . count(array_filter($cauHoiList, function ($q) {
    return $q["TrangThai"] == "Đang chờ";
})) . '</p>
            </div>
        </div>
        <div class="stat-card">
            <i class="fas fa-check-circle"></i>
            <div class="stat-info">
                <h3>Đã trả lời</h3>
                <p>' . count(array_filter($cauHoiList, function ($q) {
    return $q["TrangThai"] == "Đã trả lời";
})) . '</p>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="question-table">
            <thead>
                <tr>
                    <th>Mã câu hỏi</th>
                    <th width="30%">Nội dung</th>
                    <th width="25%">Trả lời</th>
                    <th>Trạng thái</th>
                    <th>Ngày gửi</th>
                    <th>Ngày trả lời</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>';

foreach ($cauHoiList as $cauHoi) {
    $trangThai = $cauHoi['TrangThai'];
    $statusClass = '';
    $statusIcon = '';

    switch ($trangThai) {
        case 'Đã trả lời':
            $statusClass = 'status-answered';
            $statusIcon = 'check-circle';
            break;
        case 'Đang chờ':
            $statusClass = 'status-pending';
            $statusIcon = 'clock';
            break;
        default:
            $statusClass = 'status-new';
            $statusIcon = 'bell';
    }

    $content .= '
            <tr class="question-row">
                <td class="text-center">#' . $cauHoi['MaCauHoi'] . '</td>
                <td class="question-content">
                    <div class="content-preview">' . htmlspecialchars($cauHoi['NoiDung']) . '</div>
                </td>
                <td class="answer-content">
                    <div class="content-preview">' . htmlspecialchars($cauHoi['TraLoi'] ?? 'Chưa có trả lời') . '</div>
                </td>
                <td class="text-center">
                    <span class="status-badge ' . $statusClass . '">
                        <i class="fas fa-' . $statusIcon . '"></i> ' . $trangThai . '
                    </span>
                </td>
                <td class="text-center">' . date('d/m/Y', strtotime($cauHoi['NgayGui'])) . '</td>
                <td class="text-center">' . ($cauHoi['NgayTraLoi'] ? date('d/m/Y', strtotime($cauHoi['NgayTraLoi'])) : '—') . '</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <a href="traloicauhoi.php?MaCauHoi=' . $cauHoi['MaCauHoi'] . '" class="btn-answer" title="Trả lời">
                            <i class="fas fa-reply"></i>
                        </a>
                        <button class="btn-view" title="Xem chi tiết" onclick="viewDetails(' . $cauHoi['MaCauHoi'] . ')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>';
}

$content .= '
            </tbody>
        </table>
    </div>
</div>

<style>
.dashboard-container {
    padding: 30px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border-color);
}

.page-header h2 {
    color: var(--text-color);
    font-size: 28px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-actions {
    display: flex;
    gap: 15px;
}

.search-box {
    position: relative;
}

.search-box input {
    padding: 10px 35px 10px 15px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    width: 250px;
    font-size: 14px;
}

.search-box i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-color);
}

.filter-box select {
    padding: 10px 15px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 14px;
    color: var(--text-color);
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 20px;
    border-radius: 10px;
    color: white;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card i {
    font-size: 2.5em;
    opacity: 0.9;
}

.stat-info h3 {
    font-size: 0.9em;
    font-weight: 500;
    margin-bottom: 5px;
}

.stat-info p {
    font-size: 1.8em;
    font-weight: 600;
}

.table-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

.question-table {
    width: 100%;
    border-collapse: collapse;
}

.question-table th,
.question-table td {
    padding: 15px;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.question-table thead th {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
    text-align: left;
    padding: 15px;
}

.question-row {
    transition: background-color 0.2s;
}

.question-row:hover {
    background-color: #f8f9fa;
}

.content-preview {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    line-height: 1.4;
    display: block;
}

.status-badge {
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.status-answered {
    background: #28a745;
    color: white;
}

.status-pending {
    background: #ffc107;
    color: #000;
}

.status-new {
    background: #17a2b8;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-answer,
.btn-view {
    padding: 8px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
}

.btn-answer {
    background: var(--primary-color);
    color: white;
}

.btn-view {
    background: #6c757d;
    color: white;
}

.btn-answer:hover,
.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .search-box input,
    .filter-box select {
        width: 100%;
    }
}
</style>

<script>
function viewDetails(id) {
    // Implement view details functionality
    alert("Xem chi tiết câu hỏi #" + id);
}

document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");
    
    searchInput.addEventListener("input", filterQuestions);
    statusFilter.addEventListener("change", filterQuestions);
    
    function filterQuestions() {
        // Implement search and filter functionality
    }
});
</script>';

// Gọi layout chung cho giáo viên
include '../includes/layoutgiaovien.php';
