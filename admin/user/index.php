<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$sql = "SELECT table_user.*,
		GROUP_CONCAT(table_role.role_name) AS user_roles,
		GROUP_CONCAT(DISTINCT table_channel.channel_name) AS user_channels
		FROM table_user_role
		RIGHT JOIN table_user ON table_user_role.user_role_user_id = table_user.user_id
		LEFT JOIN table_user_channel ON table_user_channel.user_channel_user_id = table_user.user_id
		LEFT JOIN table_channel ON table_channel.channel_id = table_user_channel.user_channel_channel_id
		LEFT JOIN table_role ON table_user_role.user_role_role_id = table_role.role_id
		GROUP BY table_user.user_id";
$stmt = $db->prepare($sql);
$stmt->execute();
$recordset = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlerteMNS | Liste des utilisateurs</title>
    <link rel="icon" href="../../images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../sass/main.css">
</head>

<body>
    <main class="admin-content">
        <div class="page-header">
            <h1>Liste des utilisateurs</h1>
            <button class="button add-button">
                <a href="form.php" title="Ajouter un utilisateur">
                    <img src="../../images/add.png" alt="plus">
                    Ajouter
                </a>
            </button>
        </div>

        <table>
            <caption>liste des utilisateurs</caption>
            <hr>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Email</th>
                <th scope="col">Création</th>
                <th scope="col">Rôles</th>
                <th scope="col">Canaux</th>
                <th scope="col">Supprimer</th>
                <th scope="col">Modifier</th>
            </tr>

            <?php foreach ($recordset as $row) { ?>
                <tr>
                    <td>
                        <?= $row["user_id"]; ?>
                    </td>
                    <td>
                        <?= $row["user_lastname"]; ?>
                    </td>
                    <td>
                        <?= $row["user_firstname"]; ?>
                    </td>
                    <td>
                        <?= $row["user_email"]; ?>
                    </td>
                    <td>
                        <?= $row["user_creation_date"]; ?>
                    </td>
					<!-- Liste déroulante des rôles s'il l'utilisateur en a -->
					<?php
					if (isset($row['user_roles'])) {
					?>
                    <td>
                        <select name="roles">
                            <?php
                            foreach (explode(',', $row['user_roles']) as $role) {
                                ?>
                                <option value="<?= $role ?>">
                                    <?= $role ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
					<?php
					} else {
					?>
					<td>Aucun</td>
					<?php
					}
					?>
					<!-- Liste déroulante des canaux auxquels l'utilisateur a accé (s'il en a) -->
					<?php
					if (isset($row['user_channels'])) {
					?>
                    <td>
                        <select name="channels">
                            <?php
                            foreach (explode(',', $row['user_channels']) as $channel) {
                                ?>
                                <option value="<?= $channel ?>">
                                    <?= $channel ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
					<?php
					} else {
					?>
					<td>Aucun</td>
					<?php
					}
					?>
                    <td>
                        <a href="delete.php?id=<?= $row["user_id"]; ?>" title="Supprimer le rôle" class="temp">
                            <img src="../../images/parameters/bin.png" alt="poubelle" title="supprimer l'utilisateur">
                        </a>
                    </td>
                    <td>
                        <a href="form.php?id=<?= $row["user_id"]; ?>" title="Modifier le rôle" class="temp">
                            <img src="../../images/parameters/pen.png" alt="stylo" title="modifier l'utilisateur">
                        </a>
                    </td>
                </tr>
                <?php
            } ?>
        </table>
    </main>
</body>

</html>