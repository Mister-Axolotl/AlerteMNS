<?php session_start();
if (!isset ($_SESSION['user_connected']) || $_SESSION['user_connected'] != "ok") {
    header("Location:login.php");
    exit();
} else if (isset ($_SESSION['user_role_id'])) {
    $isUserAdmin = false;
    foreach ($_SESSION['user_role_id'] as $user_role_id) {
        if ($user_role_id == 1) {
            $isUserAdmin = true;
            break;
        }
    }
    if (!$isUserAdmin) {
        header("Location:login.php");
        exit();
    }
}