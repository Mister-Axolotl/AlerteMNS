<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

if (isset ($_GET['id']) && $_GET['id'] > 0) {
    $sql = "DELETE FROM table_user_role WHERE user_role_role_id = :id;
			DELETE FROM table_role WHERE role_id= :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $_GET["id"]]);
}
header("Location:index.php");