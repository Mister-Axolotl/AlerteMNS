<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$errorMessage = "";
if (isset ($_POST['login']) && isset ($_POST['password'])) {
    $errorMessage = "Mauvais identifiant ou mot de passe";
    $sql = "SELECT * FROM table_user WHERE user_email = :login";
    $stmt = $db->prepare($sql);
    $stmt->execute([":login" => $_POST['login']]);
    if ($row = $stmt->fetch()) {
        if (password_verify($_POST['password'], $row["user_password"])) {
            session_start();
            $_SESSION['user_connected'] = "ok"; // Pour éviter le hack, il faudrait mettre des valeurs plus complexes
            $_SESSION['user_name'] = $row["user_firstname"];

            // on récupère l'id du rôle de l'utilisateur
			$sql = "SELECT user_role_role_id FROM table_user_role
					INNER JOIN table_user ON table_user_role.user_role_user_id = table_user.user_id";
    		$stmt = $db->prepare($sql);
    		$stmt->execute();

			if ($recordset = $stmt->fetchAll()) {
				$_SESSION['user_role_id'] = [];
				foreach ($recordset as $row) {
					array_push($_SESSION['user_role_id'], $row['user_role_role_id']);
				}
			}

            header("Location:index.html");
            exit(); // Bloque le script 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" href="./images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./sass/main.css">
</head>

<body id="body-connexion">
    <main>
        <form action="login.php" method="post">
            <div class="form-field" id="input-login">
                <label for="login">Identifiant</label>
                <input required type="email" placeholder="adresse mail" id="login" name="login">
            </div>

            <div class="form-field" id="input-password">
                <label for="password">Mot de passe</label>
                <input required type="password" id="password" class="dots" name="password">
                <div id="view-password">
                    <img id="eyeIcon" src="images/opened_eye.png" alt="eye icon (to see password)"
                        onclick="displayPassword()">
                </div>
            </div>

            <?php if ($errorMessage != "") { ?>
                <div class="error-message">
                    <?= $errorMessage; ?>
                </div>
            <?php } ?>

            <button type="submit" id="submit-button" class="button">Connexion</button>
        </form>
    </main>
</body>

</html>
<script src="./js/password.js"></script>