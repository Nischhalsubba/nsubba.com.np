<?php get_header(); ?>

    <main class="container">
      <?php while ( have_posts() ) : the_post(); 
        // Retrieve Custom Fields
        $industry = get_post_meta(get_the_ID(), 'project_industry', true);
        $role = get_post_meta(get_the_ID(), 'project_role', true);
        $team = get_post_meta(get_the_ID(), 'project_team', true);
        $timeline = get_post_meta(get_the_ID(), 'project_timeline', true);
        $outcome = get_post_meta(get_the_ID(), 'project_outcome', true);
        $year = get_post_meta(get_the_ID(), 'project_year', true);
        $live_url = get_post_meta(get_the_ID(), 'project_live_url', true);
      ?>
      
      <!-- 1. HERO -->
      <div class="hero-section" style="padding-bottom: 40px; min-height: auto; align-items: flex-start; text-align: left;">
         <a href="<?php echo home_url('/work'); ?>" style="margin-bottom: 32px; color: var(--text-secondary); display: inline-block;">← Back to Work</a>
         
         <div class="case-meta-chips" style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
             <?php 
             $terms = get_the_terms(get_the_ID(), 'project_category');
             if ($terms && !is_wp_error($terms)) :
                 foreach ($terms as $term) : ?>
                     <span class="badge-pill"><?php echo $term->name; ?></span>
                 <?php endforeach; 
             endif; ?>
             <?php if($year): ?><span class="badge-pill"><?php echo esc_html($year); ?></span><?php endif; ?>
         </div>

         <h1 class="hero-title" style="margin-bottom: 16px;">
             <span class="text-reveal-wrap">
                <span class="text-outline"><?php the_title(); ?></span>
                <span class="text-fill"><?php the_title(); ?></span>
            </span>
         </h1>
         <div class="body-large" style="color: var(--text-secondary); max-width: 800px;">
             <?php the_excerpt(); ?>
         </div>
         
         <?php if($live_url): ?>
         <div class="hero-actions" style="margin-top: 32px;">
             <a href="<?php echo esc_url($live_url); ?>" target="_blank" class="btn btn-primary">View Live Site</a>
         </div>
         <?php endif; ?>
      </div>

      <div class="case-hero-img-container reveal-on-scroll" style="margin-bottom: 100px;">
          <?php if(has_post_thumbnail()): ?>
            <?php the_post_thumbnail('full', array('class' => 'case-hero-img', 'style' => 'width: 100%; border-radius: 16px; border: 1px solid var(--border-faint);')); ?>
          <?php endif; ?>
      </div>

      <!-- 2. SNAPSHOT -->
      <section class="section-container" style="border-bottom: 1px solid var(--border-faint);">
          <div class="snapshot-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px;">
              <div>
                  <h5 style="color: var(--text-tertiary); margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Role</h5>
                  <p style="color: var(--text-primary);"><?php echo $role ? esc_html($role) : 'Product Designer'; ?></p>
              </div>
              <div>
                  <h5 style="color: var(--text-tertiary); margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Team</h5>
                  <p style="color: var(--text-primary);"><?php echo $team ? esc_html($team) : '-'; ?></p>
              </div>
              <div>
                  <h5 style="color: var(--text-tertiary); margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Timeline</h5>
                  <p style="color: var(--text-primary);"><?php echo $timeline ? esc_html($timeline) : '-'; ?></p>
              </div>
              <div>
                  <h5 style="color: var(--text-tertiary); margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Outcome</h5>
                  <p style="color: var(--text-primary);"><?php echo $outcome ? esc_html($outcome) : '-'; ?></p>
              </div>
          </div>
      </section>

      <!-- 3. MAIN CONTENT (Gutenberg Blocks Render Here) -->
      <section class="case-content-body section-container reveal-on-scroll">
          <div class="block-content body-large" style="max-width: 840px; margin: 0 auto; color: var(--text-secondary);">
              <?php the_content(); ?>
          </div>
      </section>

      <?php endwhile; ?>

      <!-- 4. NAVIGATION -->
      <section class="other-cases-section reveal-on-scroll">
          <h2 class="other-cases-title">Other cases</h2>
          <div class="other-cases-grid">
              <?php
                $prev_post = get_previous_post();
                if($prev_post && $prev_post->post_type == 'project'): 
                    $prev_img = get_the_post_thumbnail_url($prev_post->ID, 'large');
                ?>
              <a href="<?php echo get_permalink($prev_post->ID); ?>" class="case-nav-card prev">
                  <div class="case-nav-media">
                      <?php if($prev_img): ?>
                        <img src="<?php echo esc_url($prev_img); ?>" class="case-nav-img">
                      <?php else: ?>
                        <div style="width:100%; height:100%; background: #1a1a1a;"></div>
                      <?php endif; ?>
                      <div class="case-nav-overlay"><div class="case-nav-icon">←</div></div>
                  </div>
                  <div class="case-nav-content">
                      <h4>Previous Case</h4>
                      <h3><?php echo get_the_title($prev_post->ID); ?></h3>
                  </div>
              </a>
              <?php endif; ?>

              <?php
                $next_post = get_next_post();
                if($next_post && $next_post->post_type == 'project'): 
                    $next_img = get_the_post_thumbnail_url($next_post->ID, 'large');
                ?>
              <a href="<?php echo get_permalink($next_post->ID); ?>" class="case-nav-card next">
                  <div class="case-nav-media">
                      <?php if($next_img): ?>
                        <img src="<?php echo esc_url($next_img); ?>" class="case-nav-img">
                      <?php else: ?>
                        <div style="width:100%; height:100%; background: #1a1a1a;"></div>
                      <?php endif; ?>
                      <div class="case-nav-overlay"><div class="case-nav-icon">→</div></div>
                  </div>
                  <div class="case-nav-content" style="text-align: right;">
                      <h4>Next Case</h4>
                      <h3><?php echo get_the_title($next_post->ID); ?></h3>
                  </div>
              </a>
              <?php endif; ?>
          </div>
      </section>
      
    </main>

<?php get_footer(); ?>