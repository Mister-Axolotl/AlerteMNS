import { openCloseMenu, ifOpenMenu, startParticleAnimation } from "./functions.js";

document.addEventListener('DOMContentLoaded', function () {

    /* ==================== SMOOTH TRANSITION FOR SECTION ==================== */

    const sections = document.querySelectorAll(".scroll-section");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
            } else {
                entry.target.classList.remove("active");
            }
        });
    }, { threshold: 0.15 }); // 10% de l'écran

    sections.forEach((section) => {
        observer.observe(section);
    });

    /* ==================== WHEN CLICK ON ANCHOR, SMOOTH SCROLL ==================== */

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    const socialButtons = document.querySelectorAll(".particles-button");

    socialButtons.forEach(button => {
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
	
    /* ==================== SEARCHBAR (PHONE) ==================== */
	const searchIcon = document.querySelector('#magnifying-glass');
	const searchBarPhone = document.querySelector('#research-bar-phone');
	let isUserSearching = false;

	// Open searchbar for phone only when user is on a phone (<768px) and not in the menu
	searchIcon.addEventListener('click', () => {
		isUserSearching = !isUserSearching;
		let viewportWidth = window.innerWidth;
		console.log(isUserSearching);
		if (isMenuChannelOpen) {
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
	const menuIcon = document.querySelector('#menu-icon');
	const leftContainer = document.querySelector('#left-container');
	const rightContainer = document.querySelector('#right-container');
	const channelMemberContainer = document.querySelector('#members-container');
	const leftHeader = document.querySelector('#left-header');
	const header = document.querySelector('#header');
	let isMenuChannelOpen = false;

	menuIcon.addEventListener('click', () => {
		// Searching is not possible when the menu is open
		searchBarPhone.style.display = 'none';
		isUserSearching = false;

		if (isMenuChannelOpen) {
			menuOpenClose('none', 'block', 'none', 'none', 'column');
		} else {
			menuOpenClose('block', 'none', 'none', 'block', 'column-reverse');
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
			isMembersChannelOpen =!isMembersChannelOpen;
		}
	})


    /* ==================== USER INFORMATIONS ==================== */
	const userInfos = document.querySelector('#user-infos');
	const closeIcon = document.querySelector('#close');

	document.querySelectorAll('.user-profile-picture').forEach(picture => {
        picture.addEventListener('click', () => {
			userInfos.style.display = "block";
		});
    });

	closeIcon.addEventListener('click', () => {
		userInfos.style.display = "none";
	})

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
    let currentEmojiIndex = 1;

    emojiButton.addEventListener('mouseover', (event) => {
        const emojiImage = emojiButton.querySelector('.writting-image'); // Sélectionnez l'image à l'intérieur du bouton
        const nextEmojiSrc = `../images/emojis/${emojiNames[currentEmojiIndex]}.png`;
        emojiImage.src = nextEmojiSrc;
        currentEmojiIndex = (currentEmojiIndex + 1) % emojiNames.length;
    });

    /* ==================== OPEN EMOJIS MENU ==================== */


    /* ==================== ON RESIZE WINDOW ==================== */
	window.addEventListener('resize', () => {
		let windowWidth = window.innerWidth;

		if (windowWidth >= 768) {
			menuOpenClose('flex', 'block', 'block', 'row');
			isMenuChannelOpen = true;
			searchBarPhone.style.display = 'none';
		} else {
			menuOpenClose('none', 'block', 'none', 'column');
			isMenuChannelOpen = false;
		}
	})
});
