<?php
/**
 * One project card (used on the homepage and the Projects page). Call inside the loop.
 */
$badge       = get_post_meta( get_the_ID(), 'project_badge', true );
$meta        = get_post_meta( get_the_ID(), 'project_meta', true );
$link_label  = get_post_meta( get_the_ID(), 'project_link_label', true );
$link_url    = get_post_meta( get_the_ID(), 'project_link_url', true );
$style       = get_post_meta( get_the_ID(), 'project_style', true );
$line1       = get_post_meta( get_the_ID(), 'project_title_line1', true );
$line2       = get_post_meta( get_the_ID(), 'project_title_line2', true );
$style_class = 'project-card--' . sanitize_html_class( $style ? $style : 'turquoise' );
$cta_label   = $link_label ? $link_label : __( 'Explore', 'regen-wp' );
$link_href   = $link_url ? $link_url : get_permalink();
$external    = $link_url && preg_match( '/^https?:\/\//i', $link_url ) && 0 !== strpos( $link_url, home_url() );
?>
<div class="project-card <?php echo esc_attr( $style_class ); ?>">
    <div class="project-eyebrow">
        <span class="eyebrow-mark"></span>
        <?php if ( $badge ) : ?>
            <span class="project-badge"><?php echo esc_html( $badge ); ?></span>
            <?php if ( $meta ) : ?><span class="project-meta-sep">&bull;</span><?php endif; ?>
        <?php endif; ?>
        <?php if ( $meta ) : ?><span class="project-meta"><?php echo esc_html( $meta ); ?></span><?php endif; ?>
    </div>
    <h3>
        <?php
        if ( $line1 ) {
            echo esc_html( $line1 );
            if ( $line2 ) {
                echo '<br>' . esc_html( $line2 );
            }
        } else {
            the_title();
        }
        ?>
    </h3>
    <?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
    <a href="<?php echo esc_url( $link_href ); ?>"<?php echo $external ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $cta_label ); ?> &rarr;</a>
</div>
