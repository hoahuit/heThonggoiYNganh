<?php
include '../includes/db.php';

session_start();
$maHocSinh = $_SESSION['MaHocSinh'];

// Lấy lịch sử làm bài
$stmt = $conn->prepare("SELECT * FROM BaiTest WHERE MaHocSinh = ? ORDER BY NgayLamBai DESC");
$stmt->execute([$maHocSinh]);
$lichSu = $stmt->fetchAll(PDO::FETCH_ASSOC);

$content = '
<style>
.history-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.history-header {
    text-align: center;
    margin-bottom: 3rem;
}

.history-header h2 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.history-table {
    width: 100%;
    background: white;
    border-radius: 15px;
    border-collapse: collapse;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.history-table th {
    background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
    color: white;
    padding: 1rem;
    text-align: left;
}

.history-table td {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.history-table tr:last-child td {
    border-bottom: none;
}

.history-table tr:hover {
    background-color: #f8f9fa;
}

.empty-message {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
    font-style: italic;
}
</style>

<div class="history-container">
    <div class="history-header">
        <h2>Lịch sử làm bài</h2>
    </div>';

if (count($lichSu) > 0) {
    $content .= '
    <table class="history-table">
        <tr>
            <th>Ngày làm bài</th>
            <th>Ngành đề xuất</th>
        </tr>';

    foreach ($lichSu as $baiTest) {
        $ngayLamBai = date('d/m/Y H:i', strtotime($baiTest['NgayLamBai']));
        $content .= "<tr>
            <td>{$ngayLamBai}</td>
            <td>{$baiTest['NganhDeXuat']}</td>
        </tr>";
    }

    $content .= '</table>';
} else {
    $content .= '<div class="empty-message">Bạn chưa có lịch sử làm bài nào</div>';
}

$content .= '</div>';

include '../includes/layouthocsinh.php';
