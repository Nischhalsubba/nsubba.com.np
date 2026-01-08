document.addEventListener('DOMContentLoaded', () => {
    console.log("Portfolio Script Loaded");

    // Initialize Lucide Icons
    if (window.lucide) {
        lucide.createIcons();
    }

    // GSAP Animations
    const gsap = window.gsap;
    const ScrollTrigger = window.ScrollTrigger;
    
    if (gsap && ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);

        // 1. Hero Animations
        // We use .from() so the elements start at opacity 1 in CSS (visible)
        // and GSAP snaps them to 0 immediately and animates to 1.
        const tl = gsap.timeline();
        tl.from(".fade-in", {
            y: 30,
            opacity: 0,
            duration: 1,
            stagger: 0.2,
            ease: "power3.out"
        });

        // 2. Scroll Reveals
        const revealElements = document.querySelectorAll(".reveal-on-scroll");
        revealElements.forEach((element) => {
            gsap.from(element, {
                scrollTrigger: {
                    trigger: element,
                    start: "top 85%",
                    toggleActions: "play none none reverse"
                },
                y: 40,
                opacity: 0,
                duration: 1,
                ease: "power2.out"
            });
        });
    }

    // 3. Navigation Active State
    const sections = document.querySelectorAll("section");
    const navItems = document.querySelectorAll(".nav-item");

    window.addEventListener("scroll", () => {
        let current = "";
        const scrollY = window.pageYOffset;
        
        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            // Logic: if we have scrolled past the top - 1/3 of screen
            if (scrollY >= sectionTop - window.innerHeight / 3) {
                current = section.getAttribute("id");
            }
        });

        navItems.forEach((item) => {
            item.classList.remove("active");
            if (item.getAttribute("href").includes(current)) {
                item.classList.add("active");
            }
        });
    });
});