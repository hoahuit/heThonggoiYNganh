<?php
include '../includes/db.php';
session_start();

// Kiểm tra nếu chưa đăng nhập
if (!isset($_SESSION['MaHocSinh'])) {
    header("Location: login.php");
    exit();
}

$maHocSinh = $_SESSION['MaHocSinh'];
$nganhDeXuat = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $dinhHuong = $_POST['dinhHuongNgheNghiep'];
    $soThich = $_POST['soThich'];
    $diemToan = floatval($_POST['diemToan']);
    $diemVan = floatval($_POST['diemVan']);
    $diemAnh = floatval($_POST['diemAnh']);
    $diemLy = floatval($_POST['diemLy']);
    $diemHoa = floatval($_POST['diemHoa']);
    $diemSinh = floatval($_POST['diemSinh']);

    // Validate điểm số
    $diem_array = [
        'Toán' => $diemToan,
        'Văn' => $diemVan,
        'Anh' => $diemAnh,
        'Lý' => $diemLy,
        'Hóa' => $diemHoa,
        'Sinh' => $diemSinh
    ];

    foreach ($diem_array as $mon => $diem) {
        if ($diem < 0 || $diem > 10) {
            $error = "Điểm môn $mon phải nằm trong khoảng từ 0 đến 10";
            break;
        }
    }

    if (empty($error)) {
        // Logic đề xuất ngành
        $stmt = $conn->prepare("
            SELECT TOP 1 TenNganh 
            FROM NganhHoc
            WHERE 
                (DieuKienDeXuat LIKE '%DiemToan%' AND :DiemToan >= 8.0)
                OR (DieuKienDeXuat LIKE '%DiemVan%' AND :DiemVan >= 7.0)
                OR (DieuKienDeXuat LIKE '%DiemAnh%' AND :DiemAnh >= 7.0)
                OR (DieuKienDeXuat LIKE '%DiemLy%' AND :DiemLy >= 7.0)
                OR (DieuKienDeXuat LIKE '%DiemHoa%' AND :DiemHoa >= 7.0)
                OR (DieuKienDeXuat LIKE '%DiemSinh%' AND :DiemSinh >= 7.0)
            ORDER BY TenNganh
        ");
        $stmt->execute([
            ':DiemToan' => $diemToan,
            ':DiemVan' => $diemVan,
            ':DiemAnh' => $diemAnh,
            ':DiemLy' => $diemLy,
            ':DiemHoa' => $diemHoa,
            ':DiemSinh' => $diemSinh
        ]);

        $nganh = $stmt->fetch(PDO::FETCH_ASSOC);
        $nganhDeXuat = $nganh ? $nganh['TenNganh'] : "Chưa có ngành phù hợp";

        // Lưu vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO BaiTest (MaHocSinh, DinhHuongNgheNghiep, SoThich, DiemToan, DiemVan, DiemAnh, DiemLy, DiemHoa, DiemSinh, NganhDeXuat) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$maHocSinh, $dinhHuong, $soThich, $diemToan, $diemVan, $diemAnh, $diemLy, $diemHoa, $diemSinh, $nganhDeXuat]);
    }
}

// Nội dung chính của trang
$content = '
<style>
.test-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.page-title {
    color: #2c3e50;
    margin-bottom: 2rem;
    font-size: 2.5em;
    text-align: center;
}

.result-card {
    background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 10px 20px rgba(37, 117, 252, 0.2);
}

.result-card h3 {
    font-size: 1.5em;
    margin-bottom: 1rem;
}

.form-section {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.form-section h3 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 0.8rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #2575fc;
    outline: none;
}

.score-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.submit-btn {
    background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
    color: white;
    padding: 1rem 2rem;
    border: none;
    border-radius: 30px;
    font-size: 1.1em;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(37, 117, 252, 0.2);
}

.error-message {
    background: #ffe5e5;
    color: #d63031;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border-left: 4px solid #d63031;
}
</style>

<div class="test-container">
    <h2 class="page-title">Làm bài kiểm tra</h2>
    
    ' . ($error ? '<div class="error-message">' . $error . '</div>' : '') . '
    
    ' . ($nganhDeXuat ? '<div class="result-card">
        <h3>Ngành được đề xuất</h3>
        <p style="font-size: 1.2em; font-weight: 500;">' . $nganhDeXuat . '</p>
    </div>' : '') . '

    <form method="POST" class="test-form">
        <div class="form-section">
            <h3><i class="fas fa-compass"></i> Phần 1: Định hướng và sở thích</h3>
            <div class="form-group">
                <label for="dinhHuongNgheNghiep">Định hướng nghề nghiệp:</label>
                <input type="text" class="form-control" id="dinhHuongNgheNghiep" name="dinhHuongNgheNghiep" required>
            </div>
            
            <div class="form-group">
                <label for="soThich">Sở thích:</label>
                <textarea class="form-control" id="soThich" name="soThich" rows="4" required></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-chart-bar"></i> Phần 2: Điểm số các môn</h3>
            <div class="score-grid">
                <div class="form-group">
                    <label for="diemToan">Điểm Toán:</label>
                    <input type="number" class="form-control" id="diemToan" name="diemToan" step="0.1" min="0" max="10" required>
                </div>

                <div class="form-group">
                    <label for="diemVan">Điểm Văn:</label>
                    <input type="number" class="form-control" id="diemVan" name="diemVan" step="0.1" min="0" max="10" required>
                </div>

                <div class="form-group">
                    <label for="diemAnh">Điểm Anh:</label>
                    <input type="number" class="form-control" id="diemAnh" name="diemAnh" step="0.1" min="0" max="10" required>
                </div>

                <div class="form-group">
                    <label for="diemLy">Điểm Lý:</label>
                    <input type="number" class="form-control" id="diemLy" name="diemLy" step="0.1" min="0" max="10" required>
                </div>

                <div class="form-group">
                    <label for="diemHoa">Điểm Hóa:</label>
                    <input type="number" class="form-control" id="diemHoa" name="diemHoa" step="0.1" min="0" max="10" required>
                </div>

                <div class="form-group">
                    <label for="diemSinh">Điểm Sinh:</label>
                    <input type="number" class="form-control" id="diemSinh" name="diemSinh" step="0.1" min="0" max="10" required>
                </div>
            </div>
        </div>

        <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Nộp bài</button>
    </form>
</div>

<script>
document.querySelectorAll("input[type=number]").forEach(input => {
    input.addEventListener("input", function() {
        let value = parseFloat(this.value);
        if(value < 0) this.value = 0;
        if(value > 10) this.value = 10;
    });
});
</script>
';

include '../includes/layouthocsinh.php'; // Sử dụng layout chung
