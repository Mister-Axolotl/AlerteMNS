<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$channelId = $_SESSION['user_channel_active_id'];

echo json_encode(['channelId' => $channelId]);