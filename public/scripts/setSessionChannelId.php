<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

if (isset ($_POST['channelId'])) {
    $_SESSION['user_channel_active_id'] = $_POST['channelId'];
} else {
    echo "Error: channelId parameter is missing!";
}