<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senderId = $_POST['sender_id'];
    $receiverId = $_POST['receiver_id'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO Messages (SenderId, ReceiverId, Message) VALUES (?, ?, ?)");
    $stmt->execute([$senderId, $receiverId, $message]);
}
