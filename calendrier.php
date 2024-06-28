<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$user_id = $_SESSION['user_id'];
$errorMessage = "";

// DELETE
if (isset($_POST["delete"]) && $_POST["delete"] == "delete") {
	$sql = "DELETE FROM table_user_event WHERE user_event_event_id = :id;
			DELETE FROM table_event WHERE event_id = :id";
	$stmt = $db->prepare($sql);
	if (isset($_POST["id"])) {
		$stmt->bindParam(':id', $_POST["id"]);
	}
	$stmt->execute();
	$errorMessage = "";
}

// INSERT OR UPDATE
if (isset($_POST["title"]) && isset($_POST["beginAt"]) && htmlspecialchars($_POST["beginAt"] != "null") && isset($_POST["endAt"]) && htmlspecialchars($_POST["endAt"] != "null")) {
	$errorMessage = "";
	// VERIFIER SI UN EVENEMMENT N'EST PAS CHEVAUCHE PAR LE NOUVEAU
	$beginDate = htmlspecialchars($_POST["date"]) . " " . $_POST["beginAt"] . ":00:00";
	$endDate = htmlspecialchars($_POST["date"]) . " " . $_POST["endAt"] . ":00:00";

	$sql = "SELECT COUNT(*) FROM table_event
			WHERE :start < event_end_at
			AND :end > event_begin_at";
	
	if (isset($_POST["id"]) && $_POST["id"] != null) {
		$sql .= " AND event_id != :id";
	}
	
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':start', $beginDate);
	$stmt->bindParam(':end', $endDate);
	if (isset($_POST["id"]) && $_POST["id"] != null) {
		$stmt->bindParam(':id', $_POST["id"]);
	}
	$stmt->execute();

	$count = $stmt->fetch();

	if ($count[0] == "0" && isset($_POST["newForm"])) {
		if ($_POST["newForm"] == 'true') {
			// AJOUT DE L'EVENNEMENT DANS LA BDD (table_event)
			$sql = "INSERT INTO table_event (event_title, event_description, event_begin_at, event_end_at)
					VALUES (:title, :description, :begin_at, :end_at)";
		} else {
			// MISE A  JOUR DE L'EVENEMMENT
			$sql = "UPDATE table_event SET
					event_title = :title,
					event_description = :description,
					event_begin_at = :begin_at,
					event_end_at = :end_at
					WHERE event_id = :id";
		}
		$stmt = $db->prepare($sql);

		$stmt->bindParam(':title', $_POST["title"]);
		$stmt->bindParam(':begin_at', $beginDate);
		$stmt->bindParam(':end_at', $endDate);

		if (isset($_POST["place"])) {
		$stmt->bindParam(':description', $_POST["place"]);
		} else {
		$stmt->bindParam(':description', null);
		}

		if (isset($_POST["id"]) && htmlspecialchars($_POST["id"]) != null) {
			$stmt->bindParam(':id', $_POST["id"]);
		}

		$stmt->execute();
		$eventId = $db->lastInsertId();

		if ($_POST["newForm"] == 'true') {
			// AJOUT DU LIEN ENTRE L'UTILISATEUR ET L'EVENNEMENT (table_user_event)
			$sql = "INSERT INTO table_user_event (user_event_user_id, user_event_event_id)
					VALUES ($user_id, $eventId)";
			$stmt = $db->prepare($sql);
			$stmt->execute();
		}
	} else {
		$errorMessage = "Il y a déjà " . $count[0] . " évènnement(s) sur cet horaire.";
	}
} else if (isset($_POST["date"])) {
	$errorMessage = "Vérifiez que vous avez bien remplis les champs obligatoires";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerte MNS</title>
    <link rel="icon" href="./images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./sass/main.css">
	<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
</head>

<body>
	<header id="header">
        <div class="left-header" id="left-header"></div>
        <div class="right-header">
            <img src="./images/menu.png" alt="icon to open menu" class="icon-header" id="menu-icon">
            <div class="left-header-content">
                <h1 id="channel-name" title="Ouvrir la liste des utilisateurs">
                    <img src="./images/calendar.png" class="channel-image icon-header invert-image-white" alt="Agenda">
                    <div class="channel-name">Agenda</div>
                </h1>
            </div>

            <div class="right-header-content">
                <div class="research-bar">
                    <input type="text" class="bar">
                    <img src="./images/magnifying_glass.png" class="icon-header" id="magnifying-glass" alt="Loupe"
                        title="Ouvrir la barre de recherche">
                </div>
                <button class="button-image">
                    <img src="./images/bell.png" class="icon-header" id="notifications" alt="Cloche"
                        title="Ouvrir le centre de notifications">
                </button>
                <div class="notifications"></div>
            </div>
        </div>
        <div class="research-bar-phone" id="research-bar-phone">
            <img src="./images/magnifying_glass.png" class="icon-header" id="magnifying-glass" alt="Loupe"
                title="Ouvrir la barre de recherche">
            <input type="text">
        </div>
    </header>

    <main>
        <div class="container">

            <!-- MENU CANAL / AGENDA / PARAMETRES-->
            <div class="left-container" id="left-container">
                <!-- CANAUX -->
                <div class="channels">
				<?php if ($errorMessage != "") { ?>
					<div class="error-message">
						<?= $errorMessage; ?>
					</div>
				<?php } ?>
				</div>

                <!--HOME AND PARAMETERS -->
                <div class="calendar-parameters">
					<a href="./index.php" class="calendar-link">
						<button class="calendar-div">
							<div>
								<img src="./images/channel/global.png" class="icon invert-image-black" alt="Home" title="Ouvrir les channels">
							</div>
							<p>Canaux</p>
						</button>
					</a>
                    <div class="parameters-div">
                        <div class="left">
                            <img src="./images/parameters/user.png" id="parameters-user-profil" class="icon"
                                alt="Image de profil">
                        </div>
                        <div class="center">
                            <p id="parameters-name">Prénom Nom</p>
                            <p id="parameters-role">Rôle</p>
                        </div>
                        <div class="right">
                            <a href="./parametres.php">
                                <button>
                                    <img src="./images/parameters/setting.png" class="icon" alt="Engrenage"
                                        title="Ouvrir les paramètres">
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
		
		<!-- CALENDRIER -->
		<div class="right-container">
			<div class="calendar-container">
				<div id='calendar'></div>
				<div id="addEventPopUp">
					<form action="calendrier.php" method="post">
		
						<input type="hidden" name="newForm" id="new-form">
						<input type="hidden" name="delete" id="delete">
						<input type="hidden" name="id" id="id">
		
						<div class="form-field">
							<label for="title">Titre</label>
							<input required type="text" placeholder="Titre" id="title" name="title">
						</div>
		
						<div>
							<label for="date" class="date-label">Date</label>
							<input type="text" name="date" id="date" readonly>

							<div class="hours">
								<!-- Starting hour -->
								<div class="form-field hour">
									<select name="beginAt" id="begin-at">
										<option value="null" selected>-- Heure de début --</option>
										<?php
										for ($i=1; $i < 23; $i++) {
											echo ('<option value="' . $i . '">'.$i.'</option>');
										}
										?>
									</select>
								</div>
								
								<!-- Ending hour -->
								<div class="form-field hour">
									<select name="endAt" id="end-at">
										<option value="null" selected>-- Heure de fin --</option>
										<?php
										for ($i=2; $i < 24; $i++) {
											echo ('<option value="' . $i . '">'.$i.'</option>');
										}
										?>
									</select>
								</div>
							</div>
						</div>

						<!-- 
						<div class="form-field">
							<label for="other-user">Personne impliquée</label>
							<input type="otherUser" id="other-user" class="dots" name="otherUser">
						</div>
						-->
		
						<div class="form-field">
							<label for="place">Lieu</label>
							<input type="place" id="place" class="dots" name="place">
						</div>
		
						<div class="buttons">
							<button type="button" id="cancel-event-button" class="button">Annuler</button>
							<button type="submit" id="delete-event-button" class="button" value="delete" name="delete">Supprimer</button>
							<button type="submit" id="submit-event-button" class="button">Enregistrer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<script type="module" src="./js/main.js"></script>
</body>

</html>
