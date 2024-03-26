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
    $file = "connected_users.json";
    $currentData = file_get_contents($file);
    $data = json_decode($currentData, true);
    return $data;
}

$connectedUsers = getConnectedUsers();

$userIds = array_keys($connectedUsers);

foreach ($userIds as $userId) {
    if ($channelId == $_SESSION['user_channel_active_id']) {
        echo "<script>
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Traitement en fonction de la réponse
                    }
                };
                xhttp.open('GET', 'script_js.php', true);
                xhttp.send();
              </script>";
        //TODO faire en sorte que ça envoie un message à tous les userId
    }
}