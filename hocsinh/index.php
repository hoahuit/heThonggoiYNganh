<?php
session_start();
if (!isset($_SESSION['MaHocSinh'])) {
    header("Location: login.php");
    exit();
}

// Trang chính của học sinh
$content = '
<style>
.welcome-container {
    text-align: center;
    padding: 3rem;
    max-width: 1200px;
    margin: 0 auto;
    background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.8));
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.welcome-header {
    margin-bottom: 4rem;
    animation: fadeInDown 1s ease;
}

.welcome-header h2 {
    font-size: 3em;
    background: linear-gradient(135deg, #2575fc, #6a11cb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1.5rem;
    font-weight: 800;
}

.welcome-header p {
    color: #4a5568;
    font-size: 1.4em;
    line-height: 1.6;
    max-width: 800px;
    margin: 0 auto;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2.5rem;
    margin-top: 4rem;
    animation: fadeInUp 1s ease 0.3s backwards;
}

.feature-card {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    text-align: center;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.feature-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #2575fc22, #6a11cb22);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.feature-card:hover {
    transform: translateY(-10px);
}

.feature-card:hover::before {
    opacity: 1;
}

.feature-icon {
    width: 140px;
    height: 140px;
    margin-bottom: 2rem;
    transition: transform 0.4s ease;
}

.feature-card:hover .feature-icon {
    transform: scale(1.1);
}

.feature-title {
    font-size: 1.5em;
    color: #2d3748;
    margin-bottom: 1.2rem;
    font-weight: 700;
    position: relative;
}

.feature-description {
    color: #4a5568;
    line-height: 1.8;
    font-size: 1.1em;
}

.cta-section {
    margin-top: 5rem;
    text-align: center;
    animation: fadeInUp 1s ease 0.6s backwards;
}

.cta-button {
    display: inline-block;
    padding: 1.2rem 3rem;
    background: linear-gradient(135deg, #2575fc, #6a11cb);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: bold;
    font-size: 1.2em;
    transition: all 0.4s ease;
    box-shadow: 0 5px 20px rgba(37, 117, 252, 0.4);
}

.cta-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(37, 117, 252, 0.5);
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .welcome-container {
        padding: 2rem;
    }
    
    .welcome-header h2 {
        font-size: 2.5em;
    }
    
    .features-grid {
        gap: 2rem;
    }
}
</style>

<div class="welcome-container">
    <div class="welcome-header">
        <h2>Chào mừng, ' . $_SESSION['HoTen'] . '!</h2>
        <p>Khám phá tiềm năng và định hướng tương lai của bạn cùng với hệ thống tư vấn nghề nghiệp thông minh</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <img src="https://cdn-icons-png.flaticon.com/512/2641/2641409.png" alt="Khám phá nghề nghiệp" class="feature-icon">
            <h3 class="feature-title">Khám Phá Nghề Nghiệp</h3>
            <p class="feature-description">Khám phá và tìm hiểu chi tiết về các ngành nghề phù hợp với sở thích, đam mê và năng lực của bạn</p>
        </div>

        <div class="feature-card">
            <img src="https://cdn-icons-png.flaticon.com/512/1535/1535019.png" alt="Đánh giá năng lực" class="feature-icon">
            <h3 class="feature-title">Đánh Giá Năng Lực</h3>
            <p class="feature-description">Thực hiện bài kiểm tra toàn diện để đánh giá chính xác điểm mạnh và tiềm năng phát triển của bạn</p>
        </div>

        <div class="feature-card">
            <img src="https://cdn-icons-png.flaticon.com/512/1940/1940611.png" alt="Tư vấn chuyên sâu" class="feature-icon">
            <h3 class="feature-title">Tư Vấn Chuyên Sâu</h3>
            <p class="feature-description">Nhận tư vấn chi tiết từ đội ngũ chuyên gia giàu kinh nghiệm về định hướng nghề nghiệp tương lai</p>
        </div>
    </div>

    <div class="cta-section">
        <a href="lambai.php" class="cta-button">Bắt đầu hành trình của bạn</a>
    </div>
</div>
';

include '../includes/layouthocsinh.php';
