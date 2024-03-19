<?php session_start();
$_SESSION['user_connected'] = "";
$_SESSION['user_name'] = "";
$_SESSION['user_role_id'] = "";
session_destroy();
header("Location:login.php");