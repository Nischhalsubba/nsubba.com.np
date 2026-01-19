

<?php
/* Template Name: Work Page */
get_header(); ?>

    <main class="container">
      <section class="hero-section" style="min-height: 40vh;">
        <h1 class="hero-title reveal-on-scroll">
            <span class="text-reveal-wrap">
                <span class="text-outline">Selected Work</span>
                <span class="text-fill">Selected Work</span>
            </span>
        </h1>
        <p class="body-large reveal-on-scroll">A curated showcase of design systems, complex products, and interaction design.</p>
      </section>

      <div class="search-wrapper reveal-on-scroll">
          <input type="text" id="search-work" class="search-input" placeholder="Search by title, industry, or year...">
          <button id="clear-work" class="search-clear" aria-label="Clear">✕</button>
      </div>

      <!-- FILTER PILLS -->
      <div class="filter-row reveal-on-scroll">
          <button class="filter-btn active" data-filter="all">All Projects</button>
          <button class="filter-btn" data-filter="fintech">Fintech</button>
          <button class="filter-btn" data-filter="web3">Web3</button>
          <button class="filter-btn" data-filter="system">Design Systems</button>
          <button class="filter-btn" data-filter="ai">AI / Data</button>
      </div>

      <section class="section-container" style="padding-top: 0;">
        <div class="project-grid">
           <?php 
           // 1. DYNAMIC (Existing)
           $args = array('post_type' => 'project', 'posts_per_page' => -1);
           $projects = new WP_Query($args);
           if($projects->have_posts()):
               while($projects->have_posts()): $projects->the_post();
                   $cats_slug = get_project_cat_slugs(get_the_ID());
                   $year = get_post_meta(get_the_ID(), 'project_year', true);
                   $industry = get_post_meta(get_the_ID(), 'project_industry', true);
                   $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
           ?>
           <a href="<?php the_permalink(); ?>" class="project-card reveal-on-scroll" data-category="<?php echo esc_attr($cats_slug); ?>">
               <div class="card-media-wrap">
                   <?php if($thumb_url): ?>
                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title(); ?>">
                   <?php else: ?>
                    <div style="width:100%; height:100%; background: var(--bg-card); display:flex; align-items:center; justify-content:center; color: var(--text-tertiary);">No Image</div>
                   <?php endif; ?>
               </div>
               <div class="card-content">
                   <h3><?php the_title(); ?></h3>
                   <div class="card-meta-line">
                       <span><?php echo $industry ? esc_html($industry) : 'Design'; ?></span>
                       <span><?php echo $year ? esc_html($year) : get_the_date('Y'); ?></span>
                   </div>
               </div>
           </a>
           <?php endwhile; wp_reset_postdata(); endif; ?>

           <!-- 2. STATIC (New Comprehensive Cases) -->
           
           <!-- Case: Velonex (Web3) -->
           <a href="#" class="project-card reveal-on-scroll" data-category="web3 finance">
               <div class="card-media-wrap">
                   <img src="https://images.unsplash.com/photo-1642104704074-907c0698cbd9?q=80&w=1200&auto=format&fit=crop" alt="Velonex DeFi Dashboard">
               </div>
               <div class="card-content">
                   <h3>Velonex DeFi</h3>
                   <div class="card-meta-line"><span>Web3 Finance</span><span>2025</span></div>
               </div>
           </a>

           <!-- Case: Lumina (Health) -->
           <a href="#" class="project-card reveal-on-scroll" data-category="mobile app">
               <div class="card-media-wrap">
                   <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=1200&auto=format&fit=crop" alt="Lumina Health App">
               </div>
               <div class="card-content">
                   <h3>Lumina Health</h3>
                   <div class="card-meta-line"><span>Telemedicine App</span><span>2024</span></div>
               </div>
           </a>

           <!-- Case: AeroStream (SaaS) -->
           <a href="#" class="project-card reveal-on-scroll" data-category="system saas">
               <div class="card-media-wrap">
                   <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=1200&auto=format&fit=crop" alt="AeroStream Logistics">
               </div>
               <div class="card-content">
                   <h3>AeroStream</h3>
                   <div class="card-meta-line"><span>Logistics SaaS</span><span>2023</span></div>
               </div>
           </a>

           <!-- Case: Quantai (AI) -->
           <a href="#" class="project-card reveal-on-scroll" data-category="ai fintech">
               <div class="card-media-wrap">
                   <img src="https://images.unsplash.com/photo-1639322537228-f710d846310a?q=80&w=1200&auto=format&fit=crop" alt="Quantai Trading">
               </div>
               <div class="card-content">
                   <h3>Quantai</h3>
                   <div class="card-meta-line"><span>AI Trading Interface</span><span>2024</span></div>
               </div>
           </a>

           <!-- Case: CivicGrid (Smart City) -->
           <a href="#" class="project-card reveal-on-scroll" data-category="enterprise data">
               <div class="card-media-wrap">
                   <img src="https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?q=80&w=1200&auto=format&fit=crop" alt="CivicGrid Dashboard">
               </div>
               <div class="card-content">
                   <h3>CivicGrid</h3>
                   <div class="card-meta-line"><span>Smart City Dashboard</span><span>2023</span></div>
               </div>
           </a>

        </div>
      </section>
    </main>

<?php get_footer(); ?>