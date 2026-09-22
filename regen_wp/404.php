<?php
/**
 * 404
 */

get_header();
regen_wp_masthead( array(
    'eyebrow' => '404',
    'title'   => __( 'Page not found', 'regen-wp' ),
    'tagline' => __( 'That page has moved or never existed.', 'regen-wp' ),
) );
?>
<section class="page-section">
    <p class="section-intro"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">&larr; <?php esc_html_e( 'Back to the homepage', 'regen-wp' ); ?></a></p>
</section>
<?php
get_footer();
