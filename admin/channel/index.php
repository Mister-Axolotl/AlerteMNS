<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protectAdmin.php";

$sql = "SELECT * FROM table_channel";
$stmt = $db->prepare($sql);
$stmt->execute();
$recordset = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlerteMNS | Liste des canaux</title>
    <link rel="icon" href="../../images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../sass/main.css">
</head>

<body>
    <main class="admin-content">
        <div class="page-header">
            <h1>Liste des canaux</h1>
            <button class="button add-button">
                <a href="form.php" title="Ajouter un utilisateur">
                    <img src="../../images/add.png" alt="plus">
                    Ajouter
                </a>
            </button>
        </div>

        <table>
            <caption>liste des canaux</caption>
            <hr>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nom</th>
                <th scope="col">Description</th>
                <th scope="col">Création</th>
                <th scope="col">Supprimer</th>
                <th scope="col">Modifier</th>
            </tr>

            <?php foreach ($recordset as $row) { ?>
                <tr>
                    <td>
                        <?= $row["channel_id"]; ?>
                    </td>
                    <td>
                        <?= $row["channel_name"]; ?>
                    </td>
                    <td>
                        <?= $row["channel_description"]; ?>
                    </td>
                    <td>
                        <?= $row["channel_creation_date"]; ?>
                    </td>
                    <td>
                        <a href="delete.php?id=<?= $row["channel_id"]; ?>" title="Supprimer le rôle" class="temp">
                            <img src="../../images/parameters/bin.png" alt="poubelle" title="supprimer l'utilisateur">
                        </a>
                    </td>
                    <td>
                        <a href="form.php?id=<?= $row["channel_id"]; ?>" title="Modifier le rôle" class="temp">
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