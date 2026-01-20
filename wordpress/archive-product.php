
<?php get_header(); ?>

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

      <section class="section-container" style="padding-top: 0;">
        <div class="project-grid">
           <?php 
           if(have_posts()):
               while(have_posts()): the_post();
                   $price = get_post_meta(get_the_ID(), 'product_price', true);
                   $format = get_post_meta(get_the_ID(), 'product_format', true);
                   $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
           ?>
           <a href="<?php the_permalink(); ?>" class="project-card reveal-on-scroll">
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
                       <span><?php echo $format ? esc_html($format) : 'Digital'; ?></span>
                       <span><?php echo $price ? esc_html($price) : 'Free'; ?></span>
                   </div>
               </div>
           </a>
           <?php endwhile; else: ?>
               <p style="color: var(--text-secondary);">No products found.</p>
           <?php endif; ?>
        </div>
      </section>
    </main>

<?php get_footer(); ?>
