<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống giáo viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1d4ed8;
            --text-color: #1f2937;
            --bg-color: #f3f4f6;
            --border-color: #e5e7eb;
            --sidebar-width: 280px;
            --header-height: 150px;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Header Styles */
        .header-banner {
            height: var(--header-height);
            width: 100%;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            z-index: 10;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.9), rgba(29, 78, 216, 0.9));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 2rem;
        }

        .header-content {
            text-align: center;
            max-width: 800px;
        }

        .header-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            letter-spacing: -0.5px;
        }

        .header-content p {
            font-size: 1.1rem;
            opacity: 0.95;
            font-weight: 300;
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e3a8a, #2563eb);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            color: white;
            padding: 1.5rem 0;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 20;
        }

        .sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }

        .sidebar-header i {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            color: #fbbf24;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .sidebar-header h1 {
            font-size: 1.25rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .nav-menu {
            padding: 0.5rem 0;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            padding: 0.75rem 1.5rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 1.5px;
            font-weight: 500;
        }

        .nav-menu ul {
            list-style: none;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-weight: 400;
        }

        .nav-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #fbbf24;
            transform: translateX(4px);
        }

        .nav-menu a i {
            margin-right: 1rem;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--bg-color);
            min-height: calc(100vh - var(--header-height));
        }

        main {
            flex: 1;
            padding: 1.5rem;
            width: 100%;
        }

        footer {
            background-color: white;
            color: var(--text-color);
            text-align: center;
            padding: 1rem;
            border-top: 1px solid var(--border-color);
            margin-top: auto;
            font-size: 0.9rem;
        }

        footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        footer a:hover {
            color: var(--secondary-color);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 240px;
            }
        }

        @media (max-width: 768px) {
            :root {
                --header-height: 120px;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
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

            .header-content h1 {
                font-size: 1.75rem;
            }

            .header-content p {
                font-size: 1rem;
            }

            main {
                padding: 1rem;
            }

            .mobile-toggle {
                display: flex;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
                background: var(--primary-color);
                color: white;
                padding: 0.5rem;
                border-radius: 8px;
                cursor: pointer;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .mobile-toggle:hover {
                background: var(--secondary-color);
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        main {
            animation: fadeIn 0.3s ease-out;
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
                <p>Nền tảng hiện đại hỗ trợ giảng dạy và tương tác với học sinh một cách hiệu quả</p>
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
            <p>&copy; 2024 Hệ thống quản lý giáo viên. Được phát triển bởi <a href="#">Education Tech</a></p>
        </footer>
    </div>

    <script>
        document.querySelector('.mobile-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.querySelector('.mobile-toggle');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(event.target) &&
                !toggle.contains(event.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>

</html>