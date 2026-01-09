
document.addEventListener('DOMContentLoaded', () => {
    
    const REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const htmlEl = document.documentElement;

    // --- 0. THEME ENGINE ---
    const themeBtn = document.getElementById('theme-toggle');
    const DARK_IMG = "https://i.imgur.com/ixsEpYM.png";
    const LIGHT_IMG = "https://i.imgur.com/oFHdPUS.png";

    const sunIcon = `<svg viewBox="0 0 24 24"><path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 00-1.41 0 .996.996 0 000 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 00-1.41 0 .996.996 0 000 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29zm1.41-13.78c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29zM7.28 17.28c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29z"/></svg>`;
    const moonIcon = `<svg viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-3.03 0-5.5-2.47-5.5-5.5 0-1.82.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/></svg>`;

    function setTheme(theme) {
        htmlEl.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        if (themeBtn) themeBtn.innerHTML = theme === 'light' ? moonIcon : sunIcon;
        document.querySelectorAll('.hero-portrait-img, .footer-portrait-img, .profile-img').forEach(img => {
            img.src = theme === 'light' ? LIGHT_IMG : DARK_IMG;
        });
    }

    const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
    setTheme(savedTheme);

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            const current = htmlEl.getAttribute('data-theme');
            setTheme(current === 'light' ? 'dark' : 'light');
        });
    }

    // --- 1. SPOTLIGHT CANVAS ---
    const canvas = document.getElementById('grid-canvas');
    if (canvas && !REDUCED_MOTION) {
        const ctx = canvas.getContext('2d');
        let w, h, mouse = { x: -1000, y: -1000 };

        const resize = () => { w = canvas.width = window.innerWidth; h = canvas.height = window.innerHeight; };
        window.addEventListener('resize', resize);
        resize();

        window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });

        const draw = () => {
            ctx.clearRect(0, 0, w, h);
            const isL = htmlEl.getAttribute('data-theme') === 'light';
            const size = 64; 
            const radius = 380;
            const line = isL ? 'rgba(0,0,0,0.06)' : 'rgba(255,255,255,0.06)';
            const glow = isL ? 'rgba(59,130,246,0.12)' : 'rgba(59,130,246,0.18)';

            ctx.strokeStyle = line; ctx.lineWidth = 1;
            ctx.beginPath();
            for(let x=0; x<=w; x+=size) { ctx.moveTo(x,0); ctx.lineTo(x,h); }
            for(let y=0; y<=h; y+=size) { ctx.moveTo(0,y); ctx.lineTo(w,y); }
            ctx.stroke();

            const grad = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, radius);
            grad.addColorStop(0, glow); grad.addColorStop(1, 'transparent');
            ctx.strokeStyle = grad; ctx.lineWidth = 2;
            ctx.beginPath();
            const startX = Math.floor((mouse.x-radius)/size)*size;
            const startY = Math.floor((mouse.y-radius)/size)*size;
            for(let x=startX; x<=mouse.x+radius; x+=size) { if(x<0||x>w)continue; ctx.moveTo(x, mouse.y-radius); ctx.lineTo(x, mouse.y+radius); }
            for(let y=startY; y<=mouse.y+radius; y+=size) { if(y<0||y>h)continue; ctx.moveTo(mouse.x-radius, y); ctx.lineTo(mouse.x+radius, y); }
            ctx.stroke();
            requestAnimationFrame(draw);
        };
        draw();
    }

    // --- 2. NAVIGATION GLIDER ---
    const navLinks = document.querySelectorAll('.nav-link');
    const glider = document.querySelector('.nav-glider');
    if (glider && navLinks.length) {
        const moveGlider = (el) => {
            gsap.to(glider, { 
                x: el.offsetLeft, 
                width: el.offsetWidth, 
                opacity: 1, 
                duration: 0.45, 
                ease: "power4.out" 
            });
        };
        const activeLink = document.querySelector('.nav-link.active');
        if(activeLink) setTimeout(() => moveGlider(activeLink), 200);
        
        navLinks.forEach(link => {
            link.addEventListener('mouseenter', () => moveGlider(link));
            link.addEventListener('mouseleave', () => activeLink ? moveGlider(activeLink) : gsap.to(glider, { opacity: 0 }));
        });
    }

    // --- 3. CUSTOM CURSOR ---
    const cursorDot = document.querySelector('.custom-cursor-dot');
    const cursorOutline = document.querySelector('.custom-cursor-outline');
    if (cursorDot && !REDUCED_MOTION && window.matchMedia('(pointer: fine)').matches) {
        document.body.classList.add('custom-cursor-active');
        let mx=0, my=0, ox=0, oy=0;
        window.addEventListener('mousemove', e => { 
            mx = e.clientX; my = e.clientY; 
            gsap.set(cursorDot, {x:mx, y:my}); 
        });
        const loop = () => { 
            ox += (mx-ox)*0.12; oy += (my-oy)*0.12; 
            gsap.set(cursorOutline, {x:ox, y:oy}); 
            requestAnimationFrame(loop); 
        };
        loop();
        document.querySelectorAll('a, button, input, .achieve-item, .project-card, .writing-item').forEach(el => {
            el.addEventListener('mouseenter', () => { 
                gsap.to(cursorOutline, {width:64, height:64, backgroundColor:'rgba(128,128,128,0.1)', borderColor:'transparent'}); 
                gsap.to(cursorDot, {scale:0.4}); 
            });
            el.addEventListener('mouseleave', () => { 
                gsap.to(cursorOutline, {width:34, height:34, backgroundColor:'transparent', borderColor:'var(--cursor-border)'}); 
                gsap.to(cursorDot, {scale:1}); 
            });
        });
    }

    // --- 4. SEARCH SYSTEM ---
    const setupSearch = (inputId, clearId, itemsClass, categoryAttr) => {
        const input = document.getElementById(inputId);
        const clear = document.getElementById(clearId);
        const items = document.querySelectorAll(itemsClass);
        if (!input) return;

        const filter = () => {
            const query = input.value.toLowerCase();
            if(clear) clear.classList.toggle('visible', query.length > 0);
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                const tags = (item.getAttribute(categoryAttr) || "").toLowerCase();
                const isMatch = text.includes(query) || tags.includes(query);
                
                if(isMatch) {
                    item.style.display = itemsClass.includes('writing') ? 'grid' : 'flex';
                    gsap.to(item, { opacity: 1, y: 0, duration: 0.4 });
                } else {
                    item.style.display = 'none';
                    gsap.set(item, { opacity: 0, y: 15 });
                }
            });
        };
        input.addEventListener('input', filter);
        if(clear) clear.addEventListener('click', () => { input.value = ""; filter(); input.focus(); });
    };
    setupSearch('search-work', 'clear-work', '.project-card', 'data-category');
    setupSearch('search-blog', 'clear-blog', '.writing-item', 'data-category');

    // --- 5. TESTIMONIALS ---
    const track = document.querySelector('.t-track');
    const slides = document.querySelectorAll('.t-slide');
    if(track && slides.length) {
        let idx = 0;
        const update = () => { 
            track.style.transform = `translateX(-${idx * 100}%)`; 
            slides.forEach((s, i) => s.classList.toggle('active', i === idx)); 
        };
        document.getElementById('t-next')?.addEventListener('click', () => { idx = (idx + 1) % slides.length; update(); });
        document.getElementById('t-prev')?.addEventListener('click', () => { idx = (idx - 1 + slides.length) % slides.length; update(); });
    }

    // --- 6. GSAP REVEALS ---
    if (!REDUCED_MOTION && window.gsap && window.ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);
        document.querySelectorAll('.text-reveal-wrap').forEach(w => {
            const f = w.querySelector('.text-fill');
            if(f) gsap.to(f, { 
                clipPath: 'inset(0 0% 0 0)', 
                ease: "none",
                scrollTrigger: { trigger: w, start: 'top 90%', end: 'top 40%', scrub: 1 } 
            });
        });
        document.querySelectorAll(".reveal-on-scroll").forEach(el => {
            gsap.fromTo(el, {y:30, opacity:0}, { 
                y:0, opacity:1, duration:0.8, ease: "power2.out",
                scrollTrigger: { trigger: el, start: "top 92%" } 
            });
        });
    }

    // Mobile Menu
    const mBtn = document.querySelector('.mobile-nav-toggle');
    if(mBtn) {
        mBtn.addEventListener('click', () => document.body.classList.toggle('menu-open'));
        document.querySelectorAll('.mobile-nav-links a').forEach(a => {
            a.addEventListener('click', () => document.body.classList.remove('menu-open'));
        });
    }
});
