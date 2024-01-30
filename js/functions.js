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