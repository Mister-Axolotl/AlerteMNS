<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$sql = "SELECT * FROM table_channel";
$stmt = $db->query($sql);
$channels = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($channels);