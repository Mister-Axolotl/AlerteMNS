<?php 
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$userId = $_SESSION['user_id'];

if (isset($_POST['otherUserId'])) {
	// on regarde s'il existe déjà une conversation entre les deux utilisateurs
	$sql = "SELECT channel_id FROM table_channel
			WHERE table_channel.channel_id IN (
				SELECT table_user_channel.user_channel_channel_id
				FROM table_user_channel
				GROUP BY table_user_channel.user_channel_channel_id
				HAVING COUNT(table_user_channel.user_channel_user_id) = 2
				AND FIND_IN_SET('$userId', GROUP_CONCAT(table_user_channel.user_channel_user_id))
				AND FIND_IN_SET(:otherUserId, GROUP_CONCAT(table_user_channel.user_channel_user_id))
			)
			GROUP BY table_channel.channel_id";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':otherUserId', $_POST['otherUserId']);
	$stmt->execute();
	$channelId = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	// si la conversation n'existe pas, on la crée et on ajoute les utilisateurs dedans
	if ($channelId == []) {
		// création du nouveau canal
		$name = $_POST['otherUserId'] . "-" . $userId;
		$sql = "INSERT INTO table_channel (channel_name, channel_creation_date)
				VALUES (:name, NOW())";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':name', $name);
		$stmt->execute();
		$channelId = $db->lastInsertId();
		
		// Ajout des utilisateurs dans le nouveau canal
		$sql = "INSERT INTO table_user_channel (user_channel_user_id, user_channel_channel_id)
		VALUES ($userId, :channelId), (:otherUserId, :channelId)";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':otherUserId', $_POST['otherUserId']);
		$stmt->bindParam(':channelId', $channelId);
		$stmt->execute();
	}

} else {
	$channelId = false;
}

echo json_encode($channelId);