<?php
/**
 * Single Project template
 */

get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $badge        = get_post_meta( get_the_ID(), 'project_badge', true );
        $meta         = get_post_meta( get_the_ID(), 'project_meta', true );
        $link_label   = get_post_meta( get_the_ID(), 'project_link_label', true );
        $style        = get_post_meta( get_the_ID(), 'project_style', true );
        $style_class  = $style ? ' article-style--' . sanitize_html_class( $style ) : ' article-style--turquoise';
        $cta_label    = $link_label ? $link_label : 'Explore';
        $thumb_url    = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        $title_line1  = get_post_meta( get_the_ID(), 'project_title_line1', true );
        $title_line2  = get_post_meta( get_the_ID(), 'project_title_line2', true );
        ?>
        <article <?php post_class( 'content-article project-single' ); ?>>
            <header class="article-header<?php echo esc_attr( $style_class ); ?>">
                <?php if ( $badge && 0 === strcasecmp( trim( $badge ), 'ongoing' ) ) : ?><div class="item-badge"><?php echo esc_html( $badge ); ?></div><?php endif; ?>
                <h1 class="article-title">
                    <?php
                    if ( $title_line1 ) {
                        echo esc_html( $title_line1 );
                        if ( $title_line2 ) {
                            echo '<br>' . esc_html( $title_line2 );
                        }
                    } else {
                        the_title();
                    }
                    ?>
                </h1>
                <?php if ( $meta ) : ?><p class="article-meta"><?php echo esc_html( $meta ); ?></p><?php endif; ?>
                <?php if ( $thumb_url ) : ?>
                    <div class="article-hero" style="background-image: url(<?php echo esc_url( $thumb_url ); ?>);"></div>
                <?php endif; ?>
            </header>

            <div class="article-content">
                <?php
                $content = get_the_content();

                if ( '' !== trim( wp_strip_all_tags( $content ) ) ) {
                    the_content();
                } elseif ( has_excerpt() ) {
                    the_excerpt();
                } else {
                    echo '<p>No additional content yet.</p>';
                }
                ?>
            </div>
            <div class="article-footer">
                <a class="item-link" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">← Back to Projects</a>
            </div>
        </article>
        <?php
    endwhile;
else :
    echo '<p>No project found.</p>';
endif;

get_footer();
