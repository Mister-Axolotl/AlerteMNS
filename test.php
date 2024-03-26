<?php
function getConnectedUsers()
{
    $file = "connected_users.json";
    $currentData = file_get_contents($file);
    $data = json_decode($currentData, true);
    return $data;
}

$connectedUsers = getConnectedUsers();

$userIds = array_keys($connectedUsers);

// Afficher les ID des utilisateurs
foreach ($userIds as $userId) {
    echo "User ID: $userId <br>";
}