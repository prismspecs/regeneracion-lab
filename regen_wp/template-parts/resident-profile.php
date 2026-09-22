<?php
/**
 * One resident profile (Residents page + single resident). Call inside the loop.
 * Args: index (int, for the accent color).
 */
$index      = isset( $args['index'] ) ? (int) $args['index'] : 0;
$accent     = regen_wp_accent( $index );
// On the resident's own page the masthead already shows the name (h1) and
// role/dates (eyebrow); repeating both here would duplicate them and skip
// from h1 straight to h3 (name heading omitted -- see is_singular check below).
$is_single  = is_singular( 'resident' );
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
        <?php if ( ! $is_single ) : ?>
            <h3><a href="<?php the_permalink(); ?>" class="profile-name-link"><?php the_title(); ?></a></h3>
            <?php if ( $eyebrow ) : ?>
                <div class="profile-eyebrow profile-eyebrow--<?php echo esc_attr( $accent ); ?>">
                    <span class="eyebrow-mark eyebrow-mark--<?php echo esc_attr( $accent ); ?>"></span>
                    <?php echo esc_html( $eyebrow ); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ( $bio ) : ?>
            <div class="profile-bio"><?php echo wp_kses_post( wpautop( $bio ) ); ?></div>
        <?php endif; ?>
        <?php if ( $content ) : ?>
            <div class="profile-projects">
                <?php
                // On the listing page this nests under h1 > h2 (section) > h3 (name);
                // on the resident's own page h1 IS the name and there is no h3, so this
                // is the next heading down from h1 -- keep the sequence unbroken.
                $focus_tag = $is_single ? 'h2' : 'h4';
                ?>
                <<?php echo $focus_tag; ?> class="profile-subheading"><?php esc_html_e( 'Residency Focus', 'regen-wp' ); ?></<?php echo $focus_tag; ?>>
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
