document.addEventListener('DOMContentLoaded', () => {
    
    // --- CONFIGURATION ---
    const REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // --- 1. SCANNER BEAM INTERACTION (Refined) ---
    const scanner = document.querySelector('.grid-scanner');
    if (scanner && !REDUCED_MOTION) {
        let scannerY = 0;
        let direction = 1;
        let baseSpeed = 1.0; 
        let currentSpeed = baseSpeed;
        
        let mouseX = 0;
        let mouseY = 0;
        window.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        function animateScanner() {
            const windowHeight = window.innerHeight;
            
            // Interaction: If mouse is close vertically, "glitch" direction or speed
            const distY = Math.abs(mouseY - scannerY);
            if (distY < 60) {
                 if (Math.random() > 0.92) direction *= -1; // Random direction flip
                 currentSpeed = 4.0; // Speed up near mouse
            } else {
                 currentSpeed += (baseSpeed - currentSpeed) * 0.1; // Ease back to normal
            }

            scannerY += currentSpeed * direction;

            // Bounce at edges
            if (scannerY > windowHeight) {
                scannerY = windowHeight;
                direction = -1;
            } else if (scannerY < 0) {
                scannerY = 0;
                direction = 1;
            }

            scanner.style.transform = `translateY(${scannerY}px)`;
            requestAnimationFrame(animateScanner);
        }

        requestAnimationFrame(animateScanner);
    }

    // --- 2. CUSTOM CURSOR ---
    const cursorDot = document.querySelector('.custom-cursor-dot');
    const cursorOutline = document.querySelector('.custom-cursor-outline');
    
    if (!REDUCED_MOTION && window.matchMedia('(pointer: fine)').matches && cursorDot) {
        let outlineX = window.innerWidth / 2;
        let outlineY = window.innerHeight / 2;
        let mouseX = outlineX;
        let mouseY = outlineY;

        window.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
            cursorDot.style.transform = `translate(${mouseX}px, ${mouseY}px) translate(-50%, -50%)`;
        });

        const animateCursor = () => {
            outlineX += (mouseX - outlineX) * 0.15;
            outlineY += (mouseY - outlineY) * 0.15;
            cursorOutline.style.transform = `translate(${outlineX}px, ${outlineY}px) translate(-50%, -50%)`;
            requestAnimationFrame(animateCursor);
        };
        animateCursor();

        const clickables = document.querySelectorAll('a, button, input, select, textarea');
        clickables.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursorOutline.style.width = '60px';
                cursorOutline.style.height = '60px';
                cursorOutline.style.background = 'rgba(255, 255, 255, 0.05)';
                cursorOutline.style.borderColor = 'rgba(255, 255, 255, 0.5)';
            });
            el.addEventListener('mouseleave', () => {
                cursorOutline.style.width = '40px';
                cursorOutline.style.height = '40px';
                cursorOutline.style.background = 'transparent';
                cursorOutline.style.borderColor = 'rgba(255, 255, 255, 0.2)';
            });
        });
    }

    // --- 3. NAV GLIDER ---
    const navLinks = document.querySelectorAll('.nav-link');
    const glider = document.querySelector('.nav-glider');
    const navPill = document.querySelector('.nav-pill');
    const currentPath = window.location.pathname;
    
    // Set Active Class
    navLinks.forEach(link => {
        link.classList.remove('active');
        const href = link.getAttribute('href');
        if (href === currentPath || (currentPath === '/' && href === '/index.html')) {
            link.classList.add('active');
        }
    });

    if (glider && navPill) {
        function moveGlider(element) {
            if (!element) return;
            const rect = element.getBoundingClientRect();
            const parentRect = navPill.getBoundingClientRect();
            
            gsap.to(glider, {
                x: rect.left - parentRect.left,
                y: rect.top - parentRect.top,
                width: rect.width,
                height: rect.height,
                opacity: 1,
                duration: 0.3,
                ease: "power2.out"
            });
        }

        navLinks.forEach(link => {
            link.addEventListener('mouseenter', () => moveGlider(link));
            link.addEventListener('mouseleave', () => {
                const active = document.querySelector('.nav-link.active');
                if (active) moveGlider(active);
                else gsap.to(glider, { opacity: 0 });
            });
        });

        const initialActive = document.querySelector('.nav-link.active');
        if (initialActive) setTimeout(() => moveGlider(initialActive), 100);
    }

    // --- 4. FAQ ACCORDION (Fixed) ---
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const trigger = item.querySelector('.faq-trigger');
        const answer = item.querySelector('.faq-answer');
        
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const isActive = item.classList.contains('active');
            
            // Close others
            faqItems.forEach(other => {
                if (other !== item && other.classList.contains('active')) {
                    other.classList.remove('active');
                    gsap.to(other.querySelector('.faq-answer'), { height: 0, duration: 0.3, ease: "power2.out" });
                }
            });

            if (!isActive) {
                item.classList.add('active');
                // Use 'auto' to let GSAP calculate height
                gsap.to(answer, { height: "auto", duration: 0.3, ease: "power2.out" });
            } else {
                item.classList.remove('active');
                gsap.to(answer, { height: 0, duration: 0.3, ease: "power2.out" });
            }
        });
    });

    // --- 5. ANIMATIONS (GSAP) ---
    if (window.gsap && window.ScrollTrigger && !REDUCED_MOTION) {
        gsap.registerPlugin(ScrollTrigger);

        // A. Hero Load
        const timeline = gsap.timeline({ defaults: { ease: "power3.out" } });
        timeline.fromTo(".nav-wrapper", { y: -20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8 })
                .fromTo(".ticker-container", { opacity: 0 }, { opacity: 1, duration: 0.8 }, "-=0.6")
                .fromTo(".hero-title", { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 1 }, "-=0.6")
                .fromTo(".body-large.fade-in", { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8 }, "-=0.8")
                .fromTo(".hero-actions", { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8 }, "-=0.8")
                .fromTo(".hero-visual", { opacity: 0, scale: 0.95 }, { opacity: 0.9, scale: 1, duration: 1.2 }, "-=0.5");

        // B. Scroll Reveal
        document.querySelectorAll(".reveal-on-scroll").forEach(el => {
            gsap.fromTo(el, 
                { y: 30, opacity: 0 },
                {
                    y: 0, opacity: 1, duration: 0.8, ease: "power2.out",
                    scrollTrigger: { trigger: el, start: "top 85%" }
                }
            );
        });

        // C. Text Reveal (Scrub Fix)
        document.querySelectorAll(".text-reveal-wrap").forEach(title => {
            const fill = title.querySelector(".text-fill");
            if (fill) {
                gsap.fromTo(fill, 
                    { clipPath: "inset(0 100% 0 0)" },
                    {
                        clipPath: "inset(0 0% 0 0)",
                        ease: "none",
                        scrollTrigger: {
                            trigger: title,
                            start: "top 90%", // Trigger earlier
                            end: "top 40%",   // End later
                            scrub: 1
                        }
                    }
                );
            }
        });
    }

    // --- 6. CONTACT FORM ---
    const form = document.getElementById('contact-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = form.querySelector('button');
            const successMsg = form.querySelector('.form-success-msg');
            const originalText = btn.textContent;
            
            btn.textContent = "Sending...";
            btn.style.opacity = "0.7";
            
            setTimeout(() => {
                btn.textContent = "Sent";
                btn.style.opacity = "1";
                form.reset();
                if(successMsg) successMsg.classList.add('visible');

                setTimeout(() => {
                    btn.textContent = originalText;
                    if(successMsg) successMsg.classList.remove('visible');
                }, 3000);
            }, 1500);
        });
    }
});