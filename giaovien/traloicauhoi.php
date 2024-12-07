<?php
include '../includes/db.php';
session_start();

// Kiểm tra nếu giáo viên đã đăng nhập
if (!isset($_SESSION['MaGiaoVien'])) {
    header("Location: login.php");
    exit();
}

$maGiaoVien = $_SESSION['MaGiaoVien'];

// Kiểm tra nếu có MaCauHoi trong URL
if (!isset($_GET['MaCauHoi'])) {
    echo "<div class='alert alert-danger'>Không tìm thấy câu hỏi.</div>";
    exit();
}

$maCauHoi = $_GET['MaCauHoi'];

// Lấy câu hỏi cần trả lời
$stmt = $conn->prepare("SELECT * FROM CauHoi WHERE MaCauHoi = ?");
$stmt->execute([$maCauHoi]);
$cauHoi = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra nếu câu hỏi tồn tại
if (!$cauHoi) {
    echo "<div class='alert alert-danger'>Câu hỏi không tồn tại.</div>";
    exit();
}

$message = "";

// Kiểm tra khi giáo viên gửi câu trả lời
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $traLoi = $_POST['traLoi'];

    // Cập nhật câu trả lời vào cơ sở dữ liệu
    $stmt = $conn->prepare("UPDATE CauHoi SET TraLoi = ?, TrangThai = N'Đã trả lời', MaGiaoVien = ?, NgayTraLoi = GETDATE() WHERE MaCauHoi = ?");
    $stmt->execute([$traLoi, $maGiaoVien, $maCauHoi]);

    $message = "<div class='alert alert-success'>Câu hỏi đã được trả lời thành công!</div>";
}

// Nội dung chính của trang
$content = '
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Trả lời câu hỏi</title>
    <style>
        body {
            background-color: #f0f2f5;
            font-family: "Segoe UI", Arial, sans-serif;
        }
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 40px;
            max-width: 900px;
        }
        h2 {
            color: #1a73e8;
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 2rem;
        }
        .form-group label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.25);
        }
        textarea.form-control {
            min-height: 120px;
        }
        .btn-primary {
            background-color: #1a73e8;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1557b0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
        }
        .alert {
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .fas {
            margin-right: 8px;
        }
        .question-container {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center"><i class="fas fa-question-circle"></i> Trả lời câu hỏi</h2>
    ' . ($message ? $message : '') . '
    <form method="POST" class="mt-4">
        <div class="question-container">
            <div class="form-group">
                <label for="noiDung"><i class="fas fa-comment-dots"></i> Nội dung câu hỏi:</label>
                <textarea class="form-control" rows="4" readonly>' . htmlspecialchars($cauHoi['NoiDung']) . '</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="traLoi"><i class="fas fa-pen"></i> Trả lời:</label>
            <textarea class="form-control" id="traLoi" name="traLoi" rows="6" required placeholder="Nhập câu trả lời của bạn tại đây..."></textarea>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg mt-4">
                <i class="fas fa-paper-plane"></i> Gửi trả lời
            </button>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
';

// Gọi layout chung cho giáo viên
include '../includes/layoutgiaovien.php';
