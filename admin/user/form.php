<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
$user_id = 0;
$user_lastname = "";
$user_firstname = "";
$user_email = "";
$user_roles = "";
$user_channels = "";
$roles = "";
$channels = "";

if (isset ($_GET['id']) && $_GET['id'] > 0) {
    $sql = "SELECT table_user.*,
	GROUP_CONCAT(table_role.role_name) AS user_roles,
	GROUP_CONCAT(DISTINCT table_channel.channel_name) AS user_channels
	FROM table_user_role
	RIGHT JOIN table_user ON table_user_role.user_role_user_id = table_user.user_id
	LEFT JOIN table_user_channel ON table_user_channel.user_channel_user_id = table_user.user_id
	LEFT JOIN table_channel ON table_channel.channel_id = table_user_channel.user_channel_channel_id
	LEFT JOIN table_role ON table_user_role.user_role_role_id = table_role.role_id
	WHERE user_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $_GET['id']]);
    if ($row = $stmt->fetch()) {
        $user_id = htmlspecialchars($_GET['id']);
        $user_lastname = $row["user_lastname"];
        $user_firstname = $row["user_firstname"];
        $user_email = $row["user_email"];
        $user_roles = $row['user_roles'];
        $user_channels = $row['user_channels'];
    }
}

// ROLES
$sql = "SELECT role_id, role_name FROM table_role";
$stmt = $db->prepare($sql);
$stmt->execute();
$roles = $stmt->fetchAll();

// CHANNELS
$sql = "SELECT channel_id, channel_name FROM table_channel
		WHERE table_channel.channel_id IN (
			SELECT table_user_channel.user_channel_channel_id
			FROM table_user_channel
			GROUP BY table_user_channel.user_channel_channel_id
			HAVING COUNT(table_user_channel.user_channel_user_id) != 2
		)
		GROUP BY table_channel.channel_id";
$stmt = $db->prepare($sql);
$stmt->execute();
$channels = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlerteMNS | Formulaire modification de l'utilisateur</title>
    <link rel="icon" href="../../images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../sass/main.css">
</head>

<body>
    <main>
        <a href="index.php" title="Retour">
            <img src="./images/parameters/left_arrow.png" alt="flèche de retour"
                title="retourner sur la page principale">
            Retour
        </a>
        <form action="process.php" method="post">
            <div class="form-field">
                <label for="user_lastname">Nom :</label>
                <input required type="text" name="user_lastname" id="user_lastname"
                    placeholder="Entrez le nom de l'utilisateur" value="<?= $user_lastname; ?>">
            </div>
            <div class="form-field">
                <label for="user_firstname">Prénom :</label>
                <input type="text" name="user_firstname" id="user_firstname"
                    placeholder="Entrez le prénom de l'utilisateur" value="<?= $user_firstname; ?>">
            </div>
            <div class="form-field">
                <label for="user_email">Email :</label>
                <input required type="email" name="user_email" id="user_email"
                    placeholder="Entrez l'email de l'utilisateur" value="<?= $user_email; ?>">
            </div>
            <div class="roles">
                <?php
                foreach ($roles as $role) {
                    if (str_contains($user_roles, $role['role_name'])) {
                        ?>
                        <div class="form-field">
                            <label for="<?= $role['role_name'] ?>">
                                <?= $role['role_name'] ?>
                            </label>
                            <input type="checkbox" id="<?= $role['role_name'] ?>" name="<?= $role['role_name'] ?>"
                                value="<?= $role['role_id'] ?>" checked>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="form-field">
                            <label for="<?= $role['role_name'] ?>">
                                <?= $role['role_name'] ?>
                            </label>
                            <input type="checkbox" id="<?= $role['role_name'] ?>" name="<?= $role['role_name'] ?>"
                                value="<?= $role['role_id'] ?>">
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
			<div class="form-channels">
                <?php
                foreach ($channels as $channel) {
                    if (str_contains($user_channels, $channel['channel_name'])) {
                        ?>
                        <div class="form-field">
                            <label for="<?= $channel['channel_name'] ?>">
                                <?= $channel['channel_name'] ?>
                            </label>
                            <input type="checkbox" id="<?= $channel['channel_name'] ?>" name="<?= $channel['channel_name'] ?>"
                                value="<?= $channel['channel_id'] ?>" checked>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="form-field">
                            <label for="<?= $channel['channel_name'] ?>">
                                <?= $channel['channel_name'] ?>
                            </label>
                            <input type="checkbox" id="<?= $channel['channel_name'] ?>" name="<?= $channel['channel_name'] ?>"
                                value="<?= $channel['channel_id'] ?>">
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <button type="submit" class="button">Valider</button>
            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
        </form>
    </main>
</body>

</html>