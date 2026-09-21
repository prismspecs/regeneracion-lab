<?php
/**
 * About page (slug: about). Content is the page body; the director section
 * comes from the first People entry; the contact form is Contact Form 7.
 */

get_header();

while ( have_posts() ) :
    the_post();
    regen_wp_masthead( array(
        'eyebrow' => regen_wp_page_field( 'eyebrow' ),
        'title'   => get_the_title(),
        'tagline' => has_excerpt() ? get_the_excerpt() : get_bloginfo( 'description' ),
    ) );
    ?>
    <section class="page-section" id="about-content">
        <div class="prose-column">
            <?php echo regen_wp_dropcap( apply_filters( 'the_content', get_the_content() ) ); // phpcs:ignore ?>
        </div>
    </section>
    <?php
endwhile;

$people = new WP_Query( array(
    'post_type'      => 'person',
    'posts_per_page' => 1,
    'meta_key'       => 'person_order',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
) );
while ( $people->have_posts() ) :
    $people->the_post();
    $role      = get_post_meta( get_the_ID(), 'person_role', true );
    $link_url  = get_post_meta( get_the_ID(), 'person_link_url', true );
    $link_text = get_post_meta( get_the_ID(), 'person_link_label', true );
    ?>
    <section class="page-section" id="director">
        <h2 class="section-title"><?php echo esc_html( $role ? $role : __( 'Director', 'regen-wp' ) ); ?></h2>
        <div class="director-profile">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="director-photo-wrap">
                    <?php the_post_thumbnail( 'large', array( 'class' => 'director-photo', 'loading' => 'lazy' ) ); ?>
                    <p class="director-photo-caption"><?php the_title(); ?><?php echo $role ? ', ' . esc_html( $role ) : ''; ?>.</p>
                </div>
            <?php endif; ?>
            <div class="director-content">
                <h3><?php the_title(); ?></h3>
                <?php if ( $role ) : ?><span class="director-title"><?php echo esc_html( $role ); ?></span><?php endif; ?>
                <div class="director-bio"><?php the_content(); ?></div>
                <?php if ( $link_url ) : ?>
                    <a href="<?php echo esc_url( $link_url ); ?>" class="director-contact-link"><?php echo esc_html( $link_text ? $link_text : __( 'Contact', 'regen-wp' ) ); ?> &rarr;</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
endwhile;
wp_reset_postdata();
?>

<section class="page-section" id="contact">
    <h2 class="section-title"><?php esc_html_e( 'Contact Us', 'regen-wp' ); ?></h2>
    <div class="contact-wrap">
        <p class="contact-intro"><?php esc_html_e( 'We welcome inquiries regarding community collaborations, research justice initiatives, resident scholar proposals, and educational partnerships.', 'regen-wp' ); ?></p>
        <?php echo regen_wp_cf7( 'Main Contact Form', 'contact-form' ); // phpcs:ignore ?>
    </div>
</section>
<?php
get_footer();
