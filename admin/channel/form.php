<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
$channel_id = 0;
$channel_name = "";
$channel_description = "";
$channel_icon = "";

if (isset ($_GET['id']) && $_GET['id'] > 0) {
    $sql = "SELECT * FROM table_channel WHERE channel_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $_GET['id']]);
    if ($row = $stmt->fetch()) {
        $channel_id = htmlspecialchars($_GET['id']);
        $channel_name = $row["channel_name"];
        $channel_description = $row["channel_description"];
        $channel_icon = $row["channel_icon"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlerteMNS | Formulaire modification du canal</title>
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
                <label for="channel_name">Nom :</label>
                <input type="text" name="channel_name" id="channel_name" placeholder="Entrez le nom du canal"
                    value="<?= $channel_name; ?>">
            </div>
            <div class="form-field">
                <label for="channel_description">Description :</label>
                <input type="text" name="channel_description" id="channeldescriptione"
                    placeholder="Entrez la description du canal" value="<?= $channel_description; ?>">
            </div>

            <div class="form-field">
                <label for="channel_icon">Icône :</label>
                <input type="file" name="channel_icon" id="channel_icon"
                    accept="image/png, image/jpeg, image/jpg, image/gif">
            </div>

            <button type="submit" class="button">Valider</button>
            <input type="hidden" name="channel_id" value="<?= $channel_id; ?>">
        </form>
    </main>
</body>

</html>