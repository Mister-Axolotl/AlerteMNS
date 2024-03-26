<?php
session_start();
$userId = $_SESSION['user_id'];

removeUserFromJson($userId);

$_SESSION['user_connected'] = "";
$_SESSION['user_name'] = "";
$_SESSION['user_id'] = "";
session_destroy();

function removeUserFromJson($userId)
{
    $file = "connected_users.json";
    $currentData = file_get_contents($file);
    $data = json_decode($currentData, true);

    // Vérifier si l'ID de l'utilisateur existe dans le tableau
    if (is_array($data) && array_key_exists($userId, $data)) {
        unset($data[$userId]);
        file_put_contents($file, json_encode($data));
    }
}

header("Location:login.php");