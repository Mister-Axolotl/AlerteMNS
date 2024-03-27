<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
$uploadPath = $_SERVER['DOCUMENT_ROOT'] . "/upload/";

function showError($error)
{
    ?>
    <a href="index.php" title="Retour">Retour</a>
    <br>
    <br>
    <h1>
        <?= $error ?>
    </h1>
    <?php
}

if (!isset ($_POST['user_lastname'])) {
    showError("Il faut passer par le formulaire pour aller sur cette page");
    exit();
}

function generatePassword()
{
    $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $password = "";
    $combLen = strlen($comb) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $combLen);
        $password .= $comb[$n];
    }
    $password .= "GMLP";
    return $password;
}

// ON VERIFIE SI UN ROLE A BIEN ETE DONNE
$sql = "SELECT role_id, role_name FROM table_role";
$stmt = $db->prepare($sql);
$stmt->execute();
$roles = $stmt->fetchAll();

$hasARole = false;

foreach ($roles as $role) {
    if (isset ($_POST[$role['role_name']])) {
        $hasARole = true;
        break;
    }
}

if (!$hasARole) {
    echo (
        '<script>alert("Un utilisateur doit avoir minimum un rôle.")</script>
		<a href="../user/index.php">Retourner sur la liste</a>'
    );
    exit();
}

// ON VERIFIE SI L'UTILISATEUR A ACCES A AU MOINS UN CANAL
$sql = "SELECT channel_id, channel_name FROM table_channel";
$stmt = $db->prepare($sql);
$stmt->execute();
$channels = $stmt->fetchAll();

$hasAChannel = false;

foreach ($channels as $channel) {
    if (isset ($_POST[$channel['channel_name']])) {
        $hasAChannel = true;
        break;
    }
}

if (!$hasAChannel) {
    echo (
        '<script>alert("Un utilisateur doit avoir accès au minimum à un canal.")</script>
		<a href="../user/index.php">Retourner sur la liste</a>'
    );
    exit();
}

// UPDATE / CREATE USER
if (isset ($_POST['user_id']) && $_POST['user_id'] > 0) {
    $sql = "UPDATE table_user 
			SET user_lastname = :lastname,
				user_firstname = :firstname,
				user_email = :email
			WHERE user_id=:id";
} else {
    $password = generatePassword();
    // TODO : Donner le mot de passe à l'administrateur
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO table_user (user_lastname, user_firstname, user_email, user_password, user_creation_date)
    VALUES (:lastname, :firstname, :email, '$password_hash', NOW())";
}

$stmt = $db->prepare($sql);

if (isset ($_POST['user_id']) && $_POST['user_id'] > 0) {
    $stmt->bindParam(':id', $_POST['user_id']);
}

if (isset ($_POST['user_lastname'])) {
    $stmt->bindParam(':lastname', $_POST['user_lastname']);
}

if (isset ($_POST['user_firstname'])) {
    $stmt->bindParam(':firstname', $_POST['user_firstname']);
}

if (isset ($_POST['user_email'])) {
    $stmt->bindParam(':email', $_POST['user_email']);
}

$stmt->execute();
if (isset ($_POST['user_id']) && $_POST['user_id'] > 0) {
    $user_id = htmlspecialchars($_POST['user_id']);
} else {
    $user_id = $db->lastInsertId();
}

// AJOUT ROLES-USER DANS LA BDD
if (isset ($_POST['user_id']) && $_POST['user_id'] > 0) {
    $sql = "DELETE FROM table_user_role
			WHERE user_role_user_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
}
foreach ($roles as $role) {
    if (isset ($_POST[$role['role_name']])) {
        $sql = "INSERT INTO table_user_role (user_role_user_id, user_role_role_id)
		VALUES (:user_id, :role_id)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':role_id', $role['role_id']);
        $stmt->execute();
    }
}

// AJOUT CHANNELS-USER DANS LA BDD
if (isset ($_POST['user_id']) && $_POST['user_id'] > 0) {
    $sql = "DELETE FROM table_user_channel
			WHERE user_channel_user_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
}
foreach ($channels as $channel) {
    if (isset ($_POST[$channel['channel_name']])) {
        $sql = "INSERT INTO table_user_channel (user_channel_user_id, user_channel_channel_id)
		VALUES (:user_id, :channel_id)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':channel_id', $channel['channel_id']);
        $stmt->execute();
    }
}

header("Location:index.php");