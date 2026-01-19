
document.addEventListener('DOMContentLoaded', () => {

    const REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const IS_TOUCH = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    
    // Default Config (Merged with PHP localize)
    const defaultConfig = { 
        animSpeed: 1.0, 
        enableCursor: true,
        cursorStyle: 'classic', 
        gridHighlight: true,
        gridOpacity: 0.05,
        transStyle: 'fade'
    };
    const config = typeof themeConfig !== 'undefined' ? { ...defaultConfig, ...themeConfig } : defaultConfig;

    // --- 0. THEME TOGGLE ---
    const themeBtn = document.getElementById('theme-toggle');
    const htmlEl = document.documentElement;
    const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
    htmlEl.setAttribute('data-theme', savedTheme);

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            const current = htmlEl.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            htmlEl.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        });
    }

    // --- 1. AUTOMATIC HEADING WRAPPER ---
    document.querySelectorAll('h1, h2, h3').forEach(heading => {
        if(heading.classList.contains('text-reveal-wrap') || heading.querySelector('.text-outline') || heading.textContent.trim() === '') return;
        const originalText = heading.textContent;
        heading.classList.add('text-reveal-wrap');
        heading.innerHTML = ''; 
        const outlineSpan = document.createElement('span'); outlineSpan.className = 'text-outline'; outlineSpan.textContent = originalText;
        const fillSpan = document.createElement('span'); fillSpan.className = 'text-fill'; fillSpan.textContent = originalText;
        heading.appendChild(outlineSpan); heading.appendChild(fillSpan);
    });

    // --- 2. SCROLL ANIMATIONS ---
    if(window.gsap && window.ScrollTrigger && !REDUCED_MOTION) {
        gsap.registerPlugin(ScrollTrigger);
        const speed = parseFloat(config.animSpeed) || 1.0;

        document.querySelectorAll('.text-reveal-wrap').forEach(el => {
            const fill = el.querySelector('.text-fill');
            if(fill) {
                gsap.to(fill, {
                    clipPath: "inset(0 0% 0 0)",
                    ease: "none",
                    scrollTrigger: { 
                        trigger: el, 
                        start: "top 90%", 
                        end: "center 45%", 
                        scrub: 0.5 
                    }
                });
            }
        });

        document.querySelectorAll('.reveal-on-scroll').forEach(el => {
            gsap.fromTo(el, { y: 40, opacity: 0 }, {
                y: 0, opacity: 1, duration: 0.8 * speed,
                scrollTrigger: { trigger: el, start: "top 90%" }
            });
        });
    }

    // --- 3. PREMIUM CUSTOM CURSOR ENGINE (ALL 10 STYLES) ---
    if (!REDUCED_MOTION && !IS_TOUCH && config.cursorEnable) {
        const cursorContainer = document.createElement('div');
        cursorContainer.id = 'cursor-container';
        document.body.appendChild(cursorContainer);
        document.body.classList.add('custom-cursor-active');
        
        // Mode Classes
        if(config.cursorStyle === 'blend') document.body.classList.add('cursor-blend');
        if(config.cursorStyle === 'focus') document.body.classList.add('cursor-focus');

        let mouse = { x: window.innerWidth/2, y: window.innerHeight/2 };
        let lastMouse = { x: window.innerWidth/2, y: window.innerHeight/2 }; // For velocity
        
        window.addEventListener('mousemove', e => { 
            mouse.x = e.clientX; 
            mouse.y = e.clientY; 
        });

        let isHover = false;
        document.querySelectorAll('a, button, input, .project-card, .wp-block-button__link').forEach(el => {
            el.addEventListener('mouseenter', () => isHover = true);
            el.addEventListener('mouseleave', () => isHover = false);
        });

        // Create Elements
        const dot = document.createElement('div'); dot.className = 'custom-cursor-dot';
        const ring = document.createElement('div'); ring.className = 'custom-cursor-outline';
        
        // Conditionally append based on style
        const needsDot = ['classic', 'dot', 'blend', 'trail', 'magnetic', 'fluid', 'glitch'].includes(config.cursorStyle);
        const needsRing = ['classic', 'outline', 'blend', 'magnetic', 'fluid', 'focus', 'spotlight'].includes(config.cursorStyle);
        
        if(needsDot) cursorContainer.appendChild(dot);
        if(needsRing) cursorContainer.appendChild(ring);

        // Physics State
        let rx = mouse.x, ry = mouse.y; // Ring Pos
        let dx = mouse.x, dy = mouse.y; // Dot Pos
        let trailX = mouse.x, trailY = mouse.y;

        const renderCursor = () => {
            // Calculate velocity
            const velX = mouse.x - lastMouse.x;
            const velY = mouse.y - lastMouse.y;
            lastMouse.x = mouse.x;
            lastMouse.y = mouse.y;

            // Logic Switch
            switch(config.cursorStyle) {
                case 'dot':
                    gsap.set(dot, { x: mouse.x, y: mouse.y });
                    gsap.to(dot, { scale: isHover ? 2.5 : 1, duration: 0.2 });
                    break;

                case 'outline':
                    rx += (mouse.x - rx) * 0.15; ry += (mouse.y - ry) * 0.15;
                    gsap.set(ring, { x: rx, y: ry });
                    gsap.to(ring, { 
                        width: isHover ? 60 : 40, 
                        height: isHover ? 60 : 40,
                        opacity: isHover ? 0.5 : 1, 
                        duration: 0.3 
                    });
                    break;

                case 'blend':
                    gsap.set(dot, { x: mouse.x, y: mouse.y });
                    rx += (mouse.x - rx) * 0.15; ry += (mouse.y - ry) * 0.15;
                    gsap.set(ring, { x: rx, y: ry });
                    gsap.to(ring, { scale: isHover ? 1.5 : 1, duration: 0.3 });
                    break;

                case 'trail':
                    gsap.set(dot, { x: mouse.x, y: mouse.y });
                    // Pseudo trail effect via GSAP tweening a ghost element (simplified here to lag)
                    trailX += (mouse.x - trailX) * 0.1;
                    trailY += (mouse.y - trailY) * 0.1;
                    // Note: 'trail' implies we might want a tail. 
                    // For simplicity in this setup, we make the dot have a "laggy" ghost if we had one.
                    // Instead, let's make the dot itself have high lag.
                    gsap.to(dot, { x: mouse.x, y: mouse.y, duration: 0.1 }); 
                    break;

                case 'magnetic':
                    // Magnetic feel: Ring is slow/heavy, Dot is instant
                    rx += (mouse.x - rx) * 0.1; 
                    ry += (mouse.y - ry) * 0.1;
                    gsap.set(dot, { x: mouse.x, y: mouse.y });
                    gsap.set(ring, { x: rx, y: ry });
                    gsap.to(ring, { 
                        scale: isHover ? 1.2 : 1, 
                        borderColor: isHover ? 'var(--accent-color)' : 'var(--cursor-color)',
                        duration: 0.3 
                    });
                    break;

                case 'fluid':
                    // Fluid: Distort ring based on velocity
                    rx += (mouse.x - rx) * 0.12; 
                    ry += (mouse.y - ry) * 0.12;
                    const dist = Math.sqrt(velX*velX + velY*velY);
                    const scale = Math.min(dist * 0.005, 0.5); // Cap distortion
                    
                    gsap.set(dot, { x: mouse.x, y: mouse.y });
                    gsap.set(ring, { 
                        x: rx, 
                        y: ry, 
                        scaleX: 1 + scale, 
                        scaleY: 1 - scale, 
                        rotation: Math.atan2(velY, velX) * 180 / Math.PI 
                    });
                    break;

                case 'glitch':
                    // Jitter effect
                    const jitterX = Math.random() * 4 - 2;
                    const jitterY = Math.random() * 4 - 2;
                    gsap.set(dot, { x: mouse.x + jitterX, y: mouse.y + jitterY });
                    if(Math.random() > 0.95) dot.style.opacity = 0; else dot.style.opacity = 1;
                    break;

                case 'focus':
                    // Ring stays on mouse, rotates
                    gsap.set(ring, { x: mouse.x, y: mouse.y });
                    ring.style.borderStyle = 'dashed';
                    ring.style.animation = 'spin 4s linear infinite';
                    if(isHover) {
                        gsap.to(ring, { scale: 0.8, borderColor: 'var(--accent-color)', duration: 0.3 });
                    } else {
                        gsap.to(ring, { scale: 1.2, borderColor: 'var(--cursor-color)', duration: 0.3 });
                    }
                    break;

                case 'spotlight':
                    gsap.set(ring, { x: mouse.x, y: mouse.y });
                    break;

                default: // Classic
                    gsap.set(dot, { x: mouse.x, y: mouse.y });
                    rx += (mouse.x - rx) * 0.15; 
                    ry += (mouse.y - ry) * 0.15;
                    gsap.set(ring, { x: rx, y: ry });
                    
                    gsap.to(ring, { 
                        width: isHover ? 60 : 40, 
                        height: isHover ? 60 : 40, 
                        opacity: isHover ? 0.5 : 1,
                        duration: 0.3 
                    });
                    break;
            }
            requestAnimationFrame(renderCursor);
        };
        renderCursor();
    }

    // --- 4. GRID HIGHLIGHT ---
    const gridCanvas = document.getElementById('grid-canvas');
    if (gridCanvas && !REDUCED_MOTION && config.gridEnable) {
        const ctx = gridCanvas.getContext('2d');
        let w, h, mouse = { x: -500, y: -500 };
        const gridSize = 60;

        window.addEventListener('resize', () => { w = gridCanvas.width = window.innerWidth; h = gridCanvas.height = window.innerHeight; });
        window.dispatchEvent(new Event('resize'));
        window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });

        const drawGrid = () => {
            ctx.clearRect(0, 0, w, h);
            const isLight = htmlEl.getAttribute('data-theme') === 'light';
            const baseOp = parseFloat(config.gridOpacity) || 0.05;
            
            ctx.strokeStyle = isLight ? `rgba(0,0,0,${baseOp})` : `rgba(255,255,255,${baseOp})`;
            ctx.lineWidth = 1;
            ctx.beginPath();
            for(let x=0; x<=w; x+=gridSize) { ctx.moveTo(x,0); ctx.lineTo(x,h); }
            for(let y=0; y<=h; y+=gridSize) { ctx.moveTo(0,y); ctx.lineTo(w,y); }
            ctx.stroke();
            
            if(config.gridSpotlight) {
                const grad = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, 300);
                const accent = getComputedStyle(document.body).getPropertyValue('--accent-color').trim() || '#3B82F6';
                grad.addColorStop(0, accent + '40'); 
                grad.addColorStop(1, 'transparent');
                ctx.strokeStyle = grad;
                ctx.beginPath();
                const startX = Math.floor((mouse.x - 300)/gridSize)*gridSize;
                const endX = Math.ceil((mouse.x + 300)/gridSize)*gridSize;
                const startY = Math.floor((mouse.y - 300)/gridSize)*gridSize;
                const endY = Math.ceil((mouse.y + 300)/gridSize)*gridSize;
                for(let x=startX; x<=endX; x+=gridSize) { ctx.moveTo(x, Math.max(0, mouse.y - 300)); ctx.lineTo(x, Math.min(h, mouse.y + 300)); }
                for(let y=startY; y<=endY; y+=gridSize) { ctx.moveTo(Math.max(0, mouse.x - 300), y); ctx.lineTo(Math.min(w, mouse.x + 300), y); }
                ctx.stroke();
            }
            requestAnimationFrame(drawGrid);
        };
        drawGrid();
    }
    
    // --- 5. TESTIMONIAL CAROUSEL (Updated for Patterns) ---
    const tTracks = document.querySelectorAll('.t-track');
    tTracks.forEach(track => {
        let idx = 0;
        const slides = track.querySelectorAll('.t-slide');
        const prev = track.parentElement.querySelector('#t-prev') || track.parentElement.querySelector('.t-prev');
        const next = track.parentElement.querySelector('#t-next') || track.parentElement.querySelector('.t-next');
        
        const update = () => {
            track.style.transform = `translateX(-${idx * 100}%)`;
            slides.forEach((s,i) => s.classList.toggle('active', i===idx));
        }
        
        if(next) next.addEventListener('click', () => { idx = (idx < slides.length-1) ? idx+1 : 0; update(); });
        if(prev) prev.addEventListener('click', () => { idx = (idx > 0) ? idx-1 : slides.length-1; update(); });
    });
});
