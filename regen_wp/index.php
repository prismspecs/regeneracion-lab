<?php
/**
 * Fallback listing (blog index, archives, search).
 */

get_header();

regen_wp_masthead( array(
    'title' => is_search() ? sprintf( __( 'Search: %s', 'regen-wp' ), get_search_query() ) : ( is_archive() ? get_the_archive_title() : __( 'Updates', 'regen-wp' ) ),
) );
?>
<section class="page-section">
    <?php if ( have_posts() ) : ?>
        <div class="three-col-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="update-card">
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo esc_html( get_the_date() ); ?></p>
                    <a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read', 'regen-wp' ); ?> &rarr;</a>
                </div>
            <?php endwhile; ?>
        </div>
        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p><?php esc_html_e( 'Nothing found.', 'regen-wp' ); ?></p>
    <?php endif; ?>
</section>
<?php
get_footer();
