    <a href="<?php echo get_template_directory_uri(); ?>/assets/resume.pdf" class="floating-resume-btn" download aria-label="Download Resume">
        <span class="btn-text">Download Resume</span>
        <span class="btn-icon"><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="12" y2="18"></line><line x1="15" y1="15" x2="12" y2="18"></line></svg></span>
    </a>

    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-top-grid">
                <div class="footer-cta">
                    <h2>Let's create<br>something<br><span style="font-style: italic;">awesome.</span></h2>
                    <p>Open for opportunities in Enterprise UX, Design Systems, and Web3 Product Design.</p>
                    <a href="mailto:<?php echo get_theme_mod('footer_email', 'hinischalsubba@gmail.com'); ?>" class="footer-email-btn">
                        <?php echo get_theme_mod('footer_email', 'hinischalsubba@gmail.com'); ?>
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                    </a>
                </div>
                <div class="footer-nav-grid">
                    <div class="footer-col">
                        <h5>Sitemap</h5>
                        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false ) ); ?>
                    </div>
                    <div class="footer-col">
                        <h5>Socials</h5>
                        <?php if(get_theme_mod('social_linkedin')): ?>
                            <a href="<?php echo get_theme_mod('social_linkedin'); ?>" target="_blank">LinkedIn</a>
                        <?php endif; ?>
                        <?php if(get_theme_mod('social_behance')): ?>
                            <a href="<?php echo get_theme_mod('social_behance'); ?>" target="_blank">Behance</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom-bar">
                <div class="footer-socials">
                    <!-- Icons preserved from static HTML, simplified for brevity -->
                    <?php if(get_theme_mod('social_linkedin')): ?>
                    <a href="<?php echo get_theme_mod('social_linkedin'); ?>" target="_blank" class="footer-social-link" aria-label="LinkedIn">
                        <svg viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
                <span>© <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</span>
            </div>
        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>