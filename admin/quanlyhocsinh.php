<?php
session_start();
include '../includes/db.php';

// Kiểm tra nếu admin chưa đăng nhập
if (!isset($_SESSION['MaAdmin'])) {
    header("Location: login.php");
    exit();
}

// Xử lý thêm học sinh
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $sodienthoai = $_POST['sodienthoai'];
    $diachi = $_POST['diachi'];
    $matkhau = $_POST['matkhau'];

    $stmt = $conn->prepare("INSERT INTO HocSinh (HoTen, Email, SoDienThoai, DiaChi, MatKhau) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$hoten, $email, $sodienthoai, $diachi, $matkhau])) {
        $_SESSION['success_message'] = "Thêm học sinh thành công!";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Xử lý cập nhật học sinh
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $mahocsinh = $_POST['mahocsinh'];
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $sodienthoai = $_POST['sodienthoai'];
    $diachi = $_POST['diachi'];

    $stmt = $conn->prepare("UPDATE HocSinh SET HoTen = ?, Email = ?, SoDienThoai = ?, DiaChi = ? WHERE MaHocSinh = ?");
    if ($stmt->execute([$hoten, $email, $sodienthoai, $diachi, $mahocsinh])) {
        $_SESSION['success_message'] = "Cập nhật học sinh thành công!";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Xóa học sinh nếu nhận được yêu cầu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteId'])) {
    $deleteId = $_POST['deleteId'];

    $deleteStmt = $conn->prepare("DELETE FROM HocSinh WHERE MaHocSinh = ?");
    $deleteStmt->execute([$deleteId]);

    $_SESSION['success_message'] = "Xóa học sinh thành công!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Lấy danh sách học sinh
$stmt = $conn->prepare("SELECT MaHocSinh, HoTen, Email, SoDienThoai, DiaChi FROM HocSinh ORDER BY HoTen");
$stmt->execute();
$hocsinhList = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<!-- Add CSS styles (same as giaovien.php) -->
<style>
    .container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-title {
        margin: 0;
        color: #333;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    tr:hover {
        background-color: #f9f9f9;
    }

    .btn {
        padding: 8px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin: 0 5px;
    }

    .btn-add {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-add:hover {
        background-color: #45a049;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-add i {
        font-size: 14px;
    }

    .btn-edit {
        background-color: #2196F3;
        color: white;
    }

    .btn-delete {
        background-color: #f44336;
        color: white;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal.active {
        opacity: 1;
    }

    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 5px;
        width: 90%;
        max-width: 500px;
        position: relative;
    }

    .modal-close {
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 24px;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .modal-actions {
        text-align: right;
        margin-top: 20px;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .alert-success {
        background-color: #dff0d8;
        border-color: #d6e9c6;
        color: #3c763d;
    }
</style>

<div class="container">
    <div class="page-header">
        <h2 class="page-title">Quản lý tài khoản học sinh</h2>
        <button class="btn-add" onclick="showAddModal()">
            <i class="fas fa-plus"></i> Thêm học sinh
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
                    <th>Địa chỉ</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hocsinhList as $hocsinh): ?>
                    <tr>
                        <td><?php echo $hocsinh['MaHocSinh']; ?></td>
                        <td><?php echo htmlspecialchars($hocsinh['HoTen']); ?></td>
                        <td><?php echo htmlspecialchars($hocsinh['Email']); ?></td>
                        <td><?php echo htmlspecialchars($hocsinh['SoDienThoai']); ?></td>
                        <td><?php echo htmlspecialchars($hocsinh['DiaChi']); ?></td>
                        <td>
                            <button class="btn btn-edit" onclick="showEditModal(<?php echo htmlspecialchars(json_encode($hocsinh)); ?>)">Sửa</button>
                            <button class="btn btn-delete" onclick="deleteHocSinh(<?php echo $hocsinh['MaHocSinh']; ?>)">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Thêm học sinh -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('addModal')">&times;</span>
        <h3 class="modal-title">Thêm học sinh mới</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add">
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
                <label>Địa chỉ:</label>
                <input type="text" name="diachi" required>
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

<!-- Modal Sửa học sinh -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('editModal')">&times;</span>
        <h3 class="modal-title">Sửa thông tin học sinh</h3>
        <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="mahocsinh" id="edit_mahocsinh">
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
                <label>Địa chỉ:</label>
                <input type="text" name="diachi" id="edit_diachi" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn" onclick="closeModal('editModal')">Hủy</button>
                <button type="submit" class="btn btn-edit">Cập nhật</button>
            </div>
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

    function showEditModal(hocsinh) {
        const modal = document.getElementById('editModal');
        document.getElementById('edit_mahocsinh').value = hocsinh.MaHocSinh;
        document.getElementById('edit_hoten').value = hocsinh.HoTen;
        document.getElementById('edit_email').value = hocsinh.Email;
        document.getElementById('edit_sodienthoai').value = hocsinh.SoDienThoai;
        document.getElementById('edit_diachi').value = hocsinh.DiaChi;
        modal.style.display = 'block';
        setTimeout(() => modal.classList.add('active'), 10);
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    function deleteHocSinh(id) {
        if (confirm('Bạn có chắc chắn muốn xóa học sinh này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
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