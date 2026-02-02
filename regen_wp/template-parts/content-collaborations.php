<?php
/**
 * Collaborations block for the front page
 */

$collab_query = new WP_Query( array(
    'post_type'      => 'collaboration',
    'posts_per_page' => 6,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );
?>
<section class="section">
    <h2 class="section-header">Collaborations</h2>
    <?php if ( $collab_query->have_posts() ) : ?>
        <div class="projects-grid">
            <?php while ( $collab_query->have_posts() ) : $collab_query->the_post(); ?>
                <?php
                    $link_label = get_post_meta( get_the_ID(), 'collaboration_link_label', true );
                    $link_url   = get_post_meta( get_the_ID(), 'collaboration_link_url', true );
                    $cta_label  = $link_label ? $link_label : 'Learn More';
                    $href       = $link_url ? esc_url( $link_url ) : get_permalink();
                    $is_external = $link_url && preg_match( '/^https?:\/\//i', $link_url );
                    $target_attr = $is_external ? ' target="_blank" rel="noopener"' : '';
                ?>
                <div class="project-card">
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <a href="<?php echo esc_url( $href ); ?>" class="item-link"<?php echo $target_attr; ?>>→ <?php echo esc_html( $cta_label ); ?></a>
                </div>
            <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    <?php else : ?>
        <p>No collaborations published yet.</p>
    <?php endif; ?>
</section>
