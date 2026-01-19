<?php
/* Template Name: Products Page */
get_header(); ?>

    <main class="container">
      <section class="hero-section" style="min-height: 40vh;">
        <h1 class="hero-title fade-in">
            <span class="text-reveal-wrap">
                <span class="text-outline">Digital Products</span>
                <span class="text-fill">Digital Products</span>
            </span>
        </h1>
        <p class="body-large fade-in">High-quality resources for designers and developers.</p>
      </section>

      <section class="section-container">
        <div class="project-grid">
           <!-- UI Kit -->
           <a href="#" class="project-card reveal-on-scroll">
               <div class="card-media-wrap">
                   <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-ui-kit.svg" loading="lazy" alt="Refined UI Kit">
                   <div class="card-overlay"><span class="view-case-btn">View Product →</span></div>
               </div>
               <div class="card-content">
                   <h3>Refined UI Kit</h3>
                   <div class="card-meta-line"><span>Figma</span><span>$49</span></div>
               </div>
           </a>
           
           <!-- Design System -->
           <a href="#" class="project-card reveal-on-scroll">
               <div class="card-media-wrap">
                   <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-system.svg" loading="lazy" alt="System 2.0">
                   <div class="card-overlay"><span class="view-case-btn">View Product →</span></div>
               </div>
               <div class="card-content">
                   <h3>System 2.0</h3>
                   <div class="card-meta-line"><span>React + Figma</span><span>$129</span></div>
               </div>
           </a>

           <!-- Icons -->
           <a href="#" class="project-card reveal-on-scroll">
               <div class="card-media-wrap">
                   <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-icons.svg" loading="lazy" alt="Neon Icons">
                   <div class="card-overlay"><span class="view-case-btn">View Product →</span></div>
               </div>
               <div class="card-content">
                   <h3>Neon Icons</h3>
                   <div class="card-meta-line"><span>SVG</span><span>Free</span></div>
               </div>
           </a>
        </div>
      </section>
    </main>

<?php get_footer(); ?>