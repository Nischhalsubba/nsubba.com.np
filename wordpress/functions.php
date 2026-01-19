
<?php
/**
 * Nischhal Portfolio - Core Functions
 * Version: 16.6 (The "Everything" Merge)
 */

// --- 1. SETUP & SUPPORT ---
function nischhal_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array('height'=>80, 'width'=>200, 'flex-height'=>true) );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'align-wide' ); 
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
    add_editor_style( 'style.css' );
    
    // 1.1 Image Sizes
    add_image_size( 'hero-ultra', 1920, 1080, false ); 
    add_image_size( 'project-card', 800, 600, true ); 
    
    // Register Menus
    register_nav_menus( array( 
        'primary' => 'Primary Menu',
        'footer'  => 'Footer Menu'
    ) );
}
add_action( 'after_setup_theme', 'nischhal_theme_setup' );

// --- 1.2 OPTIMIZATION ---
add_filter( 'jpeg_quality', function($arg){ return 85; } );
add_filter( 'big_image_size_threshold', function() { return 2560; } );

// --- 1.3 SEO METADATA ---
function nischhal_seo_meta_tags() {
    global $post;
    if ( !isset($post) || !is_object($post) ) return; 
    
    $excerpt = get_bloginfo('description');
    if ( is_single() || is_page() ) {
        if ( has_excerpt( $post->ID ) ) {
            $excerpt = strip_tags( get_the_excerpt( $post->ID ) );
        } elseif ( !empty($post->post_content) ) {
            $excerpt = wp_trim_words( strip_tags( $post->post_content ), 25 );
        }
    }
    
    $img_url = get_theme_mod('hero_img', 'https://i.imgur.com/ixsEpYM.png');
    if ( has_post_thumbnail( $post->ID ) ) {
        $img_url = get_the_post_thumbnail_url( $post->ID, 'large' );
    }
    
    ?>
    <meta name="description" content="<?php echo esc_attr($excerpt); ?>">
    <meta property="og:title" content="<?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($excerpt); ?>" />
    <meta property="og:image" content="<?php echo esc_url($img_url); ?>" />
    <meta name="twitter:card" content="summary_large_image" />
    <?php
}
add_action('wp_head', 'nischhal_seo_meta_tags', 5);

// --- 2. CUSTOM POST TYPES ---
function nischhal_register_post_types() {
    register_post_type('project', array(
        'labels' => array('name' => 'Projects', 'singular_name' => 'Project', 'menu_name' => '💼 Projects'),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true, 
        'rewrite' => array('slug' => 'work'),
    ));

    register_taxonomy('project_category', 'project', array(
        'labels' => array('name' => 'Project Categories', 'singular_name' => 'Category'),
        'hierarchical' => true,
        'show_in_rest' => true,
        'public' => true
    ));

    register_post_type('testimonial', array(
        'labels' => array('name' => 'Testimonials', 'singular_name' => 'Testimonial', 'menu_name' => '💬 Testimonials'),
        'public' => true,
        'publicly_queryable' => false, 
        'show_ui' => true,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'nischhal_register_post_types');

// --- 3. CUSTOM FIELDS ---
function nischhal_add_meta_boxes() {
    add_meta_box('project_meta', '🚀 Project Details', 'nischhal_render_project_meta', 'project', 'side', 'high');
    add_meta_box('testimonial_meta', '👤 Author Details', 'nischhal_render_testimonial_meta', 'testimonial', 'normal', 'high');
}
add_action('add_meta_boxes', 'nischhal_add_meta_boxes');

function nischhal_render_project_meta($post) {
    wp_nonce_field('nischhal_save_project_meta', 'nischhal_project_nonce');
    $fields = ['project_year'=>'Year', 'project_industry'=>'Industry', 'project_role'=>'Role', 'project_team'=>'Team', 'project_timeline'=>'Timeline', 'project_outcome'=>'Outcome', 'project_live_url'=>'Live URL'];
    foreach($fields as $key => $label) {
        $val = get_post_meta($post->ID, $key, true);
        echo '<div style="margin-bottom:10px;"><label style="display:block;font-weight:600;">'.$label.'</label><input type="text" name="'.$key.'" value="'.esc_attr($val).'" style="width:100%;"></div>';
    }
}

function nischhal_render_testimonial_meta($post) {
    wp_nonce_field('nischhal_save_testimonial_meta', 'nischhal_testimonial_nonce');
    $role = get_post_meta($post->ID, 'testimonial_role', true);
    $link = get_post_meta($post->ID, 'testimonial_link', true);
    echo '<div style="margin-bottom:10px;"><label>Role</label><input type="text" name="testimonial_role" value="'.esc_attr($role).'" style="width:100%;"></div>';
    echo '<div><label>Link</label><input type="url" name="testimonial_link" value="'.esc_attr($link).'" style="width:100%;"></div>';
}

function nischhal_save_meta_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['nischhal_project_nonce']) && wp_verify_nonce($_POST['nischhal_project_nonce'], 'nischhal_save_project_meta')) {
        $fields = ['project_year', 'project_role', 'project_industry', 'project_team', 'project_timeline', 'project_outcome', 'project_live_url'];
        foreach ($fields as $field) if (isset($_POST[$field])) update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
    }
    if (isset($_POST['nischhal_testimonial_nonce']) && wp_verify_nonce($_POST['nischhal_testimonial_nonce'], 'nischhal_save_testimonial_meta')) {
        if (isset($_POST['testimonial_role'])) update_post_meta($post_id, 'testimonial_role', sanitize_text_field($_POST['testimonial_role']));
        if (isset($_POST['testimonial_link'])) update_post_meta($post_id, 'testimonial_link', esc_url_raw($_POST['testimonial_link']));
    }
}
add_action('save_post', 'nischhal_save_meta_data');

