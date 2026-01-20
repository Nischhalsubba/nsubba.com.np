
<?php get_header(); 
$blog_title = get_theme_mod('title_insights', 'Writing');
?>

    <main class="container">
      <section class="hero-section" style="min-height: 40vh;">
        <h1 class="hero-title reveal-on-scroll">
            <span class="text-reveal-wrap">
                <span class="text-outline"><?php echo esc_html($blog_title); ?></span>
                <span class="text-fill"><?php echo esc_html($blog_title); ?></span>
            </span>
        </h1>
        <p class="body-large reveal-on-scroll">Thoughts on Design Systems, Product Strategy, and the future of digital interfaces.</p>
      </section>

      <div class="search-wrapper reveal-on-scroll">
            <input type="text" id="search-blog" class="search-input" placeholder="Search articles...">
      </div>

      <!-- Category Pills -->
      <div class="filter-row reveal-on-scroll">
          <button class="filter-btn active blog-filter-btn" data-filter="all">All Articles</button>
          <?php 
            $cats = get_categories();
            foreach($cats as $cat) {
                echo '<button class="filter-btn blog-filter-btn" data-filter="' . strtolower($cat->slug) . '">' . esc_html($cat->name) . '</button>';
            }
          ?>
      </div>

      <section class="section-container" style="padding-top: 0;">
          <div class="writing-list reveal-on-scroll">
              <?php 
              if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                  $cats = get_the_category();
                  $cat_slugs = '';
                  if($cats) {
                      foreach($cats as $c) $cat_slugs .= strtolower($c->slug) . ' ';
                  }
              ?>
                  <a href="<?php the_permalink(); ?>" class="writing-item" data-category="<?php echo esc_attr($cat_slugs); ?>">
                      <span class="w-date"><?php echo get_the_date('M d, Y'); ?></span>
                      <div class="w-info">
                          <span class="w-title"><?php the_title(); ?></span>
                          <span class="w-summary"><?php echo get_the_excerpt(); ?></span>
                      </div>
                      <span class="w-arrow">→</span>
                  </a>
              <?php endwhile; else: ?>
                  <p>No articles found.</p>
              <?php endif; ?>
          </div>
          
          <div class="pagination" style="margin-top: 60px;">
              <?php echo paginate_links(); ?>
          </div>
      </section>
    </main>

<?php get_footer(); ?>
