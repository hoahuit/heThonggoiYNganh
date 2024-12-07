<?php
include 'db.php';

$senderId = $_GET['sender_id'];
$receiverId = $_GET['receiver_id'];

$stmt = $conn->prepare("
    SELECT * 
    FROM Messages
    WHERE (SenderId = ? AND ReceiverId = ?) OR (SenderId = ? AND ReceiverId = ?)
    ORDER BY Timestamp ASC
");
$stmt->execute([$senderId, $receiverId, $receiverId, $senderId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $msg) {
    $class = $msg['SenderId'] == $senderId ? 'sent' : 'received';
    echo "<div class='message {$class}'>"
        . "<strong>User {$msg['SenderId']}:</strong> " . htmlspecialchars($msg['Message'])
        . "</div>";
}
