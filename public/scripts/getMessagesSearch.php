<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$channelId = $_SESSION['user_channel_active_id'];

if (isset($_POST['keyWord'])) {
    $sql = "SELECT table_message.message_content, table_message.message_date, table_user.user_lastname, table_user.user_firstname, table_user.user_picture FROM `table_message`
			INNER JOIN table_user ON table_message.message_user_id = table_user.user_id
			WHERE message_content LIKE :keyWord
			AND message_channel_id = :channel_id";
    $stmt = $db->prepare($sql);
    $stmt->execute([":keyWord" => "%" . $_POST['keyWord'] . "%", ":channel_id" => $channelId]);

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($messages) {
        // Si des données sont trouvées, les encoder en JSON et les renvoyer
        echo json_encode($messages);
    } else {
        // Si aucun résultat n'est trouvé, renvoyer un message d'erreur
        echo json_encode(["error" => "Aucun message pour ce(s) mot(s) clef(s)"]);
    }
}