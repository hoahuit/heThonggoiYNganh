<?php
include '../includes/db.php';

session_start();
$maHocSinh = $_SESSION['MaHocSinh'];

// Hàm lấy câu trả lời từ chatbot
function getChatbotResponse($userQuestion, $conn)
{
    $stmt = $conn->prepare("SELECT ch.TraLoi, n.TenNganh 
                           FROM CauHoiChatBot ch 
                           LEFT JOIN NganhNghe n ON ch.MaNganh = n.MaNganh 
                           WHERE LOWER(ch.CauHoi) LIKE LOWER(?)");
    $searchTerm = "%$userQuestion%";
    $stmt->execute([$searchTerm]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result['TraLoi'] . ($result['TenNganh'] ? "\n(Liên quan đến ngành: " . $result['TenNganh'] . ")" : "");
    }
    return "Xin lỗi, tôi không tìm thấy câu trả lời phù hợp. Vui lòng thử câu hỏi khác!";
}

// Xử lý AJAX request
if (isset($_POST['question'])) {
    $response = getChatbotResponse($_POST['question'], $conn);
    echo json_encode(['response' => $response]);
    exit;
}

$content = '
<style>
.chatbot-interface {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.chat-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 15px;
    border-radius: 10px 10px 0 0;
    font-weight: bold;
}

.chat-messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #f8f9fa;
}

.message {
    margin-bottom: 15px;
    max-width: 80%;
}

.user-message {
    margin-left: auto;
    background: var(--primary-color);
    color: white;
    padding: 10px 15px;
    border-radius: 15px 15px 0 15px;
}

.bot-message {
    background: white;
    padding: 10px 15px;
    border-radius: 15px 15px 15px 0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.chat-input {
    padding: 15px;
    background: white;
    border-top: 1px solid #dee2e6;
}

.input-group {
    display: flex;
    gap: 10px;
}

#messageInput {
    flex: 1;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    outline: none;
}

#sendButton {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
}

#sendButton:hover {
    background: var(--secondary-color);
}

.suggestions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.suggestion-chip {
    background: #e9ecef;
    padding: 5px 15px;
    border-radius: 15px;
    cursor: pointer;
    font-size: 0.9em;
    transition: all 0.3s ease;
}

.suggestion-chip:hover {
    background: var(--primary-color);
    color: white;
}
</style>

<div class="chatbot-interface">
    <div class="chat-header">
        <i class="fas fa-robot"></i> Trợ lý ảo
    </div>
    <div class="chat-messages" id="chatMessages">
        <div class="message bot-message">
            Xin chào! Tôi là trợ lý ảo. Tôi có thể giúp gì cho bạn?
        </div>
    </div>
    <div class="chat-input">
        <div class="input-group">
            <input type="text" id="messageInput" placeholder="Nhập câu hỏi của bạn...">
            <button id="sendButton"><i class="fas fa-paper-plane"></i></button>
        </div>
        <div class="suggestions">
            <div class="suggestion-chip">Ngành học phù hợp?</div>
            <div class="suggestion-chip">Điểm chuẩn các ngành?</div>
            <div class="suggestion-chip">Cơ hội việc làm?</div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const messageInput = document.getElementById("messageInput");
    const sendButton = document.getElementById("sendButton");
    const chatMessages = document.getElementById("chatMessages");
    const suggestionChips = document.querySelectorAll(".suggestion-chip");

    function addMessage(message, isUser = false) {
        const messageDiv = document.createElement("div");
        messageDiv.className = `message ${isUser ? "user-message" : "bot-message"}`;
        messageDiv.textContent = message;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    async function sendMessage(message) {
        addMessage(message, true);
        
        try {
            const response = await fetch("chatbot.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `question=${encodeURIComponent(message)}`
            });
            
            const data = await response.json();
            addMessage(data.response);
        } catch (error) {
            addMessage("Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.");
        }
    }

    sendButton.addEventListener("click", () => {
        const message = messageInput.value.trim();
        if (message) {
            sendMessage(message);
            messageInput.value = "";
        }
    });

    messageInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            const message = messageInput.value.trim();
            if (message) {
                sendMessage(message);
                messageInput.value = "";
            }
        }
    });

    suggestionChips.forEach(chip => {
        chip.addEventListener("click", () => {
            sendMessage(chip.textContent);
        });
    });
});
</script>
';

include '../includes/layouthocsinh.php';
