<?php
session_start();
include '../includes/db.php';

// Kiểm tra đăng nhập của giáo viên
if (!isset($_SESSION['MaGiaoVien'])) {
    header("Location: login.php");
    exit();
}

$maGiaoVien = $_SESSION['MaGiaoVien'];

// Truy vấn danh sách các bài test của học sinh kèm theo ngành đề xuất
$stmt = $conn->prepare("SELECT B.MaBaiTest, B.MaHocSinh, H.HoTen AS TenHocSinh, B.NgayLamBai,
                                B.DinhHuongNgheNghiep, B.SoThich,
                                B.DiemToan, B.DiemVan, B.DiemAnh, B.DiemLy, B.DiemHoa, B.DiemSinh, 
                                B.NganhDeXuat, B.MaNganh
                         FROM BaiTest B
                         JOIN HocSinh H ON B.MaHocSinh = H.MaHocSinh
                         ORDER BY B.NgayLamBai DESC");
$stmt->execute();
$baitestList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách ngành học từ bảng NganhHoc
$nganhStmt = $conn->prepare("SELECT * FROM NganhHoc ORDER BY TenNganh");
$nganhStmt->execute();
$nganhList = $nganhStmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý quyết định ngành học cho học sinh
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maBaiTest = $_POST['MaBaiTest'];
    $maNganhChon = $_POST['MaNganhChon'];

    // Cập nhật ngành học chính thức cho học sinh
    $updateStmt = $conn->prepare("UPDATE BaiTest SET MaNganh = ? WHERE MaBaiTest = ?");
    $updateStmt->execute([$maNganhChon, $maBaiTest]);

    $_SESSION['success_message'] = "Cập nhật ngành học thành công!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Bắt đầu output buffering
ob_start();
?>

<style>
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .stat-card {
        background: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-card h3 {
        color: #333;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .stat-card .number {
        font-size: 2rem;
        font-weight: bold;
        color: #2196F3;
        margin-bottom: 0.5rem;
    }

    .card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        width: 100%;
        margin-left: 0;
        margin-right: 0;
    }

    .card-header {
        background: #2196F3;
        padding: 1.5rem;
    }

    .card-header h2 {
        font-size: 1.25rem;
        margin: 0;
        color: white;
    }

    .table {
        margin: 0;
        width: 100%;
    }

    .table th {
        font-weight: 600;
        padding: 1rem;
        background: #f5f5f5;
        border-bottom: 1px solid #eee;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }

    .badge {
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
    }

    .badge.bg-info {
        background: #E3F2FD !important;
        color: #2196F3 !important;
    }

    .badge.bg-secondary {
        background: #f5f5f5 !important;
        color: #666 !important;
    }

    .btn-detail {
        background: #2196F3;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-detail:hover {
        background: #1976D2;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        animation: fadeIn 0.3s ease-in-out;
    }

    .modal-content {
        background: white;
        width: 90%;
        max-width: 800px;
        margin: 50px auto;
        padding: 0;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        transform: translateY(-20px);
        animation: slideIn 0.3s ease-out forwards;
    }

    .modal-header {
        background: #2196F3;
        color: white;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .close {
        color: white;
        font-size: 1.8rem;
        opacity: 0.8;
        transition: opacity 0.2s;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .close:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.1);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }

    .detail-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
    }

    .detail-section h3 {
        color: #2196F3;
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
        border-bottom: 2px solid #2196F3;
        padding-bottom: 0.5rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding: 0.5rem;
        background: white;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .detail-item:hover {
        background: #e3f2fd;
    }

    .detail-label {
        font-weight: 600;
        color: #555;
    }

    .detail-value {
        color: #2196F3;
        font-weight: 500;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-60px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Content section -->
<div class="container-fluid p-0">
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Tổng số bài test</h3>
            <div class="number"><?php echo count($baitestList); ?></div>
        </div>
        <div class="stat-card">
            <h3>Đã phân ngành</h3>
            <div class="number">
                <?php echo count(array_filter($baitestList, function ($test) {
                    return !empty($test['MaNganh']);
                })); ?>
            </div>
        </div>
        <div class="stat-card">
            <h3>Chưa phân ngành</h3>
            <div class="number">
                <?php echo count(array_filter($baitestList, function ($test) {
                    return empty($test['MaNganh']);
                })); ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-list-alt me-2"></i>Danh sách bài test của học sinh</h2>
        </div>
        <div class="card-body p-0">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show mx-4 mt-4" role="alert">
                    <?php
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Mã bài test</th>
                            <th>Tên học sinh</th>
                            <th>Ngày làm bài</th>
                            <th>Ngành đề xuất</th>
                            <th>Chi tiết</th>
                            <th>Quyết định ngành</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($baitestList as $baitest): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?php echo $baitest['MaBaiTest']; ?></span></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($baitest['TenHocSinh']); ?></div>
                                    <small class="text-muted">ID: <?php echo $baitest['MaHocSinh']; ?></small>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($baitest['NgayLamBai'])); ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo htmlspecialchars($baitest['NganhDeXuat']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-detail" onclick="showDetails(<?php echo htmlspecialchars(json_encode($baitest)); ?>)">
                                        <i class="fas fa-info-circle"></i> Chi tiết
                                    </button>
                                </td>
                                <td>
                                    <form method="POST" action="danhsach_baitest.php" class="decision-form">
                                        <input type="hidden" name="MaBaiTest" value="<?php echo $baitest['MaBaiTest']; ?>">
                                        <div class="select-wrapper">
                                            <select name="MaNganhChon" class="custom-select" required>
                                                <option value="">-- Chọn ngành học --</option>
                                                <?php foreach ($nganhList as $nganh): ?>
                                                    <option value="<?php echo $nganh['MaNganh']; ?>"
                                                        <?php echo ($baitest['MaNganh'] == $nganh['MaNganh']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($nganh['TenNganh']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <i class="fas fa-chevron-down select-icon"></i>
                                        </div>
                                        <button type="submit" class="update-btn">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Xác nhận</span>
                                        </button>
                                    </form>
                                    <style>
                                        .decision-form {
                                            display: flex;
                                            gap: 0.5rem;
                                            align-items: center;
                                        }

                                        .select-wrapper {
                                            position: relative;
                                            flex: 1;
                                        }

                                        .custom-select {
                                            width: 100%;
                                            padding: 0.5rem 2rem 0.5rem 1rem;
                                            border: 1px solid #ddd;
                                            border-radius: 6px;
                                            appearance: none;
                                            background: #f8f9fa;
                                            font-size: 0.9rem;
                                            transition: all 0.3s ease;
                                        }

                                        .custom-select:focus {
                                            border-color: #4CAF50;
                                            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
                                            outline: none;
                                        }

                                        .select-icon {
                                            position: absolute;
                                            right: 10px;
                                            top: 50%;
                                            transform: translateY(-50%);
                                            color: #666;
                                            pointer-events: none;
                                        }

                                        .update-btn {
                                            display: inline-flex;
                                            align-items: center;
                                            gap: 0.5rem;
                                            padding: 0.5rem 1rem;
                                            border: none;
                                            border-radius: 6px;
                                            background: #4CAF50;
                                            color: white;
                                            font-size: 0.9rem;
                                            cursor: pointer;
                                            transition: all 0.3s ease;
                                        }

                                        .update-btn:hover {
                                            background: #45a049;
                                            transform: translateY(-1px);
                                        }
                                    </style>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal cập nhật -->
<div id="detailModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-clipboard-list me-2"></i>Chi tiết bài test</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body" id="modalContent"></div>
    </div>
</div>

<script>
    function showDetails(baitest) {
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');

        const content = `
            <div class="detail-grid">
                <div class="detail-section">
                    <h3><i class="fas fa-chart-bar me-2"></i>Thông tin điểm số</h3>
                    <div class="detail-item">
                        <span class="detail-label">Toán</span>
                        <span class="detail-value">${baitest.DiemToan}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Văn</span>
                        <span class="detail-value">${baitest.DiemVan}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Anh</span>
                        <span class="detail-value">${baitest.DiemAnh}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Lý</span>
                        <span class="detail-value">${baitest.DiemLy}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Hóa</span>
                        <span class="detail-value">${baitest.DiemHoa}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Sinh</span>
                        <span class="detail-value">${baitest.DiemSinh}</span>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3><i class="fas fa-info-circle me-2"></i>Thông tin bổ sung</h3>
                    <div class="detail-item">
                        <span class="detail-label">Định hướng nghề nghiệp</span>
                        <span class="detail-value">${baitest.DinhHuongNgheNghiep}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Sở thích</span>
                        <span class="detail-value">${baitest.SoThich}</span>
                    </div>
                </div>
            </div>
        `;

        modalContent.innerHTML = content;
        modal.style.display = 'block';
    }

    function closeModal() {
        const modal = document.getElementById('detailModal');
        modal.style.display = 'none';
    }

    // Đóng modal khi click bên ngoài
    window.onclick = function(event) {
        const modal = document.getElementById('detailModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

<?php
$content = ob_get_clean();
include '../includes/layoutgiaovien.php';
?>