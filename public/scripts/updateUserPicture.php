<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$uploadPath = $_SERVER['DOCUMENT_ROOT'] . "/upload/";
$prefix = "sm_";

function generateFileName($str, $ext, $uploadPath, $prefix)
{
    $result = $str;
    $result = strtolower($result);
    $pattern = array(' ', 'é', 'è', 'ë', 'ê', 'á', 'à', 'ä', 'â', 'å', 'ã', 'ó', 'ò', 'ö', 'ô', 'õ', 'í', 'ì', 'ï', 'ú', 'ù', 'ü', 'û', 'ý', 'ÿ', 'ø', 'œ', 'ç', 'ñ', 'ß', 'ț', 'ș', 'ř', 'ž', 'á', 'č', 'ď', 'é', 'ě', 'í', 'ň', 'ó', 'ř', 'š', 'ť', 'ú', 'ů', 'ý', 'ž');
    $replace = array('-', 'e', 'e', 'e', 'e', 'a', 'a', 'a', 'a', 'a', 'a', 'o', 'o', 'o', 'o', 'o', 'i', 'i', 'i', 'u', 'u', 'u', 'u', 'y', 'y', 'o', 'ae', 'c', 'n', 'ss', 't', 's', 'r', 'z', 'a', 'c', 'd', 'e', 'e', 'i', 'n', 'o', 'r', 's', 't', 'u', 'u', 'y', 'z');
    $result = str_replace($pattern, $replace, $result);

    $i = 1;
    while (file_exists($uploadPath . $prefix . $result . ($i > 1 ? "(" . $i . ")" : "") . "." . $ext)) {
        $i++;
    }

    if ($i > 1) {
        $result .= "(" . $i . ")";
    }

    return $result;
}

if (isset($_FILES['user_picture']) && $_FILES['user_picture']['name'] != "") {

    // Vérifier s'il y a une erreur
    if ($_FILES['user_picture']['error'] != 0) {
        echo("Erreur lors du transfert de l'image");
        exit();
    }

    // Renomme l'image

    $extension = pathinfo($_FILES['user_picture']['name'], PATHINFO_EXTENSION);
    $filename = generateFileName($_SESSION['user_name'], $extension, $uploadPath, $prefix);
    move_uploaded_file(
        $_FILES['user_picture']['tmp_name'],
        $uploadPath . $filename . "." . $extension
    );

    // Requête pour ajouter l'image dans la BDD
	// On récupère l'ancien nom de l'image pour la supprimer du dossier upload quand la nouvelle image aura été ajoutée au dossier
    $sql = "SELECT user_picture FROM table_user
			WHERE user_id = :user_id;
			UPDATE table_user 
			SET user_picture = :user_picture 
    		WHERE user_id = :user_id";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(":user_picture", $filename . "." . $extension); // Value prend la valeur là où on la déclare Param prend en compte les modifications
    $stmt->bindValue(":user_id", $_SESSION['user_id']);
    $stmt->execute();

	$oldFileName = $stmt->fetch();

    $filename = $filename . "." . $extension;

    // Création de l'image

    $tabTailles = [
        ["prefix" => $prefix, "largeur" => 150, "hauteur" => 150],
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
                $sql = "UPDATE table_user SET user_picture=null
                WHERE user_id = :user_id";

                $stmt = $db->prepare($sql);
                $stmt->bindValue(":user_id", $_SESSION['user_id']);
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
                imagegif($imgDest, $uploadPath . $imgPrefix . $filename);
                imagegif($imgDest, $uploadPath . $filename);
                break;
            case "png":
                imagepng($imgDest, $uploadPath . $imgPrefix . $filename, 5, PNG_ALL_FILTERS);
                imagepng($imgDest, $uploadPath . $filename);
                break;
            case "jpg":
            case "jpeg":
                imagejpeg($imgDest, $uploadPath . $imgPrefix . $filename, 97);
                imagejpeg($imgDest, $uploadPath . $filename);
                break;
        }
    }

    // Suppression de l'image source et l'ancienne image de profil

    unlink($uploadPath . $filename);
	if ($oldFileName[0] != "" && file_exists($uploadPath . $prefix . $oldFileName[0])) {
		unlink($uploadPath . $prefix . $oldFileName[0]);
	}
}

header("Location:/parametres.php");