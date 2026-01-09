
document.addEventListener('DOMContentLoaded', () => {
    
    // Performance optimization: Check for reduced motion preference
    const REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const htmlEl = document.documentElement;

    // --- 0. THEME ENGINE ---
    const themeBtn = document.getElementById('theme-toggle');
    const DARK_IMG = "https://i.imgur.com/ixsEpYM.png";
    const LIGHT_IMG = "https://i.imgur.com/oFHdPUS.png";

    function setTheme(theme) {
        htmlEl.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        // Update images based on theme
        document.querySelectorAll('.hero-portrait-img, .footer-portrait-img, .profile-img').forEach(img => {
            img.src = theme === 'light' ? LIGHT_IMG : DARK_IMG;
        });
        
        // Update Toggle Icon
        if (themeBtn) {
            themeBtn.innerHTML = theme === 'light' ? 
                `<svg viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-3.03 0-5.5-2.47-5.5-5.5 0-1.82.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/></svg>` : 
                `<svg viewBox="0 0 24 24"><path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 00-1.41 0 .996.996 0 000 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 00-1.41 0 .996.996 0 000 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L5.99 4.58zm1.41-13.78c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29zM7.28 17.28c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29z"/></svg>`;
        }
    }

    const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
    setTheme(savedTheme);

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            const current = htmlEl.getAttribute('data-theme');
            setTheme(current === 'light' ? 'dark' : 'light');
        });
    }

    // --- 1. SPOTLIGHT GRID CANVAS ---
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
            
            const gridSize = 64; 
            const spotlightRadius = 450;
            const lineOpacity = isL ? 0.08 : 0.06;
            const glowOpacity = isL ? 0.12 : 0.2;

            // Base Grid
            ctx.strokeStyle = isL ? `rgba(0,0,0,${lineOpacity})` : `rgba(255,255,255,${lineOpacity})`;
            ctx.lineWidth = 1;
            ctx.beginPath();
            for(let x=0; x<=w; x+=gridSize) { ctx.moveTo(x,0); ctx.lineTo(x,h); }
            for(let y=0; y<=h; y+=gridSize) { ctx.moveTo(0,y); ctx.lineTo(w,y); }
            ctx.stroke();

            // Spotlight Glow
            const grad = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, spotlightRadius);
            grad.addColorStop(0, `rgba(59, 130, 246, ${glowOpacity})`);
            grad.addColorStop(1, 'transparent');
            
            ctx.strokeStyle = grad;
            ctx.lineWidth = 2;
            ctx.beginPath();
            
            // Only draw lines within the radial bounds for performance
            const startX = Math.floor((mouse.x - spotlightRadius) / gridSize) * gridSize;
            const startY = Math.floor((mouse.y - spotlightRadius) / gridSize) * gridSize;
            
            for(let x=startX; x <= mouse.x + spotlightRadius; x+=gridSize) {
                if(x < 0 || x > w) continue;
                ctx.moveTo(x, mouse.y - spotlightRadius);
                ctx.lineTo(x, mouse.y + spotlightRadius);
            }
            for(let y=startY; y <= mouse.y + spotlightRadius; y+=gridSize) {
                if(y < 0 || y > h) continue;
                ctx.moveTo(mouse.x - spotlightRadius, y);
                ctx.lineTo(mouse.x + spotlightRadius, y);
            }
            ctx.stroke();

            requestAnimationFrame(draw);
        };
        draw();
    }

    // --- 2. MAGNETIC CUSTOM CURSOR ---
    const cursorDot = document.querySelector('.custom-cursor-dot');
    const cursorOutline = document.querySelector('.custom-cursor-outline');
    
    if (cursorDot && !REDUCED_MOTION && window.matchMedia('(pointer: fine)').matches) {
        document.body.classList.add('custom-cursor-active');
        let mouseX = 0, mouseY = 0, outlineX = 0, outlineY = 0;

        window.addEventListener('mousemove', e => {
            mouseX = e.clientX;
            mouseY = e.clientY;
            gsap.set(cursorDot, { x: mouseX, y: mouseY });
        });

        const updateOutline = () => {
            outlineX += (mouseX - outlineX) * 0.12;
            outlineY += (mouseY - outlineY) * 0.12;
            gsap.set(cursorOutline, { x: outlineX, y: outlineY });
            requestAnimationFrame(updateOutline);
        };
        updateOutline();

        const magneticTargets = 'a, button, input, .achieve-item, .project-card, .writing-item';
        document.querySelectorAll(magneticTargets).forEach(el => {
            el.addEventListener('mouseenter', () => {
                gsap.to(cursorOutline, {
                    width: 72,
                    height: 72,
                    backgroundColor: 'rgba(59, 130, 246, 0.08)',
                    borderColor: 'transparent',
                    duration: 0.4,
                    ease: "power4.out"
                });
                gsap.to(cursorDot, { scale: 0.3, duration: 0.4 });
            });
            el.addEventListener('mouseleave', () => {
                gsap.to(cursorOutline, {
                    width: 44,
                    height: 44,
                    backgroundColor: 'transparent',
                    borderColor: 'var(--cursor-outline)',
                    duration: 0.4,
                    ease: "power4.out"
                });
                gsap.to(cursorDot, { scale: 1, duration: 0.4 });
            });
        });
    }

    // --- 3. HIGH PRECISION NAV GLIDER ---
    const navLinks = document.querySelectorAll('.nav-link');
    const glider = document.querySelector('.nav-glider');
    
    if (glider && navLinks.length) {
        const moveGlider = (el) => {
            gsap.to(glider, {
                x: el.offsetLeft,
                width: el.offsetWidth,
                opacity: 1,
                duration: 0.5,
                ease: "power4.out"
            });
        };

        const activeLink = document.querySelector('.nav-link.active');
        if (activeLink) {
            // Slight delay to ensure layout is ready
            setTimeout(() => moveGlider(activeLink), 250);
        }

        navLinks.forEach(link => {
            link.addEventListener('mouseenter', () => moveGlider(link));
            link.addEventListener('mouseleave', () => {
                const currentActive = document.querySelector('.nav-link.active');
                if (currentActive) moveGlider(currentActive);
                else gsap.to(glider, { opacity: 0, duration: 0.4 });
            });
        });

        // Re-align on resize
        window.addEventListener('resize', () => {
            const currentActive = document.querySelector('.nav-link.active');
            if (currentActive) moveGlider(currentActive);
        });
    }

    // --- 4. ADVANCED SEARCH SYSTEM ---
    const setupSearch = (inputId, clearId, itemsClass, categoryAttr) => {
        const input = document.getElementById(inputId);
        const clearBtn = document.getElementById(clearId);
        const items = document.querySelectorAll(itemsClass);
        if (!input) return;

        const performSearch = () => {
            const query = input.value.toLowerCase().trim();
            if (clearBtn) clearBtn.classList.toggle('visible', query.length > 0);

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                const tags = (item.getAttribute(categoryAttr) || "").toLowerCase();
                const isMatch = text.includes(query) || tags.includes(query);

                if (isMatch) {
                    item.style.display = itemsClass.includes('writing') ? 'grid' : 'flex';
                    gsap.to(item, { opacity: 1, y: 0, scale: 1, duration: 0.5, ease: "power2.out" });
                } else {
                    item.style.display = 'none';
                    gsap.set(item, { opacity: 0, y: 20, scale: 0.98 });
                }
            });
            // Refresh scroll triggers since layout changed
            if (window.ScrollTrigger) ScrollTrigger.refresh();
        };

        input.addEventListener('input', performSearch);
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                input.value = "";
                performSearch();
                input.focus();
            });
        }
    };
    setupSearch('search-work', 'clear-work', '.project-card', 'data-category');
    setupSearch('search-blog', 'clear-blog', '.writing-item', 'data-category');

    // --- 5. TESTIMONIAL CAROUSEL ---
    const track = document.querySelector('.t-track');
    const slides = document.querySelectorAll('.t-slide');
    if (track && slides.length) {
        let currentIndex = 0;
        const updateCarousel = () => {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
            slides.forEach((s, i) => s.classList.toggle('active', i === currentIndex));
        };
        
        document.getElementById('t-next')?.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % slides.length;
            updateCarousel();
        });
        document.getElementById('t-prev')?.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            updateCarousel();
        });
    }

    // --- 6. GSAP CINEMATIC REVEALS ---
    if (!REDUCED_MOTION && window.gsap && window.ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);

        // Fill-on-Scroll Text Reveal
        document.querySelectorAll('.text-reveal-wrap').forEach(wrapper => {
            const fill = wrapper.querySelector('.text-fill');
            if (fill) {
                gsap.to(fill, {
                    clipPath: 'inset(0 0% 0 0)',
                    ease: "none",
                    scrollTrigger: {
                        trigger: wrapper,
                        start: 'top 90%',
                        end: 'top 45%',
                        scrub: 1.2
                    }
                });
            }
        });

        // General Staggered Reveal
        document.querySelectorAll(".reveal-on-scroll").forEach(el => {
            gsap.fromTo(el, 
                { y: 60, opacity: 0 },
                { 
                    y: 0, 
                    opacity: 1, 
                    duration: 1, 
                    ease: "power3.out",
                    scrollTrigger: {
                        trigger: el,
                        start: "top 94%",
                        toggleActions: "play none none none"
                    }
                }
            );
        });
    }
});
