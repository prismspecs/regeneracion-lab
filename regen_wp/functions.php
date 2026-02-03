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
}
add_action( 'init', 'regen_wp_register_block_patterns' );

// Add fallback favicon if Site Icon is not set
function regen_wp_favicon() {
    if ( ! has_site_icon() ) {
        echo '<link rel="shortcut icon" href="' . esc_url( get_stylesheet_directory_uri() . '/images/favicon.png' ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'regen_wp_favicon' );
