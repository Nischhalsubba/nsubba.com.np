
<?php
/* Template Name: Contact Page */
get_header(); ?>

    <main class="container">
      <section class="hero-section">
          <h1 class="hero-title reveal-on-scroll">
              <span class="text-reveal-wrap">
                <span class="text-outline">Get in Touch</span>
                <span class="text-fill">Get in Touch</span>
            </span>
          </h1>
          
          <div class="contact-grid reveal-on-scroll">
              <div class="contact-details">
                  <p class="body-large" style="margin-bottom: 40px;">
                      Open for opportunities in Enterprise UX, Design Systems, and Web3 Product Design. Whether you have a question or just want to say hi, I'll try my best to get back to you!
                  </p>
                  
                  <div class="contact-detail-item">
                      <h5>Email</h5>
                      <a href="mailto:<?php echo get_theme_mod('footer_email', 'hinischalsubba@gmail.com'); ?>"><?php echo get_theme_mod('footer_email', 'hinischalsubba@gmail.com'); ?></a>
                  </div>
                  
                  <div class="contact-detail-item">
                      <h5>Socials</h5>
                      <div style="display: flex; gap: 16px; margin-top: 8px;">
                          <?php if(get_theme_mod('social_linkedin')): ?><a href="<?php echo get_theme_mod('social_linkedin'); ?>" style="border-bottom: 1px solid currentColor;">LinkedIn</a><?php endif; ?>
                          <?php if(get_theme_mod('social_behance')): ?><a href="<?php echo get_theme_mod('social_behance'); ?>" style="border-bottom: 1px solid currentColor;">Behance</a><?php endif; ?>
                      </div>
                  </div>
              </div>
              
              <div class="contact-form-wrapper">
                  <div style="background: var(--bg-surface); padding: 40px; border-radius: 24px; border: 1px solid var(--border-faint);">
                      <h3 style="margin-bottom: 24px;">Send a Message</h3>
                      <form id="contact-form" style="display: flex; flex-direction: column; gap: 24px;">
                          <div>
                              <label style="display: block; margin-bottom: 8px; color: var(--text-secondary); font-size: 0.9rem;">Name</label>
                              <input type="text" class="search-input" placeholder="John Doe" style="width: 100%; font-size: 1rem; padding: 16px;">
                          </div>
                          <div>
                              <label style="display: block; margin-bottom: 8px; color: var(--text-secondary); font-size: 0.9rem;">Email</label>
                              <input type="email" class="search-input" placeholder="john@example.com" style="width: 100%; font-size: 1rem; padding: 16px;">
                          </div>
                          <div>
                              <label style="display: block; margin-bottom: 8px; color: var(--text-secondary); font-size: 0.9rem;">Message</label>
                              <textarea class="search-input" rows="5" placeholder="Tell me about your project..." style="width: 100%; border-radius: 12px; font-size: 1rem; padding: 16px;"></textarea>
                          </div>
                          <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                      </form>
                  </div>
              </div>
          </div>
      </section>
    </main>

<?php get_footer(); ?>