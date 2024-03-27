<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php"; ?>

<!-- TODO requête si l'utilisateur à au moins un rôle sinon il est redirigé vers une page qui lui dit de contacter
l'administrateur. -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerte MNS</title>
    <link rel="icon" href="./images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./sass/main.css">
</head>

<body>
    <div id="particle-container"></div>
    <header id="header">
        <div class="left-header" id="left-header">
            <div class="conversation-type-div" id="conversation_type">
                <div class="active-background"></div>
                <p class="conversation-type active-type" id="public">Public</p>
                <p class="conversation-type" id="privee">Privé</p>
            </div>
        </div>
        <div class="right-header">
            <img src="./images/menu.png" alt="icon to open menu" class="icon-header" id="menu-icon">
            <div class="left-header-content">
                <h1 id="channel-name" title="Ouvrir la liste des utilisateurs">
                    <img src="./images/channel/newspaper.png" class="channel-image icon-header" alt="Journal">
                    <div class="channel-name">Actualités</div>
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
                    <!--
                    <div class="channel public-channel active-channel">
                        <div class="dot-container">
                            <span class="dot" style="visibility: hidden;"></span>
                        </div>
                        <img src="./images/channel/chat_bubbles.png" alt="Forum icône">
                        <span class="dash">–</span>
                        <span class="name">Forum</span>
                    </div>
                    <hr class="separator">
                    <div class="channel public-channel">
                        <div class="dot-container">
                            <span class="dot"></span>
                        </div>
                        <img src="./images/channel/team.png" alt="Equipe MNS icône">
                        <span class="dash">–</span>
                        <span class="name">Equipe MNS</span>
                    </div>
                    <div class="channel public-channel">
                        <div class="dot-container">
                            <span class="dot"></span>
                        </div>
                        <img src="./images/channel/team.png" alt="Equipe MNS icône">
                        <span class="dash">–</span>
                        <span class="name">Equipe MNS</span>
                    </div>
                    <div class="channel public-channel">
                        <div class="dot-container">
                            <span class="dot"></span>
                        </div>
                        <img src="./images/channel/team.png" alt="Equipe MNS icône">
                        <span class="dash">–</span>
                        <span class="name">Equipe MNS</span>
                    </div>
                    <div class="channel private-channel">
                        <div class="dot-container">
                            <span class="dot"></span>
                        </div>
                        <img src="./images/profile-user.png" alt="Photo profil">
                        <span class="name">Prénom Nom</span>
                    </div> -->
                </div>

                <!-- ADMIN PAGE LINK -->
                <div class="admin-link">
                    <button class="admin-div">
                        <a href="/admin/index.php">
                            <div>
                                <img src="./images/parameters/RGPD.png" class="icon" alt="Administrateur"
                                    title="Ouvrir la page administrateur">
                            </div>
                            <p>Administrateur</p>
                        </a>
                    </button>
                </div>

                <!-- AGENDA AND PARAMETERS -->
                <div class="calendar-parameters">
                    <button class="calendar-div">
                        <div>
                            <img src="./images/calendar.png" class="icon" alt="Calendrier" title="Ouvrir l'agenda">
                        </div>
                        <p>Agenda</p>
                    </button>
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

            <!-- CONVERSATIONS -->
            <div class="right-container" id="right-container">
                <!-- MESSAGES -->
                <div class="message-container"></div>

                <!-- WRITING BAR -->
                <div class="writting-bar-container">
                    <emoji-picker id="emoji-picker" style="display: none;"></emoji-picker>
                    <div class="writting-bar">
                        <div id="options">
                            <button class="button-image">
                                <img src="./images/sondage.png" alt="Statistiques" title="Créer un sondage">
                                <p>Sondage</p>
                            </button>
                            <button class="button-image">
                                <img src="./images/file.png" alt="Fichier" title="Intégrer un fichier">
                                <p>Fichier</p>
                            </button>
                        </div>
                        <button class="button-image" id="messageOptionsButton">
                            <img src="./images/add.png" class="writting-image" id="message-option" alt="Plus"
                                title="Ouvrir plus de fonctionnalités de message">
                        </button>
                        <textarea id="messageInput"></textarea>
                        <button class="button-image">
                            <img id="emoji-option" src="./images/emojis/smile.png" class="writting-image image"
                                alt="Smiley qui souris" title="Ouvrir le menu des émojis">
                        </button>
                    </div>
                    <button id="send-message" class="button-image">
                        <img src="./images/send.png" class="writting-image" alt="Avion en papier"
                            title="Envoyer le message">
                    </button>
                </div>

                <!-- USER INFORMATIONS -->
                <div class="user-infos" id="user-infos">
                    <button class="button-close button-image">
                        <img id="close-image" src="./images/close.png" alt="croix fermeture">
                    </button>
                    <div class="user-infos-header">
                        <img src="./images/profile-user.png" alt="image de profil de nomUtilisateur">
                        <span>Prénom Nom</span>
                    </div>
                    <div class="user-infos-separator">
                        <hr>
                        <span>ROLES</span>
                        <hr>
                    </div>
                    <div class="user-infos-roles">
                        <div class="role">
                            <img class="role-badge" src="./images/Badges/admin.png" alt="administrateur badge">
                            <span>Administrateur</span>
                        </div>
                        <div class="role">
                            <img class="role-badge" src="./images/Badges/admin.png" alt="administrateur badge">
                            <span>Administrateur</span>
                        </div>
                        <div class="role">
                            <img class="role-badge" src="./images/Badges/admin.png" alt="administrateur badge">
                            <span>Administrateur</span>
                        </div>
                        <div class="role">
                            <img class="role-badge" src="./images/Badges/admin.png" alt="administrateur badge">
                            <span>Administrateur</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MENU MEMBRES CANAL -->
            <div class="members-container" id="members-container">
                <div class="members">
                    <!-- <div class="channel member-channel user-profile-picture">
                        <div class="dot-container">
                            <span class="dot"></span>
                        </div>
                        <img src="./images/profile-user.png" alt="Photo profil">
                        <span class="name">Prénom Nom</span>
                    </div> -->
                </div>
            </div>
        </div>
    </main>
    <script type="module" src="./js/main.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
</body>

</html>