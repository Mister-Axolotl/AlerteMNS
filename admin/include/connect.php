<?php
$dbhost = "localhost";
$dbname = "alertemns";
$username = "root";
$password = "";

try {
    $db = new PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname . ";charset=utf8", $username, $password);
} catch (Exception $e) {
    die ("Erreur :" . $e->getMessage());
}