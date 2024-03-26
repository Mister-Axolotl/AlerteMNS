import { openCloseMenu, ifOpenMenu, startParticleAnimation, renderMessages, getUserId, getChannels, renderChannels } from "./functions.js";
import fr from "./fr.js";

document.addEventListener('DOMContentLoaded', function () {

	/* ==================== SMOOTH TRANSITION FOR SECTION ==================== */

	// Fonction pour gérer les changements d'intersection
	function handleIntersection(entries) {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				entry.target.classList.add("active");
			} else {
				entry.target.classList.remove("active");
			}
		});
	}

	// Créer un nouvel observer
	const observer = new IntersectionObserver(handleIntersection, { threshold: 0.15 });

	// Observer un conteneur parent pour les nouvelles sections ajoutées dynamiquement
	const container = document.querySelector(".container");

	// Créer un observateur de mutation pour surveiller les changements dans le conteneur
	const mutationObserver = new MutationObserver((mutations) => {
		mutations.forEach((mutation) => {
			mutation.addedNodes.forEach((node) => {
				if (node.nodeType === 1 && node.classList.contains("scroll-section")) {
					observer.observe(node);
				}
			});
		});
	});

	// Démarrer l'observation des mutations dans le conteneur
	mutationObserver.observe(container, { childList: true, subtree: true });

	/* ==================== WHEN CLICK ON ANCHOR, SMOOTH SCROLL ==================== */

	document.querySelectorAll('a[href^="#"]').forEach(anchor => {
		anchor.addEventListener('click', function (e) {
			e.preventDefault();

			document.querySelector(this.getAttribute('href')).scrollIntoView({
				behavior: 'smooth'
			});
		});
	});

	const particlesButtons = document.querySelectorAll(".particles-button");

	particlesButtons.forEach(button => {
		button.addEventListener('mouseover', function (event) {
			startParticleAnimation(event, button.id);
		});
	});

	/* ==================== CONVERSATION TYPE SWITCH ==================== */

	const activeBackgroundConversation = document.querySelector('.active-background');
	const publicConversation = document.querySelector('#public');
	const priveeConversation = document.querySelector('#privee');

	publicConversation.addEventListener('click', () => {
		toggleBackground('activePublic', 'activePrivate', priveeConversation, publicConversation);
	});

	priveeConversation.addEventListener('click', () => {
		toggleBackground('activePrivate', 'activePublic', publicConversation, priveeConversation);
	});

	function toggleBackground(addClass, removeClass, activeElement, inactiveElement) {
		activeBackgroundConversation.classList.remove(removeClass);
		activeBackgroundConversation.classList.add(addClass);
		activeElement.classList.remove('active-type');
		inactiveElement.classList.add('active-type');
	}

	/* ==================== INVERT DEFAULT IMAGE FOR MENUS ==================== */

	document.querySelectorAll('.private-channel, .member-channel').forEach(channel => {
		let img = channel.childNodes[3];

		let imgSrc = img.src.split("/");
		let imgName = imgSrc[imgSrc.length - 1];

		if (imgName == "profile-user.png") {
			img.style.filter = "invert(1)";
		}
	});

	/* ==================== SEARCHBAR (PHONE) ==================== */

	const searchIcon = document.querySelector('#magnifying-glass');
	const searchBarPhone = document.querySelector('#research-bar-phone');
	let isUserSearching = false;

	// Open searchbar for phone only when user is on a phone (<768px) and not in the menu
	searchIcon.addEventListener('click', () => {
		isUserSearching = !isUserSearching;
		let viewportWidth = window.innerWidth;
		console.log(isUserSearching);
		if (isMenuChannelOpen || isMembersChannelOpen) {
			searchBarPhone.style.display = 'none';
			isUserSearching = !isUserSearching;
		} else if (isUserSearching && viewportWidth < 768) {
			searchBarPhone.style.display = 'flex';
			console.log('ok');
		} else {
			searchBarPhone.style.display = 'none';
		}
	})

	/* ==================== MENU CHANNELS OPENING (PHONE) ==================== */

	const menuMainIcon = document.querySelector('#menu-icon');
	const leftContainer = document.querySelector('#left-container');
	const rightContainer = document.querySelector('#right-container');
	const channelMemberContainer = document.querySelector('#members-container');
	const leftHeader = document.querySelector('#left-header');
	const header = document.querySelector('#header');
	let isMenuChannelOpen = false;

	menuMainIcon.addEventListener('click', () => {
		// Searching is not possible when the menu is open
		searchBarPhone.style.display = 'none';
		isUserSearching = false;

		if (isMenuChannelOpen) {
			menuOpenClose('none', 'block', 'none', 'none', 'column');
		} else {
			menuOpenClose('flex', 'none', 'none', 'block', 'column-reverse');
		}

		isMenuChannelOpen = !isMenuChannelOpen;
	});

	function menuOpenClose(leftContainerDisplay, rightContainerDisplay, channelMemberContainerDisplay, leftHeaderDisplay, headerFlexDirection) {
		leftContainer.style.display = leftContainerDisplay;
		rightContainer.style.display = rightContainerDisplay;
		channelMemberContainer.style.display = channelMemberContainerDisplay;
		leftHeader.style.display = leftHeaderDisplay;
		header.style.flexDirection = headerFlexDirection;
	}

	/* ==================== MENU MEMBERS OPENING ==================== */

	const channelNameHeader = document.querySelector('#channel-name');
	let isMembersChannelOpen = false;

	channelNameHeader.addEventListener('click', () => {
		// Searching is not possible when the menu is open
		searchBarPhone.style.display = 'none';
		isUserSearching = false;
		let viewportWidth = window.innerWidth;

		if (viewportWidth >= 768) {
			if (isMembersChannelOpen) {
				channelMemberContainer.style.display = 'none';
			} else {
				channelMemberContainer.style.display = 'block';
			}
		} else {
			if (!isMenuChannelOpen && !isMembersChannelOpen) {
				menuOpenClose('none', 'none', 'block', 'none', 'column');
			} else if (!isMenuChannelOpen && isMembersChannelOpen) {
				menuOpenClose('none', 'block', 'none', 'none', 'column');
			}
		}

		if (!isMenuChannelOpen) {
			isMembersChannelOpen = !isMembersChannelOpen;
		}
	})

	/* ==================== USER INFORMATIONS ==================== */

	const userInfos = document.querySelector('#user-infos');

	document.querySelectorAll('.user-profile-picture').forEach(picture => {
		picture.addEventListener('click', (event) => {
			if (userInfos.style.display === "block") {
				userInfos.style.display = "none";
			} else {
				userInfos.style.display = "block";
			}
			event.stopPropagation();
		});
	});

	document.querySelectorAll('.button-close').forEach(button => {
		button.addEventListener('click', (event) => {
			userInfos.style.display = "none";
			event.stopPropagation();
		});
	});

	document.addEventListener('click', (event) => {
		if (!userInfos.contains(event.target) && event.target !== userInfos) {
			userInfos.style.display = "none";
		}
	});

	/* ==================== MESSAGES OPTIONS ==================== */

	const messageOptionsButton = document.querySelector('#messageOptionsButton');
	const options = document.querySelector('#options');

	messageOptionsButton.addEventListener('click', () => {
		if (options.classList.contains('show-up')) {
			options.classList.remove('show-up');
			options.classList.add('show-down');
		} else if (options.classList.contains('show-down')) {
			options.classList.remove('show-down');
			options.classList.add('show-up');
		} else {
			options.classList.add('show-up');
		}
	});

	/* ==================== EMOJIS CHANGER ==================== */

	const emojiButton = document.querySelector('#emoji-option'); // Sélectionnez le bouton maintenant
	const emojiNames = ['smile', 'sad', 'cool', 'famous', 'in-love', 'mocking', 'rolling-eyes', 'tongue'];
	const emojiAlt = ['Visage heureux', 'Visage triste', 'Visage avec des lunettes de soleil', 'Visage avec des étoiles dans les yeux', 'Visage avec des coeurs dans les yeux', 'Visage qui plisse les yeux', 'Visage avec les yeux doux', 'Visage qui tire la langue'];
	let currentEmojiIndex = 1;

	emojiButton.addEventListener('mouseover', (event) => {
		const nextEmojiSrc = `../images/emojis/${emojiNames[currentEmojiIndex]}.png`;
		emojiButton.src = nextEmojiSrc;
		emojiButton.alt = emojiAlt[currentEmojiIndex];
		currentEmojiIndex = (currentEmojiIndex + 1) % emojiNames.length;
	});

	/* ==================== OPEN EMOJIS MENU ==================== */

	const input = document.querySelector('#messageInput');

	document.querySelector('emoji-picker').addEventListener('emoji-click', event => {
		input.value += event.detail.unicode;
	});

	const emojiPicker = document.querySelector('#emoji-picker');

	document.querySelector('#emoji-option').addEventListener('click', event => {
		event.stopPropagation();
		emojiPicker.i18n = fr;
		emojiPicker.locale = 'fr';
		emojiPicker.dataSource = 'https://cdn.jsdelivr.net/npm/emoji-picker-element-data@^1/fr/emojibase/data.json';

		if (emojiPicker.style.display === 'none') {
			emojiPicker.style.display = 'block';
		} else {
			emojiPicker.style.display = 'none';
		}
	});

	document.addEventListener('click', (event) => {
		if (!emojiPicker.contains(event.target) && event.target !== emojiPicker) {
			emojiPicker.style.display = "none";
		}
	});

	adjustEmojiPicker();

	window.addEventListener('resize', adjustEmojiPicker);

	function adjustEmojiPicker() {

		const emojiPicker = document.querySelector('#emoji-picker');

		if (window.innerWidth < 768) {
			emojiPicker.style.display = 'none';
		}
	}

	/* ==================== ON RESIZE WINDOW ==================== */

	window.addEventListener('resize', () => {
		let windowWidth = window.innerWidth;

		if (windowWidth >= 768) {
			menuOpenClose('flex', 'block', 'none', 'block', 'row');
			searchBarPhone.style.display = 'none';
		} else {
			menuOpenClose('none', 'block', 'none', 'none', 'column');
		}

		isMenuChannelOpen = false;
		isMembersChannelOpen = false;
	});

	/* ==================== MENU PARAMETERS OPENING ==================== */

	if (window.location.pathname === '/pages/interface.admin.html') {
		const menuParametersIcon = document.querySelectorAll('#menu-parameters-icon');
		const menuParameters = document.querySelector('#menu-parameters');
		const contentPageParameters = document.querySelector('#channel-content');
		let isMenuParametersOpen = false;

		menuParametersIcon.addEventListener('click', () => {
			if (isMenuParametersOpen) {
				menuParameters.style.display = 'none';
				contentPageParameters.style.display = 'block';
			} else {
				menuParameters.style.display = 'flex';
				contentPageParameters.style.display = 'none';
			}

			isMenuParametersOpen = !isMenuParametersOpen;
		});
	}

	/* ==================== SEND MESSAGE ==================== */

	document.querySelector('#send-message').addEventListener('click', function () {
		const messageInput = document.querySelector('#messageInput');
		var message = messageInput.value;

		if (message.length === 0) {
			return;
		}

		var channelId = 1; // TODO à changer
		messageInput.value = '';

		var xhr = new XMLHttpRequest();

		xhr.open("POST", "/public/message/process.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					// console.log(xhr.responseText);
				} else {
					console.error("Erreur: " + xhr.status);
				}
			}
		};

		xhr.send("message=" + encodeURIComponent(message) + "&channelId=" + encodeURIComponent(channelId));
	});

	/* ==================== CHANNELS ==================== */

	if (window.location.pathname === '/index.php') {
		getChannels().then(channels => {
			renderChannels(channels); // Rendre les canaux dans le DOM
			attachChannelClickEvent(); // Attacher l'événement de clic une fois que les canaux sont rendus
		}).catch(error => {
			console.error(error);
		});
	}

	function attachChannelClickEvent() {
		const channels = document.querySelectorAll('.channel');
		channels.forEach(channel => {
			channel.addEventListener('click', event => {
				const clickedChannel = event.currentTarget;
				const channelId = clickedChannel.dataset.channelId;

				// Supprimer la classe "active" de tous les éléments
				channels.forEach(channel => {
					if (channel !== clickedChannel) {
						channel.classList.remove("active");
					}
				});

				clickedChannel.classList.add("active");
				var xhr = new XMLHttpRequest();

				// Définir le channel dans la session
				xhr.open("POST", "/public/scripts/setSessionChannelId.php");
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.onreadystatechange = function () {
					if (xhr.readyState === XMLHttpRequest.DONE) {
						if (xhr.status === 200) {

						} else {
							console.error("Erreur lors de la session channel ID");
						}
					}
				};
				xhr.send("channelId=" + channelId);

				var xhr2 = new XMLHttpRequest();
				// Effectuez une requête AJAX pour récupérer les messages du canal
				xhr2.open("POST", "/public/message/getMessages.php", true);
				xhr2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr2.onreadystatechange = function () {
					if (xhr2.readyState === XMLHttpRequest.DONE) {
						if (xhr2.status === 200) {
							const messages = JSON.parse(xhr2.responseText);
							let channeldId = getUserId();
							channeldId.then(userId => {
								renderMessages(messages, userId);
							});

						} else {
							console.error("Erreur lors de la récupération des messages");
						}
					}
				};
				xhr2.send("channelId=" + encodeURIComponent(channelId));
			});
		});
	}
});
