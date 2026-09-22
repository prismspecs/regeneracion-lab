<?php
/**
 * Single post (a Recent Update).
 */

get_header();

while ( have_posts() ) :
    the_post();
    $links = get_post_meta( get_the_ID(), 'update_links', true );
    regen_wp_masthead( array(
        'eyebrow' => get_the_date(),
        'title'   => get_the_title(),
    ) );
    ?>
    <section class="page-section">
        <div class="prose-column">
            <?php echo regen_wp_dropcap( apply_filters( 'the_content', get_the_content() ) ); // phpcs:ignore ?>
            <?php if ( is_array( $links ) && $links ) : ?>
                <p class="page-links">
                    <?php foreach ( $links as $link ) : if ( empty( $link['url'] ) ) { continue; } ?>
                        <a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( ! empty( $link['label'] ) ? $link['label'] : $link['url'] ); ?> &rarr;</a>
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>
        </div>
        <p class="page-back"><a href="<?php echo esc_url( home_url( '/#updates' ) ); ?>">&larr; <?php esc_html_e( 'Recent updates', 'regen-wp' ); ?></a></p>
    </section>
    <?php
endwhile;

get_footer();
