/* ==================== VARIABLES ==================== */

let isOpen = false;
let isMenuAnimating = false;
let animationsInProgress = 0;
let lastAnimationTime = 0;

/* ==================== MENU BURGER ==================== */

export function openCloseMenu(event, menu, burgerMenu) {
    event.stopPropagation();

    if (isMenuAnimating) {
        return;
    }

    isOpen = !isOpen;
    isMenuAnimating = true;

    if (isOpen) {
        menu.classList.add('show');
        menu.style.animationName = 'slideDown';
        burgerMenu.style.transform = 'rotate(1turn)';
        setTimeout(() => {
            isMenuAnimating = false;
        }, 500);
    } else {
        menu.style.animationName = 'slideUp';
        burgerMenu.style.transform = 'rotate(0deg)';

        setTimeout(() => {
            menu.classList.remove('show');
            isMenuAnimating = false;
        }, 500);
    }
}

export function ifOpenMenu(menu, burgerMenu) {
    if (isOpen) {
        if (window.innerWidth <= 980) {
            menu.style.animationName = 'slideUp';
            burgerMenu.style.transform = 'rotate(0deg)';

            isOpen = false;

            setTimeout(() => {
                menu.classList.remove('show');
                isMenuAnimating = false;
            }, 500);
        }
    }
}

/* ==================== PARTICLES ==================== */

export function startParticleAnimation(event, color) {
    const currentTime = new Date().getTime();

    if (currentTime - lastAnimationTime >= 150) {
        lastAnimationTime = currentTime;
        const particleContainer = document.getElementById('particle-container');
        const explosionRadius = 100;
        const time = 1000;
        const numberParticles = 50;

        for (let i = 0; i < numberParticles; i++) {
            const angle = Math.random() * 2 * Math.PI;
            const distance = Math.random() * explosionRadius;
            const particle = document.createElement('div');
            particle.className = 'particle';

            particle.style.left = event.clientX + distance * Math.cos(angle) + 'px';
            particle.style.top = event.clientY + distance * Math.sin(angle) + 'px';

            const randomColor = getRandomColor(color);
            particle.style.backgroundColor = randomColor;
            particleContainer.appendChild(particle);

            animationsInProgress++;

            particle.animate(
                [
                    { transform: 'translate(0, 0)', opacity: 1 },
                    { transform: 'translate(' + (distance * Math.cos(angle)) + 'px, ' + (distance * Math.sin(angle)) + 'px)', opacity: 0 }
                ],
                {
                    duration: Math.random() * 2 + 1 * time,
                    easing: 'ease-out',
                    fill: 'forwards'
                }
            ).onfinish = () => {
                animationsInProgress--;
                if (animationsInProgress === 0) {
                    particleContainer.innerHTML = '';
                }
            };
        }
    }
}

function getRandomColor(color) {
    let colors = [];
    switch (color) {
        case "discord":
            colors = ['#5865f2'];
            break;
        case "instagram":
            colors = ['#fdbe57', '#ac34ac', '#ffffff'];
            break;
        case "tik-tok":
            colors = ['#ee1e52', '#69c9d0', '#ffffff'];
            break;
        case "cotillons":
            colors = ["#f9c23c", "#3f5fff", "#00a6ed", "#f70a8d"];
            break;
        case "fc-metz":
            colors = ["#731013"];
            break;
        case "arrow-down":
            colors = ["#1e4940"];
            break;
        default:
            colors = ['#ffffff'];
            break;
    }
    const randomIndex = Math.floor(Math.random() * colors.length);
    return colors[randomIndex];
}

export async function getActualChannelId() {
    return new Promise((resolve, reject) => {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var channelId = response.channelId;
                    resolve(channelId);
                } else {
                    reject("Erreur lors de la récupération de la valeur de la variable de session");
                }
            }
        };
        xhr.open("GET", "/public/scripts/getChannelId.php", true);
        xhr.send();
    });
}

export async function getUserId() {
    return new Promise((resolve, reject) => {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var userId = response.userId;
                    resolve(userId);
                } else {
                    reject("Erreur lors de la récupération de la valeur de la variable de session");
                }
            }
        };
        xhr.open("GET", "/public/scripts/getUserId.php", true);
        xhr.send();
    });
}

export function getChannels() {
    return new Promise((resolve, reject) => {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var channels = JSON.parse(xhr.responseText);
                    resolve(channels); // Résoudre la promesse avec les données des canaux
                } else {
                    reject("Erreur lors de la récupération des canaux");
                }
            }
        };
        xhr.open("GET", "/public/scripts/getChannels.php", true);
        xhr.send();
    });
}

