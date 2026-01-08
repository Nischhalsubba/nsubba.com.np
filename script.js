document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. VANILLA JS CUSTOM CURSOR (No GSAP dependency) ---
    const cursorDot = document.createElement('div');
    const cursorOutline = document.createElement('div');
    cursorDot.className = 'cursor-dot';
    cursorOutline.className = 'cursor-outline';
    document.body.appendChild(cursorDot);
    document.body.appendChild(cursorOutline);

    // Enable custom cursor styles via class
    document.body.classList.add('custom-cursor-enabled');

    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    
    // Smoothness config
    let outlineX = mouseX;
    let outlineY = mouseY;
    
    // Track mouse position
    window.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        
        // Dot follows instantly
        cursorDot.style.transform = `translate(${mouseX}px, ${mouseY}px) translate(-50%, -50%)`;
    });

    // Animation Loop for smooth outline
    const animateCursor = () => {
        // Linear interpolation for smooth lag
        const speed = 0.15;
        outlineX += (mouseX - outlineX) * speed;
        outlineY += (mouseY - outlineY) * speed;
        
        cursorOutline.style.transform = `translate(${outlineX}px, ${outlineY}px) translate(-50%, -50%)`;
        
        requestAnimationFrame(animateCursor);
    };
    animateCursor();

    // Hover Scaling logic
    const clickables = document.querySelectorAll('a, button, input, textarea, .clickable');
    clickables.forEach(el => {
        el.addEventListener('mouseenter', () => document.body.classList.add('hovering'));
        el.addEventListener('mouseleave', () => document.body.classList.remove('hovering'));
    });

    // Card Specific Hover
    const projectCards = document.querySelectorAll('.project-card');
    projectCards.forEach(card => {
        card.addEventListener('mouseenter', () => document.body.classList.add('hovering-card'));
        card.addEventListener('mouseleave', () => document.body.classList.remove('hovering-card'));
    });

    // --- 2. GSAP SCROLL ANIMATIONS ---
    if (window.gsap && window.ScrollTrigger) {
        const gsap = window.gsap;
        const ScrollTrigger = window.ScrollTrigger;
        gsap.registerPlugin(ScrollTrigger);

        // Hero Fade In - Using .from() ensures elements are visible by default if JS fails
        // and only jump to hidden state when animation starts.
        gsap.from(".fade-in", {
            y: 50,
            opacity: 0,
            duration: 1.2,
            stagger: 0.1,
            ease: "power3.out",
            delay: 0.2
        });

        // Scroll Reveal Elements
        const revealElements = document.querySelectorAll(".reveal-on-scroll");
        revealElements.forEach((element) => {
            gsap.from(element, {
                y: 50,
                opacity: 0,
                duration: 1,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: element,
                    start: "top 85%",
                }
            });
        });
    }

    // --- 3. TIME DISPLAY ---
    const timeDisplay = document.getElementById('time-display');
    if (timeDisplay) {
        const updateTime = () => {
            const now = new Date();
            const options = { timeZone: 'Asia/Kathmandu', hour: '2-digit', minute: '2-digit', hour12: true };
            timeDisplay.textContent = now.toLocaleTimeString('en-US', options);
        };
        updateTime();
        setInterval(updateTime, 1000);
    }

    // --- 4. NAVIGATION ACTIVE STATE ---
    const navLinks = document.querySelectorAll('.nav-link');
    const currentPath = window.location.pathname;
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        const href = link.getAttribute('href').replace('./', '');
        
        // Exact match or home logic
        if (currentPath.endsWith(href) || (href === 'index.html' && (currentPath === '/' || currentPath === ''))) {
            link.classList.add('active');
        }
        
        // Hash link logic (if on same page)
        if(href.startsWith('#')) {
             link.addEventListener('click', () => {
                 navLinks.forEach(l => l.classList.remove('active'));
                 link.classList.add('active');
             });
        }
    });

});