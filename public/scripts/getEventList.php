<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM table_event
		WHERE table_event.event_id IN (
			SELECT CONCAT(table_user_event.user_event_event_id)
			FROM table_user_event
			WHERE table_user_event.user_event_user_id = $user_id
		)";
$stmt = $db->query($sql);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($events);