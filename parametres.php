<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alerte MNS</title>
	<link rel="icon" href="./images/logo.png" type="image/x-icon">
	<link rel="stylesheet" href="./sass/main.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css" />
</head>

<body>
	<div class="parameters-content">
		<!-- MENU -->
		<div class="menu-parameters" id="menu-parameters">
			<!-- HEADER -->
			<div class="menu-parameters-header">
				<button>
					<a href="index.php">
						<img class="left-arrow" src="./images/parameters/left_arrow.png" alt="flèche de retour"
							title="retourner aux conversations">
					</a>
				</button>
				<h1>Paramètres</h1>
				<div class="gear-icon">
					<img class="gear" src="./images/parameters/setting.png" alt="engrenage">
				</div>
			</div>

			<!-- CHANNELS -->
			<div class="menu-parameters-channels channels">
				<div class="channel parameter-channel active" title="Mon compte">
					<img src="./images/parameters/user.png" alt="mon compte icône">
					<span class="dash">–</span>
					<span class="name">Mon compte</span>
				</div>
				<div class="channel parameter-channel" title="Un problème ?">
					<img src="./images/parameters/warning.png" alt="danger icône">
					<span class="dash">–</span>
					<span class="name">Un problème ?</span>
				</div>
				<hr class="separator">
				<div class="channel parameter-channel" title="FAQ">
					<img src="./images/parameters/faq.png" alt="FAQ icône">
					<span class="dash">–</span>
					<span class="name">FAQ</span>
				</div>
				<div class="channel parameter-channel" title="Suggestions ?">
					<img src="./images/parameters/question.png" alt="ampoule avec point d'interrogation icône">
					<span class="dash">–</span>
					<span class="name">Suggestions ?</span>
				</div>
				<hr class="separator">
				<div class="channel parameter-channel" title="RGPD">
					<img src="./images/parameters/RGPD.png" alt="bouclier icône">
					<span class="dash">–</span>
					<span class="name">RGPD</span>
				</div>
			</div>

			<!-- DECONNEXION BUTTON -->
			<div class="menu-parameters-deconnexion">
				<a href="./logout.php">
					<button class="button deconnexion">
						Déconnexion
						<img src="./images/parameters/close.png" alt="bouton on/off" title="se déconnecter">
					</button>
				</a>
			</div>
		</div>

		<!-- PAGE CONTENT -->
		<div class="channel-content" id="channel-content">
			<h2>Mon compte</h2>

			<!-- MON COMPTE CONTENT -->
			<div class="my-account">
				<div class="user-firstname">Prénom</div>
				<div class="user-lastname">Nom</div>

				<div class="profile-picture">
					<div class="picture-container">
						<img src="./images/profile-user.png" alt="photo de profil" class="user-profile-picture">
					</div>
					<button class="button-outline" id="modify-profile-picture">
						Mofifier
						<img src="./images/parameters/pen.png" alt="stylo icône">
					</button>
					<form id="submit-picture" action="./public/scripts/updateUserPicture.php" method="post" enctype="multipart/form-data">
						<label for="user-picture">Modifier l'image de profil</label>
						<input type="file" name="user_picture" id="user-picture">
						<button type="submit" class="button-outline">Submit</button>
					</form>
				</div>

				<form>
					<div class="form-field">
						<label for="email">Adresse email</label>
						<input type="text" id="email" name="email" readonly>
					</div>

					<div class="password-to-modify">
						<div class="form-field" id="input-password">
							<label for="password">Mot de passe</label>
							<input type="password" id="password" class="dots" name="password">
							<div id="view-password">
								<img id="eyeIcon" src="images/opened_eye.png" alt="eye icon (to see password)"
									onclick="displayPassword()">
							</div>
						</div>
						<button class="button-outline">
							<img src="./images/parameters/pen.png" alt="stylo icône">
						</button>
					</div>

					<div class="form-field">
						<label for="password2">Verification</label>
						<input type="text" id="password2" name="password2">
					</div>
				</form>
			</div>

			<!-- UN PROBLEME ? & SUGGESTIONS ? -->
			<div class="problem-suggestion">
				<form>
					<div class="form-field">
						<label for="object">Object</label>
						<input required type="text" id="object" name="object">
					</div>

					<div class="form-field">
						<label for="detail" id="description-label">Description</label>
						<textarea required name="detail" id="detail" rows="15"></textarea>
					</div>

					<button type="submit" class="button-send button">Envoyer</button>
				</form>
			</div>

			<!-- FAQ -->
			<div class="faq"></div>

			<!-- RGPD -->
			<div class="rgpd"></div>
		</div>
	</div>
</body>
<script type="module" src="./js/main.js"></script>
<script src="./js/password.js"></script>

</html>