<?php
/**
 * Generic page: masthead + editable content.
 */

get_header();

while ( have_posts() ) :
    the_post();
    regen_wp_masthead( array(
        'eyebrow' => regen_wp_page_field( 'eyebrow' ),
        'title'   => get_the_title(),
        'tagline' => has_excerpt() ? get_the_excerpt() : '',
    ) );
    ?>
    <section class="page-section">
        <div class="prose-column">
            <?php echo regen_wp_dropcap( apply_filters( 'the_content', get_the_content() ) ); // phpcs:ignore ?>
        </div>
    </section>
    <?php
endwhile;

get_footer();