// --- 4. ENQUEUE & JS CONFIG ---
function nischhal_enqueue_scripts() {
    $h_font = get_theme_mod('typo_heading_family', 'Playfair Display');
    $b_font = get_theme_mod('typo_body_family', 'Inter');
    wp_enqueue_style( 'nischhal-fonts', "https://fonts.googleapis.com/css2?family=" . urlencode($h_font) . ":wght@400;500;600&family=" . urlencode($b_font) . ":wght@300;400;500;600&display=swap" );
    wp_enqueue_style( 'main-style', get_stylesheet_uri(), array(), '16.6' );
    
    wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), null, true );
    wp_enqueue_script( 'gsap-st', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), null, true );
    wp_enqueue_script( 'theme-js', get_template_directory_uri() . '/js/main.js', array('gsap'), '16.6', true );

    // Dynamic Variables for JS
    wp_localize_script('theme-js', 'themeConfig', array(
        'imgDark' => get_theme_mod('hero_img', 'https://i.imgur.com/ixsEpYM.png'),
        'imgLight' => get_theme_mod('hero_img_light', 'https://i.imgur.com/oFHdPUS.png'), 
        'animSpeed' => get_theme_mod('anim_speed', 1.0),
        'cursorEnable' => get_theme_mod('cursor_enable', true),
        'siteUrl' => home_url()
    ));
}
add_action( 'wp_enqueue_scripts', 'nischhal_enqueue_scripts' );

