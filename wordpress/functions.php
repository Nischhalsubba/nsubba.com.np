<?php
function nischhal_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'nischhal' ),
    ) );
}
add_action( 'after_setup_theme', 'nischhal_theme_setup' );

function nischhal_enqueue_scripts() {
    // Enqueue Styles
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap', array(), null );
    wp_enqueue_style( 'main-style', get_stylesheet_uri(), array(), '15.0' );

    // Enqueue Scripts
    wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), null, true );
    wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), null, true );
    wp_enqueue_script( 'theme-script', get_template_directory_uri() . '/js/main.js', array('gsap', 'gsap-scrolltrigger'), '15.0', true );
}
add_action( 'wp_enqueue_scripts', 'nischhal_enqueue_scripts' );
?>