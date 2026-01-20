
<?php get_header(); ?>

    <main class="container">
      <?php while ( have_posts() ) : the_post(); 
        $price = get_post_meta(get_the_ID(), 'product_price', true);
        $format = get_post_meta(get_the_ID(), 'product_format', true);
        $link = get_post_meta(get_the_ID(), 'product_link', true);
        $version = get_post_meta(get_the_ID(), 'product_version', true);
      ?>
      
      <!-- HERO -->
      <div class="hero-section" style="padding-bottom: 40px; min-height: auto; align-items: flex-start; text-align: left;">
         <a href="<?php echo get_post_type_archive_link('product'); ?>" style="margin-bottom: 32px; color: var(--text-secondary); display: inline-block;">← Back to Products</a>
         
         <div class="case-meta-chips" style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
             <span class="badge-pill"><?php echo $format ? esc_html($format) : 'Digital'; ?></span>
             <?php if($version): ?><span class="badge-pill">v<?php echo esc_html($version); ?></span><?php endif; ?>
         </div>

         <h1 class="hero-title" style="margin-bottom: 16px; font-size: clamp(2.5rem, 5vw, 4rem);">
             <?php the_title(); ?>
         </h1>
         <div class="body-large" style="color: var(--text-secondary); max-width: 800px;">
             <?php the_excerpt(); ?>
         </div>
         
         <div class="hero-actions" style="margin-top: 32px; display: flex; align-items: center; gap: 20px;">
             <?php if($link): ?>
                <a href="<?php echo esc_url($link); ?>" target="_blank" class="btn btn-primary">Get Product <?php echo $price ? '— ' . esc_html($price) : ''; ?></a>
             <?php else: ?>
                <button class="btn btn-primary" disabled>Coming Soon</button>
             <?php endif; ?>
         </div>
      </div>

      <div class="case-hero-img-container reveal-on-scroll" style="margin-bottom: 100px;">
          <?php if(has_post_thumbnail()): ?>
            <?php the_post_thumbnail('full', array('class' => 'case-hero-img', 'style' => 'width: 100%; border-radius: 16px; border: 1px solid var(--border-faint);')); ?>
          <?php endif; ?>
      </div>

      <!-- MAIN CONTENT -->
      <section class="case-content-body section-container reveal-on-scroll">
          <div class="block-content body-large" style="max-width: 840px; margin: 0 auto; color: var(--text-secondary);">
              <?php the_content(); ?>
          </div>
      </section>

      <?php endwhile; ?>
    </main>

<?php get_footer(); ?>
