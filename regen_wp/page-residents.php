<?php
/**
 * Residents page (slug: residents). Profiles come from the Residents post
 * type (current vs. past via the "past resident" checkbox); the page body is
 * the application text; the application form is Contact Form 7.
 */

get_header();

$not_past = array(
    'relation' => 'OR',
    array( 'key' => 'resident_is_past', 'value' => '1', 'compare' => '!=' ),
    array( 'key' => 'resident_is_past', 'compare' => 'NOT EXISTS' ),
);
$current = new WP_Query( array(
    'post_type'      => 'resident',
    'posts_per_page' => -1,
    'meta_query'     => $not_past,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );
$past = new WP_Query( array(
    'post_type'      => 'resident',
    'posts_per_page' => -1,
    'meta_query'     => array( array( 'key' => 'resident_is_past', 'value' => '1', 'compare' => '=' ) ),
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );

while ( have_posts() ) :
    the_post();

    $jump = array();
    if ( $current->have_posts() ) {
        $jump[ __( 'Current Cohort', 'regen-wp' ) ] = '#current';
    }
    if ( $past->have_posts() ) {
        $jump[ __( 'Past Residents', 'regen-wp' ) ] = '#past';
    }
    $jump[ __( 'Apply for Residency', 'regen-wp' ) ] = '#apply';

    regen_wp_masthead( array(
        'eyebrow' => regen_wp_page_field( 'eyebrow' ),
        'title'   => get_the_title(),
        'tagline' => has_excerpt() ? get_the_excerpt() : '',
        'jump'    => $jump,
    ) );

    $apply_html = apply_filters( 'the_content', get_the_content() );

    foreach ( array(
        array( 'current', $current, __( 'Current Residents', 'regen-wp' ), 'intro_current' ),
        array( 'past', $past, __( 'Past Residents & Fellows', 'regen-wp' ), 'intro_past' ),
    ) as $group ) :
        list( $id, $query, $heading, $intro_key ) = $group;
        if ( ! $query->have_posts() ) {
            continue;
        }
        ?>
        <section class="page-section" id="<?php echo esc_attr( $id ); ?>">
            <h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
            <?php if ( regen_wp_page_field( $intro_key, get_queried_object_id() ) ) : ?>
                <p class="section-intro"><?php echo esc_html( regen_wp_page_field( $intro_key, get_queried_object_id() ) ); ?></p>
            <?php endif; ?>
            <div class="resident-profiles">
                <?php
                $i = 0;
                while ( $query->have_posts() ) {
                    $query->the_post();
                    get_template_part( 'template-parts/resident-profile', null, array( 'index' => $i++ ) );
                }
                wp_reset_postdata();
                ?>
            </div>
        </section>
        <?php
    endforeach;
    ?>

    <section class="page-section" id="apply">
        <h2 class="section-title"><?php esc_html_e( 'Apply for Residency', 'regen-wp' ); ?></h2>
        <div class="apply-wrap">
            <?php if ( '' !== trim( wp_strip_all_tags( $apply_html ) ) ) : ?>
                <div class="prose-column"><?php echo $apply_html; // phpcs:ignore ?></div>
            <?php endif; ?>
            <?php echo regen_wp_cf7( 'Residency Application Form', 'apply-form' ); // phpcs:ignore ?>
        </div>
    </section>
    <?php
endwhile;

get_footer();
