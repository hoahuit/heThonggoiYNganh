<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống học sinh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #357ABD;
            --accent-color: #FF6B6B;
            --text-color: #2C3E50;
            --bg-color: #F8F9FA;
            --nav-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            position: relative;
        }

        header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-align: center;
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        nav {
            padding: 0.5rem 0;
        }

        nav ul {
            list-style-type: none;
            display: flex;
            justify-content: space-between;
            flex-wrap: nowrap;
            gap: 0.5rem;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 0.6rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        main {
            padding: 2rem;
            max-width: 1200px;
            margin: calc(var(--nav-height) + 6rem) auto 8rem auto;
            background-color: white;
            box-shadow: 0 2px 25px rgba(0, 0, 0, 0.06);
            border-radius: 16px;
            min-height: calc(100vh - 400px);
        }

        footer {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-align: center;
            padding: 1rem 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            header h1 {
                font-size: 1.5rem;
            }

            nav ul {
                gap: 0.3rem;
            }

            nav ul li a {
                padding: 0.5rem 0.8rem;
                font-size: 0.8rem;
            }

            main {
                margin: calc(var(--nav-height) + 4rem) 1rem 6rem 1rem;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                padding: 1rem;
            }

            nav ul {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }

            nav ul li a {
                width: 100%;
                text-align: center;
                justify-content: center;
            }

            main {
                padding: 1.5rem;
                margin-top: calc(var(--nav-height) + 8rem);
            }
        }

        /* Animations */
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

        main {
            animation: fadeIn 0.6s ease-out;
        }

        .nav-icon {
            transition: transform 0.3s ease;
        }

        nav ul li a:hover .nav-icon {
            transform: scale(1.2);
        }
    </style>
</head>

<body>
    <header>
        <h1>Hệ thống quản lý học sinh</h1>
        <nav>
            <ul>
                <li><a href="/chattt/hocsinh/index.php"><i class="fas fa-home nav-icon"></i>Trang chủ</a></li>
                <li><a href="/chattt/hocsinh/thongtin.php"><i class="fas fa-user nav-icon"></i>Thông tin cá nhân</a></li>
                <li><a href="/chattt/hocsinh/lichsu.php"><i class="fas fa-history nav-icon"></i>Lịch sử làm bài</a></li>
                <li><a href="/chattt/hocsinh/lambai.php"><i class="fas fa-edit nav-icon"></i>Làm bài kiểm tra</a></li>
                <li><a href="/chattt/hocsinh/nganhdexuat.php"><i class="fas fa-graduation-cap nav-icon"></i>Ngành đề xuất</a></li>
                <li><a href="/chattt/hocsinh/gui_cauhoi.php"><i class="fas fa-question-circle nav-icon"></i>Gửi câu hỏi</a></li>
                <li><a href="/chattt/hocsinh/xem_cauhoi.php"><i class="fas fa-comments nav-icon"></i>Xem câu trả lời</a></li>
                <li><a href="/chattt/hocsinh/logout.php"><i class="fas fa-sign-out-alt nav-icon"></i>Đăng xuất</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php if (isset($content)) echo $content; ?>
    </main>
    <footer>
        <p>&copy; 2024 Hệ thống quản lý học sinh. Bản quyền được bảo lưu.</p>
    </footer>
</body>

</html>