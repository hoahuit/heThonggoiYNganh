<?php
session_start();
include '../includes/db.php';

// Kiểm tra nếu admin chưa đăng nhập
if (!isset($_SESSION['MaAdmin'])) {
    header("Location: login.php");
    exit();
}

// Lấy danh sách ngành học
$stmt = $conn->prepare("SELECT MaNganh, TenNganh, MoTa, DieuKienDeXuat FROM NganhHoc ORDER BY TenNganh");
$stmt->execute();
$nganhhocList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xóa ngành học nếu nhận được yêu cầu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteId'])) {
    $deleteId = $_POST['deleteId'];

    $deleteStmt = $conn->prepare("DELETE FROM NganhHoc WHERE MaNganh = ?");
    $deleteStmt->execute([$deleteId]);

    $_SESSION['success_message'] = "Xóa ngành học thành công!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Thêm hoặc sửa ngành học
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $maNganh = $_POST['maNganh'] ?? null;
    $tenNganh = $_POST['tenNganh'];
    $moTa = $_POST['moTa'];
    $dieuKienDeXuat = $_POST['dieuKienDeXuat'] ?? '';

    // Thêm chi tiết logging để debug
    error_log("Action: " . $_POST['action']);
    error_log("POST data: " . print_r($_POST, true));
    error_log("Điều kiện đề xuất: " . $dieuKienDeXuat);

    // Kiểm tra các checkbox và giá trị điểm
    foreach (['Toan', 'Van', 'Anh', 'Ly', 'Hoa', 'Sinh'] as $subject) {
        $checkboxName = "diem{$subject}_check";
        $inputName = "diem{$subject}";
        error_log("$checkboxName: " . (isset($_POST[$checkboxName]) ? "checked" : "unchecked"));
        error_log("$inputName: " . ($_POST[$inputName] ?? "không có giá trị"));
    }

    // Validate điều kiện đề xuất nếu có giá trị
    if (!empty($dieuKienDeXuat)) {
        $conditions = explode(' AND ', $dieuKienDeXuat);
        $validConditions = true;
        foreach ($conditions as $condition) {
            if (!preg_match('/^Diem(Toan|Van|Anh|Ly|Hoa|Sinh)\s*>=\s*\d+(\.\d+)?$/', trim($condition))) {
                $validConditions = false;
                error_log("Điều kiện không hợp lệ: " . $condition);
                break;
            }
        }
        if (!$validConditions) {
            $_SESSION['error_message'] = "Điều kiện đề xuất không hợp lệ!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    if ($_POST['action'] === 'add') {
        $addStmt = $conn->prepare("INSERT INTO NganhHoc (TenNganh, MoTa, DieuKienDeXuat) VALUES (?, ?, ?)");
        $addStmt->execute([$tenNganh, $moTa, $dieuKienDeXuat]);
        $_SESSION['success_message'] = "Thêm ngành học thành công!";
    } elseif ($_POST['action'] === 'edit' && $maNganh) {
        $editStmt = $conn->prepare("UPDATE NganhHoc SET TenNganh = ?, MoTa = ?, DieuKienDeXuat = ? WHERE MaNganh = ?");
        $editStmt->execute([$tenNganh, $moTa, $dieuKienDeXuat, $maNganh]);
        $_SESSION['success_message'] = "Sửa ngành học thành công!";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Nội dung hiển thị
ob_start();
?>

<style>
    .container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #eaeaea;
    }

    .page-title {
        color: #2c3e50;
        font-size: 1.8rem;
        margin: 0;
    }

    .btn-add {
        background: #3498db;
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    /* Table Styling */
    .table-responsive {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8f9fa;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 2px solid #eaeaea;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #eaeaea;
        color: #444;
    }

    tr:hover {
        background: #f8f9fa;
    }

    /* Button Styling */
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: #2ecc71;
        color: white;
        margin-right: 0.5rem;
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
    }

    .btn-edit:hover {
        background: #27ae60;
    }

    .btn-delete:hover {
        background: #c0392b;
    }

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .modal-content {
        background: white;
        width: 90%;
        max-width: 600px;
        margin: 2rem auto;
        padding: 2rem;
        border-radius: 8px;
        position: relative;
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-title {
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #eaeaea;
    }

    .modal-close {
        position: absolute;
        right: 1.5rem;
        top: 1.5rem;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
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

    .form-group input[type="text"],
    .form-group input[type="number"] {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    .dieu-kien-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .dieu-kien-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .diem-input {
        width: 80px !important;
    }

    /* Alert Styling */
    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>

<div class="container">
    <div class="page-header">
        <h2 class="page-title">Quản lý ngành học</h2>
        <button class="btn-add" onclick="showAddModal()">
            <i class="fas fa-plus"></i> Thêm ngành học
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
                    <th>Tên ngành</th>
                    <th>Mô tả</th>
                    <th>Điều kiện đề xuất</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nganhhocList as $nganh): ?>
                    <tr>
                        <td><?php echo $nganh['MaNganh']; ?></td>
                        <td><?php echo htmlspecialchars($nganh['TenNganh']); ?></td>
                        <td><?php echo htmlspecialchars($nganh['MoTa']); ?></td>
                        <td><?php echo htmlspecialchars($nganh['DieuKienDeXuat']); ?></td>
                        <td>
                            <button class="btn btn-edit" onclick="showEditModal(<?php echo htmlspecialchars(json_encode($nganh)); ?>)">Sửa</button>
                            <button class="btn btn-delete" onclick="deleteNganh(<?php echo $nganh['MaNganh']; ?>)">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Thêm ngành học -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('addModal')">&times;</span>
        <h3 class="modal-title">Thêm ngành học mới</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>Tên ngành:</label>
                <input type="text" name="tenNganh" required>
            </div>
            <div class="form-group">
                <label>Mô tả:</label>
                <input type="text" name="moTa">
            </div>

            <div class="modal-actions">
                <button type="button" class="btn" onclick="closeModal('addModal')">Hủy</button>
                <button type="submit" class="btn btn-add">Thêm</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Sửa ngành học -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('editModal')">&times;</span>
        <h3 class="modal-title">Sửa ngành học</h3>
        <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="maNganh" id="edit_maNganh">
            <div class="form-group">
                <label>Tên ngành:</label>
                <input type="text" name="tenNganh" id="edit_tenNganh" required>
            </div>
            <div class="form-group">
                <label>Mô tả:</label>
                <input type="text" name="moTa" id="edit_moTa">
            </div>
            <div class="form-group">
                <label>Điều kiện đề xuất:</label>
                <div class="dieu-kien-container">
                    <div class="dieu-kien-item">
                        <input type="checkbox" name="diemToan_check" id="edit_diemToan_check">
                        <label for="edit_diemToan_check">Điểm Toán ≥</label>
                        <input type="number" name="diemToan" id="edit_diemToan" step="0.1" min="0" max="10" class="diem-input">
                    </div>
                    <div class="dieu-kien-item">
                        <input type="checkbox" name="diemVan_check" id="edit_diemVan_check">
                        <label for="edit_diemVan_check">Điểm Văn ≥</label>
                        <input type="number" name="diemVan" id="edit_diemVan" step="0.1" min="0" max="10" class="diem-input">
                    </div>
                    <div class="dieu-kien-item">
                        <input type="checkbox" name="diemAnh_check" id="edit_diemAnh_check">
                        <label for="edit_diemAnh_check">Điểm Anh ≥</label>
                        <input type="number" name="diemAnh" id="edit_diemAnh" step="0.1" min="0" max="10" class="diem-input">
                    </div>
                    <div class="dieu-kien-item">
                        <input type="checkbox" name="diemLy_check" id="edit_diemLy_check">
                        <label for="edit_diemLy_check">Điểm Lý ≥</label>
                        <input type="number" name="diemLy" id="edit_diemLy" step="0.1" min="0" max="10" class="diem-input">
                    </div>
                    <div class="dieu-kien-item">
                        <input type="checkbox" name="diemHoa_check" id="edit_diemHoa_check">
                        <label for="edit_diemHoa_check">Điểm Hóa ≥</label>
                        <input type="number" name="diemHoa" id="edit_diemHoa" step="0.1" min="0" max="10" class="diem-input">
                    </div>
                    <div class="dieu-kien-item">
                        <input type="checkbox" name="diemSinh_check" id="edit_diemSinh_check">
                        <label for="edit_diemSinh_check">Điểm Sinh ≥</label>
                        <input type="number" name="diemSinh" id="edit_diemSinh" step="0.1" min="0" max="10" class="diem-input">
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn" onclick="closeModal('editModal')">Hủy</button>
                <button type="submit" class="btn btn-edit">Lưu</button>
            </div>
        </form>
    </div>
</div>


<!-- Add Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
    // Hàm hiển thị modal thêm mới
    function showAddModal() {
        document.getElementById('addModal').style.display = 'block';
        // Reset form
        document.querySelector('#addModal form').reset();
    }

    // Hàm hiển thị modal sửa
    function showEditModal(nganh) {
        document.getElementById('editModal').style.display = 'block';

        // Điền dữ liệu vào form
        document.getElementById('edit_maNganh').value = nganh.MaNganh;
        document.getElementById('edit_tenNganh').value = nganh.TenNganh;
        document.getElementById('edit_moTa').value = nganh.MoTa;

        // Reset tất cả checkbox và input điểm
        const checkboxes = document.querySelectorAll('#editModal input[type="checkbox"]');
        const diemInputs = document.querySelectorAll('#editModal input[type="number"]');
        checkboxes.forEach(cb => cb.checked = false);
        diemInputs.forEach(input => input.value = '');

        // Xử lý điều kiện đề xuất nếu có
        if (nganh.DieuKienDeXuat) {
            const conditions = nganh.DieuKienDeXuat.split(' AND ');
            conditions.forEach(condition => {
                const matches = condition.match(/Diem(\w+)\s*>=\s*(\d+(\.\d+)?)/);
                if (matches) {
                    const subject = matches[1];
                    const value = matches[2];
                    document.getElementById(`edit_diem${subject}_check`).checked = true;
                    document.getElementById(`edit_diem${subject}`).value = value;
                }
            });
        }
    }

    // Hàm đóng modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Hàm xác nhận xóa
    function deleteNganh(maNganh) {
        if (confirm('Bạn có chắc chắn muốn xóa ngành học này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="deleteId" value="${maNganh}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Xử lý điều kiện đề xuất trước khi submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const isAdd = this.querySelector('input[name="action"]').value === 'add';
            const prefix = isAdd ? '' : 'edit_';

            // Log để debug
            console.log('Form submission - isAdd:', isAdd);
            console.log('Prefix:', prefix);

            let dieuKien = [];
            const subjects = ['Toan', 'Van', 'Anh', 'Ly', 'Hoa', 'Sinh'];

            subjects.forEach(subject => {
                const checkbox = document.getElementById(`${prefix}diem${subject}_check`);
                const input = document.getElementById(`${prefix}diem${subject}`);

                // Log trạng thái của từng trường
                console.log(`${subject}:`, {
                    checkbox: checkbox?.checked,
                    input: input?.value,
                    checkboxId: `${prefix}diem${subject}_check`,
                    inputId: `${prefix}diem${subject}`
                });

                if (checkbox && checkbox.checked && input && input.value) {
                    dieuKien.push(`Diem${subject} >= ${input.value}`);
                }
            });

            // Tạo input ẩn mới với điều kiện đề xuất
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'dieuKienDeXuat';
            hiddenInput.value = dieuKien.join(' AND ');

            // Log giá trị cuối cùng
            console.log('Final dieuKienDeXuat:', hiddenInput.value);

            // Xóa input ẩn cũ nếu có
            const existingInput = this.querySelector('input[name="dieuKienDeXuat"]');
            if (existingInput) {
                existingInput.remove();
            }

            this.appendChild(hiddenInput);
            this.submit();
        });
    });

    // Đóng modal khi click bên ngoài
    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = 'none';
        }
    }

    // Thêm sự kiện cho nút ESC để đóng modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
        }
    });
</script>

<?php
$content = ob_get_clean();
include '../includes/layoutadmin.php';
?>