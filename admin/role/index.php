<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$sql = "SELECT * FROM table_role ORDER BY role_name";
$stmt = $db->prepare($sql);
$stmt->execute();
$recordset = $stmt->fetchAll();
$search_query = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlerteMNS | Liste des roles</title>
    <link rel="icon" href="../../images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../sass/main.css">
</head>

<body>
    <main class="admin-content">
        <div class="page-header">
            <h1>Liste des rôles</h1>
            <button class="button add-button">
                <a href="form.php" title="Ajouter un rôle">
                    <img src="../../images/add.png" alt="plus">
                    Ajouter
                </a>
            </button>
        </div>

        <table>
            <caption>liste des rôles</caption>
            <hr>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nom</th>
                <th scope="col">Supprimer</th>
                <th scope="col">Modifier</th>
            </tr>

            <?php foreach ($recordset as $row) { ?>
                <tr>
                    <td>
                        <?= $row["role_id"]; ?>
                    </td>
                    <td>
                        <?= $row["role_name"]; ?>
                    </td>
                    <td>
                        <a href="delete.php?id=<?= $row["role_id"]; ?>" title="Supprimer le rôle" class="temp">
                            <img src="../../images/parameters/bin.png" alt="poubelle" title="supprimer le rôle">
                        </a>
                    </td>
                    <td>
                        <a href="form.php?id=<?= $row["role_id"]; ?>" title="Modifier le rôle" class="temp">
                            <img src="../../images/parameters/pen.png" alt="stylo" title="modifier le rôle">
                        </a>
                    </td>
                </tr>
                <?php
            } ?>
        </table>
    </main>
</body>

</html>