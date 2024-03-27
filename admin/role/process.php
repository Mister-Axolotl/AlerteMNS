<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
$uploadPath = $_SERVER['DOCUMENT_ROOT'] . "/upload/";

function generateFileName($str, $ext, $uploadPath)
{
    $result = $str;
    $result = strtolower($result);
    $pattern = array(' ', 'é', 'è', 'ë', 'ê', 'á', 'à', 'ä', 'â', 'å', 'ã', 'ó', 'ò', 'ö', 'ô', 'õ', 'í', 'ì', 'ï', 'ú', 'ù', 'ü', 'û', 'ý', 'ÿ', 'ø', 'œ', 'ç', 'ñ', 'ß', 'ț', 'ș', 'ř', 'ž', 'á', 'č', 'ď', 'é', 'ě', 'í', 'ň', 'ó', 'ř', 'š', 'ť', 'ú', 'ů', 'ý', 'ž');
    $replace = array('-', 'e', 'e', 'e', 'e', 'a', 'a', 'a', 'a', 'a', 'a', 'o', 'o', 'o', 'o', 'o', 'i', 'i', 'i', 'u', 'u', 'u', 'u', 'y', 'y', 'o', 'ae', 'c', 'n', 'ss', 't', 's', 'r', 'z', 'a', 'c', 'd', 'e', 'e', 'i', 'n', 'o', 'r', 's', 't', 'u', 'u', 'y', 'z');
    $result = str_replace($pattern, $replace, $result);

    $i = 1;
    while (file_exists($uploadPath . $result . ($i > 1 ? " (" . $i . ")" : "") . "." . $ext)) {
        $i++;
    }

    if ($i > 1) {
        $result .= " (" . $i . ")";
    }

    return $result;
}

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

if (!isset ($_POST['role_name'])) {
    showError("Il faut passer par le formulaire pour aller sur cette page");
    exit();
}

if (isset ($_POST['role_id']) && $_POST['role_id'] > 0) {
    $sql = "UPDATE table_role 
    SET role_name = :name
    WHERE role_id=:id";
} else {
    $sql = "INSERT INTO table_role (role_name)
    VALUES (:name)";
}

$stmt = $db->prepare($sql);

if (isset ($_POST['role_id']) && $_POST['role_id'] > 0) {
    $stmt->bindParam(':id', $_POST['role_id']);
}

if (isset ($_POST['role_name'])) {
    $stmt->bindParam(':name', $_POST['role_name']);
}

$stmt->execute();

