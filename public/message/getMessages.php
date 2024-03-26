<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$channelId = $_POST['channelId'];

$sql = "SELECT table_message.*, table_user.user_lastname, table_user.user_firstname FROM table_message
LEFT JOIN table_user ON table_message.message_user_id = table_user.user_id
WHERE message_channel_id = :channelId
ORDER BY message_date
DESC";

$stmt = $db->prepare($sql);
$stmt->execute([':channelId' => $channelId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($messages);