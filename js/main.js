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
    
    const conversationTypeButtons = document.querySelectorAll('.conversation-type');

    conversationTypeButtons.forEach(button => {
        button.addEventListener('click', () => {
            conversationTypeButtons.forEach(btn => {
                btn.classList.remove('active-type');
            });
    
            button.classList.add('active-type');
        });
    });

    /* ==================== Conversation Type Switch LEA ==================== */

    const conversationTypeButtonsLEA = document.querySelector('#conversation_type');
    const activeBackgroundConversation = document.querySelector('.active-background');
    const publicConversation = document.querySelector('#public');
    const priveeConversation = document.querySelector('#privee');

    conversationTypeButtonsLEA.addEventListener('click', () => {
        if (publicConversation.classList.contains('active')) {
            console.log("ok");
            activeBackgroundConversation.style.left = '100px';
            publicConversation.classList.remove('active');
            priveeConversation.classList.add('active');
        }
    })
});
