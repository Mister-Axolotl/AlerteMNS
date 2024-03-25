<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$sql = "INSERT INTO table_message (message_content, message_date, message_channel_id, message_user_id)
        VALUES (:content, NOW(), :channel_id, :user_id)";
$stmt = $db->prepare($sql);
$stmt->execute([
    ":content" => $_POST['message'],
    ":channel_id" => 1,
    ":user_id" => 1
]);