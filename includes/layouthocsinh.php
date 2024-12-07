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

        /* Chatbox Styles */
        .chatbox {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            z-index: 1000;
        }

        .chatbox-header {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbox-body {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
        }

        .chat-message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
            max-width: 80%;
        }

        .user-message {
            background: #e3f2fd;
            margin-left: auto;
        }

        .bot-message {
            background: #f5f5f5;
        }

        .chatbox-input {
            padding: 15px;
            border-top: 1px solid #eee;
        }

        .chat-input-field {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
        }

        .chat-toggle-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
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
    <div class="chatbox" id="chatbox">
        <div class="chatbox-header">
            <h3>Tư vấn ngành nghề</h3>
            <button onclick="toggleChat()" style="background: none; border: none; color: white;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chatbox-body" id="chatBody">
            <!-- Messages will appear here -->
        </div>
        <div class="chatbox-input">
            <input type="text" class="chat-input-field" id="chatInput" placeholder="Nhập câu hỏi của bạn..."
                onkeypress="handleKeyPress(event)">
        </div>
    </div>

    <button class="chat-toggle-btn" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
    </button>

    <script>
        const careerResponses = {
            'công nghệ thông tin': 'Ngành CNTT là một lựa chọn tuyệt vời với nhiều cơ hội việc làm như: Lập trình viên, Kỹ sư phần mềm, Chuyên gia an ninh mạng, Quản trị hệ thống...',
            'kế toán': 'Ngành Kế toán đào tạo các chuyên gia về tài chính, có thể làm việc tại các công ty, ngân hàng hoặc tự mở công ty dịch vụ kế toán.',
            'y tế': 'Ngành Y tế có nhiều chuyên ngành như: Bác sĩ đa khoa, Nha khoa, Dược sĩ, Y tá. Đây là ngành có tính ổn định cao và được xã hội tôn trọng.',
            'kinh doanh': 'Ngành Kinh doanh giúp bạn phát triển kỹ năng quản lý, marketing, và khởi nghiệp. Có thể làm việc trong các lĩnh vực: Quản trị kinh doanh, Marketing, Sales...',
            'giáo dục': 'Ngành Giáo dục đào tạo giáo viên các cấp, có thể làm việc tại các trường học hoặc trung tâm giáo dục.',
            'luật': 'Ngành Luật đào tạo chuyên gia pháp lý, có thể làm việc tại các công ty luật, tòa án, hoặc tư vấn pháp lý độc lập.',
            'du lịch': 'Ngành Du lịch - Khách sạn có nhiều cơ hội trong lĩnh vực dịch vụ, quản lý khách sạn, hướng dẫn viên du lịch...',
            'xây dựng': 'Ngành Xây dựng đào tạo kỹ sư xây dựng, kiến trúc sư, có thể làm việc tại các công ty xây dựng, thiết kế...',
            'truyền thông': 'Ngành Truyền thông bao gồm báo chí, quảng cáo, PR, có nhiều cơ hội trong lĩnh vực media và marketing.',
            'ngoại ngữ': 'Ngành Ngoại ngữ có thể làm phiên dịch, giáo viên ngoại ngữ, hoặc làm việc trong môi trường quốc tế.',
            'lương công nghệ thông tin': 'Lương ngành CNTT dao động từ 8-25 triệu cho người mới ra trường, có thể lên đến 30-50 triệu cho người có 3-5 năm kinh nghiệm. Senior có thể đạt 100 triệu/tháng.',
            'lương kế toán': 'Lương khởi điểm ngành kế toán từ 7-12 triệu, kế toán trưởng có thể đạt 20-35 triệu/tháng. CFO tại các tập đoàn lớn có thể đạt trên 100 triệu/tháng.',
            'lương y tế': 'Bác sĩ mới ra trường lương từ 8-15 triệu, bác sĩ chuyên khoa có thể đạt 30-60 triệu/tháng. Bác sĩ giỏi làm phòng khám tư có thu nhập trên 100 triệu/tháng.',
            'tôi nên học gì': 'Để tư vấn chính xác, bạn nên cho tôi biết: Sở thích của bạn là gì? Điểm mạnh của bạn? Bạn muốn mức lương như thế nào? Bạn thích làm việc trong môi trường nào?',
            'khối a nên học gì': 'Khối A có nhiều lựa chọn như: Công nghệ thông tin, Kỹ thuật, Y khoa, Dược, Kinh tế, Kế toán. Tùy vào điểm số và sở thích, bạn có thể chọn ngành phù hợp.',
            'khối d nên học gì': 'Khối D phù hợp với các ngành: Ngoại ngữ, Quan hệ quốc tế, Du lịch, Kinh tế đối ngoại, Giáo dục, Marketing.',
            'ngành nào dễ xin việc': 'Các ngành dễ xin việc hiện nay: CNTT, Kế toán, Marketing, Logistics, Du lịch - Khách sạn, Y tế. Tuy nhiên quan trọng là phải có kỹ năng và kinh nghiệm thực tế.',
            'ngành nào lương cao': 'Các ngành có mức lương cao: CNTT (đặc biệt là AI, Blockchain), Tài chính - Ngân hàng, Dược phẩm, Bác sĩ chuyên khoa, Kiến trúc sư, Phi công, Chuyên gia tư vấn.',
            'học ngành gì thì tốt': 'Ngành tốt phụ thuộc vào: Sở thích cá nhân, Khả năng học tập, Nhu cầu thị trường, Mức lương mong muốn. Hãy cho tôi biết thêm về bạn để tư vấn chi tiết.',
            'ngành hot hiện nay': 'Các ngành hot hiện nay: AI/Machine Learning, Data Science, Digital Marketing, Logistics, Fintech, E-commerce, Renewable Energy, Healthcare Technology.',
            'ngành nào không nên học': 'Thay vì nói về ngành không nên học, hãy tập trung vào ngành phù hợp với bạn. Mỗi ngành đều có cơ hội riêng nếu bạn giỏi và đam mê.',
            'học ngành gì dễ': 'Không có ngành nào thực sự "dễ". Quan trọng là chọn ngành phù hợp với khả năng và đam mê của bạn. Bạn có thể chia sẻ sở thích để tôi tư vấn cụ thể hơn.',
            'học ngành gì làm việc nhẹ lương cao': 'Thực tế, mọi ngành nghề đều cần nỗ lực để thành công. Tuy nhiên, một số ngành có môi trường làm việc thoải mái: IT, Digital Marketing, Tư vấn, Giảng dạy, Freelancer.',
            'ngành nào phù hợp nữ': 'Mọi ngành nghề đều phù hợp với cả nam và nữ. Tuy nhiên một số ngành có nhiều nữ: Giáo dục, Y tế, Marketing, PR, Nhân sự, Kế toán, Du lịch.',
            'ngành nào phù hợp nam': 'Không có giới hạn về giới tính trong chọn ngành. Một số ngành có nhiều nam: Kỹ thuật, CNTT, Xây dựng, Cơ khí, Điện - Điện tử, Logistics.',
            'học ngành gì ra làm sếp': 'Để trở thành sếp cần kỹ năng lãnh đạo và kinh nghiệm, không phụ thuộc hoàn toàn vào ngành học. Tuy nhiên các ngành liên quan: Quản trị kinh doanh, QTNL, MBA.',
            'ngành nào không lo thất nghiệp': 'Các ngành ít rủi ro thất nghiệp: Y tế, CNTT, Kế toán, Giáo dục, Logistics, Điện - Điện tử. Quan trọng là phải không ngừng học hỏi và cập nhật kiến thức.',
            'ngành nào nhiều việc làm': 'Các ngành có nhiều cơ hội việc làm: CNTT, Marketing, Kế toán, Nhân sự, Du lịch - Khách sạn, Logistics, Giáo dục, Y tế.',
            'ngành nào dễ làm giàu': 'Làm giàu phụ thuộc vào nhiều yếu tố, không chỉ ngành học. Tuy nhiên một số ngành có tiềm năng: Kinh doanh, CNTT, Bất động sản, Tài chính - Chứng khoán, Y tế.',
            'ngành nào có tương lai': 'Các ngành có triển vọng tương lai: AI/Machine Learning, Robotics, Renewable Energy, Biotechnology, Digital Marketing, Fintech, Healthcare Technology.',
            'ngành nào phù hợp người hướng nội': 'Người hướng nội có thể phù hợp với: Lập trình viên, Kế toán, Thiết kế, Nghiên cứu, Viết lách, Phân tích dữ liệu.',
            'ngành nào phù hợp người hướng ngoại': 'Người hướng ngoại thường phù hợp với: Marketing, Sales, PR, Du lịch, Giáo dục, Nhân sự, Quản lý khách sạn.',
            'ngành nào làm việc ổn định': 'Các ngành có tính ổn định cao: Y tế, Giáo dục, Công chức - Viên chức, Kế toán, Ngân hàng, Bảo hiểm.',
            'ngành nào làm remote được': 'Các ngành có thể làm việc từ xa: CNTT, Digital Marketing, Thiết kế, Content Writing, Dịch thuật, Tư vấn online, Giảng dạy trực tuyến.'
        };

        function toggleChat() {
            const chatbox = document.getElementById('chatbox');
            chatbox.style.display = chatbox.style.display === 'none' || chatbox.style.display === '' ? 'flex' : 'none';
            if (chatbox.style.display === 'flex') {
                addBotMessage("Xin chào! Tôi có thể giúp bạn tư vấn về các ngành nghề. Bạn quan tâm đến ngành nào?");
            }
        }

        function addUserMessage(message) {
            const chatBody = document.getElementById('chatBody');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message user-message';
            messageDiv.textContent = message;
            chatBody.appendChild(messageDiv);
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        function addBotMessage(message) {
            const chatBody = document.getElementById('chatBody');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message bot-message';
            messageDiv.textContent = message;
            chatBody.appendChild(messageDiv);
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                const input = document.getElementById('chatInput');
                const message = input.value.trim().toLowerCase();

                if (message) {
                    addUserMessage(input.value);
                    input.value = '';

                    let bestMatch = {
                        key: null,
                        similarity: 0
                    };

                    // Tìm câu trả lời phù hợp nhất dựa trên độ tương đồng
                    for (const [key, value] of Object.entries(careerResponses)) {
                        const similarity = calculateSimilarity(message, key);
                        if (similarity > bestMatch.similarity && similarity >= 0.5) { // Ngưỡng 50%
                            bestMatch = {
                                key: key,
                                similarity: similarity,
                                value: value
                            };
                        }
                    }

                    if (bestMatch.key) {
                        addBotMessage(bestMatch.value);
                    } else {
                        addBotMessage("Xin lỗi, tôi chưa có thông tin về ngành này. Bạn có thể hỏi về các ngành: CNTT, Kế toán, Y tế, Kinh doanh, Giáo dục, Luật, Du lịch, Xây dựng, Truyền thông, Ngoại ngữ.");
                    }
                }
            }
        }

        // Hàm tính độ tương đồng giữa hai chuỗi
        function calculateSimilarity(str1, str2) {
            const words1 = str1.split(' ');
            const words2 = str2.split(' ');
            let matchCount = 0;

            for (const word1 of words1) {
                if (words2.some(word2 => word2.includes(word1) || word1.includes(word2))) {
                    matchCount++;
                }
            }

            return matchCount / Math.max(words1.length, words2.length);
        }
    </script>
</body>

</html>