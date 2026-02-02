<?php
/**
 * Regeneración Lab Theme Functions
 */

function regen_wp_enqueue_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style( 'regen-main-style', get_stylesheet_uri() );
    
    // Enqueue Google Fonts (from index.html)
    wp_enqueue_style( 'regen-google-fonts', 'https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&family=Roboto:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&family=Instrument+Serif:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap', array(), null );
}
add_action( 'wp_enqueue_scripts', 'regen_wp_enqueue_scripts' );

// Theme supports
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );

// Navigation menu locations
function regen_wp_register_menus() {
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'regen-wp' ),
    ) );
}
add_action( 'after_setup_theme', 'regen_wp_register_menus' );

// Add nav-link class to primary menu anchors for styling parity
function regen_wp_nav_link_class( $atts, $item, $args ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        $existing_class = isset( $atts['class'] ) ? $atts['class'] . ' ' : '';
        $atts['class'] = trim( $existing_class . 'nav-link' );
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'regen_wp_nav_link_class', 10, 3 );

// Theme options via Customizer (hero and support CTA)
function regen_wp_customize_register( $wp_customize ) {
    $section_id = 'regen_wp_theme_options';

    $wp_customize->add_section( $section_id, array(
        'title'       => __( 'Regeneracion Theme Options', 'regen-wp' ),
        'priority'    => 30,
        'description' => __( 'Homepage hero and support call-to-action settings.', 'regen-wp' ),
    ) );

    // Hero quote
    $wp_customize->add_setting( 'regen_hero_quote', array(
        'default'           => '"THEY TRIED TO BURY US BUT THEY DIDN\'T KNOW WE WERE SEEDS"',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'regen_hero_quote', array(
        'label'   => __( 'Hero Quote', 'regen-wp' ),
        'section' => $section_id,
        'type'    => 'textarea',
    ) );

    // Hero attribution
    $wp_customize->add_setting( 'regen_hero_attribution', array(
        'default'           => 'Mexican revolutionary dicho circa. 1910',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'regen_hero_attribution', array(
        'label'   => __( 'Hero Attribution', 'regen-wp' ),
        'section' => $section_id,
        'type'    => 'text',
    ) );

    // Hero background image
    $wp_customize->add_setting( 'regen_hero_image', array(
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'regen_hero_image', array(
        'label'    => __( 'Hero Background Image', 'regen-wp' ),
        'section'  => $section_id,
        'mime_type'=> 'image',
    ) ) );

    // Support heading
    $wp_customize->add_setting( 'regen_support_heading', array(
        'default'           => 'Support Our Work',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'regen_support_heading', array(
        'label'   => __( 'Support Heading', 'regen-wp' ),
        'section' => $section_id,
        'type'    => 'text',
    ) );

    // Support text
    $wp_customize->add_setting( 'regen_support_text', array(
        'default'           => 'Regeneración Lab operates through community support and grant funding. Your contribution helps us maintain this platform, support resident scholars, and keep these resources freely accessible.',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'regen_support_text', array(
        'label'   => __( 'Support Text', 'regen-wp' ),
        'section' => $section_id,
        'type'    => 'textarea',
    ) );

    // Support URL
    $wp_customize->add_setting( 'regen_support_url', array(
        'default'           => 'https://give.ucsb.edu/campaigns/58594/donations/new',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'regen_support_url', array(
        'label'   => __( 'Support URL', 'regen-wp' ),
        'section' => $section_id,
        'type'    => 'url',
    ) );
}
add_action( 'customize_register', 'regen_wp_customize_register' );

// Note: SPA hash-routing has been removed from enqueue; app.js remains in the theme for reference if hash navigation is ever needed again.

// Custom post types for structured content
function regen_wp_register_cpts() {
    register_post_type( 'project', array(
        'labels' => array(
            'name' => __( 'Projects', 'regen-wp' ),
            'singular_name' => __( 'Project', 'regen-wp' ),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 5,
        'show_in_rest' => true,
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
        'rewrite' => array( 'slug' => 'projects' ),
    ) );

    register_post_type( 'resident', array(
        'labels' => array(
            'name' => __( 'Residents', 'regen-wp' ),
            'singular_name' => __( 'Resident', 'regen-wp' ),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 6,
        'show_in_rest' => true,
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
        'rewrite' => array( 'slug' => 'residents' ),
    ) );

    register_post_type( 'collaboration', array(
        'labels' => array(
            'name' => __( 'Collaborations', 'regen-wp' ),
            'singular_name' => __( 'Collaboration', 'regen-wp' ),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 7,
        'show_in_rest' => true,
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
        'rewrite' => array( 'slug' => 'collaborations' ),
    ) );
}
add_action( 'init', 'regen_wp_register_cpts' );
