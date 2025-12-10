// Custom Cursor
document.addEventListener('mousemove', (e) => {
    document.querySelector('.cursor').style.left = e.clientX + 'px';
    document.querySelector('.cursor').style.top = e.clientY + 'px';
    document.querySelector('.cursor-follow').style.left = e.clientX + 'px';
    document.querySelector('.cursor-follow').style.top = e.clientY + 'px';
});

// Typing Animation
function typeWriter(element, text, speed = 100) {
    let i = 0;
    element.innerHTML = '';
    const timer = setInterval(() => {
        element.innerHTML += text.charAt(i);
        i++;
        if (i > text.length) clearInterval(timer);
    }, speed);
}

// Particles Background
particlesJS('particles-js', {
    particles: {
        number: { value: 80 },
        color: { value: '#8a2be2' },
        shape: { type: 'circle' },
        opacity: { value: 0.5, random: true },
        size: { value: 3, random: true },
        line_linked: { enable: true, distance: 150, color: '#8a2be2', opacity: 0.4, width: 1 },
        move: { enable: true, speed: 2 }
    },
    interactivity: {
        detect_on: 'canvas',
        events: { onhover: { enable: true, mode: 'repulse' } }
    }
});