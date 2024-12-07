<?php
include '../includes/db.php';
session_start();

// Kiểm tra nếu học sinh đã đăng nhập, chuyển hướng về trang chính
if (isset($_SESSION['MaHocSinh'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenDangNhap = $_POST['tenDangNhap'];
    $matKhau = $_POST['matKhau'];

    // Lấy thông tin học sinh từ cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT * FROM HocSinh WHERE TenDangNhap = ? AND MatKhau = ?");
    $stmt->execute([$tenDangNhap, $matKhau]);
    $hocSinh = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($hocSinh) {
        // Đăng nhập thành công, khởi tạo session
        $_SESSION['MaHocSinh'] = $hocSinh['MaHocSinh'];
        $_SESSION['HoTen'] = $hocSinh['HoTen'];
        header("Location: index.php");
        exit();
    } else {
        // Sai tên đăng nhập hoặc mật khẩu
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Học sinh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #357ABD;
            --accent-color: #FF6B6B;
            --text-color: #2C3E50;
            --bg-color: #F8F9FA;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.6s ease-out;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: var(--text-color);
            font-size: 2em;
            margin-bottom: 0.5rem;
        }

        .error-message {
            background: #FFE8E8;
            color: #D63031;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 2px solid #E0E0E0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .form-group i {
            position: absolute;
            left: 1rem;
            top: 2.3rem;
            color: #999;
        }

        .login-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .login-button:hover {
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Đăng nhập học sinh</h2>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="tenDangNhap">Tên đăng nhập</label>
                <i class="fas fa-user"></i>
                <input type="text" id="tenDangNhap" name="tenDangNhap" required
                    placeholder="Nhập tên đăng nhập">
            </div>

            <div class="form-group">
                <label for="matKhau">Mật khẩu</label>
                <i class="fas fa-lock"></i>
                <input type="password" id="matKhau" name="matKhau" required
                    placeholder="Nhập mật khẩu">
            </div>

            <button type="submit" class="login-button">
                Đăng nhập
            </button>
        </form>
    </div>
</body>

</html>