<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$userId = $_SESSION['user_id'];

echo json_encode(['userId' => $userId]);