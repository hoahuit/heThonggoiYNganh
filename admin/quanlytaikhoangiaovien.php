<?php
session_start();
include '../includes/db.php';

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['MaAdmin'])) {
    header("Location: login.php");
    exit();
}

// Xử lý thêm giáo viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $hoten = $_POST['hoten'];
    $tendangnhap = $_POST['tendangnhap'];
    $email = $_POST['email'];
    $sodienthoai = $_POST['sodienthoai'];
    $manganh = $_POST['manganh'];
    $matkhau = $_POST['matkhau']; // Có thể thêm password_hash() nếu muốn mã hóa

    $stmt = $conn->prepare("INSERT INTO GiaoVien (TenDangNhap, MatKhau, HoTen, Email, SoDienThoai, MaNganh) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$tendangnhap, $matkhau, $hoten, $email, $sodienthoai, $manganh])) {
        $_SESSION['success_message'] = "Thêm giáo viên thành công!";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Xử lý cập nhật giáo viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $magiaovien = $_POST['magiaovien'];
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $sodienthoai = $_POST['sodienthoai'];
    $manganh = $_POST['manganh'];

    $stmt = $conn->prepare("UPDATE GiaoVien SET HoTen = ?, Email = ?, SoDienThoai = ?, MaNganh = ? WHERE MaGiaoVien = ?");
    if ($stmt->execute([$hoten, $email, $sodienthoai, $manganh, $magiaovien])) {
        $_SESSION['success_message'] = "Cập nhật giáo viên thành công!";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Xử lý xóa giáo viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $deleteId = $_POST['deleteId'];
    $stmt = $conn->prepare("DELETE FROM GiaoVien WHERE MaGiaoVien = ?");
    if ($stmt->execute([$deleteId])) {
        $_SESSION['success_message'] = "Xóa giáo viên thành công!";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Lấy danh sách ngành học
$stmt = $conn->prepare("SELECT MaNganh, TenNganh FROM NganhHoc");
$stmt->execute();
$nganhList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách giáo viên
$stmt = $conn->prepare("SELECT gv.*, nh.TenNganh 
                       FROM GiaoVien gv 
                       LEFT JOIN NganhHoc nh ON gv.MaNganh = nh.MaNganh 
                       ORDER BY gv.HoTen");
$stmt->execute();
$giaovienList = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>
<!-- CSS styles -->
<style>
    .container {
        padding: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
    }

    .page-title {
        color: #2c3e50;
        font-size: 24px;
        font-weight: 600;
        margin: 0;
    }

    .btn-add {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .table-responsive {
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    th {
        background: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
        padding: 15px;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eee;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        color: #555;
    }

    tr:hover {
        background-color: #f8f9fa;
    }

    .btn {
        padding: 8px 16px;
        margin: 0 4px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }

    .alert {
        padding: 16px;
        margin-bottom: 25px;
        border-radius: 6px;
        border-left: 4px solid;
    }

    .alert-success {
        background-color: #e3f2fd;
        border-color: #2196F3;
        color: #0d47a1;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .modal-content {
        position: relative;
        background-color: white;
        margin: 5% auto;
        padding: 30px;
        width: 90%;
        max-width: 500px;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        transform: scale(0.7);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .modal.active .modal-content {
        transform: scale(1);
        opacity: 1;
    }

    .modal-close {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 24px;
        color: #999;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .modal-close:hover {
        color: #333;
    }

    .modal-title {
        color: #2c3e50;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #2c3e50;
        font-weight: 500;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.1);
        outline: none;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
    }
</style>

<div class="container">
    <div class="page-header">
        <h2 class="page-title">Quản lý tài khoản giáo viên</h2>
        <button class="btn-add" onclick="showAddModal()">
            <i class="fas fa-plus"></i> Thêm giáo viên
        </button>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success_message'];
            unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Ngành</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($giaovienList as $giaovien): ?>
                    <tr>
                        <td><?php echo $giaovien['MaGiaoVien']; ?></td>
                        <td><?php echo htmlspecialchars($giaovien['HoTen']); ?></td>
                        <td><?php echo htmlspecialchars($giaovien['Email']); ?></td>
                        <td><?php echo htmlspecialchars($giaovien['SoDienThoai']); ?></td>
                        <td><?php echo htmlspecialchars($giaovien['TenNganh']); ?></td>
                        <td>
                            <button class="btn btn-edit" onclick="showEditModal(<?php echo htmlspecialchars(json_encode($giaovien)); ?>)">Sửa</button>
                            <button class="btn btn-delete" onclick="deleteGiaoVien(<?php echo $giaovien['MaGiaoVien']; ?>)">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Thêm giáo viên -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('addModal')">&times;</span>
        <h3 class="modal-title">Thêm giáo viên mới</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>Tên đăng nhập:</label>
                <input type="text" name="tendangnhap" required>
            </div>
            <div class="form-group">
                <label>Họ tên:</label>
                <input type="text" name="hoten" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại:</label>
                <input type="text" name="sodienthoai" required>
            </div>
            <div class="form-group">
                <label>Ngành:</label>
                <select name="manganh" required>
                    <?php foreach ($nganhList as $nganh): ?>
                        <option value="<?php echo $nganh['MaNganh']; ?>"><?php echo $nganh['TenNganh']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mật khẩu:</label>
                <input type="password" name="matkhau" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn" onclick="closeModal('addModal')">Hủy</button>
                <button type="submit" class="btn btn-add">Thêm</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Sửa giáo viên -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('editModal')">&times;</span>
        <h3 class="modal-title">Sửa thông tin giáo viên</h3>
        <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="magiaovien" id="edit_magiaovien">
            <div class="form-group">
                <label>Họ tên:</label>
                <input type="text" name="hoten" id="edit_hoten" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại:</label>
                <input type="text" name="sodienthoai" id="edit_sodienthoai" required>
            </div>
            <div class="form-group">
                <label>Ngành:</label>
                <select name="manganh" id="edit_manganh" required>
                    <?php foreach ($nganhList as $nganh): ?>
                        <option value="<?php echo $nganh['MaNganh']; ?>"><?php echo $nganh['TenNganh']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-edit">Cập nhật</button>
            <button type="button" class="btn" onclick="closeModal('editModal')">Hủy</button>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
    function showAddModal() {
        const modal = document.getElementById('addModal');
        modal.style.display = 'block';
        setTimeout(() => modal.classList.add('active'), 10);
    }

    function showEditModal(giaovien) {
        const modal = document.getElementById('editModal');
        document.getElementById('edit_magiaovien').value = giaovien.MaGiaoVien;
        document.getElementById('edit_hoten').value = giaovien.HoTen;
        document.getElementById('edit_email').value = giaovien.Email;
        document.getElementById('edit_sodienthoai').value = giaovien.SoDienThoai;
        document.getElementById('edit_manganh').value = giaovien.MaNganh;
        modal.style.display = 'block';
        setTimeout(() => modal.classList.add('active'), 10);
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    function deleteGiaoVien(id) {
        if (confirm('Bạn có chắc chắn muốn xóa giáo viên này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="deleteId" value="${id}">
        `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Cập nhật event listener cho click outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    }
</script>

<!-- Thêm Font Awesome cho icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<?php
$content = ob_get_clean();
include '../includes/layoutadmin.php';
?>