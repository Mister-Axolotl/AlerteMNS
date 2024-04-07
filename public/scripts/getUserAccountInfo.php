<?php 
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$userId = $_SESSION['user_id'];

$sql = "SELECT user_lastname, user_firstname, user_picture, user_email FROM table_user
		WHERE user_id = $userId";
$stmt = $db->query($sql);
$userAccount = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($userAccount);