import { openCloseMenu, ifOpenMenu, startParticleAnimation } from "../js/functions.js";

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

    const conversationTypeButtons = document.querySelector('#conversation_type');
    const activeBackgroundConversation = document.querySelector('.active-background');
    const publicConversation = document.querySelector('#public');
    const priveeConversation = document.querySelector('#privee');

    conversationTypeButtons.addEventListener('click', () => {
		// puts background behind private if public is activ	
        if (publicConversation.classList.contains('active-type')) {
            activeBackgroundConversation.classList.remove('activePublic');
			activeBackgroundConversation.classList.add('activePrivate');
            publicConversation.classList.remove('active-type');
            priveeConversation.classList.add('active-type');
        }
		// puts background behind public if private is activ	
		else if (priveeConversation.classList.contains('active-type')) {
            activeBackgroundConversation.classList.remove('activePrivate');
			activeBackgroundConversation.classList.add('activePublic');
            priveeConversation.classList.remove('active-type');
            publicConversation.classList.add('active-type');
		}
    })
});