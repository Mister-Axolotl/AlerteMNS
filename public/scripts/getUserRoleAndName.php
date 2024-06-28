<?php 
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$userId = $_SESSION['user_id'];

$sql = "SELECT user_lastname, user_firstname, user_picture, GROUP_CONCAT(role_name) AS user_roles FROM table_role
		INNER JOIN table_user_role ON table_role.role_id = table_user_role.user_role_role_id
		INNER JOIN table_user ON table_user_role.user_role_user_id = table_user.user_id
		WHERE table_user_role.user_role_user_id = $userId";
$stmt = $db->query($sql);
$userRoleAndName = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($userRoleAndName);