// --- 8. THEME CUSTOMIZER (COMPREHENSIVE) ---
function nischhal_customize_register( $wp_customize ) {
    
    // --- PANEL: INTERACTION & CURSORS ---
    $wp_customize->add_panel( 'panel_interaction', array( 'title' => '⚡ Interaction & Cursors', 'priority' => 20 ) );

    // Section 1: Animation System
    $wp_customize->add_section( 'sec_anim_system', array( 'title' => 'Animation System', 'panel' => 'panel_interaction' ) );
    $wp_customize->add_setting('anim_speed', array('default'=>1.0));
    $wp_customize->add_control('anim_speed', array('label'=>'Global Speed Multiplier', 'section'=>'sec_anim_system', 'type'=>'number', 'input_attrs'=>array('min'=>0.5, 'max'=>2.0, 'step'=>0.1)));
    
    // Section 2: Cursor
    $wp_customize->add_section( 'sec_cursor', array( 'title' => 'Cursor', 'panel' => 'panel_interaction' ) );
    $wp_customize->add_setting('cursor_enable', array('default'=>true));
    $wp_customize->add_control('cursor_enable', array('label'=>'Enable Custom Cursor', 'section'=>'sec_cursor', 'type'=>'checkbox'));
    
    $wp_customize->add_setting('cursor_size', array('default'=>20));
    $wp_customize->add_control('cursor_size', array('label'=>'Cursor Size (px)', 'section'=>'sec_cursor', 'type'=>'number'));

    // --- PANEL: DESIGN LAYOUT ---
    $wp_customize->add_panel( 'panel_layout', array( 'title' => '📐 Design: Layout', 'priority' => 22 ) );
    $wp_customize->add_section( 'sec_layout_dims', array( 'title' => 'Dimensions', 'panel' => 'panel_layout' ) );
    
    $wp_customize->add_setting('container_width', array('default'=>'1200px'));
    $wp_customize->add_control('container_width', array('label'=>'Max Width', 'section'=>'sec_layout_dims', 'type'=>'text'));
    
    $wp_customize->add_setting('section_gap', array('default'=>'160px'));
    $wp_customize->add_control('section_gap', array('label'=>'Section Gap (Desktop)', 'section'=>'sec_layout_dims', 'type'=>'text'));

    // --- PANEL: TYPOGRAPHY ---
    $wp_customize->add_panel( 'panel_typography', array( 'title' => 'Aa Design: Typography', 'priority' => 23 ) );
    $wp_customize->add_section( 'sec_fonts', array( 'title' => 'Font Families', 'panel' => 'panel_typography' ) );
    
    $wp_customize->add_setting('typo_heading_family', array('default'=>'Playfair Display'));
    $wp_customize->add_control('typo_heading_family', array('label'=>'Heading Font', 'section'=>'sec_fonts', 'type'=>'text'));
    
    $wp_customize->add_setting('typo_body_family', array('default'=>'Inter'));
    $wp_customize->add_control('typo_body_family', array('label'=>'Body Font', 'section'=>'sec_fonts', 'type'=>'text'));

    // --- PANEL: COLORS (ADVANCED) ---
    $wp_customize->add_panel( 'panel_colors', array( 'title' => '🎨 Design: Colors', 'priority' => 21 ) );

    // Light Tokens
    $wp_customize->add_section('sec_tokens_light', array('title'=>'Light Mode', 'panel'=>'panel_colors'));
    $light_colors = [
        'l_bg'=>'#FFFFFF', 'l_surface'=>'#F8FAFC', 'l_text'=>'#0F172A', 'l_text_muted'=>'#475569', 'l_border'=>'rgba(0,0,0,0.1)', 'l_accent'=>'#2563EB'
    ];
    foreach($light_colors as $id=>$default) {
        $wp_customize->add_setting($id, array('default'=>$default));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $id, array('label'=>ucfirst(str_replace('_',' ',$id)), 'section'=>'sec_tokens_light')));
    }

    // Dark Tokens
    $wp_customize->add_section('sec_tokens_dark', array('title'=>'Dark Mode', 'panel'=>'panel_colors'));
    $dark_colors = [
        'd_bg'=>'#050505', 'd_surface'=>'#0a0a0a', 'd_text'=>'#FFFFFF', 'd_text_muted'=>'#A1A1AA', 'd_border'=>'rgba(255,255,255,0.1)', 'd_accent'=>'#3B82F6'
    ];
    foreach($dark_colors as $id=>$default) {
        $wp_customize->add_setting($id, array('default'=>$default));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $id, array('label'=>ucfirst(str_replace('_',' ',$id)), 'section'=>'sec_tokens_dark')));
    }
    
    $wp_customize->add_section('sec_grid_overlay', array('title'=>'Grid Overlay', 'panel'=>'panel_colors'));
    $wp_customize->add_setting('grid_opacity', array('default'=>0.05));
    $wp_customize->add_control('grid_opacity', array('label'=>'Grid Opacity', 'section'=>'sec_grid_overlay', 'type'=>'number', 'input_attrs'=>array('step'=>0.01,'min'=>0,'max'=>1)));

    // --- HOME HERO (DYNAMIC CONTENT) ---
    $wp_customize->add_section('sec_hero', array('title'=>'🏠 Home: Hero', 'priority'=>30));
    $wp_customize->add_setting('hero_layout_style', array('default'=>'hero-v1'));
    $wp_customize->add_control('hero_layout_style', array('label'=>'Layout', 'section'=>'sec_hero', 'type'=>'select', 'choices'=>array('hero-v1'=>'Center', 'hero-v2'=>'Split')));
    
    $wp_customize->add_setting('hero_h1_line1', array('default'=>'Crafting scalable'));
    $wp_customize->add_control('hero_h1_line1', array('label'=>'H1 Line 1', 'section'=>'sec_hero', 'type'=>'text'));
    $wp_customize->add_setting('hero_h1_line2', array('default'=>'digital products.'));
    $wp_customize->add_control('hero_h1_line2', array('label'=>'H1 Line 2', 'section'=>'sec_hero', 'type'=>'text'));
    
    $wp_customize->add_setting('hero_desc', array('default'=>"I’m Nischhal Raj Subba..."));
    $wp_customize->add_control('hero_desc', array('label'=>'Description', 'section'=>'sec_hero', 'type'=>'textarea'));

    // IMAGES (BOTH FIELDS)
    $wp_customize->add_setting('hero_img', array('default'=>'https://i.imgur.com/ixsEpYM.png'));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_img', array('label'=>'Portrait (Dark Mode)', 'section'=>'sec_hero')));
    
    $wp_customize->add_setting('hero_img_light', array('default'=>'https://i.imgur.com/oFHdPUS.png'));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_img_light', array('label'=>'Portrait (Light Mode)', 'section'=>'sec_hero')));

    $wp_customize->add_setting('hero_ticker_items', array('default'=>'Design Systems, Enterprise UX, Web3 Specialist'));
    $wp_customize->add_control('hero_ticker_items', array('label'=>'Ticker Items', 'section'=>'sec_hero', 'type'=>'text'));

    $wp_customize->add_setting('hero_btn_1_text', array('default'=>'View Projects'));
    $wp_customize->add_control('hero_btn_1_text', array('label'=>'Button 1 Text', 'section'=>'sec_hero', 'type'=>'text'));
    $wp_customize->add_setting('hero_btn_1_link', array('default'=>'/work'));
    $wp_customize->add_control('hero_btn_1_link', array('label'=>'Button 1 Link', 'section'=>'sec_hero', 'type'=>'text'));
    $wp_customize->add_setting('hero_btn_2_text', array('default'=>'Read Bio'));
    $wp_customize->add_control('hero_btn_2_text', array('label'=>'Button 2 Text', 'section'=>'sec_hero', 'type'=>'text'));
    $wp_customize->add_setting('hero_btn_2_link', array('default'=>'/about'));
    $wp_customize->add_control('hero_btn_2_link', array('label'=>'Button 2 Link', 'section'=>'sec_hero', 'type'=>'text'));

    // STATS
    $wp_customize->add_section('sec_stats', array('title'=>'🏠 Home: Stats', 'priority'=>32));
    $wp_customize->add_setting('stat_1_num', array('default'=>'#1'));
    $wp_customize->add_control('stat_1_num', array('label'=>'Stat 1 #', 'section'=>'sec_stats', 'type'=>'text'));
    $wp_customize->add_setting('stat_1_label', array('default'=>'Ranked Designer'));
    $wp_customize->add_control('stat_1_label', array('label'=>'Stat 1 Label', 'section'=>'sec_stats', 'type'=>'text'));
    
    $wp_customize->add_setting('stat_2_num', array('default'=>'Top 1%'));
    $wp_customize->add_control('stat_2_num', array('label'=>'Stat 2 #', 'section'=>'sec_stats', 'type'=>'text'));
    $wp_customize->add_setting('stat_2_label', array('default'=>'Verified Skills'));
    $wp_customize->add_control('stat_2_label', array('label'=>'Stat 2 Label', 'section'=>'sec_stats', 'type'=>'text'));
    
    $wp_customize->add_setting('stat_3_num', array('default'=>'6+'));
    $wp_customize->add_control('stat_3_num', array('label'=>'Stat 3 #', 'section'=>'sec_stats', 'type'=>'text'));
    $wp_customize->add_setting('stat_3_label', array('default'=>'Years Experience'));
    $wp_customize->add_control('stat_3_label', array('label'=>'Stat 3 Label', 'section'=>'sec_stats', 'type'=>'text'));

    // GLOBAL LABELS
    $wp_customize->add_section('sec_labels', array('title'=>'📝 Global Labels', 'priority'=>33));
    $wp_customize->add_setting('title_selected_work', array('default'=>'Selected Work'));
    $wp_customize->add_control('title_selected_work', array('label'=>'Selected Work Title', 'section'=>'sec_labels', 'type'=>'text'));
    $wp_customize->add_setting('btn_view_all_work', array('default'=>'View All Projects'));
    $wp_customize->add_control('btn_view_all_work', array('label'=>'View All Button', 'section'=>'sec_labels', 'type'=>'text'));
    $wp_customize->add_setting('title_testimonials', array('default'=>'Kind Words'));
    $wp_customize->add_control('title_testimonials', array('label'=>'Testimonials Title', 'section'=>'sec_labels', 'type'=>'text'));
    $wp_customize->add_setting('title_insights', array('default'=>'Insights'));
    $wp_customize->add_control('title_insights', array('label'=>'Insights Title', 'section'=>'sec_labels', 'type'=>'text'));
    $wp_customize->add_setting('btn_view_all_blog', array('default'=>'View all writing'));
    $wp_customize->add_control('btn_view_all_blog', array('label'=>'View All Blog Button', 'section'=>'sec_labels', 'type'=>'text'));
    
    $wp_customize->add_setting('cta_ready_title', array('default'=>'Ready to build?'));
    $wp_customize->add_control('cta_ready_title', array('label'=>'CTA Title', 'section'=>'sec_labels', 'type'=>'text'));
    $wp_customize->add_setting('cta_ready_desc', array('default'=>'I am currently available...'));
    $wp_customize->add_control('cta_ready_desc', array('label'=>'CTA Desc', 'section'=>'sec_labels', 'type'=>'textarea'));
    $wp_customize->add_setting('cta_ready_btn', array('default'=>'Start a Project'));
    $wp_customize->add_control('cta_ready_btn', array('label'=>'CTA Button', 'section'=>'sec_labels', 'type'=>'text'));

    // FOOTER
    $wp_customize->add_section('sec_footer', array('title'=>'Footer', 'priority'=>34));
    $wp_customize->add_setting('footer_main_heading', array('default'=>"Let's create something awesome."));
    $wp_customize->add_control('footer_main_heading', array('label'=>'Heading', 'section'=>'sec_footer', 'type'=>'textarea'));
    $wp_customize->add_setting('footer_sub_heading', array('default'=>"Open for opportunities..."));
    $wp_customize->add_control('footer_sub_heading', array('label'=>'Sub Heading', 'section'=>'sec_footer', 'type'=>'textarea'));
    $wp_customize->add_setting('footer_email', array('default'=>'hinischalsubba@gmail.com'));
    $wp_customize->add_control('footer_email', array('label'=>'Email', 'section'=>'sec_footer', 'type'=>'text'));
    $wp_customize->add_setting('footer_copyright', array('default'=>'© 2026 Nischhal Raj Subba.'));
    $wp_customize->add_control('footer_copyright', array('label'=>'Copyright', 'section'=>'sec_footer', 'type'=>'text'));
    
    // Socials
    $wp_customize->add_setting('social_linkedin', array('default'=>''));
    $wp_customize->add_control('social_linkedin', array('label'=>'LinkedIn', 'section'=>'sec_footer', 'type'=>'url'));
    $wp_customize->add_setting('social_behance', array('default'=>''));
    $wp_customize->add_control('social_behance', array('label'=>'Behance', 'section'=>'sec_footer', 'type'=>'url'));
    $wp_customize->add_setting('social_dribbble', array('default'=>''));
    $wp_customize->add_control('social_dribbble', array('label'=>'Dribbble', 'section'=>'sec_footer', 'type'=>'url'));
    $wp_customize->add_setting('social_uxcel', array('default'=>''));
    $wp_customize->add_control('social_uxcel', array('label'=>'Uxcel', 'section'=>'sec_footer', 'type'=>'url'));
    $wp_customize->add_setting('social_figma', array('default'=>''));
    $wp_customize->add_control('social_figma', array('label'=>'Figma', 'section'=>'sec_footer', 'type'=>'url'));
    $wp_customize->add_setting('social_x', array('default'=>''));
    $wp_customize->add_control('social_x', array('label'=>'X (Twitter)', 'section'=>'sec_footer', 'type'=>'url'));
    
    $wp_customize->add_setting('footer_col1_title', array('default'=>'Sitemap'));
    $wp_customize->add_control('footer_col1_title', array('label'=>'Col 1 Title', 'section'=>'sec_footer', 'type'=>'text'));
    $wp_customize->add_setting('footer_col2_title', array('default'=>'Socials'));
    $wp_customize->add_control('footer_col2_title', array('label'=>'Col 2 Title', 'section'=>'sec_footer', 'type'=>'text'));
    $wp_customize->add_setting('footer_col3_title', array('default'=>'Products'));
    $wp_customize->add_control('footer_col3_title', array('label'=>'Col 3 Title', 'section'=>'sec_footer', 'type'=>'text'));
}
add_action( 'customize_register', 'nischhal_customize_register' );

