<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$message = $_POST['message'];
$channelId = $_POST['channelId'];

$sql = "INSERT INTO table_message (message_content, message_date, message_channel_id, message_user_id)
        VALUES (:content, NOW(), :channel_id, :user_id)";
$stmt = $db->prepare($sql);
$stmt->execute([
    ":content" => $message,
    ":channel_id" => $channelId,
    ":user_id" => $_SESSION['user_id']
]);

// Récupérer le dernier message inséré
$sql = "SELECT * FROM table_message WHERE message_channel_id = :channel_id ORDER BY message_id DESC LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute([":channel_id" => $channelId]);
$lastMessage = $stmt->fetch(PDO::FETCH_ASSOC);

function getConnectedUsers()
{
    $file = "../../connected_users.json";
    if (file_exists($file)) {
        $currentData = file_get_contents($file);
        $data = json_decode($currentData, true);
        return $data;
    }
}

$connectedUsers = getConnectedUsers();

$usersIds = array_keys($connectedUsers);
$newData = array(
    'userIds' => array(),
    'message' => array()
);

$newData['message'][0] = "$_SESSION[user_id]";
$newData['message'][1] = "$lastMessage[message_content]";
$newData['message'][2] = "$lastMessage[message_date]";

foreach ($connectedUsers as $userId => $userTimestamp) {
    if ($channelId == $_SESSION['user_channel_active_id']) {
        $newData['userIds'][] = $userId;
    } else {
        //TODO pastille bleue
    }
}

$newDataJson = json_encode($newData);
echo $newDataJson;