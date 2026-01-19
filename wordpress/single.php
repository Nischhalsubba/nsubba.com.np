
<?php get_header(); ?>

    <main>
        <?php while ( have_posts() ) : the_post(); ?>
        <article>
            <header class="hero-section"
                style="min-height: 60vh; padding-top: 180px; justify-content: flex-end; padding-bottom: 80px; align-items: flex-start; text-align: left;">
                <div class="container">
                    <div
                        style="margin-bottom: 24px; color: var(--accent-blue); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; border: 1px solid var(--accent-blue); padding: 4px 12px; border-radius: 99px;">
                        <?php the_category(', '); ?></div>
                    <h1 class="hero-title fade-in"
                        style="font-size: clamp(2.5rem, 5vw, 4rem); line-height: 1.1; margin-bottom: 32px;">
                        <?php the_title(); ?></h1>
                    <div class="meta-text fade-in"
                        style="display: flex; gap: 24px; color: var(--text-secondary); border-top: 1px solid var(--border-faint); padding-top: 24px; width: 100%;">
                        <span><?php echo get_the_date(); ?></span>
                        <span>•</span>
                        <span>By <?php the_author(); ?></span>
                    </div>
                </div>
            </header>

            <section class="section-container" style="padding-top: 0;">
                <div class="container">
                    <div class="blog-layout" style="display: grid; grid-template-columns: 1fr 300px; gap: 80px;">

                        <!-- Content Column -->
                        <div class="body-large reveal-on-scroll"
                            style="color: var(--text-secondary); font-size: 1.15rem; line-height: 1.8;">
                            <?php the_content(); ?>
                        </div>

                        <!-- Sidebar Column -->
                        <aside class="blog-sidebar" style="position: sticky; top: 120px; height: fit-content;">
                            <div
                                style="background: var(--bg-surface); border: 1px solid var(--border-faint); padding: 24px; border-radius: 12px; margin-bottom: 24px;">
                                <h5 style="color: var(--text-primary); margin-bottom: 16px;">Share this article</h5>
                                <div style="display: flex; gap: 12px;">
                                    <button class="social-icon-btn" style="width: 32px; height: 32px; border: 1px solid var(--border-faint); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); cursor: pointer;"><svg
                                            viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                            <path
                                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                                        </svg></button>
                                    <button class="social-icon-btn" style="width: 32px; height: 32px; border: 1px solid var(--border-faint); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); cursor: pointer;"><svg
                                            viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                            <path
                                                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                        </svg></button>
                                </div>
                            </div>
                        </aside>

                    </div>
                </div>
            </section>
        </article>
        <?php endwhile; ?>

        <!-- Read Next / Other Blogs (Static for Demo or use WP_Query) -->
        <section class="other-cases-section reveal-on-scroll">
            <div class="container">
                <h2 class="other-cases-title">Read Next</h2>
                <div class="other-cases-grid">
                    <?php
                    $next_post = get_next_post();
                    $prev_post = get_previous_post();
                    if($prev_post): ?>
                    <a href="<?php echo get_permalink($prev_post->ID); ?>" class="case-nav-card prev">
                        <div class="case-nav-media">
                            <div style="width:100%; height:100%; background: linear-gradient(45deg, #1a1a1a, #2a2a2a); display:flex; align-items:center; justify-content:center; color: var(--text-tertiary);">
                                <span style="font-size: 3rem; opacity: 0.2;">Aa</span>
                            </div>
                            <div class="case-nav-overlay">
                                <div class="case-nav-icon"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7" /></svg></div>
                            </div>
                        </div>
                        <div class="case-nav-content">
                            <h4>Previous</h4>
                            <h3><?php echo get_the_title($prev_post->ID); ?></h3>
                        </div>
                    </a>
                    <?php endif; ?>

                    <?php if($next_post): ?>
                    <a href="<?php echo get_permalink($next_post->ID); ?>" class="case-nav-card next">
                        <div class="case-nav-media">
                            <div style="width:100%; height:100%; background: linear-gradient(45deg, #1a1a1a, #2a2a2a); display:flex; align-items:center; justify-content:center; color: var(--text-tertiary);">
                                <span style="font-size: 3rem; opacity: 0.2;">{ }</span>
                            </div>
                            <div class="case-nav-overlay">
                                <div class="case-nav-icon"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7" /></svg></div>
                            </div>
                        </div>
                        <div class="case-nav-content" style="text-align: right;">
                            <h4>Next</h4>
                            <h3><?php echo get_the_title($next_post->ID); ?></h3>
                        </div>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </main>

<?php get_footer(); ?>