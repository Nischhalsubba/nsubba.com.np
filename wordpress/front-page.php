<?php get_header(); ?>

    <main class="container">
      
      <!-- HERO -->
      <section class="hero-section center-aligned-hero">
        <div class="ticker-wrap reveal-on-scroll">
            <div class="ticker">
                <?php 
                $items = explode(',', get_theme_mod('ticker_items', 'Design Systems, Enterprise UX, Web3 Strategy'));
                foreach($items as $item) echo '<span class="ticker-pill">'.trim($item).'</span>';
                ?>
            </div>
        </div>

        <h1 class="hero-title reveal-on-scroll">
          <span class="text-reveal-wrap">
            <span class="text-outline"><?php echo get_theme_mod('hero_h1_line1', 'Crafting scalable'); ?></span>
            <span class="text-fill"><?php echo get_theme_mod('hero_h1_line1', 'Crafting scalable'); ?></span>
          </span><br />
          <span class="text-reveal-wrap">
            <span class="text-outline"><?php echo get_theme_mod('hero_h1_line2', 'digital products.'); ?></span>
            <span class="text-fill"><?php echo get_theme_mod('hero_h1_line2', 'digital products.'); ?></span>
          </span>
        </h1>

        <p class="body-large reveal-on-scroll" style="margin: 0 auto 40px auto; max-width: 600px;">
            <?php echo get_bloginfo('description'); ?>
        </p>
        
        <div class="hero-actions reveal-on-scroll" style="display: flex; gap: 16px; justify-content: center;">
          <a href="<?php echo home_url('/work'); ?>" class="btn btn-primary">View Projects</a>
          <a href="<?php echo home_url('/about'); ?>" class="btn btn-secondary">Read Bio</a>
        </div>

        <div class="hero-portrait-container reveal-on-scroll" style="margin-top: 80px; max-width: 600px; margin-left: auto; margin-right: auto;">
            <?php $h_img = get_theme_mod('hero_img', 'https://i.imgur.com/ixsEpYM.png'); ?>
            <img src="<?php echo esc_url($h_img); ?>" alt="Portrait" class="hero-portrait-img img-blend-gradient" style="width: 100%; border-radius: 20px; opacity: 0.9;" />
        </div>
      </section>

      <!-- PROJECTS -->
      <section class="section-container">
        <h2 class="section-title reveal-on-scroll">Selected Work</h2>
        <div class="project-grid">
           <?php 
           $projects = new WP_Query(array('post_type' => 'project', 'posts_per_page' => 3));
           if($projects->have_posts()): while($projects->have_posts()): $projects->the_post();
               $thumb = get_the_post_thumbnail_url(get_the_ID(), 'large');
           ?>
           <a href="<?php the_permalink(); ?>" class="project-card reveal-on-scroll">
               <div class="card-media-wrap">
                   <?php if($thumb): ?><img src="<?php echo esc_url($thumb); ?>"><?php else: ?><div style="height:100%; background:var(--bg-surface);"></div><?php endif; ?>
               </div>
               <div class="card-content">
                   <h3><?php the_title(); ?></h3>
                   <div class="card-meta-line"><span><?php echo get_the_date('Y'); ?></span></div>
               </div>
           </a>
           <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>
        <div style="text-align:center; margin-top:60px;">
            <a href="<?php echo home_url('/work'); ?>" class="btn btn-secondary">View All Work</a>
        </div>
      </section>

      <!-- TESTIMONIALS -->
      <?php if(get_theme_mod('testim_1_quote')): ?>
      <section class="testimonial-section reveal-on-scroll" style="padding: 120px 0; text-align: center;">
         <h2 class="section-title">Kind Words</h2>
         <div class="t-track">
             <?php for($i=1; $i<=3; $i++): 
                $q = get_theme_mod("testim_{$i}_quote"); $a = get_theme_mod("testim_{$i}_auth");
                if($q): ?>
                <div class="t-slide <?php echo $i===1?'active':''; ?>" style="margin-bottom: 40px;">
                    <p class="t-quote" style="font-size: 2rem; margin-bottom: 20px;">"<?php echo esc_html($q); ?>"</p>
                    <div class="t-author"><h5><?php echo esc_html($a); ?></h5></div>
                </div>
             <?php endif; endfor; ?>
         </div>
      </section>
      <?php endif; ?>

    </main>

<?php get_footer(); ?>