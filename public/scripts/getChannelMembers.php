<?php 
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$channelId = $_SESSION['user_channel_active_id'];

$sql = "SELECT user_id, user_firstname, user_lastname, user_picture FROM table_user
		INNER JOIN table_user_channel ON table_user.user_id = table_user_channel.user_channel_user_id
		WHERE table_user_channel.user_channel_channel_id = $channelId
		ORDER BY user_lastname ASC";
$stmt = $db->query($sql);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($members);