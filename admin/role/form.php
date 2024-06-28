<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protectAdmin.php";
$role_id = 0;
$role_name = "";
$role_badge = "";

if (isset ($_GET['id']) && $_GET['id'] > 0) {
    $sql = "SELECT * FROM table_role WHERE role_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $_GET['id']]);
    if ($row = $stmt->fetch()) {
        $role_id = htmlspecialchars($_GET['id']);
        $role_name = $row["role_name"];
        $role_badge = $row["role_badge"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlerteMNS | Formulaire modification du rôle</title>
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
        <form action="process.php" method="post" enctype="multipart/form-data">
            <!-- enctype sert à envoyer autre chose que du texte -->
            <div class="form-field">
                <label for="role_name">Nom :</label>
                <input type="text" name="role_name" id="role_name" placeholder="Entrez le nom du role"
                    value="<?= $role_name; ?>">
            </div>

            <div class="form-field">
                <label for="role_badge">Badge :</label>
                <input type="file" name="role_badge" id="role_badge"
                    accept="image/png, image/jpeg, image/jpg, image/gif">
            </div>

            <button type="submit" class="button">Valider</button>
            <input type="hidden" name="role_id" value="<?= $role_id; ?>">
        </form>
    </main>
</body>

</html>