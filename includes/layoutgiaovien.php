<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống giáo viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #357ABD;
            --text-color: #2C3E50;
            --bg-color: #F8F9FA;
            --border-color: #E9ECEF;
            --sidebar-width: 250px;
            --header-height: 200px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
        }

        /* Header Styles */
        .header-banner {
            height: var(--header-height);
            width: 100%;
            background-image: url('https://img.freepik.com/free-vector/abstract-education-background-with-books-minimal-style_1017-25184.jpg');
            background-size: cover;
            background-position: center;
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(44, 62, 80, 0.8), rgba(52, 152, 219, 0.8));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .header-content {
            text-align: center;
        }

        .header-content h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header-content p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #2C3E50, #3498DB);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            color: white;
            padding: 1rem 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 20;
        }

        .sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-header i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #FFC107;
        }

        .nav-menu {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 1px;
        }

        .nav-menu ul {
            list-style: none;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #FFC107;
        }

        .nav-menu a i {
            margin-right: 0.8rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        footer {
            background-color: white;
            color: var(--text-color);
            text-align: center;
            padding: 1rem;
            border-top: 1px solid var(--border-color);
            margin-top: auto;
        }

        footer a {
            color: var(--primary-color);
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1000;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header-banner {
                left: 0;
            }

            .mobile-toggle {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
                background: var(--primary-color);
                color: white;
                padding: 0.5rem;
                border-radius: 4px;
                cursor: pointer;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-graduation-cap"></i>
            <h1>Hệ thống quản lý giáo viên</h1>
        </div>
        <nav class="nav-menu">
            <div class="nav-section">
                <div class="nav-section-title">Quản lý chính</div>
                <ul>
                    <li><a href="/chattt/giaovien/index.php"><i class="fas fa-home"></i>Trang chủ</a></li>
                    <li><a href="/chattt/giaovien/danhsach_cauhoi_giaovien.php"><i class="fas fa-question-circle"></i>Danh sách câu hỏi</a></li>
                    <li><a href="/chattt/giaovien/danhsach_baitest.php"><i class="fas fa-comments"></i>Danh sách bài kiểm tra</a></li>

                </ul>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Tài khoản</div>
                <ul>
                    <?php if (isset($_SESSION['MaGiaoVien'])): ?>
                        <li><a href="/chattt/giaovien/logout.php"><i class="fas fa-sign-out-alt"></i>Đăng xuất</a></li>
                    <?php else: ?>
                        <li><a href="/chattt/giaovien/login.php"><i class="fas fa-sign-in-alt"></i>Đăng nhập</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>

    <div class="header-banner">
        <div class="header-overlay">
            <div class="header-content">
                <h1>Hệ Thống Quản Lý Giáo Viên</h1>
                <p>Nền tảng hỗ trợ giảng dạy và tương tác với học sinh</p>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="mobile-toggle">
            <i class="fas fa-bars"></i>
        </div>
        <main>
            <?php if (isset($content)) echo $content; ?>
        </main>
        <footer>
            <p>&copy; 2024 Hệ thống quản lý giáo viên. Được thiết kế bởi <a href="#">Your Company</a></p>
        </footer>
    </div>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>

</html>