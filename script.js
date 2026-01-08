document.addEventListener('DOMContentLoaded', () => {
    
    // --- CONFIGURATION ---
    const REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // --- 1. CUSTOM CURSOR (Refined) ---
    const cursorDot = document.querySelector('.custom-cursor-dot');
    const cursorOutline = document.querySelector('.custom-cursor-outline');
    
    if (!REDUCED_MOTION && window.matchMedia('(pointer: fine)').matches && cursorDot) {
        let mouseX = window.innerWidth / 2;
        let mouseY = window.innerHeight / 2;
        let outlineX = mouseX;
        let outlineY = mouseY;

        window.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
            // Dot moves instantly
            cursorDot.style.transform = `translate(${mouseX}px, ${mouseY}px) translate(-50%, -50%)`;
        });

        // Loop for smooth outline following
        const animateCursor = () => {
            outlineX += (mouseX - outlineX) * 0.15;
            outlineY += (mouseY - outlineY) * 0.15;
            cursorOutline.style.transform = `translate(${outlineX}px, ${outlineY}px) translate(-50%, -50%)`;
            requestAnimationFrame(animateCursor);
        };
        animateCursor();

        // Hover states
        const clickables = document.querySelectorAll('a, button, input, select, textarea');
        clickables.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursorOutline.style.width = '60px';
                cursorOutline.style.height = '60px';
                cursorOutline.style.background = 'rgba(255,255,255,0.05)';
            });
            el.addEventListener('mouseleave', () => {
                cursorOutline.style.width = '40px';
                cursorOutline.style.height = '40px';
                cursorOutline.style.background = 'transparent';
            });
        });
    }

    // --- 2. NAV ACTIVE STATE & GLIDER ---
    const navLinks = document.querySelectorAll('.nav-link');
    const glider = document.querySelector('.nav-glider');
    const navPill = document.querySelector('.nav-pill');
    
    // Set Active Link based on URL
    const currentPath = window.location.pathname;
    navLinks.forEach(link => {
        link.classList.remove('active');
        const href = link.getAttribute('href');
        if (href === currentPath || (href !== '/index.html' && currentPath.includes(href))) {
            link.classList.add('active');
        } else if (currentPath === '/' && href === '/index.html') {
            link.classList.add('active');
        }
    });

    if (glider && navPill) {
        function moveGlider(element) {
            if (!element) return;
            // Calculate relative position inside the nav-pill
            const rect = element.getBoundingClientRect();
            const parentRect = navPill.getBoundingClientRect();
            
            const relLeft = rect.left - parentRect.left;
            const relTop = rect.top - parentRect.top; // Should be consistent usually
            
            // Animate using GSAP for smoothness
            gsap.to(glider, {
                x: relLeft,
                y: relTop,
                width: rect.width,
                height: rect.height,
                opacity: 1,
                duration: 0.3,
                ease: "power2.out"
            });
        }

        navLinks.forEach(link => {
            link.addEventListener('mouseenter', () => moveGlider(link));
            // On mouse leave, return to active link if it exists inside
            link.addEventListener('mouseleave', () => {
                const active = document.querySelector('.nav-link.active');
                if (active) moveGlider(active);
                else gsap.to(glider, { opacity: 0 });
            });
        });

        // Initialize on Active
        const initialActive = document.querySelector('.nav-link.active');
        if (initialActive) {
            // Small timeout to wait for layout
            setTimeout(() => moveGlider(initialActive), 100);
        }
    }

    // --- 3. FAQ ACCORDION ---
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const trigger = item.querySelector('.faq-trigger');
        const answer = item.querySelector('.faq-answer');
        
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const isActive = item.classList.contains('active');
            
            // Close others
            faqItems.forEach(other => {
                other.classList.remove('active');
                gsap.to(other.querySelector('.faq-answer'), { height: 0, duration: 0.3 });
            });

            if (!isActive) {
                item.classList.add('active');
                gsap.set(answer, { height: "auto" });
                gsap.from(answer, { height: 0, duration: 0.3, ease: "power2.out" });
            }
        });
    });

    // --- 4. ANIMATIONS (GSAP) ---
    // Ensure content is visible if JS/GSAP fails by setting opacity:1 in CSS.
    // We use .fromTo here to handle the "hidden" start state programmatically.
    if (window.gsap && window.ScrollTrigger && !REDUCED_MOTION) {
        const gsap = window.gsap;
        const ScrollTrigger = window.ScrollTrigger;
        gsap.registerPlugin(ScrollTrigger);

        // Page Load Sequence (Hero)
        const timeline = gsap.timeline({ defaults: { ease: "power3.out" } });
        
        // Use fromTo to strictly define start and end states
        timeline.fromTo(".nav-wrapper", { y: -20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8 })
                .fromTo(".hero-pills-row", { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8 }, "-=0.6")
                .fromTo(".hero-title", { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 1 }, "-=0.6")
                .fromTo(".body-large.fade-in", { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8 }, "-=0.8")
                .fromTo(".hero-actions", { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8 }, "-=0.8");

        // Scroll Reveals
        const revealElements = document.querySelectorAll(".reveal-on-scroll");
        revealElements.forEach(el => {
            gsap.fromTo(el, 
                { y: 40, opacity: 0 },
                {
                    y: 0, opacity: 1, duration: 0.8, ease: "power2.out",
                    scrollTrigger: {
                        trigger: el,
                        start: "top 85%"
                    }
                }
            );
        });

        // Project Card Hover Effect (subtle parallax on image)
        const cards = document.querySelectorAll('.project-card');
        cards.forEach(card => {
            const img = card.querySelector('img');
            card.addEventListener('mouseenter', () => {
                gsap.to(img, { scale: 1.05, duration: 0.5, ease: "power2.out" });
            });
            card.addEventListener('mouseleave', () => {
                gsap.to(img, { scale: 1, duration: 0.5, ease: "power2.out" });
            });
        });

    } 
    // Fallback handled by CSS opacity: 1

    // --- 5. TIME DISPLAY ---
    const timeDisplay = document.getElementById('time-display');
    if (timeDisplay) {
        const updateTime = () => {
            const now = new Date();
            const options = { timeZone: 'Asia/Kathmandu', hour: '2-digit', minute: '2-digit', hour12: true };
            try {
                const timeString = now.toLocaleTimeString('en-US', options);
                timeDisplay.textContent = `${timeString} KTH`;
            } catch (e) {
                timeDisplay.textContent = "Kathmandu";
            }
        };
        updateTime();
        setInterval(updateTime, 1000);
    }
    
    // --- 6. FORM SUBMIT SIMULATION ---
    const form = document.getElementById('contact-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = form.querySelector('button');
            const originalText = btn.textContent;
            
            btn.textContent = "Sending...";
            btn.style.opacity = "0.7";
            
            setTimeout(() => {
                btn.textContent = "Message Sent!";
                btn.style.background = "#ffffff";
                btn.style.color = "#000000";
                btn.style.opacity = "1";
                form.reset();
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.background = "";
                    btn.style.color = "";
                }, 3000);
            }, 1500);
        });
    }
});