document.addEventListener('DOMContentLoaded', () => {
    console.log("Script Initialized");

    // Initialize Lucide Icons
    if (window.lucide) {
        lucide.createIcons();
    }

    // GSAP Setup
    const gsap = window.gsap;
    const ScrollTrigger = window.ScrollTrigger;
    
    if (gsap && ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);

        // 1. Hero Animations (Timeline)
        const tl = gsap.timeline();
        
        // Ensure initial state
        gsap.set(".fade-in", { y: 30, opacity: 0 });
        
        tl.to(".fade-in", {
            y: 0,
            opacity: 1,
            duration: 1,
            stagger: 0.15,
            ease: "power3.out",
            delay: 0.2
        });

        // 2. Parallax Effect (Requirement 8)
        // Only run if image exists
        const heroImgWrapper = document.querySelector('.hero-image-wrapper');
        const heroImg = document.querySelector('.hero-img');
        
        if (heroImgWrapper && heroImg) {
            gsap.to(heroImg, {
                yPercent: 20, // Moves image down slowly
                ease: "none",
                scrollTrigger: {
                    trigger: heroImgWrapper,
                    start: "top top", 
                    end: "bottom top",
                    scrub: true
                }
            });
        }

        // 3. Scroll Reveals
        const revealElements = document.querySelectorAll(".reveal-on-scroll");
        revealElements.forEach((element) => {
            gsap.fromTo(element, 
                { y: 50, opacity: 0 },
                {
                    y: 0,
                    opacity: 1,
                    duration: 1,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: element,
                        start: "top 85%", // Triggers when top of element hits 85% viewport
                        toggleActions: "play none none reverse"
                    }
                }
            );
        });

        // 4. Staggered Blog/List Animation
        // Use batch to handle dynamic lists better
        ScrollTrigger.batch(".blog-row", {
            start: "top 85%",
            onEnter: batch => gsap.to(batch, {
                opacity: 1, 
                y: 0, 
                stagger: 0.2, 
                duration: 0.8, 
                ease: "power2.out"
            }),
            onLeaveBack: batch => gsap.to(batch, { opacity: 0, y: 30 }) // Optional fade out
        });
        
        // Initial set for batch items
        gsap.set(".blog-row", { y: 30, opacity: 0 });
    }

    // 5. Custom Cursor (Requirement 4)
    // Create cursor elements dynamically
    const cursorDot = document.createElement('div');
    const cursorOutline = document.createElement('div');
    cursorDot.className = 'cursor-dot';
    cursorOutline.className = 'cursor-outline';
    document.body.appendChild(cursorDot);
    document.body.appendChild(cursorOutline);

    // Track mouse
    window.addEventListener('mousemove', (e) => {
        const posX = e.clientX;
        const posY = e.clientY;

        // Dot follows instantly
        cursorDot.style.left = `${posX}px`;
        cursorDot.style.top = `${posY}px`;

        // Outline follows with lag
        gsap.to(cursorOutline, {
            x: posX,
            y: posY,
            duration: 0.15,
            ease: "power2.out"
        });
    });

    // Hover states
    const interactiveElements = document.querySelectorAll('a, button, .clickable, .project-card, .blog-row');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            document.body.classList.add('hovering');
        });
        el.addEventListener('mouseleave', () => {
            document.body.classList.remove('hovering');
        });
    });

    // 6. Testimonial Carousel (Requirement 7)
    const slides = document.querySelectorAll('.testimonial-slide');
    const dotsContainer = document.querySelector('.testi-dots');
    
    if (slides.length > 0 && dotsContainer) {
        let currentSlide = 0;
        const totalSlides = slides.length;
        let slideInterval;

        // Create dots
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            
            dot.addEventListener('click', () => {
                goToSlide(index);
                resetTimer();
            });
            dotsContainer.appendChild(dot);
        });

        const dots = document.querySelectorAll('.dot');

        function goToSlide(index) {
            // Hide all
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));

            // Show target
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            currentSlide = index;
        }

        function nextSlide() {
            let next = (currentSlide + 1) % totalSlides;
            goToSlide(next);
        }

        function startTimer() {
            slideInterval = setInterval(nextSlide, 5000); // 5s interval
        }

        function resetTimer() {
            clearInterval(slideInterval);
            startTimer();
        }

        startTimer();
    }

    // 7. Nav Active State
    const navItems = document.querySelectorAll(".nav-item");
    const currentPath = window.location.pathname;
    
    navItems.forEach(item => {
        item.classList.remove('active');
        const href = item.getAttribute('href');
        
        // Simple logic for active state
        if (href === 'index.html' && (currentPath.endsWith('index.html') || currentPath === '/')) {
            item.classList.add('active');
        } else if (href.includes('.html') && currentPath.includes(href)) {
            item.classList.add('active');
        } else if (href.startsWith('#')) {
            // Anchor link check handled by scroll listener if needed, but keeping it simple for now
        }
    });

});