document.addEventListener('DOMContentLoaded', () => {

    const REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const IS_TOUCH = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    
    // Config from PHP (defaults provided)
    const config = typeof themeConfig !== 'undefined' ? themeConfig : {
        enableMotion: true,
        animSpeed: 1.0,
        cursorStyle: 'dot',
        pageTrans: true
    };

    // --- 0. THEME HANDLING ---
    const themeBtn = document.getElementById('theme-toggle');
    const htmlEl = document.documentElement;
    const DARK_IMG = "https://i.imgur.com/ixsEpYM.png"; 
    const LIGHT_IMG = "https://i.imgur.com/oFHdPUS.png";
    const sunIcon = `<svg viewBox="0 0 24 24"><path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 00-1.41 0 .996.996 0 000 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 00-1.41 0 .996.996 0 000 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29zm1.41-13.78c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29zM7.28 17.28c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29z"/></svg>`;
    const moonIcon = `<svg viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-3.03 0-5.5-2.47-5.5-5.5 0-1.82.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/></svg>`;

    function updateImages(theme) {
        const targetSrc = theme === 'light' ? LIGHT_IMG : DARK_IMG;
        const heroImg = document.querySelector('.hero-portrait-img');
        if (heroImg) heroImg.src = targetSrc;
        const footerImg = document.querySelector('.footer-portrait-img');
        if (footerImg) footerImg.src = targetSrc;
        const aboutImg = document.querySelector('.profile-img');
        if (aboutImg) aboutImg.src = targetSrc;
    }

    function setTheme(theme) {
        htmlEl.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        if (themeBtn) themeBtn.innerHTML = theme === 'light' ? moonIcon : sunIcon;
        updateImages(theme);
    }

    const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
    setTheme(savedTheme);

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            const currentTheme = htmlEl.getAttribute('data-theme') || 'dark';
            setTheme(currentTheme === 'light' ? 'dark' : 'light');
        });
    }

    // --- PAGE TRANSITIONS (SPA EFFECT) ---
    if (config.pageTrans && !REDUCED_MOTION && window.gsap) {
        const curtain = document.querySelector('.page-transition-curtain');
        
        // Enter Animation
        gsap.to(curtain, { height: 0, duration: 0.8 * config.animSpeed, ease: "power4.inOut", delay: 0.2 });

        // Intercept Links
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', e => {
                const href = link.getAttribute('href');
                if (
                    href && 
                    !href.startsWith('#') && 
                    !href.startsWith('mailto:') && 
                    !link.target && 
                    link.hostname === window.location.hostname
                ) {
                    e.preventDefault();
                    gsap.to(curtain, {
                        height: '100%',
                        top: 0,
                        bottom: 'auto',
                        duration: 0.6 * config.animSpeed,
                        ease: "power4.inOut",
                        onComplete: () => window.location.href = href
                    });
                }
            });
        });
    }

    // --- GRID CANVAS ---
    const canvas = document.getElementById('grid-canvas');
    if (canvas && !REDUCED_MOTION && !IS_TOUCH) {
        const ctx = canvas.getContext('2d');
        let width, height;
        let mouse = { x: -1000, y: -1000 };

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        function drawGrid() {
            ctx.clearRect(0, 0, width, height);
            // Dynamic Grid Config from CSS Vars
            const style = getComputedStyle(document.body);
            const size = parseInt(style.getPropertyValue('--grid-size')) || 60;
            const opacity = parseFloat(style.getPropertyValue('--grid-opacity')) || 0.05;
            
            const isLight = htmlEl.getAttribute('data-theme') === 'light';
            const gridColor = isLight ? `rgba(0,0,0,${opacity})` : `rgba(255,255,255,${opacity})`;
            
            ctx.lineWidth = 1;
            ctx.strokeStyle = gridColor;
            ctx.beginPath();
            for (let x = 0; x <= width; x += size) { ctx.moveTo(x, 0); ctx.lineTo(x, height); }
            for (let y = 0; y <= height; y += size) { ctx.moveTo(0, y); ctx.lineTo(width, y); }
            ctx.stroke();

            // Spotlight Logic
            const grad = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, 400);
            grad.addColorStop(0, isLight ? 'rgba(37,99,235,0.1)' : 'rgba(59,130,246,0.1)');
            grad.addColorStop(1, 'rgba(0,0,0,0)');
            
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, width, height);

            requestAnimationFrame(drawGrid);
        }
        drawGrid();
    }

    // --- CUSTOM CURSOR ---
    if (config.cursorStyle !== 'none' && !REDUCED_MOTION && !IS_TOUCH) {
        const cursorDot = document.querySelector('.custom-cursor-dot');
        const cursorOutline = document.querySelector('.custom-cursor-outline');
        
        if (cursorDot && cursorOutline) {
            document.body.classList.add('custom-cursor-active');
            let mouseX = window.innerWidth / 2, mouseY = window.innerHeight / 2;
            let outlineX = mouseX, outlineY = mouseY;

            gsap.set([cursorDot, cursorOutline], { xPercent: -50, yPercent: -50, opacity: 1 });

            window.addEventListener('mousemove', e => {
                mouseX = e.clientX; mouseY = e.clientY;
                gsap.to(cursorDot, { x: mouseX, y: mouseY, duration: 0 });
            });

            const animateCursor = () => {
                outlineX += (mouseX - outlineX) * 0.15;
                outlineY += (mouseY - outlineY) * 0.15;
                gsap.set(cursorOutline, { x: outlineX, y: outlineY });
                requestAnimationFrame(animateCursor);
            };
            animateCursor();

            // Hover States
            const targets = 'a, button, input, .project-card, .nav-pill';
            document.querySelectorAll(targets).forEach(el => {
                el.addEventListener('mouseenter', () => {
                    gsap.to(cursorOutline, { width: 60, height: 60, opacity: 0.5, duration: 0.3 });
                });
                el.addEventListener('mouseleave', () => {
                    gsap.to(cursorOutline, { width: 40, height: 40, opacity: 1, duration: 0.3 });
                });
            });
        }
    }

    // --- GSAP REVEALS ---
    if (config.enableMotion && window.gsap && window.ScrollTrigger && !REDUCED_MOTION) {
        gsap.registerPlugin(ScrollTrigger);
        
        const speed = config.animSpeed;

        document.querySelectorAll('.text-reveal-wrap').forEach(title => {
            const fillText = title.querySelector('.text-fill');
            if (fillText) {
                gsap.to(fillText, {
                    clipPath: 'inset(0 0% 0 0)',
                    ease: 'none',
                    scrollTrigger: {
                        trigger: title,
                        start: 'top 90%',
                        end: 'bottom 20%',
                        scrub: 0.5 * speed
                    }
                });
            }
        });

        document.querySelectorAll(".reveal-on-scroll").forEach(el => {
            gsap.fromTo(el, { y: 40, opacity: 0 }, {
                y: 0, opacity: 1, duration: 0.8 * speed,
                scrollTrigger: { trigger: el, start: "top 85%" }
            });
        });
    }

    // --- MOBILE MENU ---
    const mobileBtn = document.querySelector('.mobile-nav-toggle');
    if (mobileBtn) {
        mobileBtn.addEventListener('click', () => {
            document.body.classList.toggle('menu-open');
            const links = document.querySelectorAll('.mobile-nav-links a');
            if(document.body.classList.contains('menu-open')) {
                gsap.fromTo(links, {y: 20, opacity:0}, {y:0, opacity:1, stagger:0.1, duration: 0.4});
            }
        });
    }
});