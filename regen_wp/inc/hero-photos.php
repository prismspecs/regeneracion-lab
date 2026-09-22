<?php
/**
 * Homepage hero photos: the pool the landing screen picks from at random.
 * Managed at Appearance > Hero Photos (Media Library picker, add/remove).
 */

function regen_wp_hero_photo_ids() {
    $ids = get_option( 'regen_hero_photos', array() );
    if ( ! is_array( $ids ) ) {
        return array();
    }
    return array_values( array_filter( array_map( 'absint', $ids ), static function ( $id ) {
        return $id && wp_attachment_is_image( $id );
    } ) );
}

/** Full-size URLs of the chosen photos (empty array = script uses its built-in fallback). */
function regen_wp_hero_photo_urls() {
    $urls = array();
    foreach ( regen_wp_hero_photo_ids() as $id ) {
        $url = wp_get_attachment_image_url( $id, 'full' );
        if ( $url ) {
            $urls[] = $url;
        }
    }
    return $urls;
}

function regen_wp_hero_photos_menu() {
    add_theme_page(
        __( 'Homepage Hero Photos', 'regen-wp' ),
        __( 'Hero Photos', 'regen-wp' ),
        'edit_theme_options',
        'regen-hero-photos',
        'regen_wp_hero_photos_page'
    );
}
add_action( 'admin_menu', 'regen_wp_hero_photos_menu' );

function regen_wp_hero_photos_register() {
    register_setting( 'regen_hero_photos_group', 'regen_hero_photos', array(
        'type'              => 'array',
        'sanitize_callback' => static function ( $value ) {
            $ids = is_string( $value ) ? explode( ',', $value ) : (array) $value;
            $ids = array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );
            return array_values( array_filter( $ids, 'wp_attachment_is_image' ) );
        },
        'default'           => array(),
    ) );
}
add_action( 'admin_init', 'regen_wp_hero_photos_register' );

function regen_wp_hero_photos_assets( $hook ) {
    if ( 'appearance_page_regen-hero-photos' !== $hook ) {
        return;
    }
    wp_enqueue_media();
    $file = get_template_directory() . '/assets/admin/hero-photos.js';
    wp_enqueue_script( 'regen-hero-photos', get_template_directory_uri() . '/assets/admin/hero-photos.js', array( 'jquery' ), file_exists( $file ) ? filemtime( $file ) : null, true );
}
add_action( 'admin_enqueue_scripts', 'regen_wp_hero_photos_assets' );

function regen_wp_hero_photos_page() {
    $ids = regen_wp_hero_photo_ids();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Homepage Hero Photos', 'regen-wp' ); ?></h1>
        <p><?php esc_html_e( 'The homepage picks one of these photos at random each time it loads. Add or remove photos any time; if none are chosen the site falls back to the photos bundled with the theme.', 'regen-wp' ); ?></p>
        <p class="description"><?php esc_html_e( 'Use wide, landscape photos (at least 2000px across). They are shown full-screen and through the letters of the title.', 'regen-wp' ); ?></p>

        <form method="post" action="options.php">
            <?php settings_fields( 'regen_hero_photos_group' ); ?>
            <input type="hidden" name="regen_hero_photos" id="regen-hero-photos-input" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>" />

            <ul id="regen-hero-photos-list" style="display:flex;flex-wrap:wrap;gap:12px;margin:20px 0;padding:0;list-style:none;">
                <?php foreach ( $ids as $id ) : ?>
                    <li data-id="<?php echo (int) $id; ?>" style="position:relative;width:180px;">
                        <?php echo wp_get_attachment_image( $id, 'medium', false, array( 'style' => 'width:180px;height:120px;object-fit:cover;display:block;' ) ); ?>
                        <button type="button" class="button-link-delete regen-hero-remove" style="position:absolute;top:4px;right:4px;background:#fff;padding:2px 8px;border:1px solid #c3c4c7;cursor:pointer;">&times;</button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p id="regen-hero-photos-empty" <?php echo $ids ? 'hidden' : ''; ?>><em><?php esc_html_e( 'No photos chosen: the theme’s bundled photos are being used.', 'regen-wp' ); ?></em></p>

            <p>
                <button type="button" class="button" id="regen-hero-photos-add"><?php esc_html_e( 'Add photos from Media Library', 'regen-wp' ); ?></button>
                <?php submit_button( __( 'Save Hero Photos', 'regen-wp' ), 'primary', 'submit', false ); ?>
            </p>
        </form>
    </div>
    <?php
}