export function renderChannels(channels) {
    var channelsContainer = document.querySelector('.channels');
    var count = 0; // Variable de comptage pour suivre le nombre de canaux ajoutés

    channels.forEach(channel => {
        var channelDiv = document.createElement('div');
        channelDiv.classList.add('channel', 'public-channel');

        // Ajouter un attribut de données avec l'ID du canal
        channelDiv.setAttribute('data-channel-id', channel.channel_id);

        // Créer un conteneur pour le point
        var dotContainer = document.createElement('div');
        dotContainer.classList.add('dot-container');
        var dot = document.createElement('span');
        dot.classList.add('dot');
        dotContainer.appendChild(dot);

        // Créer une balise img pour l'icône
        var iconImg = document.createElement('img');
        iconImg.src = '/images/channel/' + channel.channel_icon;
        iconImg.alt = channel.channel_name; // Correction de la concaténation erronée ici

        // Créer une balise span pour le tiret
        var dashSpan = document.createElement('span');
        dashSpan.classList.add('dash');
        dashSpan.textContent = '–';

        // Créer une balise span pour le nom du canal
        var nameSpan = document.createElement('span');
        nameSpan.classList.add('name');
        nameSpan.textContent = channel.channel_name;

        // Ajouter les éléments au canal
        channelDiv.appendChild(dotContainer);
        channelDiv.appendChild(iconImg);
        channelDiv.appendChild(dashSpan);
        channelDiv.appendChild(nameSpan);

        // Ajouter le canal au conteneur des canaux
        channelsContainer.appendChild(channelDiv);

        // Incrémenter le compteur
        count++;

        // Après le troisième canal, ajout d'un séparateur
        if (count == 3) {
            var separator = document.createElement('hr');
            separator.classList.add('separator');
            channelsContainer.appendChild(separator);
        }
    });
}

export function renderMessages(messages, userId) {
    const messageContainer = document.querySelector('.message-container');
    messageContainer.innerHTML = ''; // Effacer les anciens messages

    messages.forEach(message => {
        const messageDiv = document.createElement('div');

        if (userId == message.message_user_id) {
            messageDiv.classList.add('my-message');
        } else {
            messageDiv.classList.add('others-message');
        }

        messageDiv.classList.add('scroll-section');

        const profilePictureImg = document.createElement('img');
        profilePictureImg.src = './images/profile-user.png';
        profilePictureImg.classList.add('user-profile-picture');
        profilePictureImg.alt = 'Image de profil utilisateur';
        profilePictureImg.setAttribute('data-user-id', message.message_user_id);

        const messageContentDiv = document.createElement('div');
        messageContentDiv.classList.add('message');

        const infoDiv = document.createElement('div');
        infoDiv.classList.add('info');

        if (message.message_user_id === userId) {
            const dateParagraph = document.createElement('p');
            dateParagraph.classList.add('date');
            dateParagraph.textContent = message.message_date;
            infoDiv.appendChild(dateParagraph);
        } else {
            const nameParagraph = document.createElement('p');
            nameParagraph.classList.add('name');
            nameParagraph.textContent = message.user_firstname + " " + message.user_lastname;
            infoDiv.appendChild(nameParagraph);

            const dateParagraph = document.createElement('p');
            dateParagraph.classList.add('date');
            dateParagraph.textContent = message.message_date;
            infoDiv.appendChild(dateParagraph);
        }

        const contentParagraph = document.createElement('p');
        contentParagraph.classList.add('content');
        contentParagraph.textContent = message.message_content;

        messageContentDiv.appendChild(infoDiv);
        messageContentDiv.appendChild(contentParagraph);

        messageDiv.appendChild(profilePictureImg);
        messageDiv.appendChild(messageContentDiv);

        messageContainer.appendChild(messageDiv);
    });
}

function sendMessage(userId, message) {
    console.log(userId, message);
    const messageContainer = document.querySelector('.message-container');
    const messageDiv = document.createElement('div');

    if (userId == message[0]) {
        console.log(userId);
        messageDiv.classList.add('my-message');
    } else {
        console.log(userId);
        messageDiv.classList.add('others-message');
    }

    messageContainer.prepend(messageDiv);
}

export function broadcastMessage(usersIds, message) {
    usersIds.forEach(userId => {
        sendMessage(userId, message);
    });
}