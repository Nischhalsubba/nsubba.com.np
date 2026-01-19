
<?php get_header(); ?>

    <?php 
    // Hero Customizer Vars
    $layout_style = get_theme_mod('hero_layout_style', 'hero-v1');
    $h1_line1 = get_theme_mod('hero_h1_line1', 'Crafting scalable');
    $h1_line2 = get_theme_mod('hero_h1_line2', 'digital products.');
    $hero_desc = get_theme_mod('hero_desc', "I’m Nischhal Raj Subba...");
    
    $btn1_text = get_theme_mod('hero_btn_1_text', 'View Projects');
    $btn1_link = get_theme_mod('hero_btn_1_link', '/work');
    $btn2_text = get_theme_mod('hero_btn_2_text', 'Read Bio');
    $btn2_link = get_theme_mod('hero_btn_2_link', '/about');
    
    $hero_img = get_theme_mod('hero_img', 'https://i.imgur.com/ixsEpYM.png');
    
    $ticker_raw = get_theme_mod('hero_ticker_items', 'Design Systems, Enterprise UX, Web3 Specialist');
    $ticker_items = array_map('trim', explode(',', $ticker_raw));
    
    // Labels
    $lbl_work = get_theme_mod('title_selected_work', 'Selected Work');
    $btn_work = get_theme_mod('btn_view_all_work', 'View All Projects');
    $lbl_test = get_theme_mod('title_testimonials', 'Kind Words');
    $lbl_insight = get_theme_mod('title_insights', 'Insights');
    $btn_blog = get_theme_mod('btn_view_all_blog', 'View all writing');
    
    $cta_title = get_theme_mod('cta_ready_title', 'Ready to build?');
    $cta_desc = get_theme_mod('cta_ready_desc', 'I am currently available...');
    $cta_btn = get_theme_mod('cta_ready_btn', 'Start a Project');
    ?>

    <main class="container">
      
      <!-- HERO SECTION -->
      <section class="hero-section <?php echo esc_attr($layout_style); ?>">
        <div class="ticker-wrap reveal-on-scroll">
            <div class="ticker">
                <?php foreach($ticker_items as $item): ?><span class="ticker-pill"><?php echo esc_html($item); ?></span><?php endforeach; ?>
                <?php foreach($ticker_items as $item): ?><span class="ticker-pill"><?php echo esc_html($item); ?></span><?php endforeach; ?>
            </div>
        </div>

        <div class="hero-content-wrapper">
            <h1 class="hero-title reveal-on-scroll">
              <span class="text-reveal-wrap"><span class="text-outline"><?php echo esc_html($h1_line1); ?></span><span class="text-fill"><?php echo esc_html($h1_line1); ?></span></span><br />
              <span class="text-reveal-wrap"><span class="text-outline"><?php echo esc_html($h1_line2); ?></span><span class="text-fill"><?php echo esc_html($h1_line2); ?></span></span>
            </h1>
            <p class="body-large reveal-on-scroll"><?php echo nl2br(esc_html($hero_desc)); ?></p>
            <div class="hero-actions reveal-on-scroll">
              <?php if($btn1_text): ?><a href="<?php echo esc_url(home_url($btn1_link)); ?>" class="btn btn-primary"><?php echo esc_html($btn1_text); ?></a><?php endif; ?>
              <?php if($btn2_text): ?><a href="<?php echo esc_url(home_url($btn2_link)); ?>" class="btn btn-secondary"><?php echo esc_html($btn2_text); ?></a><?php endif; ?>
            </div>
        </div>

        <?php if($hero_img): ?>
        <div class="hero-portrait-container reveal-on-scroll">
            <img src="<?php echo esc_url($hero_img); ?>" alt="Portrait" class="hero-portrait-img img-blend-gradient" />
        </div>
        <?php endif; ?>
      </section>

      <!-- DYNAMIC STATS SECTION -->
      <section class="section-container reveal-on-scroll">
          <div class="achievements-strip">
              <a href="#" class="achieve-item">
                  <div class="achieve-number"><?php echo get_theme_mod('stat_1_num', '#1'); ?></div>
                  <div class="achieve-label"><?php echo nl2br(get_theme_mod('stat_1_label', 'Ranked Designer')); ?></div>
              </a>
              <a href="#" class="achieve-item">
                  <div class="achieve-number"><?php echo get_theme_mod('stat_2_num', 'Top 1%'); ?></div>
                  <div class="achieve-label"><?php echo nl2br(get_theme_mod('stat_2_label', 'Verified Skills')); ?></div>
              </a>
              <a href="#" class="achieve-item">
                  <div class="achieve-number"><?php echo get_theme_mod('stat_3_num', '6+'); ?></div>
                  <div class="achieve-label"><?php echo nl2br(get_theme_mod('stat_3_label', 'Years Experience')); ?></div>
              </a>
          </div>
      </section>

      <!-- PROJECTS -->
      <section class="section-container">
        <h2 class="section-title reveal-on-scroll">
            <span class="text-reveal-wrap">
                <span class="text-outline"><?php echo esc_html($lbl_work); ?></span>
                <span class="text-fill"><?php echo esc_html($lbl_work); ?></span>
            </span>
        </h2>
        <div class="project-grid">
           <?php 
           $projects = new WP_Query(array('post_type' => 'project', 'posts_per_page' => 4));
           if($projects->have_posts()): while($projects->have_posts()): $projects->the_post();
               $thumb = get_the_post_thumbnail_url(get_the_ID(), 'large');
               $cats = get_project_cat_slugs(get_the_ID());
           ?>
           <a href="<?php the_permalink(); ?>" class="project-card reveal-on-scroll">
               <div class="card-media-wrap">
                   <?php if($thumb): ?><img src="<?php echo esc_url($thumb); ?>" loading="lazy"><?php else: ?><div style="height:100%; background:var(--bg-surface);"></div><?php endif; ?>
               </div>
               <div class="card-content">
                   <h3><?php the_title(); ?></h3>
                   <div class="card-meta-line"><span><?php echo get_the_date('Y'); ?></span></div>
               </div>
           </a>
           <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>
        <div style="text-align:center; margin-top:60px;">
            <a href="<?php echo home_url('/work'); ?>" class="btn btn-secondary"><?php echo esc_html($btn_work); ?></a>
        </div>
      </section>

      <!-- TESTIMONIALS -->
      <section id="testimonials" class="testimonial-section reveal-on-scroll">
         <h2 class="section-title" style="text-align: center;">
             <span class="text-reveal-wrap">
                <span class="text-outline"><?php echo esc_html($lbl_test); ?></span>
                <span class="text-fill"><?php echo esc_html($lbl_test); ?></span>
            </span>
         </h2>
         <div class="t-track">
             <?php 
             $testimonials = new WP_Query(array('post_type' => 'testimonial', 'posts_per_page' => 5));
             $i=0; 
             if($testimonials->have_posts()): while($testimonials->have_posts()): $testimonials->the_post(); 
                $role = get_post_meta(get_the_ID(), 'testimonial_role', true);
             ?>
             <div class="t-slide <?php echo $i===0 ? 'active' : ''; ?>">
                 <div class="t-quote">"<?php echo strip_tags(get_the_content()); ?>"</div>
                 <div class="t-author">
                     <h5><?php the_title(); ?></h5>
                     <span><?php echo esc_html($role); ?></span>
                 </div>
             </div>
             <?php $i++; endwhile; wp_reset_postdata(); endif; ?>
         </div>
         <div class="t-controls">
             <button id="t-prev" class="t-btn">←</button>
             <button id="t-next" class="t-btn">→</button>
         </div>
      </section>

      <!-- INSIGHTS -->
      <section class="section-container">
          <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;" class="reveal-on-scroll">
              <h2 class="section-title" style="margin-bottom: 0;"><?php echo esc_html($lbl_insight); ?></h2>
              <?php 
                $blog_url = get_post_type_archive_link('post');
                if(!$blog_url) $blog_url = home_url('/blog');
              ?>
              <a href="<?php echo esc_url($blog_url); ?>" class="btn btn-secondary" style="padding: 12px 24px; font-size: 0.9rem;"><?php echo esc_html($btn_blog); ?></a>
          </div>
          
          <div class="blog-grid reveal-on-scroll">
              <?php 
              $blogs = new WP_Query(array('post_type'=>'post', 'posts_per_page'=>3));
              if($blogs->have_posts()): while($blogs->have_posts()): $blogs->the_post(); ?>
              <a href="<?php the_permalink(); ?>" class="blog-card-modern">
                  <div class="blog-card-meta">
                      <span><?php echo get_the_date('M d, Y'); ?></span>
                      <span><?php $cat = get_the_category(); if($cat) echo $cat[0]->cat_name; ?></span>
                  </div>
                  <h3 class="blog-card-title"><?php the_title(); ?></h3>
                  <div class="blog-card-excerpt">
                      <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                  </div>
                  <div class="blog-card-link">
                      Read Article 
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                  </div>
              </a>
              <?php endwhile; wp_reset_postdata(); endif; ?>
          </div>
      </section>

      <!-- CTA -->
      <section class="section-container reveal-on-scroll" style="padding: 100px 0; text-align: center; border-top: 1px solid var(--border-faint);">
          <h2 class="hero-title" style="font-size: clamp(3rem, 6vw, 5rem); margin-bottom: 32px;">
              <span class="text-reveal-wrap">
                <span class="text-outline"><?php echo esc_html($cta_title); ?></span>
                <span class="text-fill"><?php echo esc_html($cta_title); ?></span>
            </span>
          </h2>
          <p class="body-large" style="margin: 0 auto 48px auto; max-width: 600px;"><?php echo nl2br(esc_html($cta_desc)); ?></p>
          <a href="<?php echo home_url('/contact'); ?>" class="btn btn-primary" style="font-size: 1.1rem; padding: 24px 56px;"><?php echo esc_html($cta_btn); ?></a>
      </section>

    </main>

<?php get_footer(); ?>
