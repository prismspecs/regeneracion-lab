<?php
/**
 * Single project: masthead (badge + dates, title, excerpt) + editable content.
 */

get_header();

while ( have_posts() ) :
    the_post();
    $badge  = get_post_meta( get_the_ID(), 'project_badge', true );
    $meta   = get_post_meta( get_the_ID(), 'project_meta', true );
    $line1  = get_post_meta( get_the_ID(), 'project_title_line1', true );
    $line2  = get_post_meta( get_the_ID(), 'project_title_line2', true );
    $title  = $line1 ? esc_html( $line1 ) . ( $line2 ? '<br>' . esc_html( $line2 ) : '' ) : esc_html( get_the_title() );
    regen_wp_masthead( array(
        'eyebrow' => trim( implode( ' • ', array_filter( array( __( 'Project', 'regen-wp' ), $badge, $meta ) ) ) ),
        'title'   => $title,
        'tagline' => has_excerpt() ? get_the_excerpt() : '',
    ) );
    ?>
    <section class="page-section">
        <?php if ( has_post_thumbnail() ) : ?>
            <figure class="page-figure"><?php the_post_thumbnail( 'large' ); ?></figure>
        <?php endif; ?>
        <div class="prose-column">
            <?php echo regen_wp_dropcap( apply_filters( 'the_content', get_the_content() ) ); // phpcs:ignore ?>
        </div>
        <p class="page-back"><a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">&larr; <?php esc_html_e( 'All projects', 'regen-wp' ); ?></a></p>
    </section>
    <?php
endwhile;

get_footer();
