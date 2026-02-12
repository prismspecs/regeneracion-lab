<?php
/**
 * Single Resident template
 */

get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $title = get_post_meta( get_the_ID(), 'resident_title', true );
        $dates = get_post_meta( get_the_ID(), 'resident_dates', true );
        $bio   = get_post_meta( get_the_ID(), 'resident_bio', true );
        $links = get_post_meta( get_the_ID(), 'resident_links', true );
        
        $meta_line = array();
        if ( $title ) $meta_line[] = $title;
        if ( $dates ) $meta_line[] = $dates;
        $meta_text = implode( ' • ', $meta_line );
        ?>
        <article <?php post_class( 'content-article resident-single' ); ?>>
            <header class="article-header">
                <h1 class="article-title"><?php the_title(); ?></h1>
                <?php if ( $meta_text ) : ?>
                    <p class="article-meta"><?php echo esc_html( $meta_text ); ?></p>
                <?php endif; ?>
            </header>

            <div class="resident-profile">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'large' ); ?>
                <?php endif; ?>
                <div>
                    <h3 class="mt-0"><?php esc_html_e( 'Biography', 'regen-wp' ); ?></h3>
                    <div class="resident-bio-text">
                        <?php echo wp_kses_post( wpautop( $bio ) ); ?>
                    </div>

                    <?php
                    $content = get_the_content();
                    if ( '' !== trim( wp_strip_all_tags( $content ) ) ) : ?>
                        <h3 class="mt-30"><?php esc_html_e( 'Residency Projects', 'regen-wp' ); ?></h3>
                        <div class="resident-bio-text">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $links ) && is_array( $links ) ) : ?>
                        <h3 class="mt-30"><?php esc_html_e( 'Links', 'regen-wp' ); ?></h3>
                        <p>
                            <?php foreach ( $links as $link ) : 
                                $label = isset( $link['label'] ) ? $link['label'] : '';
                                $url   = isset( $link['url'] ) ? $link['url'] : '';
                                if ( ! $url ) continue;
                                $display_label = $label ? $label : $url;
                            ?>
                                <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="item-link resident-link-block">→ <?php echo esc_html( strtoupper( $display_label ) ); ?></a>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="article-footer">
                <a class="item-link" href="<?php echo esc_url( get_post_type_archive_link( 'resident' ) ); ?>">← Back to Residents</a>
            </div>
        </article>
        <?php
    endwhile;
else :
    echo '<p>No resident found.</p>';
endif;

get_footer();