<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protectAdmin.php";

if (isset ($_GET['id']) && $_GET['id'] > 0) {
    $sql = "DELETE FROM table_user_channel WHERE user_channel_channel_id = :id;
			DELETE FROM table_channel WHERE channel_id= :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $_GET["id"]]);
}
header("Location:index.php");