if (isset ($_FILES['role_badge']) && $_FILES['role_badge']['name'] != "") {

    // Vérifier s'il y a une erreur
    if ($_FILES['role_badge']['error'] != 0) {
        showError("Erreur lors du transfert de l'image");
        exit();
    }

    $role_id = $db->lastInsertId();

    $sql = "SELECT role_badge FROM table_role
    WHERE role_id = :role_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":role_id", $_POST['role_id'] > 0 ? $_POST['role_id'] : $role_id);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        if ($row['role_badge'] != "" && file_exists($uploadPath . $row['role_badge'])) {
            unlink($uploadPath . $row['role_badge']);
        }
    }

    // Renomme l'image

    $extension = pathinfo($_FILES['role_badge']['name'], PATHINFO_EXTENSION);
    $filename = generateFileName($_POST['role_name'], $extension, $uploadPath);
    move_uploaded_file(
        $_FILES['role_badge']['tmp_name'],
        $uploadPath . $filename . "." . $extension
    );

    // Requête pour ajouter l'image dans la BDD

    $sql = "UPDATE table_role SET role_badge=:role_badge 
    WHERE role_id = :role_id";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(":role_badge", $filename . "." . $extension); // Value prend la valeur là où on la déclare Param prend en compte les modifications
    $stmt->bindValue(":role_id", $_POST['role_id'] > 0 ? $_POST['role_id'] : $role_id);
    $stmt->execute();

    $filename = $filename . "." . $extension;

    // Création de l'image

    $tabTailles = [
        ["prefix" => "sm", "largeur" => 150, "hauteur" => 150],
    ];

    foreach ($tabTailles as $taille) {
        // Traitement d'image

        switch (strtolower($extension)) {
            case "gif":
                $imgSource = imagecreatefromgif($uploadPath . $filename);
                break;
            case "png":
                $imgSource = imagecreatefrompng($uploadPath . $filename);
                break;
            case "jpg":
            case "jpeg":
                $imgSource = imagecreatefromjpeg($uploadPath . $filename);
                break;
            default:
                unlink($uploadPath . $filename);
                showError("Format de fichier non autorisé");
                $sql = "UPDATE table_role SET role_badge=null
                WHERE role_id = :role_id";

                $stmt = $db->prepare($sql);
                $stmt->bindValue(":role_id", $_POST['role_id'] > 0 ? $_POST['role_id'] : $role_id);
                $stmt->execute();
                exit();
        }

        $sizes = getimagesize($uploadPath . $filename);
        $imgSourceLargeur = $sizes[0];
        $imgSourceHauteur = $sizes[1];

        $imgPrefix = $taille['prefix'];
        $imgDestLargeur = $taille['largeur'];
        $imgDestHauteur = $taille['hauteur'];
        $imageSourceZoneX = 0;
        $imageSourceZoneY = 0;
        $imgSourceZoneLargeur = $imgSourceLargeur;
        $imgSourceZoneHauteur = $imgSourceHauteur;

        if ($imgDestLargeur == $imgDestHauteur) {
            // Crop
            if ($imgSourceLargeur > $imgSourceHauteur) {
                // format paysage
                $imageSourceZoneX = ($imgSourceLargeur - $imgSourceHauteur) / 2;
                $imgSourceZoneLargeur = $imgSourceHauteur;
            } else {
                // format portrait
                $imageSourceZoneY = ($imgSourceHauteur - $imgSourceLargeur) / 2;
                $imgSourceZoneHauteur = $imgSourceLargeur;
            }
        } else {
            // Resize
            if ($imgSourceLargeur > $imgSourceHauteur) {
                // format paysage
                $imgDestHauteur = ($imgSourceHauteur * $imgDestLargeur) / $imgSourceLargeur;
            } else {
                // format portrait
                $imgDestLargeur = ($imgSourceLargeur * $imgDestHauteur) / $imgSourceHauteur;
            }
        }

        $imgDest = imagecreatetruecolor($imgDestLargeur, $imgDestHauteur); // Créer une image vierge à la taille souhaitée

        // Transparence
        imagesavealpha($imgDest, true);
        $trans_colour = imagecolorallocatealpha($imgDest, 0, 0, 0, 127);
        imagefill($imgDest, 0, 0, $trans_colour);

        // Copie de l'image source dans l'image vierge
        imagecopyresampled(
            $imgDest,
            $imgSource,
            0,
            0,
            $imageSourceZoneX,
            $imageSourceZoneY,
            $imgDestLargeur,
            $imgDestHauteur,
            $imgSourceZoneLargeur,
            $imgSourceZoneHauteur
        );

        // Création du nouveau fichier

        switch (strtolower($extension)) {
            case "gif":
                imagecolortransparent($imgDest, $trans_colour); // Transparence
                imagegif($imgDest, $uploadPath . $imgPrefix . "_" . $filename);
                imagegif($imgDest, $uploadPath . $filename);
                break;
            case "png":
                imagepng($imgDest, $uploadPath . $imgPrefix . "_" . $filename, 5, PNG_ALL_FILTERS);
                imagepng($imgDest, $uploadPath . $filename);
                break;
            case "jpg":
            case "jpeg":
                imagejpeg($imgDest, $uploadPath . $imgPrefix . "_" . $filename, 97);
                imagejpeg($imgDest, $uploadPath . $filename);
                break;
        }
    }

    // Suppression de l'image source

    unlink($uploadPath . $filename);
}

header("Location:index.php");