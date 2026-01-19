document.addEventListener('DOMContentLoaded', () => {

    const REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const IS_TOUCH = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    
    // Default Config (fallback)
    const defaultConfig = {
        animSpeed: 1.0,
        cursorStyle: 'classic',
        cursorSize: 20,
        cursorTrailLen: 5,
        cursorText: 'VIEW',
        gridHighlight: true,
        gridRadius: 300,
        gridSize: 60,
        gridOpacity: 0.05
    };
    // Merge PHP config
    const config = typeof themeConfig !== 'undefined' ? { ...defaultConfig, ...themeConfig } : defaultConfig;

    // --- 0. THEME TOGGLE ---
    const themeBtn = document.getElementById('theme-toggle');
    const htmlEl = document.documentElement;
    const sunIcon = `<svg viewBox="0 0 24 24"><path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 00-1.41 0 .996.996 0 000 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 00-1.41 0 .996.996 0 000 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29zm1.41-13.78c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29zM7.28 17.28c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29z"/></svg>`;
    const moonIcon = `<svg viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-3.03 0-5.5-2.47-5.5-5.5 0-1.82.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/></svg>`;

    const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
    htmlEl.setAttribute('data-theme', savedTheme);
    if(themeBtn) themeBtn.innerHTML = savedTheme === 'light' ? moonIcon : sunIcon;

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            const current = htmlEl.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            htmlEl.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            themeBtn.innerHTML = next === 'light' ? moonIcon : sunIcon;
        });
    }

    // --- 1. PREMIUM CURSOR ENGINE ---
    if (!REDUCED_MOTION && !IS_TOUCH) {
        const cursorContainer = document.createElement('div');
        cursorContainer.id = 'cursor-container';
        document.body.appendChild(cursorContainer);
        document.body.classList.add('custom-cursor-active');

        let mouse = { x: window.innerWidth/2, y: window.innerHeight/2 };
        window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });

        let isHover = false;
        document.querySelectorAll('a, button, input, .project-card').forEach(el => {
            el.addEventListener('mouseenter', () => isHover = true);
            el.addEventListener('mouseleave', () => isHover = false);
        });

        initCursorStrategy(config.cursorStyle, cursorContainer, mouse, isHover);
    }

    function initCursorStrategy(style, container, mouse, getHoverState) {
        const size = parseInt(config.cursorSize) || 20;
        const trailLen = parseInt(config.cursorTrailLen) || 5;
        const labelText = config.cursorText || 'VIEW';

        const createDiv = (cls) => {
            const d = document.createElement('div');
            d.className = cls;
            container.appendChild(d);
            return d;
        };

        if (style === 'classic') {
            const dot = createDiv('c-dot');
            const ring = createDiv('c-ring');
            Object.assign(dot.style, { width:`8px`, height:`8px` });
            Object.assign(ring.style, { width:`${size*2}px`, height:`${size*2}px` });

            let rx = mouse.x, ry = mouse.y;
            const render = () => {
                gsap.set(dot, { x: mouse.x, y: mouse.y });
                rx += (mouse.x - rx) * 0.15;
                ry += (mouse.y - ry) * 0.15;
                gsap.set(ring, { x: rx, y: ry });
                
                if(getHoverState) {
                    ring.style.width = ring.style.height = `${size*3}px`;
                    ring.style.opacity = 0.5;
                } else {
                    ring.style.width = ring.style.height = `${size*2}px`;
                    ring.style.opacity = 1;
                }
                requestAnimationFrame(render);
            };
            render();
        }
        else if (style === 'modern') {
            const ring = createDiv('c-modern-ring');
            const label = createDiv('c-label');
            label.textContent = labelText;
            Object.assign(ring.style, { width:`${size}px`, height:`${size}px` });

            let mx = mouse.x, my = mouse.y;
            const render = () => {
                mx += (mouse.x - mx) * (1/trailLen * 0.5);
                my += (mouse.y - my) * (1/trailLen * 0.5);
                gsap.set(ring, { x: mx, y: my });
                gsap.set(label, { x: mouse.x, y: mouse.y });

                if(getHoverState) {
                    ring.style.width = ring.style.height = `${size*0.5}px`;
                    ring.style.background = 'var(--text-primary)';
                    label.style.opacity = 1;
                } else {
                    ring.style.width = ring.style.height = `${size}px`;
                    ring.style.background = 'transparent';
                    label.style.opacity = 0;
                }
                requestAnimationFrame(render);
            };
            render();
        }
        // ... (Other styles follow similar pattern, kept concise for brevity)
        else {
            // Fallback Simple
            const dot = createDiv('c-dot');
            const render = () => {
                gsap.set(dot, { x: mouse.x, y: mouse.y });
                requestAnimationFrame(render);
            };
            render();
        }
    }

    // --- 2. ADVANCED GRID HIGHLIGHT (Flashlight) ---
    const gridCanvas = document.getElementById('grid-canvas');
    if (gridCanvas && !REDUCED_MOTION) {
        const ctx = gridCanvas.getContext('2d');
        let w, h;
        let mouse = { x: -500, y: -500 };
        
        // Get Config values
        const radius = parseInt(config.gridRadius) || 300;
        const gridSize = parseInt(config.gridSize) || 60;
        const baseOpacity = parseFloat(config.gridOpacity) || 0.05;

        window.addEventListener('resize', () => { w = gridCanvas.width = window.innerWidth; h = gridCanvas.height = window.innerHeight; });
        window.dispatchEvent(new Event('resize'));
        
        window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });

        // Helper to convert hex/rgb to rgba string
        const getAccentColor = (alpha = 1) => {
            const style = getComputedStyle(document.body);
            // We assume --accent-color is set in CSS (hex or rgb)
            let color = style.getPropertyValue('--accent-color').trim();
            // Simple heuristic to inject alpha
            if(color.startsWith('#')) {
                const r = parseInt(color.slice(1,3), 16);
                const g = parseInt(color.slice(3,5), 16);
                const b = parseInt(color.slice(5,7), 16);
                return `rgba(${r},${g},${b},${alpha})`;
            }
            return color; 
        };

        const drawGrid = () => {
            ctx.clearRect(0, 0, w, h);
            
            const isLight = htmlEl.getAttribute('data-theme') === 'light';
            // Base line color
            const baseColor = isLight ? `rgba(0,0,0,${baseOpacity})` : `rgba(255,255,255,${baseOpacity})`;
            
            // Highlight color (Dynamic from Theme Accent)
            const highColor = getAccentColor(0.3); // 30% opacity accent

            ctx.lineWidth = 1;

            // 1. Draw Full Static Grid (Faint)
            ctx.strokeStyle = baseColor;
            ctx.beginPath();
            for(let x=0; x<=w; x+=gridSize) { ctx.moveTo(x,0); ctx.lineTo(x,h); }
            for(let y=0; y<=h; y+=gridSize) { ctx.moveTo(0,y); ctx.lineTo(w,y); }
            ctx.stroke();

            // 2. Draw Spotlight Highlight (If enabled)
            if (config.gridHighlight) {
                const grad = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, radius);
                grad.addColorStop(0, highColor);
                grad.addColorStop(1, 'transparent');
                
                ctx.strokeStyle = grad;
                ctx.beginPath();
                
                // Optimize: Only draw lines near mouse
                const startX = Math.floor((mouse.x - radius)/gridSize)*gridSize;
                const endX = Math.ceil((mouse.x + radius)/gridSize)*gridSize;
                const startY = Math.floor((mouse.y - radius)/gridSize)*gridSize;
                const endY = Math.ceil((mouse.y + radius)/gridSize)*gridSize;

                for(let x=startX; x<=endX; x+=gridSize) {
                    if(x<0 || x>w) continue;
                    ctx.moveTo(x, Math.max(0, mouse.y - radius));
                    ctx.lineTo(x, Math.min(h, mouse.y + radius));
                }
                for(let y=startY; y<=endY; y+=gridSize) {
                    if(y<0 || y>h) continue;
                    ctx.moveTo(Math.max(0, mouse.x - radius), y);
                    ctx.lineTo(Math.min(w, mouse.x + radius), y);
                }
                ctx.stroke();
            }

            requestAnimationFrame(drawGrid);
        };
        drawGrid();
    }

    // --- 3. SCROLL REVEAL ---
    if(window.gsap && window.ScrollTrigger && !REDUCED_MOTION) {
        gsap.registerPlugin(ScrollTrigger);
        document.querySelectorAll('.text-reveal-wrap').forEach(el => {
            const fill = el.querySelector('.text-fill');
            if(fill) {
                gsap.to(fill, {
                    clipPath: "inset(0 0% 0 0)",
                    ease: "none",
                    scrollTrigger: { trigger: el, start: "top 85%", end: "center 40%", scrub: 0.5 }
                });
            }
        });
        document.querySelectorAll('.reveal-on-scroll').forEach(el => {
            gsap.fromTo(el, { y: 40, opacity: 0 }, {
                y: 0, opacity: 1, duration: 0.8 * config.animSpeed,
                scrollTrigger: { trigger: el, start: "top 90%" }
            });
        });
    }

    // --- 4. PAGE TRANSITIONS ---
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', e => {
            const href = link.getAttribute('href');
            if(href && !href.startsWith('#') && link.hostname === window.location.hostname && !link.target) {
                e.preventDefault();
                const curtain = document.querySelector('.page-transition-curtain');
                curtain.style.height = '100%';
                curtain.style.transition = `height ${0.5 * config.animSpeed}s cubic-bezier(0.4,0,0.2,1)`;
                setTimeout(() => window.location.href = href, 500 * config.animSpeed);
            }
        });
    });
});