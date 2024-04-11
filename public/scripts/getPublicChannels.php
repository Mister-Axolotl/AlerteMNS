<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM table_channel
		WHERE table_channel.channel_id IN (
			SELECT table_user_channel.user_channel_channel_id
			FROM table_user_channel
			GROUP BY table_user_channel.user_channel_channel_id
			HAVING COUNT(table_user_channel.user_channel_user_id) > 2
			AND FIND_IN_SET('$user_id', GROUP_CONCAT(table_user_channel.user_channel_user_id))
		)
		GROUP BY table_channel.channel_id";
$stmt = $db->query($sql);
$channels = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($channels);