// Helper
function get_project_cat_slugs($post_id) {
    $terms = get_the_terms($post_id, 'project_category');
    if ($terms && !is_wp_error($terms)) {
        $slugs = [];
        foreach ($terms as $term) $slugs[] = $term->slug;
        return implode(' ', $slugs);
    }
    return '';
}

// CSS Injection
function nischhal_customizer_css() {
    ?>
    <style>
        :root {
            /* Layout */
            --max-width: <?php echo get_theme_mod('container_width', '1200px'); ?>;
            --section-gap: <?php echo get_theme_mod('section_gap', '160px'); ?>;
            
            /* Typography */
            --font-serif: "<?php echo get_theme_mod('typo_heading_family', 'Playfair Display'); ?>", serif;
            --font-sans: "<?php echo get_theme_mod('typo_body_family', 'Inter'); ?>", sans-serif;
            
            /* Interaction */
            --anim-speed: <?php echo get_theme_mod('anim_speed', 1.0); ?>;
            --cursor-size: <?php echo get_theme_mod('cursor_size', 20); ?>px;

            /* Dark Theme (Default) */
            --bg-root: <?php echo get_theme_mod('d_bg', '#050505'); ?>;
            --bg-surface: <?php echo get_theme_mod('d_surface', '#0a0a0a'); ?>;
            --text-primary: <?php echo get_theme_mod('d_text', '#FFFFFF'); ?>;
            --text-secondary: <?php echo get_theme_mod('d_text_muted', '#D4D4D8'); ?>;
            --border-faint: <?php echo get_theme_mod('d_border', 'rgba(255,255,255,0.08)'); ?>;
            --accent-blue: <?php echo get_theme_mod('d_accent', '#3B82F6'); ?>;
            --cursor-color: #FFFFFF;
        }

        [data-theme="light"] {
            --bg-root: <?php echo get_theme_mod('l_bg', '#FFFFFF'); ?>;
            --bg-surface: <?php echo get_theme_mod('l_surface', '#F8FAFC'); ?>;
            --text-primary: <?php echo get_theme_mod('l_text', '#0F172A'); ?>;
            --text-secondary: <?php echo get_theme_mod('l_text_muted', '#475569'); ?>;
            --border-faint: <?php echo get_theme_mod('l_border', 'rgba(0,0,0,0.08)'); ?>;
            --accent-blue: <?php echo get_theme_mod('l_accent', '#2563EB'); ?>;
            --cursor-color: #000000;
        }
        
        #grid-canvas { opacity: <?php echo get_theme_mod('grid_opacity', 0.05); ?> !important; }
    </style>
    <?php
}
add_action( 'wp_head', 'nischhal_customizer_css' );
?>
