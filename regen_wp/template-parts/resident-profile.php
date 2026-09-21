<?php
/**
 * One resident profile (Residents page + single resident). Call inside the loop.
 * Args: index (int, for the accent color).
 */
$index   = isset( $args['index'] ) ? (int) $args['index'] : 0;
$accent  = regen_wp_accent( $index );
$role    = get_post_meta( get_the_ID(), 'resident_title', true );
$dates   = get_post_meta( get_the_ID(), 'resident_dates', true );
$bio     = get_post_meta( get_the_ID(), 'resident_bio', true );
$links   = get_post_meta( get_the_ID(), 'resident_links', true );
$eyebrow = trim( implode( ' • ', array_filter( array( $role, $dates ) ) ) );
$content = trim( get_the_content() );
?>
<article class="profile-entry">
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="profile-photo-col">
            <?php the_post_thumbnail( 'large', array( 'class' => 'profile-photo', 'loading' => 'lazy' ) ); ?>
            <p class="profile-caption"><?php echo esc_html( get_the_title() . ( $eyebrow ? ', ' . $eyebrow : '' ) ); ?>.</p>
        </div>
    <?php endif; ?>
    <div class="profile-body">
        <h3><a href="<?php the_permalink(); ?>" class="profile-name-link"><?php the_title(); ?></a></h3>
        <?php if ( $eyebrow ) : ?>
            <div class="profile-eyebrow profile-eyebrow--<?php echo esc_attr( $accent ); ?>">
                <span class="eyebrow-mark eyebrow-mark--<?php echo esc_attr( $accent ); ?>"></span>
                <?php echo esc_html( $eyebrow ); ?>
            </div>
        <?php endif; ?>
        <?php if ( $bio ) : ?>
            <div class="profile-bio"><?php echo wp_kses_post( wpautop( $bio ) ); ?></div>
        <?php endif; ?>
        <?php if ( $content ) : ?>
            <div class="profile-projects">
                <h4 class="profile-subheading"><?php esc_html_e( 'Residency Focus', 'regen-wp' ); ?></h4>
                <div class="profile-bio"><?php echo apply_filters( 'the_content', get_the_content() ); // phpcs:ignore ?></div>
        <?php else : ?>
            <div class="profile-projects">
        <?php endif; ?>
            <?php if ( is_array( $links ) && $links ) : ?>
                <div class="profile-links">
                    <?php foreach ( $links as $link ) :
                        if ( empty( $link['url'] ) ) {
                            continue;
                        }
                        $label = ! empty( $link['label'] ) ? $link['label'] : $link['url'];
                        ?>
                        <a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="profile-link"><?php echo esc_html( $label ); ?> &rarr;</a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</article>
