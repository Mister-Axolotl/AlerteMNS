import { startParticleAnimation, renderMessages, getUserId, getChannels, renderPublicChannels, renderPrivateChannels, getActualChannelId, getMembers, renderMembers, removeChild } from "./functions.js";
import fr from "./fr.js";

const badgePrefix = "sm_";
const profilePicturePrefix = "sm_";

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
	const observer = new IntersectionObserver(handleIntersection, { threshold: 0.5 });

	// Observer un conteneur parent pour les nouvelles sections ajoutées dynamiquement
	var container;
	if (window.location.pathname === '/index.php') {
		container = document.querySelector(".container");
	} else if (window.location.pathname === '/parametres.php') {
		container = document.querySelector(".parameters-content");

	}

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

	// MAIN PAGE
	if (window.location.pathname === '/index.php') {

		/* ==================== CONVERSATION TYPE SWITCH ==================== */

		const activeBackgroundConversation = document.querySelector('.active-background');
		const publicConversation = document.querySelector('#public');
		const priveeConversation = document.querySelector('#privee');

		publicConversation.addEventListener('click', () => {
			toggleBackground('activePublic', 'activePrivate', priveeConversation, publicConversation);
			getRenderClickChannel("public");
		});

		priveeConversation.addEventListener('click', () => {
			toggleBackground('activePrivate', 'activePublic', publicConversation, priveeConversation);
			getRenderClickChannel("private");
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
			if (isMenuChannelOpen || isMembersChannelOpen) {
				searchBarPhone.style.display = 'none';
				isUserSearching = !isUserSearching;
			} else if (isUserSearching && viewportWidth < 768) {
				searchBarPhone.style.display = 'flex';
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

		/* ==================== MENU CHANNELS USER ==================== */
		const parametersUserName = document.querySelector('#parameters-name');
		const parametersUserRole = document.querySelector('#parameters-role');
		const parametersUserPicture = document.querySelector('#parameters-user-profil');
		const adminLinkDiv = document.querySelector('#admin-link');
		const channelsDiv = leftContainer.querySelector('.channels');

		var xhr = new XMLHttpRequest();
		xhr.open("POST", "/public/scripts/getUserRoleAndName.php");
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send();
		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					const userRoleAndName = JSON.parse(xhr.responseText);
					const userPicture = userRoleAndName.user_picture;
					const userRoles = userRoleAndName.user_roles.split(",");
					parametersUserName.textContent = `${userRoleAndName.user_firstname} ${userRoleAndName.user_lastname}`;
					parametersUserName.title = `${userRoleAndName.user_firstname} ${userRoleAndName.user_lastname}`;
					parametersUserRole.textContent = userRoles[0];
					parametersUserRole.title = userRoles;

					// Display profile picture if there is one otherwise default picture
					if (userPicture != "") {
						parametersUserPicture.src = `/upload/${profilePicturePrefix}${userPicture}`;
					} else {
						parametersUserPicture.src = "/images/parameters/user.png";
					}

					// Display roles
					if (userRoles[0] == "administrateur") {
						channelMenuAdmin();
					}

					for (let i = 1; i < userRoles.length; i++) {
						parametersUserRole.textContent += `, ${userRoles[i]}`;

						if (userRoles[i] == "administrateur") {
							channelMenuAdmin();
						}

					}
				}
			}
		}

		function channelMenuAdmin() {
			// Admin button
			adminLinkDiv.style.display = "block";
			const adminButtonLink = adminLinkDiv.querySelector('a');
			const adminButtonText = adminLinkDiv.querySelector('p');
			adminButtonLink.style.justifyContent = "center";

			// Calendar & parameters buttons
			const calendarParamDiv = document.querySelector('.calendar-parameters');

			if (window.innerWidth < 768) {
				channelsDiv.style.height = '80%';
				adminButtonText.style.width = "fit-content";
				calendarParamDiv.style.marginBottom = "4rem";
			} else if (window.innerHeight < 500) {
				channelsDiv.style.height = '60%';
				adminButtonText.style.width = "75%";
				calendarParamDiv.style.marginBottom = "1rem";
			} else {
				channelsDiv.style.height = '70%';
			}
		}

		/* ==================== GET/DISPLAY MEMBERS ==================== */
		function members() {
			getMembers().then(members => {
				renderMembers(members); // Rendre les canaux dans le DOM
				setupUserInfos(); // Attacher l'événement de clic une fois que les membres sont rendus
			}).catch(error => {
				console.error(error);
			});
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
					members();
				}
			} else {
				if (!isMenuChannelOpen && !isMembersChannelOpen) {
					menuOpenClose('none', 'none', 'block', 'none', 'column');
					members();
				} else if (!isMenuChannelOpen && isMembersChannelOpen) {
					menuOpenClose('none', 'block', 'none', 'none', 'column');
				}
			}

			if (!isMenuChannelOpen) {
				isMembersChannelOpen = !isMembersChannelOpen;
			}
		});

		/* ==================== USER INFORMATIONS ==================== */

		function setupUserInfos() {
			const userInfos = document.querySelector('#user-infos');
			const userInfosHeader = document.querySelector('.user-infos-header');
			const pictures = document.querySelectorAll('.user-profile-picture');

			pictures.forEach(picture => {
				picture.addEventListener('click', (event) => {
					userInfos.style.display = "none";
					let userId;
					// Member
					if (picture.hasChildNodes()) {
						userId = picture.querySelector('img').getAttribute('data-user-id');
					} else {
						// Message
						userId = picture.getAttribute('data-user-id');
					}

					var xhr = new XMLHttpRequest();
					xhr.open("POST", "/public/scripts/getUserInformations.php");
					xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					xhr.onreadystatechange = function () {
						if (xhr.readyState === XMLHttpRequest.DONE) {
							if (xhr.status === 200) {
								const userInfos = JSON.parse(xhr.responseText);
								const userFirstname = userInfos.user_firstname;
								const userLastname = userInfos.user_lastname;
								const userPicture = userInfos.user_picture;
								const userBadgeRole = userInfos.roles_badge.split(",");
								const userRoleName = userInfos.roles_name.split(",");

								const imgElement = userInfosHeader.querySelector('img');
								const spanElement = userInfosHeader.querySelector('span');

								const userInfosRoles = document.querySelector('.user-infos-roles');

								if (userPicture != "") {
									imgElement.src = `/upload/${profilePicturePrefix}${userPicture}`
								} else {
									imgElement.src = "/images/profile-user.png";
								}

								// User lastname and firstname
								spanElement.textContent = `${userFirstname} ${userLastname}`;

								// User roles
								// delete all roles before displaying user's role(s)
								removeChild(userInfosRoles);

								// display user's roles
								for (let i = 0; i < userRoleName.length; i++) {
									const divRole = document.createElement('div');
									divRole.classList.add('role');

									const imgRoleBadge = document.createElement('img');
									imgRoleBadge.classList.add('role-badge');
									imgRoleBadge.src = `/upload/${badgePrefix}${userBadgeRole[i]}`;
									imgRoleBadge.alt = userRoleName[i] + 'badge';
									divRole.appendChild(imgRoleBadge);

									const spanRoleName = document.createElement('span');
									spanRoleName.textContent = userRoleName[i];
									divRole.appendChild(spanRoleName);

									userInfosRoles.appendChild(divRole);
								}

							} else {
								console.error("Erreur lors de la session channel ID");
							}
						}
					};
					xhr.send("userId=" + userId);

					userInfos.style.display = "block";

					event.stopPropagation();

					document.querySelectorAll('#conversation-button').forEach(button => {
						button.addEventListener('click', (event) => {
							var xhr = new XMLHttpRequest();
							xhr.open("POST", "/public/scripts/insertNewPrivateChannel.php", true);
							xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
							xhr.onreadystatechange = function () {
								if (xhr.readyState === XMLHttpRequest.DONE) {
									if (xhr.status === 200) {
										const channelInfo = JSON.parse(xhr.responseText);
									}
								}
							}
							xhr.send(`otherUserId=${userId}`);
							// Open new/existant conversation
							priveeConversation.click();
							event.stopPropagation();
						})
					})
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
		}

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

			channelMenuAdmin();
			isMenuChannelOpen = false;
			isMembersChannelOpen = false;
		});

		/* ==================== SEND MESSAGE ==================== */

		document.querySelector('#send-message').addEventListener('click', function () {
			const messageInput = document.querySelector('#messageInput');
			var message = messageInput.value;

			// Annule l'envoi du message si le message est vide
			if (message.length === 0) {
				return;
			}

			getActualChannelId().then(channelId => {
				messageInput.value = '';

				var xhr = new XMLHttpRequest();

				xhr.open("POST", "/public/message/process.php", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.onreadystatechange = function () {
					if (xhr.readyState === XMLHttpRequest.DONE) {
						if (xhr.status === 200) {
							var responseData = JSON.parse(xhr.responseText);
							// console.log(responseData);
						} else {
							console.error("Erreur: " + xhr.status);
						}
					}
				};

				xhr.send("message=" + encodeURIComponent(message) + "&channelId=" + encodeURIComponent(channelId));
			});
		});

		/* ==================== CHANNELS ==================== */

		async function getRenderClickChannel(type) {
			try {
				const channels = await getChannels(type);
				if (type === "public") {
					renderPublicChannels(channels); // Rendre les canaux publics dans le DOM
				} else if (type === "private") {
					await renderPrivateChannels(channels);
					attachChannelClickEvent(channels); // Attacher l'événement de clic une fois que le rendu des canaux privés est terminé
				}
				attachChannelClickEvent(channels); // Attacher l'événement de clic une fois que le rendu des canaux privés est terminé
			} catch (error) {
				console.error(error);
			}
		}

		getRenderClickChannel("public");

		//TODO Vérifier si l'utilisateur a la permission d'aller dans le channel car il peut modifier l'html

		let channelId = null;

		function attachChannelClickEvent(channelsList, type) {
			const channels = document.querySelectorAll('.channel');
			const channelName = document.querySelector('#channel-name');

			channels.forEach(channel => {
				channel.addEventListener('click', event => {
					const clickedChannel = event.currentTarget;
					channelId = clickedChannel.dataset.channelId;

					// Modifier le nom et l'image du canal dans le DOM
					const imageElement = channelName.querySelector('.channel-image');
					imageElement.src = channel.querySelector('img').src;

					const nameElement = channelName.querySelector('.channel-name');
					nameElement.textContent = channel.querySelector('span').textContent;

					// Supprimer la classe "active" de tous les éléments
					channels.forEach(channel => {
						if (channel !== clickedChannel) {
							channel.classList.remove("active");
						}
					});

					clickedChannel.classList.add("active");

					// Récupérer les messages du canal
					retrieveAndRenderMessages();

					// récupérer tous les membres du channel si celui-ci est ouvert
					if (isMembersChannelOpen) {
						members();
					}
				});
			});

			// Définir l'intervalle pour rafraîchir les messages toutes les 5 secondes
			setInterval(retrieveAndRenderMessages, 5000);
		}

		function retrieveAndRenderMessages() {
			if (channelId) {
				var xhr = new XMLHttpRequest();
				xhr.open("POST", "/public/message/getMessages.php", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.onreadystatechange = function () {
					if (xhr.readyState === XMLHttpRequest.DONE) {
						if (xhr.status === 200) {
							const messages = JSON.parse(xhr.responseText);
							let userId = getUserId();
							userId.then(user => {
								renderMessages(messages, user);
								setupUserInfos();
								var xhr2 = new XMLHttpRequest();
								xhr2.open("POST", "/public/scripts/setSessionChannelId.php", true);
								xhr2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
								xhr2.onreadystatechange = function () {
									if (xhr2.readyState === XMLHttpRequest.DONE) {
										if (xhr2.status === 200) {

										}
									}
								};
								xhr2.send("channelId=" + encodeURIComponent(channelId));
							});
						} else {
							console.error("Erreur lors de la récupération des messages");
						}
					}
				};
				xhr.send("channelId=" + encodeURIComponent(channelId));
			}
		}
	}

	// PARAMETERS PAGE

	if (window.location.pathname === '/parametres.php') {

		/* ==================== MENU PARAMETERS OPENING ==================== */
		// const menuParametersIcon = document.querySelectorAll('#menu-parameters-icon');
		// const menuParameters = document.querySelector('#menu-parameters');
		const contentPageParameters = document.querySelector('#channel-content');
		/* let isMenuParametersOpen = false;

		menuParametersIcon.addEventListener('click', () => {
			if (isMenuParametersOpen) {
				menuParameters.style.display = 'none';
				contentPageParameters.style.display = 'block';
			} else {
				menuParameters.style.display = 'flex';
				contentPageParameters.style.display = 'none';
			}

			isMenuParametersOpen = !isMenuParametersOpen;
		}); */

		/* ==================== PARAMETER'S CHANNEL OPENNING ==================== */

		const parameterChannels = document.querySelectorAll('.parameter-channel');
		const parameterTitle = contentPageParameters.querySelector('h2');
		const problemSuggestionDescription = document.querySelector('#description-label');
		const channelToDivName = { 'Mon compte': 'my-account', 'Un problème ?': 'problem-suggestion', 'FAQ': 'faq', 'Suggestions ?': 'problem-suggestion', 'RGPD': 'rgpd' };
		let activeDivName = 'my-account';
		let activeChannel = parameterChannels[0];

		parameterChannels.forEach(channel => {
			channel.addEventListener('click', event => {
				// The channel that was open should no longer be visible
				let activeDiv = document.querySelector(`.${activeDivName}`);
				activeDiv.style.display = 'none';
				activeChannel.classList.remove('active');

				// Display content of the channel that was clicked and change title
				activeDivName = channelToDivName[channel.title];
				parameterTitle.textContent = channel.title;

				if (channel.title === "Un problème ?") {
					problemSuggestionDescription.textContent = "Description du problème";
				} else if (channel.title === "Suggestions ?") {
					problemSuggestionDescription.textContent = "Description de la suggestion";
				}

				activeChannel = channel;
				activeDiv = document.querySelector(`.${activeDivName}`);
				activeDiv.style.display = 'block';
				channel.classList.add('active');
			});
		});

		/* ==================== MY-ACCOUNT ==================== */
		const accountDiv = document.querySelector('.my-account');

		// LOAD PAGE WITH USER'S INFOS
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "/public/scripts/getUserAccountInfo.php");
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send();
		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					const userAccount = JSON.parse(xhr.responseText);
					accountDiv.querySelector('.user-firstname').textContent = userAccount.user_firstname;
					accountDiv.querySelector('.user-lastname').textContent = userAccount.user_lastname;
					accountDiv.querySelector('#email').value = userAccount.user_email;
					const image = accountDiv.querySelector('.user-profile-picture');

					if (userAccount.user_picture != "") {
						image.src = `/upload/sm_${userAccount.user_picture}`;
					} else {
						image.src = "/images/profile-user.png";
					}
				}
			}
		}

		// CHANGE PROFILE PICTURE
		const modifyPictureButton = document.querySelector('#modify-profile-picture');
		const formPicture = document.querySelector('#submit-picture');
		const inputPicture = document.querySelector('#user-picture');

		modifyPictureButton.addEventListener('click', () => {
			inputPicture.click();
			modifyPictureButton.style.display = 'none';
			formPicture.style.display = 'block';
		});

		// CHANGE PASSWORD
		const modifyPasswordButton = document.querySelector('#button-modify-password');
		const cancelPasswordModify = document.querySelector('#cancel-password-change');
		const passwordForm = document.querySelector('#modify-password');

		// Display form to modify password on button-modify-password's click
		modifyPasswordButton.addEventListener('click', () => {
			displayFormPassword('none', 'block');
		});

		// Hide form to modify password on cancel-password-change button's click and reset values to empty strings 
		cancelPasswordModify.addEventListener('click', () => {
			displayFormPassword('block', 'none');
		});

		function displayFormPassword(buttonDisplay, formDisplay) {
			modifyPasswordButton.style.display = buttonDisplay;
			passwordForm.style.display = formDisplay;

			passwordForm.querySelectorAll('input').forEach(input => {
				input.value = "";
			})
		}
	}
});
