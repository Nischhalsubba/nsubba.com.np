<?php
/**
 * Nischhal Portfolio Functions
 * 
 * 1. Theme Setup
 * 2. Enqueue Scripts/Styles
 * 3. Custom Post Types (Projects)
 * 4. Customizer Settings (The Core Logic)
 * 5. CSS Variable Injection
 */

// --- 1. THEME SETUP ---
function nischhal_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption' ) );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'nischhal' ),
    ) );
}
add_action( 'after_setup_theme', 'nischhal_theme_setup' );

// --- 2. ENQUEUE ASSETS ---
function nischhal_enqueue_scripts() {
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap', array(), null );
    wp_enqueue_style( 'main-style', get_stylesheet_uri(), array(), '17.0' );
    wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), null, true );
    wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), null, true );
    wp_enqueue_script( 'theme-script', get_template_directory_uri() . '/js/main.js', array('gsap', 'gsap-scrolltrigger'), '17.0', true );
    
    wp_localize_script( 'theme-script', 'themeData', array(
        'templateUrl' => get_template_directory_uri(),
        'ajaxUrl'     => admin_url( 'admin-ajax.php' )
    ));
}
add_action( 'wp_enqueue_scripts', 'nischhal_enqueue_scripts' );

// --- 3. CUSTOM POST TYPES (PROJECTS) ---
function nischhal_register_projects() {
    $labels = array(
        'name'               => 'Work',
        'singular_name'      => 'Project',
        'menu_name'          => 'Work (Projects)',
        'add_new'            => 'Add Project',
        'add_new_item'       => 'Add New Project',
        'edit_item'          => 'Edit Project',
        'new_item'           => 'New Project',
        'view_item'          => 'View Project',
        'search_items'       => 'Search Work',
        'not_found'          => 'No projects found',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'menu_icon'           => 'dashicons-art', 
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
        'rewrite'             => array( 'slug' => 'work' ),
        'show_in_rest'        => true, 
    );

    register_post_type( 'project', $args );

    register_taxonomy( 'project_category', 'project', array(
        'labels' => array( 'name' => 'Categories' ),
        'hierarchical' => true,
        'show_in_rest' => true,
        'public' => true
    ));
}
add_action( 'init', 'nischhal_register_projects' );

