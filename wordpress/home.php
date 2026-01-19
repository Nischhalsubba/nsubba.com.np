

<?php get_header(); ?>

    <main class="container">
      <section class="hero-section" style="min-height: 40vh;">
        <h1 class="hero-title reveal-on-scroll">
            <span class="text-reveal-wrap">
                <span class="text-outline">Writing</span>
                <span class="text-fill">Writing</span>
            </span>
        </h1>
        <p class="body-large reveal-on-scroll">Thoughts on Design Systems, Product Strategy, and the future of digital interfaces.</p>
      </section>

      <div class="search-wrapper reveal-on-scroll">
            <input type="text" id="search-blog" class="search-input" placeholder="Search articles...">
      </div>

      <section class="section-container" style="padding-top: 0;">
          <div class="writing-list reveal-on-scroll">
              <?php 
              // Set main query to 6 posts
              query_posts('posts_per_page=6');
              if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                  <a href="<?php the_permalink(); ?>" class="writing-item" data-category="<?php echo strtolower(strip_tags(get_the_category_list(' '))); ?>">
                      <span class="w-date"><?php echo get_the_date('M d, Y'); ?></span>
                      <div class="w-info">
                          <span class="w-title"><?php the_title(); ?></span>
                          <span class="w-summary"><?php echo get_the_excerpt(); ?></span>
                      </div>
                      <span class="w-arrow">→</span>
                  </a>
              <?php endwhile; endif; ?>

              <!-- STATIC MOCK ARTICLES -->
              <a href="#" class="writing-item" data-category="design system">
                  <span class="w-date">Nov 15, 2025</span>
                  <div class="w-info">
                      <span class="w-title">The Psychology of Dark Mode</span>
                      <span class="w-summary">Exploring the cognitive impact of high-contrast interfaces in enterprise environments and when to avoid them.</span>
                  </div>
                  <span class="w-arrow">→</span>
              </a>

              <a href="#" class="writing-item" data-category="ux">
                  <span class="w-date">Oct 02, 2025</span>
                  <div class="w-info">
                      <span class="w-title">Micro-interactions: The Unsung Heroes</span>
                      <span class="w-summary">How small animation details significantly reduce perceived latency and improve user trust.</span>
                  </div>
                  <span class="w-arrow">→</span>
              </a>

              <a href="#" class="writing-item" data-category="strategy">
                  <span class="w-date">Sep 20, 2025</span>
                  <div class="w-info">
                      <span class="w-title">Why Your Design System is Failing</span>
                      <span class="w-summary">It's not the tokens; it's the adoption strategy. A look at governance models for scaling teams.</span>
                  </div>
                  <span class="w-arrow">→</span>
              </a>

              <a href="#" class="writing-item" data-category="web3">
                  <span class="w-date">Aug 14, 2025</span>
                  <div class="w-info">
                      <span class="w-title">Web3 UX: Beyond the Wallet</span>
                      <span class="w-summary">Designing for trustless systems without overwhelming users with cryptographic complexity.</span>
                  </div>
                  <span class="w-arrow">→</span>
              </a>

              <a href="#" class="writing-item" data-category="ai">
                  <span class="w-date">Jul 30, 2025</span>
                  <div class="w-info">
                      <span class="w-title">Designing for AI Trust</span>
                      <span class="w-summary">Patterns for disclosure and confidence scoring in generative AI interfaces.</span>
                  </div>
                  <span class="w-arrow">→</span>
              </a>

          </div>
          
          <div class="pagination" style="margin-top: 60px;">
              <?php echo paginate_links(); ?>
          </div>
      </section>
    </main>

<?php get_footer(); ?>