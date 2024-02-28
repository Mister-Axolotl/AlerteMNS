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

    /* ==================== Conversation Type Switch ==================== */

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


});


