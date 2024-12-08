<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        :root {
            --primary-color: #2196F3;
            --sidebar-bg: #263238;
            --sidebar-hover: #1976D2;
            --header-bg: #37474F;
            --text-light: #ffffff;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        body {
            display: flex;
            background: #f4f6f9;
        }

        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            min-height: 100vh;
            color: var(--text-light);
            transition: all 0.3s;
            box-shadow: var(--shadow);
        }

        .sidebar-header {
            padding: 25px;
            background: var(--header-bg);
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header i {
            font-size: 2.5em;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .sidebar-header h1 {
            font-size: 1.2em;
            font-weight: 500;
        }

        .nav-menu ul {
            list-style: none;
            padding: 0;
        }

        .nav-menu ul li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nav-menu ul li a {
            color: #fff;
            text-decoration: none;
            padding: 18px 25px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .nav-menu ul li a:hover {
            background: var(--sidebar-hover);
            transform: translateX(5px);
        }

        .nav-menu ul li a i {
            margin-right: 15px;
            width: 20px;
            font-size: 1.1em;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            background: #f5f7fa;
        }

        header {
            background: var(--text-light);
            padding: 25px 30px;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        header h2 {
            color: #37474F;
            font-weight: 600;
            font-size: 1.8em;
        }

        main {
            background: var(--text-light);
            padding: 30px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            min-height: calc(100vh - 180px);
        }

        /* Animation for hover effects */
        .nav-menu ul li a {
            transition: all 0.3s ease;
        }

        /* Improved Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .sidebar-header {
                padding: 15px;
            }

            .sidebar-header i {
                font-size: 2em;
                margin-bottom: 0;
            }

            .nav-menu ul li a {
                padding: 20px 15px;
                justify-content: center;
            }

            .nav-menu ul li a i {
                margin-right: 0;
                font-size: 1.3em;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-user-shield"></i>
            <h1>Quản lý Admin</h1>
        </div>
        <nav class="nav-menu">
            <ul>
                <li><a href="/chattt/admin/index.php"><i class="fas fa-home"></i><span>Trang chủ</span></a></li>
                <li><a href="/chattt/admin/quanlytaikhoangiaovien.php"><i class="fas fa-user-tie"></i><span>Quản lý giáo viên</span></a></li>
                <li><a href="/chattt/admin/quanlyhocsinh.php"><i class="fas fa-user-graduate"></i><span>Quản lý học sinh</span></a></li>
                <li><a href="/chattt/admin/quanlynganhnghe.php"><i class="fas fa-book"></i><span>Quản lý ngành nghề</span></a></li>
                <li><a href="/chattt/admin/logout.php"><i class="fas fa-sign-out-alt"></i><span>Đăng xuất</span></a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h2>Hệ thống quản lý Admin</h2>
        </header>
        <main>
            <?php if (isset($content)) echo $content; ?>
        </main>
    </div>
</body>

</html>