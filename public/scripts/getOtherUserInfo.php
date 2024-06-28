<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$userId = $_SESSION['user_id'];

if (isset ($_POST['channelName'])) {
	$channelUsersId = explode('-', $_POST['channelName']);

	// on récupère l'id de l'autre utilisateur
	// (puisque le nom du channel est constitué de l'id de l'utilisateur connecté et de celui avec qui il a une conversation, il suffit de prendre l'id qui n'est pas celui de l'utilisateur connecté)
	if ($channelUsersId[0] != $userId) {
		$otherUserId = $channelUsersId[0];
	} else if ($channelUsersId[1] != $userId) {
		$otherUserId = $channelUsersId[1];
	}

	$sql = "SELECT user_firstname, user_lastname, user_picture FROM table_user
			WHERE user_id = :id";
    $stmt = $db->prepare($sql);
	$stmt->bindParam(':id', $otherUserId);
    $stmt->execute();

    $otherUserInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($otherUserInfo) {
        // Si des données sont trouvées, les encoder en JSON et les renvoyer
        echo json_encode($otherUserInfo);
    } else {
        // Si aucun résultat n'est trouvé, renvoyer un message d'erreur
        echo json_encode(["error" => "Aucun utilisateur trouvé avec l'ID spécifié"]);
    }
}