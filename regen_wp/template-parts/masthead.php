<?php
/**
 * Page masthead. Args: eyebrow, title, tagline, jump (array of label => #anchor).
 */
$eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$title   = isset( $args['title'] ) ? $args['title'] : get_the_title();
$tagline = isset( $args['tagline'] ) ? $args['tagline'] : '';
$jump    = isset( $args['jump'] ) && is_array( $args['jump'] ) ? $args['jump'] : array();
?>
<header class="page-header">
    <?php if ( $eyebrow ) : ?>
        <div class="page-eyebrow">
            <span class="eyebrow-mark"></span>
            <?php echo esc_html( $eyebrow ); ?>
        </div>
    <?php endif; ?>
    <h1 class="page-title"><?php echo wp_kses_post( $title ); ?></h1>
    <?php if ( $tagline ) : ?>
        <p class="page-tagline"><?php echo esc_html( $tagline ); ?></p>
    <?php endif; ?>
    <?php if ( $jump ) : ?>
        <nav class="section-jump-nav" aria-label="<?php esc_attr_e( 'Jump to section', 'regen-wp' ); ?>">
            <?php foreach ( $jump as $label => $anchor ) : ?>
                <a href="<?php echo esc_attr( $anchor ); ?>"><?php echo esc_html( $label ); ?> &rarr;</a>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>
</header>
