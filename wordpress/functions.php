<?php
/**
 * Nischhal Portfolio - Core Functions
 * Includes: One-Stop Customizer (Typography, Colors, Layout, Interaction)
 */

// --- 1. SETUP ---
function nischhal_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array('height'=>80, 'width'=>200, 'flex-height'=>true) );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption' ) );
    register_nav_menus( array( 'primary' => 'Primary Menu' ) );
}
add_action( 'after_setup_theme', 'nischhal_theme_setup' );

// --- 2. ENQUEUE ASSETS ---
function nischhal_enqueue_scripts() {
    // Dynamic Google Fonts Loader
    $h_font = get_theme_mod('typo_heading_family', 'Playfair Display');
    $b_font = get_theme_mod('typo_body_family', 'Inter');
    $weights = get_theme_mod('typo_weights', '300;400;500;600;700'); // User defined weights
    
    if( !empty($h_font) || !empty($b_font) ) {
        $fonts = array();
        if($h_font) $fonts[] = $h_font . ':wght@' . $weights;
        if($b_font) $fonts[] = $b_font . ':wght@' . $weights;
        $fonts = array_unique($fonts);
        $font_args = implode('&family=', array_map('urlencode', $fonts));
        $fonts_url = "https://fonts.googleapis.com/css2?family={$font_args}&display=swap";
        wp_enqueue_style( 'nischhal-google-fonts', $fonts_url, array(), null );
    }

    // Main Styles
    wp_enqueue_style( 'main-style', get_stylesheet_uri(), array(), '4.0' );
    
    // JS Libraries
    wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), null, true );
    wp_enqueue_script( 'gsap-st', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), null, true );
    wp_enqueue_script( 'theme-js', get_template_directory_uri() . '/js/main.js', array('gsap'), '4.0', true );

    // Pass Configuration to JS
    wp_localize_script( 'theme-js', 'themeConfig', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'animSpeed' => get_theme_mod('motion_speed', 1.0),
        'cursorStyle' => get_theme_mod('cursor_style', 'classic'),
        'cursorSize' => get_theme_mod('cursor_size', 20),
        'cursorTrailLen' => get_theme_mod('cursor_trail_len', 5),
        'cursorText' => get_theme_mod('cursor_hover_text', 'OPEN'),
        'gridHighlight' => get_theme_mod('grid_highlight', true),
        'gridRadius' => get_theme_mod('grid_radius', 300),
    ));
}
add_action( 'wp_enqueue_scripts', 'nischhal_enqueue_scripts' );

