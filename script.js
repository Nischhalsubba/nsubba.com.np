document.addEventListener('DOMContentLoaded', () => {
    
    const REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // --- 1. SPOTLIGHT GRID CANVAS ---
    const canvas = document.getElementById('grid-canvas');
    if (canvas && !REDUCED_MOTION) {
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
            
            const gridSize = 60; 
            const spotlightRadius = 300; // Radius of light

            ctx.lineWidth = 1;

            // Helper to draw line
            const drawLine = (x1, y1, x2, y2) => {
                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x2, y2);
                ctx.stroke();
            };

            // We only need to draw lines relative to mouse for performance, 
            // but for simple grid, drawing all with distance calc is fine for modern PCs.
            
            for (let x = 0; x <= width; x += gridSize) {
                // Calculate distance from mouse to this vertical line (approx)
                // For a vertical line, distance is primarily |x - mouse.x| but we want radial.
                // Simpler: iterate segments or just use a mask.
                // Best visual: Draw full dark grid, then draw bright grid with mask.
            }

            // Efficient Approach:
            // 1. Draw base very faint grid
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.03)';
            ctx.beginPath();
            for(let x=0; x<=width; x+=gridSize) { ctx.moveTo(x,0); ctx.lineTo(x,height); }
            for(let y=0; y<=height; y+=gridSize) { ctx.moveTo(0,y); ctx.lineTo(width,y); }
            ctx.stroke();

            // 2. Draw Spotlight
            // Create a radial gradient at mouse position
            const grad = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, spotlightRadius);
            grad.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
            grad.addColorStop(1, 'rgba(59, 130, 246, 0)');

            ctx.strokeStyle = grad;
            ctx.beginPath();
            // Draw lines again, but this time they will take the gradient stroke
            // Only draw lines near mouse to save perf
            const startX = Math.floor((mouse.x - spotlightRadius)/gridSize) * gridSize;
            const endX = Math.floor((mouse.x + spotlightRadius)/gridSize) * gridSize;
            const startY = Math.floor((mouse.y - spotlightRadius)/gridSize) * gridSize;
            const endY = Math.floor((mouse.y + spotlightRadius)/gridSize) * gridSize;

            for(let x=startX; x<=endX; x+=gridSize) {
                 if(x<0 || x>width) continue;
                 ctx.moveTo(x, Math.max(0, mouse.y - spotlightRadius));
                 ctx.lineTo(x, Math.min(height, mouse.y + spotlightRadius));
            }
            for(let y=startY; y<=endY; y+=gridSize) {
                 if(y<0 || y>height) continue;
                 ctx.moveTo(Math.max(0, mouse.x - spotlightRadius), y);
                 ctx.lineTo(Math.min(width, mouse.x + spotlightRadius), y);
            }
            ctx.stroke();

            requestAnimationFrame(drawGrid);
        }
        drawGrid();
    }

    // --- 2. CUSTOM CURSOR ---
    const cursorDot = document.querySelector('.custom-cursor-dot');
    const cursorOutline = document.querySelector('.custom-cursor-outline');
    
    if (!REDUCED_MOTION && window.matchMedia('(pointer: fine)').matches && cursorDot) {
        let mouseX = -100, mouseY = -100;
        let outlineX = -100, outlineY = -100;

        window.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
            cursorDot.style.transform = `translate(${mouseX}px, ${mouseY}px) translate(-50%, -50%)`;
        });

        const animateCursor = () => {
            outlineX += (mouseX - outlineX) * 0.2;
            outlineY += (mouseY - outlineY) * 0.2;
            cursorOutline.style.transform = `translate(${outlineX}px, ${outlineY}px) translate(-50%, -50%)`;
            requestAnimationFrame(animateCursor);
        };
        animateCursor();
    }

    // --- 3. NAV GLIDER & LOGIC ---
    const navLinks = document.querySelectorAll('.nav-link');
    const glider = document.querySelector('.nav-glider');
    
    // Fix: Highlight active based on URL
    const page = window.location.pathname.split('/').pop() || 'index.html';
    let activeFound = false;
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === page) {
            link.classList.add('active');
            activeFound = true;
        }
    });
    // Fallback for root
    if (!activeFound && page === 'index.html') {
         const homeLink = document.querySelector('a[href="index.html"]');
         if(homeLink) homeLink.classList.add('active');
    }

    if (glider) {
        const moveGlider = (el) => {
            const rect = el.getBoundingClientRect();
            const parent = el.parentElement.getBoundingClientRect();
            gsap.to(glider, {
                x: rect.left - parent.left,
                width: rect.width,
                opacity: 1,
                duration: 0.3
            });
        };
        const activeLink = document.querySelector('.nav-link.active');
        if(activeLink) setTimeout(() => moveGlider(activeLink), 100);

        navLinks.forEach(link => {
            link.addEventListener('mouseenter', () => moveGlider(link));
            link.addEventListener('mouseleave', () => {
                if(activeLink) moveGlider(activeLink);
                else gsap.to(glider, { opacity: 0 });
            });
        });
    }

    // --- 4. PROJECT FILTERING ---
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projects = document.querySelectorAll('.project-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const category = btn.getAttribute('data-filter');
            
            projects.forEach(card => {
                const tags = card.getAttribute('data-category');
                if (category === 'all' || tags.includes(category)) {
                    card.classList.remove('hidden');
                    gsap.fromTo(card, {y: 20, opacity:0}, {y:0, opacity:1, duration: 0.4});
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });

    // --- 5. TESTIMONIAL CAROUSEL ---
    const tTrack = document.querySelector('.t-track');
    const tPrev = document.getElementById('t-prev');
    const tNext = document.getElementById('t-next');
    const tSlides = document.querySelectorAll('.t-slide');
    
    if(tTrack && tSlides.length > 0) {
        let tIndex = 0;
        const updateT = () => {
            tTrack.style.transform = `translateX(-${tIndex * 100}%)`;
            tSlides.forEach((s, i) => {
                s.classList.toggle('active', i === tIndex);
            });
        };
        
        if(tPrev) tPrev.addEventListener('click', () => {
            tIndex = (tIndex > 0) ? tIndex - 1 : tSlides.length - 1;
            updateT();
        });
        
        if(tNext) tNext.addEventListener('click', () => {
            tIndex = (tIndex < tSlides.length - 1) ? tIndex + 1 : 0;
            updateT();
        });
        
        // Auto play
        setInterval(() => {
           // tIndex = (tIndex < tSlides.length - 1) ? tIndex + 1 : 0;
           // updateT(); 
        }, 6000);
        updateT();
    }

    // --- 6. FAQ ACCORDION ---
    document.querySelectorAll('.faq-question').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.parentElement;
            item.classList.toggle('active');
        });
    });

    // --- 7. GSAP ---
    if (window.gsap && window.ScrollTrigger && !REDUCED_MOTION) {
        gsap.registerPlugin(ScrollTrigger);
        document.querySelectorAll(".reveal-on-scroll").forEach(el => {
            gsap.fromTo(el, {y:30, opacity:0}, {
                y:0, opacity:1, duration:0.8,
                scrollTrigger: { trigger: el, start: "top 85%" }
            });
        });
    }
});