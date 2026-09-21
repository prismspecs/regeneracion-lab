<?php
/**
 * Support page (slug: support). Section copy is editable per page (Support
 * Sections box) and falls back to defaults; the donation copy/URL live in the
 * Customizer.
 */

get_header();

$support_url    = get_theme_mod( 'regen_support_url', 'https://give.ucsb.edu/campaigns/58594/donations/new' );
$support_button = get_theme_mod( 'regen_support_button_label', 'CONTRIBUTE' );

while ( have_posts() ) :
    the_post();
    $id = get_the_ID();
    $sections = array(
        array(
            get_post_meta( $id, 'support_section1_heading', true ) ?: 'Financial Contributions',
            get_post_meta( $id, 'support_section1_body', true ) ?: 'Direct financial support helps us maintain our digital platform, support resident scholars, and develop new educational resources. If you are a funder interested in our work, please connect with us using the contact form below.',
            'donate',
        ),
        array(
            get_post_meta( $id, 'support_section2_heading', true ) ?: 'Share Our Resources',
            get_post_meta( $id, 'support_section2_body', true ) ?: 'Help us reach broader audiences by sharing our educational materials, research, and resident work with your networks.',
            '',
        ),
        array(
            get_post_meta( $id, 'support_section3_heading', true ) ?: 'Collaborate',
            get_post_meta( $id, 'support_section3_body', true ) ?: 'We welcome collaboration with communities, collectives, organizations, scholars, artists, activists, and organizers working on related projects. Send us a message with your potential partnership ideas.',
            'contact',
        ),
    );
    $contact_heading = get_post_meta( $id, 'support_contact_heading', true ) ?: 'Contact Form';

    regen_wp_masthead( array(
        'eyebrow' => regen_wp_page_field( 'eyebrow' ),
        'title'   => get_the_title(),
        'tagline' => has_excerpt() ? get_the_excerpt() : '',
    ) );

    foreach ( $sections as $i => $section ) :
        ?>
        <section class="page-section">
            <h2 class="section-title"><?php echo esc_html( $section[0] ); ?></h2>
            <p class="section-intro"><?php echo esc_html( $section[1] ); ?></p>
            <?php if ( 'donate' === $section[2] ) : ?>
                <button type="button" class="support-button" id="supportOpenBtn"><?php echo esc_html( $support_button ); ?> &rarr;</button>
            <?php elseif ( 'contact' === $section[2] ) : ?>
                <a href="#contact-form" class="support-button"><?php esc_html_e( 'Contact us', 'regen-wp' ); ?> &rarr;</a>
            <?php endif; ?>
        </section>
        <?php
    endforeach;

    if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) :
        ?>
        <section class="page-section">
            <div class="prose-column"><?php the_content(); ?></div>
        </section>
    <?php endif; ?>

    <section class="page-section" id="contact-form">
        <h2 class="section-title"><?php echo esc_html( $contact_heading ); ?></h2>
        <div class="contact-wrap">
            <?php echo regen_wp_cf7( 'Main Contact Form', 'contact-form' ); // phpcs:ignore ?>
        </div>
    </section>
    <?php
endwhile;

get_template_part( 'template-parts/support-modal' );
get_footer();