// --- 3. PROJECT CPT ---
function nischhal_register_projects() {
    register_post_type( 'project', array(
        'labels' => array( 'name' => 'Work', 'singular_name' => 'Project' ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-art',
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'rewrite' => array( 'slug' => 'work' ),
        'show_in_rest' => true
    ));
    register_taxonomy( 'project_category', 'project', array( 'labels' => array('name'=>'Categories'), 'hierarchical'=>true, 'show_in_rest'=>true, 'public'=>true ) );
}
add_action( 'init', 'nischhal_register_projects' );

// --- 4. CUSTOMIZER ---
function nischhal_customize_register( $wp_customize ) {
    
    // --- PANEL: TYPOGRAPHY (NEW) ---
    $wp_customize->add_panel( 'panel_typography', array( 'title' => 'Design: Typography', 'priority' => 15 ) );
    
    // Section: Fonts
    $wp_customize->add_section( 'sec_typo_fam', array( 'title' => 'Font Families', 'panel' => 'panel_typography' ) );
    
    $wp_customize->add_setting( 'typo_heading_family', array( 'default' => 'Playfair Display' ) );
    $wp_customize->add_control( 'typo_heading_family', array( 
        'label' => 'Heading Font (Google)', 
        'description' => 'Enter exact name from fonts.google.com (e.g. "Space Grotesk")',
        'section' => 'sec_typo_fam', 'type' => 'text' 
    ));

    $wp_customize->add_setting( 'typo_body_family', array( 'default' => 'Inter' ) );
    $wp_customize->add_control( 'typo_body_family', array( 
        'label' => 'Body Font (Google)', 
        'section' => 'sec_typo_fam', 'type' => 'text' 
    ));

    $wp_customize->add_setting( 'typo_weights', array( 'default' => '300;400;500;600;700' ) );
    $wp_customize->add_control( 'typo_weights', array( 
        'label' => 'Weights to Load', 
        'description' => 'Semicolon separated (e.g. 400;700)',
        'section' => 'sec_typo_fam', 'type' => 'text' 
    ));

    // Section: Sizes
    $wp_customize->add_section( 'sec_typo_size', array( 'title' => 'Sizes & Scale', 'panel' => 'panel_typography' ) );
    
    $wp_customize->add_setting( 'typo_base_size', array( 'default' => '1rem' ) );
    $wp_customize->add_control( 'typo_base_size', array( 'label' => 'Body Base Size', 'section' => 'sec_typo_size', 'type' => 'text' ) );
    
    $wp_customize->add_setting( 'typo_h1_size', array( 'default' => '4.5rem' ) );
    $wp_customize->add_control( 'typo_h1_size', array( 'label' => 'H1 Desktop Size', 'section' => 'sec_typo_size', 'type' => 'text' ) );

    // --- PANEL: INTERACTION & CURSOR ---
    $wp_customize->add_panel( 'panel_interaction', array( 'title' => 'Interaction & Cursor', 'priority' => 20 ) );
    
    $wp_customize->add_section( 'sec_cursor_style', array( 'title' => 'Premium Cursor', 'panel' => 'panel_interaction' ) );
    $wp_customize->add_setting( 'cursor_style', array( 'default' => 'classic' ) );
    $wp_customize->add_control( 'cursor_style', array( 
        'label' => 'Cursor Design', 
        'section' => 'sec_cursor_style', 
        'type' => 'select',
        'choices' => array(
            'classic' => 'Classic Dot', 'modern' => 'Modern Ring', 'liquid' => 'Liquid Trail',
            'exclusion' => 'Exclusion Orb', 'blur' => 'Soft Blur', 'crosshair' => 'Crosshair',
            'spotlight' => 'Spotlight', 'brackets' => 'Brackets', 'elastic' => 'Elastic', 'echo' => 'Ghost Echo'
        )
    ));
    $wp_customize->add_setting( 'cursor_size', array( 'default' => 20 ) );
    $wp_customize->add_control( 'cursor_size', array( 'label' => 'Size (px)', 'section' => 'sec_cursor_style', 'type' => 'number' ) );
    $wp_customize->add_setting( 'cursor_hover_text', array( 'default' => 'VIEW' ) );
    $wp_customize->add_control( 'cursor_hover_text', array( 'label' => 'Hover Label', 'section' => 'sec_cursor_style', 'type' => 'text' ) );

    // --- PANEL: COLORS ---
    $wp_customize->add_panel( 'panel_colors', array( 'title' => 'Design: Colors', 'priority' => 21 ) );
    
    $themes = ['dark' => 'Dark Mode', 'light' => 'Light Mode'];
    foreach($themes as $slug => $label) {
        $wp_customize->add_section( "sec_colors_{$slug}", array( 'title' => $label, 'panel' => 'panel_colors' ) );
        $defaults = $slug === 'dark' ? ['bg'=>'#050505', 'surface'=>'#0a0a0a', 'text'=>'#FFFFFF', 'accent'=>'#3B82F6'] : ['bg'=>'#FFFFFF', 'surface'=>'#F8FAFC', 'text'=>'#0f172a', 'accent'=>'#2563EB'];
        foreach(['bg','surface','text','accent'] as $k) {
            $wp_customize->add_setting( "{$slug}_{$k}", array( 'default' => $defaults[$k], 'transport' => 'refresh' ) );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "{$slug}_{$k}", array( 'label' => ucfirst($k), 'section' => "sec_colors_{$slug}" ) ) );
        }
    }

    // --- PANEL: LAYOUT ---
    $wp_customize->add_panel( 'panel_layout', array( 'title' => 'Design: Layout', 'priority' => 22 ) );
    $wp_customize->add_section( 'sec_grid', array( 'title' => 'Grid Settings', 'panel' => 'panel_layout' ) );
    
    $wp_customize->add_setting( 'layout_max_width', array( 'default' => '1200px' ) );
    $wp_customize->add_control( 'layout_max_width', array( 'label' => 'Container Width', 'section' => 'sec_grid' ) );
    
    $wp_customize->add_setting( 'grid_highlight', array( 'default' => true ) );
    $wp_customize->add_control( 'grid_highlight', array( 'label' => 'Enable Flashlight Grid', 'section' => 'sec_grid', 'type' => 'checkbox' ) );
}
add_action( 'customize_register', 'nischhal_customize_register' );

// --- 5. CSS VARIABLES ---
function nischhal_customizer_css() {
    ?>
    <style>
        :root {
            /* TYPOGRAPHY */
            --font-serif: "<?php echo get_theme_mod('typo_heading_family', 'Playfair Display'); ?>", serif;
            --font-sans: "<?php echo get_theme_mod('typo_body_family', 'Inter'); ?>", sans-serif;
            --body-size: <?php echo get_theme_mod('typo_base_size', '1rem'); ?>;
            --h1-size: <?php echo get_theme_mod('typo_h1_size', '4.5rem'); ?>;
            
            /* LAYOUT */
            --max-width: <?php echo get_theme_mod('layout_max_width', '1200px'); ?>;

            /* DARK THEME */
            --bg-root: <?php echo get_theme_mod('dark_bg', '#050505'); ?>;
            --bg-surface: <?php echo get_theme_mod('dark_surface', '#0a0a0a'); ?>;
            --text-primary: <?php echo get_theme_mod('dark_text', '#FFFFFF'); ?>;
            --accent-color: <?php echo get_theme_mod('dark_accent', '#3B82F6'); ?>;
        }
        [data-theme="light"] {
            /* LIGHT THEME */
            --bg-root: <?php echo get_theme_mod('light_bg', '#FFFFFF'); ?>;
            --bg-surface: <?php echo get_theme_mod('light_surface', '#F8FAFC'); ?>;
            --text-primary: <?php echo get_theme_mod('light_text', '#0f172a'); ?>;
            --accent-color: <?php echo get_theme_mod('light_accent', '#2563EB'); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'nischhal_customizer_css' );

// Helper for Project Cats
function get_project_cat_slugs($post_id) {
    $terms = get_the_terms($post_id, 'project_category');
    if ($terms && !is_wp_error($terms)) {
        $slugs = [];
        foreach ($terms as $term) $slugs[] = $term->slug;
        return implode(' ', $slugs);
    }
    return '';
}
?>