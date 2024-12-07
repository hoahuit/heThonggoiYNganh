<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        #chat-box {
            width: 100%;
            height: 400px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            padding: 10px;
            margin-bottom: 10px;
        }

        .message {
            margin-bottom: 10px;
        }

        .sent {
            text-align: right;
        }

        .received {
            text-align: left;
        }

        #message {
            width: calc(100% - 100px);
            padding: 10px;
        }

        button {
            padding: 10px 20px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h2>Chat Application</h2>
    <div id="chat-box"></div>
    <input type="text" id="message" placeholder="Type your message">
    <button id="send">Send</button>

    <script>
        const senderId = 1; // ID của người gửi
        const receiverId = 2; // ID của người nhận

        function loadMessages() {
            $.get("load_messages.php", {
                sender_id: senderId,
                receiver_id: receiverId
            }, function(data) {
                $("#chat-box").html(data);
                $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
            });
        }

        $("#send").click(function() {
            const message = $("#message").val();
            if (message.trim() !== "") {
                $.post("send_message.php", {
                    sender_id: senderId,
                    receiver_id: receiverId,
                    message: message
                }, function() {
                    $("#message").val(""); // Xóa input sau khi gửi
                    loadMessages(); // Tải lại tin nhắn
                });
            }
        });

        // Tải tin nhắn mỗi giây
        setInterval(loadMessages, 1000);

        // Tải tin nhắn khi bắt đầu
        loadMessages();
    </script>
</body>

</html>