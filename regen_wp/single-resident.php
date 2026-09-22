<?php
/**
 * Single resident: same profile layout as the Residents page.
 */

get_header();

while ( have_posts() ) :
    the_post();
    $role  = get_post_meta( get_the_ID(), 'resident_title', true );
    $dates = get_post_meta( get_the_ID(), 'resident_dates', true );
    regen_wp_masthead( array(
        'eyebrow' => trim( implode( ' • ', array_filter( array( $role, $dates ) ) ) ),
        'title'   => get_the_title(),
    ) );
    ?>
    <section class="page-section">
        <div class="resident-profiles">
            <?php get_template_part( 'template-parts/resident-profile', null, array( 'index' => 0 ) ); ?>
        </div>
        <p class="page-back"><a href="<?php echo esc_url( home_url( '/residents/' ) ); ?>">&larr; <?php esc_html_e( 'All residents', 'regen-wp' ); ?></a></p>
    </section>
    <?php
endwhile;

get_footer();
