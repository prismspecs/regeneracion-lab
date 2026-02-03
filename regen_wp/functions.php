<?php
/**
 * Regeneración Lab Theme Functions
 */

function regen_wp_enqueue_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style( 'regen-main-style', get_stylesheet_uri() );
    
    // Enqueue Google Fonts (from index.html)
    wp_enqueue_style( 'regen-google-fonts', 'https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&family=Roboto:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&family=Instrument+Serif:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap', array(), null );

    // PJAX-style nav swaps (keeps clean URLs)
    wp_enqueue_script( 'regen-pjax', get_template_directory_uri() . '/pjax.js', array(), null, true );
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

    // Support button label
    $wp_customize->add_setting( 'regen_support_button_label', array(
        'default'           => 'CONTRIBUTE',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'regen_support_button_label', array(
        'label'   => __( 'Support Button Label', 'regen-wp' ),
        'section' => $section_id,
        'type'    => 'text',
    ) );

    // Support popover message
    $wp_customize->add_setting( 'regen_support_popover_message', array(
        'default'           => 'When you check out, specify the donation is for Regeneracion Lab.',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'regen_support_popover_message', array(
        'label'   => __( 'Support Popover Message', 'regen-wp' ),
        'section' => $section_id,
        'type'    => 'textarea',
    ) );

    // Support popover continue button
    $wp_customize->add_setting( 'regen_support_popover_button', array(
        'default'           => 'Continue',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'regen_support_popover_button', array(
        'label'   => __( 'Support Popover Button Label', 'regen-wp' ),
        'section' => $section_id,
        'type'    => 'text',
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
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
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
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
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
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
        'rewrite' => array( 'slug' => 'collaborations' ),
    ) );

    register_post_type( 'person', array(
        'labels' => array(
            'name' => __( 'People', 'regen-wp' ),
            'singular_name' => __( 'Person', 'regen-wp' ),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 8,
        'show_in_rest' => true,
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
        'rewrite' => array( 'slug' => 'people' ),
    ) );
}
add_action( 'init', 'regen_wp_register_cpts' );

// Project meta (badge/meta/link label) with editor-friendly UI
function regen_wp_register_project_meta() {
    register_post_meta( 'project', 'project_badge', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_post_meta( 'project', 'project_meta', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_post_meta( 'project', 'project_link_label', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_post_meta( 'project', 'project_link_url', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'esc_url_raw',
    ) );

    register_post_meta( 'project', 'project_style', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_post_meta( 'project', 'project_title_line1', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_post_meta( 'project', 'project_title_line2', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    // Collaboration link meta
    register_post_meta( 'collaboration', 'collaboration_link_label', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_post_meta( 'collaboration', 'collaboration_link_url', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'esc_url_raw',
    ) );

    // Post update links (multiple CTA links per update/post)
    register_post_meta( 'post', 'update_links', array(
        'type'              => 'array',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'regen_wp_sanitize_update_links',
    ) );

    // People meta
    register_post_meta( 'person', 'person_role', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_post_meta( 'person', 'person_years', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_post_meta( 'person', 'person_link_label', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_post_meta( 'person', 'person_link_url', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'esc_url_raw',
    ) );

    register_post_meta( 'person', 'person_order', array(
        'type'         => 'number',
        'single'       => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'absint',
    ) );
}
add_action( 'init', 'regen_wp_register_project_meta' );

function regen_wp_project_metabox() {
    add_meta_box(
        'regen_project_meta',
        __( 'Project Display', 'regen-wp' ),
        'regen_wp_project_metabox_render',
        'project',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'regen_wp_project_metabox' );

function regen_wp_project_metabox_render( $post ) {
    wp_nonce_field( 'regen_wp_save_project_meta', 'regen_wp_project_nonce' );

    $badge        = get_post_meta( $post->ID, 'project_badge', true );
    $meta         = get_post_meta( $post->ID, 'project_meta', true );
    $link_label   = get_post_meta( $post->ID, 'project_link_label', true );
    $link_url     = get_post_meta( $post->ID, 'project_link_url', true );
    $style_value  = get_post_meta( $post->ID, 'project_style', true );
    $title_line1  = get_post_meta( $post->ID, 'project_title_line1', true );
    $title_line2  = get_post_meta( $post->ID, 'project_title_line2', true );

    $badge_options = array( '', 'NEW', 'ACTIVE', 'ONGOING', 'PAUSED', 'ARCHIVE' );
    $style_options = array(
        'turquoise' => __( 'Turquoise (default)', 'regen-wp' ),
        'brown'     => __( 'Brown', 'regen-wp' ),
        'amber'     => __( 'Amber', 'regen-wp' ),
    );
    ?>
    <p><strong><?php esc_html_e( 'Badge (NEW, ACTIVE, etc.)', 'regen-wp' ); ?></strong></p>
    <select name="project_badge" style="width:100%">
        <?php foreach ( $badge_options as $option ) : ?>
            <option value="<?php echo esc_attr( $option ); ?>" <?php selected( $badge, $option ); ?>><?php echo $option ? esc_html( ucfirst( strtolower( $option ) ) ) : '—'; ?></option>
        <?php endforeach; ?>
    </select>

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Year(s) or status (e.g., Ongoing, 2025-2026)', 'regen-wp' ); ?></strong></p>
    <input type="text" name="project_meta" value="<?php echo esc_attr( $meta ); ?>" style="width:100%" />

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Button label (default: Explore)', 'regen-wp' ); ?></strong></p>
    <input type="text" name="project_link_label" value="<?php echo esc_attr( $link_label ); ?>" style="width:100%" />

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Link URL (leave blank to use this project page; add full URL for external destinations)', 'regen-wp' ); ?></strong></p>
    <input type="url" name="project_link_url" value="<?php echo esc_attr( $link_url ); ?>" style="width:100%" />

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Card style (accent color)', 'regen-wp' ); ?></strong></p>
    <select name="project_style" style="width:100%">
        <?php foreach ( $style_options as $key => $label ) : ?>
            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $style_value ? $style_value : 'turquoise', $key ); ?>><?php echo esc_html( $label ); ?></option>
        <?php endforeach; ?>
    </select>

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Title Line 1 (use to split long titles manually)', 'regen-wp' ); ?></strong></p>
    <input type="text" name="project_title_line1" value="<?php echo esc_attr( $title_line1 ); ?>" style="width:100%" />

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Title Line 2 (optional second line)', 'regen-wp' ); ?></strong></p>
    <input type="text" name="project_title_line2" value="<?php echo esc_attr( $title_line2 ); ?>" style="width:100%" />
    <?php
}

function regen_wp_save_project_meta( $post_id ) {
    if ( ! isset( $_POST['regen_wp_project_nonce'] ) || ! wp_verify_nonce( $_POST['regen_wp_project_nonce'], 'regen_wp_save_project_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['post_type'] ) && 'project' === $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array( 'project_badge', 'project_meta', 'project_link_label', 'project_link_url', 'project_style', 'project_title_line1', 'project_title_line2' );
    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            $raw   = wp_unslash( $_POST[ $field ] );
            $value = ( 'project_link_url' === $field ) ? esc_url_raw( $raw ) : sanitize_text_field( $raw );
            update_post_meta( $post_id, $field, $value );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post_project', 'regen_wp_save_project_meta' );

// Collaboration meta box
function regen_wp_collaboration_metabox() {
    add_meta_box(
        'regen_collaboration_meta',
        __( 'Collaboration Link', 'regen-wp' ),
        'regen_wp_collaboration_metabox_render',
        'collaboration',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'regen_wp_collaboration_metabox' );

function regen_wp_collaboration_metabox_render( $post ) {
    wp_nonce_field( 'regen_wp_save_collaboration_meta', 'regen_wp_collaboration_nonce' );

    $link_label = get_post_meta( $post->ID, 'collaboration_link_label', true );
    $link_url   = get_post_meta( $post->ID, 'collaboration_link_url', true );
    ?>
    <p><strong><?php esc_html_e( 'Button label (default: Learn More)', 'regen-wp' ); ?></strong></p>
    <input type="text" name="collaboration_link_label" value="<?php echo esc_attr( $link_label ); ?>" style="width:100%" />

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Link URL (leave blank to use this collaboration page; add full URL for external destinations)', 'regen-wp' ); ?></strong></p>
    <input type="url" name="collaboration_link_url" value="<?php echo esc_attr( $link_url ); ?>" style="width:100%" />
    <?php
}

function regen_wp_save_collaboration_meta( $post_id ) {
    if ( ! isset( $_POST['regen_wp_collaboration_nonce'] ) || ! wp_verify_nonce( $_POST['regen_wp_collaboration_nonce'], 'regen_wp_save_collaboration_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['post_type'] ) && 'collaboration' === $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array( 'collaboration_link_label', 'collaboration_link_url' );
    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            $raw   = wp_unslash( $_POST[ $field ] );
            $value = ( 'collaboration_link_url' === $field ) ? esc_url_raw( $raw ) : sanitize_text_field( $raw );
            update_post_meta( $post_id, $field, $value );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post_collaboration', 'regen_wp_save_collaboration_meta' );

// Update links meta box for posts
function regen_wp_update_links_metabox() {
    add_meta_box(
        'regen_update_links',
        __( 'Update Links', 'regen-wp' ),
        'regen_wp_update_links_metabox_render',
        'post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'regen_wp_update_links_metabox' );

function regen_wp_update_links_metabox_render( $post ) {
    wp_nonce_field( 'regen_wp_save_update_links', 'regen_wp_update_links_nonce' );
    $links = get_post_meta( $post->ID, 'update_links', true );
    if ( ! is_array( $links ) ) {
        $links = array();
    }
    $max = 3;
    ?>
    <p><?php esc_html_e( 'Add one or more links for this update (label + URL). Leave blank to skip a row.', 'regen-wp' ); ?></p>
    <?php for ( $i = 0; $i < $max; $i++ ) :
        $label = isset( $links[ $i ]['label'] ) ? $links[ $i ]['label'] : '';
        $url   = isset( $links[ $i ]['url'] ) ? $links[ $i ]['url'] : '';
        ?>
        <div style="margin-bottom:12px;">
            <label style="display:block; font-weight:600; margin-bottom:4px;">Link <?php echo ( $i + 1 ); ?></label>
            <input type="text" name="update_links[label][]" value="<?php echo esc_attr( $label ); ?>" placeholder="Label (e.g., Read more)" style="width:100%; margin-bottom:6px;" />
            <input type="url" name="update_links[url][]" value="<?php echo esc_attr( $url ); ?>" placeholder="https://example.com" style="width:100%;" />
        </div>
    <?php endfor; ?>
    <?php
}

function regen_wp_sanitize_update_links( $value ) {
    if ( ! is_array( $value ) ) {
        return array();
    }
    $clean = array();

    // Handle shape: [ 'label' => [...], 'url' => [...] ] from the metabox inputs
    if ( isset( $value['label'] ) || isset( $value['url'] ) ) {
        $labels = isset( $value['label'] ) ? (array) $value['label'] : array();
        $urls   = isset( $value['url'] ) ? (array) $value['url'] : array();
        $max    = max( count( $labels ), count( $urls ) );
        for ( $i = 0; $i < $max; $i++ ) {
            $label = isset( $labels[ $i ] ) ? sanitize_text_field( $labels[ $i ] ) : '';
            $url   = isset( $urls[ $i ] ) ? esc_url_raw( $urls[ $i ] ) : '';
            if ( '' === $label && '' === $url ) {
                continue;
            }
            $clean[] = array(
                'label' => $label,
                'url'   => $url,
            );
        }
        return $clean;
    }

    // Fallback: array of rows
    foreach ( $value as $row ) {
        if ( ! is_array( $row ) ) {
            continue;
        }
        $label = isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '';
        $url   = isset( $row['url'] ) ? esc_url_raw( $row['url'] ) : '';
        if ( '' === $label && '' === $url ) {
            continue;
        }
        $clean[] = array(
            'label' => $label,
            'url'   => $url,
        );
    }
    return $clean;
}

function regen_wp_save_update_links( $post_id ) {
    if ( ! isset( $_POST['regen_wp_update_links_nonce'] ) || ! wp_verify_nonce( $_POST['regen_wp_update_links_nonce'], 'regen_wp_save_update_links' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['post_type'] ) && 'post' === $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['update_links'] ) ) {
        $raw   = wp_unslash( $_POST['update_links'] );
        $clean = regen_wp_sanitize_update_links( $raw );
        if ( ! empty( $clean ) ) {
            update_post_meta( $post_id, 'update_links', $clean );
        } else {
            delete_post_meta( $post_id, 'update_links' );
        }
    } else {
        delete_post_meta( $post_id, 'update_links' );
    }
}
add_action( 'save_post', 'regen_wp_save_update_links' );

// People metabox
function regen_wp_person_metabox() {
    add_meta_box(
        'regen_person_meta',
        __( 'Person Details', 'regen-wp' ),
        'regen_wp_person_metabox_render',
        'person',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'regen_wp_person_metabox' );

function regen_wp_person_metabox_render( $post ) {
    wp_nonce_field( 'regen_wp_save_person_meta', 'regen_wp_person_nonce' );

    $role       = get_post_meta( $post->ID, 'person_role', true );
    $years      = get_post_meta( $post->ID, 'person_years', true );
    $link_label = get_post_meta( $post->ID, 'person_link_label', true );
    $link_url   = get_post_meta( $post->ID, 'person_link_url', true );
    $order      = get_post_meta( $post->ID, 'person_order', true );
    ?>
    <p><strong><?php esc_html_e( 'Role (e.g., Director, Resident, Scholar)', 'regen-wp' ); ?></strong></p>
    <input type="text" name="person_role" value="<?php echo esc_attr( $role ); ?>" style="width:100%" />

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Year(s) (e.g., 2025-2026)', 'regen-wp' ); ?></strong></p>
    <input type="text" name="person_years" value="<?php echo esc_attr( $years ); ?>" style="width:100%" />

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Button label (optional)', 'regen-wp' ); ?></strong></p>
    <input type="text" name="person_link_label" value="<?php echo esc_attr( $link_label ); ?>" style="width:100%" />

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Link URL (optional; external allowed)', 'regen-wp' ); ?></strong></p>
    <input type="url" name="person_link_url" value="<?php echo esc_attr( $link_url ); ?>" style="width:100%" />

    <p style="margin-top:12px;"><strong><?php esc_html_e( 'Order (lower shows first)', 'regen-wp' ); ?></strong></p>
    <input type="number" name="person_order" value="<?php echo esc_attr( $order ); ?>" style="width:100%" />
    <?php
}

function regen_wp_save_person_meta( $post_id ) {
    if ( ! isset( $_POST['regen_wp_person_nonce'] ) || ! wp_verify_nonce( $_POST['regen_wp_person_nonce'], 'regen_wp_save_person_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['post_type'] ) && 'person' === $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $map = array(
        'person_role'       => 'text',
        'person_years'      => 'text',
        'person_link_label' => 'text',
        'person_link_url'   => 'url',
        'person_order'      => 'int',
    );

    foreach ( $map as $field => $type ) {
        if ( isset( $_POST[ $field ] ) ) {
            $raw = wp_unslash( $_POST[ $field ] );
            if ( 'url' === $type ) {
                $val = esc_url_raw( $raw );
            } elseif ( 'int' === $type ) {
                $val = ( '' === $raw ) ? '' : absint( $raw );
            } else {
                $val = sanitize_text_field( $raw );
            }
            if ( '' === $val ) {
                delete_post_meta( $post_id, $field );
            } else {
                update_post_meta( $post_id, $field, $val );
            }
        } else {
            delete_post_meta( $post_id, $field );
        }
    }

    // Keep menu_order in sync with person_order for easier ordering in Query Loop blocks.
    $person_order = get_post_meta( $post_id, 'person_order', true );
    $menu_order   = ( '' === $person_order ) ? 0 : absint( $person_order );
    // Avoid infinite save loops by checking current value first.
    $current_menu_order = get_post_field( 'menu_order', $post_id );
    if ( (int) $current_menu_order !== (int) $menu_order ) {
        wp_update_post( array( 'ID' => $post_id, 'menu_order' => $menu_order ) );
    }
}
add_action( 'save_post_person', 'regen_wp_save_person_meta' );

// Shortcode to output person meta in blocks: [person_meta key="person_role"]
function regen_wp_person_meta_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'key' => '' ), $atts, 'person_meta' );
    $key  = $atts['key'];
    if ( ! $key ) {
        return '';
    }
    $value = get_post_meta( get_the_ID(), $key, true );
    return esc_html( $value );
}
add_shortcode( 'person_meta', 'regen_wp_person_meta_shortcode' );

// Renders the person link as a button using meta fields for label/url.
function regen_person_link_button_shortcode() {
    $post_id = get_the_ID();
    if ( ! $post_id ) {
        return '';
    }

    $label = get_post_meta( $post_id, 'person_link_label', true );
    $url   = get_post_meta( $post_id, 'person_link_url', true );

    if ( empty( $label ) || empty( $url ) ) {
        return '';
    }

    $label = esc_html( $label );
    $url   = esc_url( $url );

    return '<div class="wp-block-buttons"><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="' . $url . '" target="_blank" rel="noopener">' . $label . '</a></div></div>';
}
add_shortcode( 'person_link_button', 'regen_person_link_button_shortcode' );

// Outputs the person order as a badge (01, 02, ...).
function regen_person_order_badge_shortcode() {
    $post_id = get_the_ID();
    if ( ! $post_id ) {
        return '';
    }
    $order = get_post_meta( $post_id, 'person_order', true );
    if ( '' === $order ) {
        return '';
    }
    $num = str_pad( (string) absint( $order ), 2, '0', STR_PAD_LEFT );
    return '<span class="person-order-badge">' . esc_html( $num ) . '</span>';
}
add_shortcode( 'person_order_badge', 'regen_person_order_badge_shortcode' );

// Add a custom block category for theme blocks.
function regen_wp_block_category( $categories ) {
    $categories[] = array(
        'slug'  => 'regen',
        'title' => __( 'Regeneracion', 'regen-wp' ),
        'icon'  => null,
    );
    return $categories;
}
add_filter( 'block_categories_all', 'regen_wp_block_category' );

// Render callback for the Person Card dynamic block.
function regen_render_person_card_block( $attributes ) {
    $person_id = isset( $attributes['personId'] ) ? absint( $attributes['personId'] ) : 0;
    $variant   = isset( $attributes['variant'] ) ? sanitize_key( $attributes['variant'] ) : 'about';

    if ( ! $person_id ) {
        return '<div class="person-card-placeholder">Select a person in the block settings.</div>';
    }

    $post = get_post( $person_id );
    if ( ! $post || 'person' !== $post->post_type ) {
        return '<div class="person-card-placeholder">Select a valid person.</div>';
    }

    $title   = esc_html( get_the_title( $post ) );
    $role    = esc_html( get_post_meta( $person_id, 'person_role', true ) );
    $years   = esc_html( get_post_meta( $person_id, 'person_years', true ) );
    $label   = esc_html( get_post_meta( $person_id, 'person_link_label', true ) );
    $url     = esc_url( get_post_meta( $person_id, 'person_link_url', true ) );
    $content = regen_person_get_content( $person_id );

    $image_html = '';
    if ( has_post_thumbnail( $person_id ) ) {
        $img_class = ( 'resident' === $variant ) ? 'resident-avatar' : 'about-profile-image';
        $image_html = get_the_post_thumbnail( $person_id, 'large', array( 'class' => $img_class ) );
    }

    $button_html = '';
    if ( $label && $url ) {
        $button_html = '<div class="wp-block-buttons"><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="' . $url . '" target="_blank" rel="noopener">' . $label . '</a></div></div>';
    }

    $meta_line = '';
    if ( $role || $years ) {
        $meta_line = '<p><strong>' . $role . '</strong>' . ( $years ? ' <span style="margin-left:12px;">' . $years . '</span>' : '' ) . '</p>';
    }

    // Order badge for resident variant
    $order_badge = '';
    if ( 'resident' === $variant ) {
        $order_meta = get_post_meta( $person_id, 'person_order', true );
        $order_val  = ( '' !== $order_meta ) ? absint( $order_meta ) : (int) get_post_field( 'menu_order', $person_id );
        if ( $order_val ) {
            $order_badge = '<span class="person-order-badge">' . str_pad( (string) $order_val, 2, '0', STR_PAD_LEFT ) . '</span>';
        }
    }

    if ( 'resident' === $variant ) {
        $html  = '<div class="project-card resident-card person-card person-card--resident" data-variant="resident">';
        $html .= '<div class="wp-block-columns are-vertically-aligned-top">';
        $html .= '<div class="wp-block-column" style="flex-basis:30%">' . $image_html . '</div>';
        $html .= '<div class="wp-block-column" style="flex-basis:70%">';
        if ( $order_badge ) {
            $html .= '<p class="person-order">' . $order_badge . '</p>';
        }
        if ( $meta_line ) {
            $html .= '<p class="person-meta-line"><span class="person-role">' . $role . '</span>' . ( $years ? ' <span class="person-years" style="margin-left:12px;">' . $years . '</span>' : '' ) . '</p>';
        }
        $html .= '<h3 class="card-title">' . $title . '</h3>';
        if ( $content ) {
            $html .= '<div class="card-text">' . $content . '</div>';
        }
        $html .= $button_html;
        $html .= '</div></div></div>';
        return $html;
    }

    // About/default variant
    $html  = '<div class="project-card full-width">';
    $html .= '<div class="about-profile-container">';
    $html .= $image_html ? $image_html : '';
    $html .= '<div class="about-profile-content">';
    $html .= '<h3 class="about-profile-name">' . $title . '</h3>';
    if ( $content ) {
        $html .= $content;
    }
    if ( $button_html ) {
        $html .= $button_html;
    }
    $html .= '</div></div></div>';

    return $html;
}

// Register the Person Card dynamic block and its editor script.
function regen_register_person_card_block() {
    $dir        = get_template_directory();
    $script_rel = '/blocks/person-card/index.js';
    $script_abs = $dir . $script_rel;

    if ( file_exists( $script_abs ) ) {
        wp_register_script(
            'regen-person-card-block',
            get_template_directory_uri() . $script_rel,
            array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-data', 'wp-i18n', 'wp-block-editor' ),
            filemtime( $script_abs ),
            true
        );
    }

    register_block_type( 'regen/person-card', array(
        'api_version'     => 2,
        'editor_script'   => 'regen-person-card-block',
        'render_callback' => 'regen_render_person_card_block',
        'attributes'      => array(
            'personId' => array(
                'type' => 'integer',
            ),
            'variant'  => array(
                'type'    => 'string',
                'default' => 'about',
            ),
        ),
        'supports'       => array(
            'html' => false,
        ),
        'category'      => 'regen',
        'title'         => __( 'Person Card', 'regen-wp' ),
        'description'   => __( 'Render a Person CPT card (About or Resident style).', 'regen-wp' ),
    ) );
}
add_action( 'init', 'regen_register_person_card_block' );

// Returns current person title.
function regen_person_title_shortcode() {
    return esc_html( get_the_title() );
}
add_shortcode( 'person_title', 'regen_person_title_shortcode' );

// Returns current person featured image HTML.
function regen_person_featured_image_shortcode() {
    if ( ! has_post_thumbnail() ) {
        return '';
    }
    return get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'resident-avatar' ) );
}
add_shortcode( 'person_featured_image', 'regen_person_featured_image_shortcode' );

// Safely fetches person content with recursion guard to avoid person_card loops.
function regen_person_get_content( $post_id ) {
    static $in_person_content = false;

    if ( $in_person_content ) {
        return '';
    }

    $content = get_post_field( 'post_content', $post_id );
    if ( ! $content ) {
        return '';
    }

    // Strip nested person_card shortcodes to avoid infinite loops during render.
    $content = preg_replace( '/\[person_card[^\]]*\]/', '', $content );

    $in_person_content = true;
    $rendered         = apply_filters( 'the_content', $content );
    $in_person_content = false;

    return $rendered;
}

// Returns current person content.
function regen_person_content_shortcode() {
    $post_id = get_the_ID();
    if ( ! $post_id ) {
        return '';
    }
    return regen_person_get_content( $post_id );
}
add_shortcode( 'person_content', 'regen_person_content_shortcode' );

// Renders a full person card via shortcode: [person_card id="123"] (id optional; falls back to current post).
function regen_person_card_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'id' => 0 ), $atts, 'person_card' );
    $post_id = absint( $atts['id'] );

    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    if ( ! $post_id ) {
        error_log( 'person_card: missing post id' );
        return '';
    }

    $post = get_post( $post_id );
    if ( ! $post || 'person' !== $post->post_type ) {
        error_log( 'person_card: invalid post id ' . $post_id );
        return '';
    }

    $title   = esc_html( get_the_title( $post ) );
    $role    = esc_html( get_post_meta( $post_id, 'person_role', true ) );
    $years   = esc_html( get_post_meta( $post_id, 'person_years', true ) );
    $label   = esc_html( get_post_meta( $post_id, 'person_link_label', true ) );
    $url     = esc_url( get_post_meta( $post_id, 'person_link_url', true ) );
    $content = regen_person_get_content( $post_id );

    $img_html = '';
    if ( has_post_thumbnail( $post_id ) ) {
        $img_html = get_the_post_thumbnail( $post_id, 'large', array( 'class' => 'resident-avatar' ) );
    }

    $button_html = '';
    if ( $label && $url ) {
        $button_html = '<div class="wp-block-buttons"><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="' . $url . '" target="_blank" rel="noopener">' . $label . '</a></div></div>';
    }

    $meta_line = '';
    if ( $role || $years ) {
        $meta_line = '<p><strong>' . $role . '</strong>' . ( $years ? ' <span style="margin-left:12px;">' . $years . '</span>' : '' ) . '</p>';
    }

    $html  = '<div class="project-card full-width">';
    $html .= '<div class="wp-block-columns are-vertically-aligned-top">';
    $html .= '<div class="wp-block-column" style="flex-basis:28%">' . $img_html . '</div>';
    $html .= '<div class="wp-block-column" style="flex-basis:72%">';
    $html .= '<h3 class="card-title">' . $title . '</h3>';
    $html .= $meta_line;
    $html .= $content ? '<div class="card-text">' . $content . '</div>' : '';
    $html .= $button_html;
    $html .= '</div></div></div>';

    return $html;
}
add_shortcode( 'person_card', 'regen_person_card_shortcode' );

// Block pattern for timeline
function regen_wp_register_block_patterns() {
    if ( ! function_exists( 'register_block_pattern' ) ) {
        return;
    }

    register_block_pattern_category( 'regen', array( 'label' => __( 'Regeneracion', 'regen-wp' ) ) );

    register_block_pattern(
        'regen/timeline-default',
        array(
            'title'       => __( 'Project Timeline', 'regen-wp' ),
            'description' => __( 'Three-step timeline with markers and years.', 'regen-wp' ),
            'categories'  => array( 'regen' ),
            'content'     => '<div class="timeline">
    <div class="timeline-item">
        <div class="timeline-marker"></div>
        <div class="timeline-content">
            <div class="timeline-year">2025</div>
            <ul class="timeline-list">
                <li>Save the archive!</li>
            </ul>
        </div>
    </div>
    <div class="timeline-item">
        <div class="timeline-marker"></div>
        <div class="timeline-content">
            <div class="timeline-year">2026</div>
            <ul class="timeline-list">
                <li>Indexing, preservation, and repair of archival material</li>
                <li>Creating an annotated finding guide for the archive</li>
                <li>Research publication possibilities for the family, including developing new scholarly introductions to Holmes’ previously published and unpublished works</li>
            </ul>
        </div>
    </div>
    <div class="timeline-item">
        <div class="timeline-marker"></div>
        <div class="timeline-content">
            <div class="timeline-year">2027</div>
            <ul class="timeline-list">
                <li>Finding a long-term home for the archive where the family, scholars, and artists can visit the materials</li>
                <li>Supporting the family in developing a research symposium on Holmes’ work and significance</li>
            </ul>
        </div>
    </div>
</div>',
        )
    );

    // Resource header (standalone heading block)
    register_block_pattern(
        'regen/resource-header',
        array(
            'title'       => __( 'Resource Header', 'regen-wp' ),
            'description' => __( 'Standalone section heading for resource lists.', 'regen-wp' ),
            'categories'  => array( 'regen' ),
            'content'     => '<!-- wp:heading {"level":4,"className":"resource-header"} -->
<h4 class="resource-header">Resource Title</h4>
<!-- /wp:heading -->',
        )
    );

    // Resource list (list block with resource-list class)
    register_block_pattern(
        'regen/resource-list',
        array(
            'title'       => __( 'Resource List', 'regen-wp' ),
            'description' => __( 'Lined resource list styled like the HTML site.', 'regen-wp' ),
            'categories'  => array( 'regen' ),
            'content'     => '<!-- wp:list {"className":"resource-list"} -->
<ul class="resource-list">
    <li>Example resource entry with optional <a href="https://example.com" target="_blank" rel="noopener">link</a>.</li>
    <li>Another resource entry with citation details.</li>
    <li>A third resource entry.</li>
</ul>
<!-- /wp:list -->',
        )
    );

    // Profile card for About/Team sections
    register_block_pattern(
        'regen/profile-card',
        array(
            'title'       => __( 'Profile Card', 'regen-wp' ),
            'description' => __( 'Director / Principal Investigator profile card with image and bio.', 'regen-wp' ),
            'categories'  => array( 'regen' ),
            'content'     => '<!-- wp:group {"className":"about-profile-card"} -->
<div class="about-profile-card"><img class="about-profile-image" src="https://via.placeholder.com/180x240" alt="Profile"/><div class="about-profile-content"><h3 class="about-profile-name">Amrah Salomon</h3><p>Amrah Salomon is a scholar, creative writer, and practitioner of research justice working at the intersections of Ethnic Studies, Indigenous studies, Women of Color feminisms and Queer theory, environmental justice, and decolonial methodologies.</p><p>At the Regeneracion Lab, Dr. Salomon develops collaborative projects with communities, supports resident scholars and artists, and builds educational resources for students and activists.</p></div></div>
<!-- /wp:group -->',
        )
    );

}
add_action( 'init', 'regen_wp_register_block_patterns' );

// Add fallback favicon if Site Icon is not set
function regen_wp_favicon() {
    if ( ! has_site_icon() ) {
        echo '<link rel="shortcut icon" href="' . esc_url( get_stylesheet_directory_uri() . '/images/favicon.png' ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'regen_wp_favicon' );
