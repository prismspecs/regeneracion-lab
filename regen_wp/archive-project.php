<?php
/**
 * Projects archive template
 */

get_header();
?>

<div class="fade-in">
    <div class="article-header no-border">
        <h1 class="article-title"><?php post_type_archive_title(); ?></h1>
    </div>

    <section class="section">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post();
                $badge       = get_post_meta( get_the_ID(), 'project_badge', true );
                $meta        = get_post_meta( get_the_ID(), 'project_meta', true );
                $link_label  = get_post_meta( get_the_ID(), 'project_link_label', true );
                $link_url    = get_post_meta( get_the_ID(), 'project_link_url', true );
                $style       = get_post_meta( get_the_ID(), 'project_style', true );
                $line1       = get_post_meta( get_the_ID(), 'project_title_line1', true );
                $line2       = get_post_meta( get_the_ID(), 'project_title_line2', true );
                $style_class = $style ? 'project-card--' . sanitize_html_class( $style ) : 'project-card--turquoise';
                $cta_label   = $link_label ? $link_label : 'Explore';
                $link_href   = $link_url ? esc_url( $link_url ) : get_permalink();
                $is_external = $link_url && preg_match( '/^https?:\/\//i', $link_url );
                $link_target = $is_external ? ' target="_blank" rel="noopener"' : '';
                $title_1     = $line1 ? $line1 : get_the_title();
                $title_2     = $line2 ? $line2 : '';
                $classes     = array( 'project-card', 'full-width', $style_class );
            ?>
            <article <?php post_class( $classes ); ?>>
                <?php if ( $badge ) : ?><div class="item-badge"><?php echo esc_html( $badge ); ?></div><?php endif; ?>
                <h2 class="card-title">
                    <?php echo esc_html( $title_1 ); ?>
                    <?php if ( $title_2 ) : ?><br><?php echo esc_html( $title_2 ); ?><?php endif; ?>
                </h2>
                <?php if ( $meta ) : ?><p class="item-meta"><?php echo esc_html( $meta ); ?></p><?php endif; ?>
                <p class="card-text"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <a class="item-link" href="<?php echo esc_url( $link_href ); ?>"<?php echo $link_target; ?>>→ <?php echo esc_html( $cta_label ); ?></a>
            </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No projects published yet.</p>
        <?php endif; ?>
    </section>

    <?php get_template_part( 'template-parts/content', 'collaborations' ); ?>
</div>

<?php
get_footer();