// --- 4. CUSTOMIZER SETTINGS ---
function nischhal_customize_register( $wp_customize ) {
    
    // --- PANEL: BRANDING & DESIGN SYSTEM ---
    $wp_customize->add_panel( 'nischhal_design_system', array(
        'title'       => __( 'Design System & Branding', 'nischhal' ),
        'priority'    => 20,
        'description' => 'Manage Global Colors, Fonts, and Theme Modes.'
    ));

    /**
     * SECTION: DARK MODE COLORS (Default)
     */
    $wp_customize->add_section( 'nischhal_colors_dark', array(
        'title'    => __( 'Colors: Dark Theme', 'nischhal' ),
        'panel'    => 'nischhal_design_system',
        'priority' => 10,
    ));

    $dark_colors = array(
        'dark_bg_root' => array( 'default' => '#050505', 'label' => 'Background Base' ),
        'dark_bg_surface' => array( 'default' => '#0a0a0a', 'label' => 'Surface / Panels' ),
        'dark_text_primary' => array( 'default' => '#FFFFFF', 'label' => 'Primary Text' ),
        'dark_text_secondary' => array( 'default' => '#D4D4D8', 'label' => 'Secondary Text' ),
        'dark_accent' => array( 'default' => '#3B82F6', 'label' => 'Accent Color' ),
    );

    foreach( $dark_colors as $id => $data ) {
        $wp_customize->add_setting( $id, array( 'default' => $data['default'], 'transport' => 'refresh' ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
            'label'    => $data['label'],
            'section'  => 'nischhal_colors_dark',
        )));
    }

    /**
     * SECTION: LIGHT MODE COLORS
     */
    $wp_customize->add_section( 'nischhal_colors_light', array(
        'title'    => __( 'Colors: Light Theme', 'nischhal' ),
        'panel'    => 'nischhal_design_system',
        'priority' => 11,
    ));

    $light_colors = array(
        'light_bg_root' => array( 'default' => '#FFFFFF', 'label' => 'Background Base' ),
        'light_bg_surface' => array( 'default' => '#F8FAFC', 'label' => 'Surface / Panels' ),
        'light_text_primary' => array( 'default' => '#0f172a', 'label' => 'Primary Text' ),
        'light_text_secondary' => array( 'default' => '#475569', 'label' => 'Secondary Text' ),
        'light_accent' => array( 'default' => '#2563EB', 'label' => 'Accent Color' ),
    );

    foreach( $light_colors as $id => $data ) {
        $wp_customize->add_setting( $id, array( 'default' => $data['default'], 'transport' => 'refresh' ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
            'label'    => $data['label'],
            'section'  => 'nischhal_colors_light',
        )));
    }

    /**
     * SECTION: TYPOGRAPHY (Responsive)
     */
    $wp_customize->add_section( 'nischhal_typo', array(
        'title'    => __( 'Typography (Web & Mobile)', 'nischhal' ),
        'panel'    => 'nischhal_design_system',
        'priority' => 12,
    ));

    $typo_settings = array(
        'h1_size_desktop' => array('default' => '4rem', 'label' => 'H1 Size (Desktop)'),
        'h1_size_mobile'  => array('default' => '2.5rem', 'label' => 'H1 Size (Mobile)'),
        'h2_size_desktop' => array('default' => '3rem', 'label' => 'H2 Size (Desktop)'),
        'h2_size_mobile'  => array('default' => '2rem', 'label' => 'H2 Size (Mobile)'),
        'body_size_desktop' => array('default' => '1.15rem', 'label' => 'Body Text (Desktop)'),
        'body_size_mobile'  => array('default' => '1rem', 'label' => 'Body Text (Mobile)'),
    );

    foreach($typo_settings as $id => $data) {
        $wp_customize->add_setting( $id, array( 'default' => $data['default'], 'transport' => 'refresh' ) );
        $wp_customize->add_control( $id, array(
            'label'    => $data['label'],
            'section'  => 'nischhal_typo',
            'type'     => 'text',
            'description' => 'Use rem, px, or clamp()'
        ));
    }

    // --- PANEL: HOME PAGE BUILDER ---
    $wp_customize->add_panel( 'nischhal_home_builder', array(
        'title'       => __( 'Home Page Builder', 'nischhal' ),
        'priority'    => 30,
    ));

    // Section: Hero
    $wp_customize->add_section( 'nischhal_home_hero', array( 'title' => 'Hero Section', 'panel' => 'nischhal_home_builder' ) );
    
    $wp_customize->add_setting( 'home_hero_title_1', array( 'default' => 'Crafting scalable', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'home_hero_title_1', array( 'label' => 'Hero Line 1', 'section' => 'nischhal_home_hero' ) );
    
    $wp_customize->add_setting( 'home_hero_title_2', array( 'default' => 'digital products.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'home_hero_title_2', array( 'label' => 'Hero Line 2', 'section' => 'nischhal_home_hero' ) );

    $wp_customize->add_setting( 'home_hero_img', array( 'default' => 'https://i.imgur.com/ixsEpYM.png' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'home_hero_img', array(
        'label'    => 'Hero Portrait',
        'section'  => 'nischhal_home_hero',
    )));

    // Section: Testimonials
    $wp_customize->add_section( 'nischhal_home_testimonials', array( 'title' => 'Testimonials', 'panel' => 'nischhal_home_builder' ) );

    for( $i = 1; $i <= 3; $i++ ) {
        $wp_customize->add_setting( "testimonial_{$i}_quote", array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "testimonial_{$i}_quote", array( 'label' => "Testimonial $i Quote", 'type' => 'textarea', 'section' => 'nischhal_home_testimonials' ) );
        
        $wp_customize->add_setting( "testimonial_{$i}_author", array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "testimonial_{$i}_author", array( 'label' => "Testimonial $i Name", 'section' => 'nischhal_home_testimonials' ) );

        $wp_customize->add_setting( "testimonial_{$i}_role", array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "testimonial_{$i}_role", array( 'label' => "Testimonial $i Role/Company", 'section' => 'nischhal_home_testimonials' ) );
    }

    // Section: Footer (Global)
    $wp_customize->add_section( 'nischhal_footer_section', array( 'title' => 'Footer Settings', 'priority' => 100 ) );
    $wp_customize->add_setting( 'footer_email', array( 'default' => 'hinischalsubba@gmail.com', 'sanitize_callback' => 'sanitize_email' ) );
    $wp_customize->add_control( 'footer_email', array( 'label' => 'Contact Email', 'section' => 'nischhal_footer_section' ) );

    $wp_customize->add_setting( 'social_linkedin', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'social_linkedin', array( 'label' => 'LinkedIn URL', 'section' => 'nischhal_footer_section' ) );
    
    $wp_customize->add_setting( 'social_behance', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'social_behance', array( 'label' => 'Behance URL', 'section' => 'nischhal_footer_section' ) );

}
add_action( 'customize_register', 'nischhal_customize_register' );

// --- 5. INJECT CUSTOM CSS (THE BRANDING ENGINE) ---
function nischhal_customizer_css() {
    ?>
    <style type="text/css">
        /* DARK THEME (Default / Root) */
        :root {
            --bg-root: <?php echo get_theme_mod( 'dark_bg_root', '#050505' ); ?>;
            --bg-surface: <?php echo get_theme_mod( 'dark_bg_surface', '#0a0a0a' ); ?>;
            --text-primary: <?php echo get_theme_mod( 'dark_text_primary', '#FFFFFF' ); ?>;
            --text-secondary: <?php echo get_theme_mod( 'dark_text_secondary', '#D4D4D8' ); ?>;
            --accent-blue: <?php echo get_theme_mod( 'dark_accent', '#3B82F6' ); ?>;
            
            /* Calculated variants for Dark */
            --bg-card: color-mix(in srgb, var(--bg-root), #fff 8%);
            --border-faint: color-mix(in srgb, var(--text-primary), transparent 92%);
        }

        /* LIGHT THEME (Data Attribute Override) */
        [data-theme="light"] {
            --bg-root: <?php echo get_theme_mod( 'light_bg_root', '#FFFFFF' ); ?>;
            --bg-surface: <?php echo get_theme_mod( 'light_bg_surface', '#F8FAFC' ); ?>;
            --text-primary: <?php echo get_theme_mod( 'light_text_primary', '#0f172a' ); ?>;
            --text-secondary: <?php echo get_theme_mod( 'light_text_secondary', '#475569' ); ?>;
            --accent-blue: <?php echo get_theme_mod( 'light_accent', '#2563EB' ); ?>;
            
            /* Calculated variants for Light */
            --bg-card: <?php echo get_theme_mod( 'light_bg_root', '#FFFFFF' ); ?>;
            --border-faint: rgba(0, 0, 0, 0.08);
        }
        
        /* RESPONSIVE TYPOGRAPHY */
        
        /* Mobile (Default) */
        .hero-title { font-size: <?php echo get_theme_mod('h1_size_mobile', '2.5rem'); ?>; }
        .section-title, h2 { font-size: <?php echo get_theme_mod('h2_size_mobile', '2rem'); ?>; }
        .body-large, p { font-size: <?php echo get_theme_mod('body_size_mobile', '1rem'); ?>; }

        /* Desktop */
        @media (min-width: 768px) {
            .hero-title { font-size: <?php echo get_theme_mod('h1_size_desktop', '4rem'); ?>; }
            .section-title, h2 { font-size: <?php echo get_theme_mod('h2_size_desktop', '3rem'); ?>; }
            .body-large { font-size: <?php echo get_theme_mod('body_size_desktop', '1.15rem'); ?>; }
        }
    </style>
    <?php
}
add_action( 'wp_head', 'nischhal_customizer_css' );

// Helper: Get project meta
function get_project_meta_value($id, $key, $default = '') {
    $val = get_post_meta($id, $key, true);
    return $val ? $val : $default;
}
?>