<?php
/**
 * Homepage: pinned title/hero (assets/home.css + home.js) over normal content.
 *
 * Editable in WP:
 *  - hero quote/attribution and the support copy/URL: Appearance > Customize
 *  - intro copy: the "Home" page body
 *  - projects, collaborations, recent updates: their own post types / posts
 */

get_header();

$support_heading = get_theme_mod( 'regen_support_heading', 'Support Our Work' );
$support_text    = get_theme_mod( 'regen_support_text', 'Regeneración Lab operates through community support and grant funding. Your contribution helps us maintain this platform, support resident scholars, and keep these resources freely accessible.' );
$support_button  = get_theme_mod( 'regen_support_button_label', 'Contribute' );
$support_note    = get_theme_mod( 'regen_support_note', 'When you check out, please specify that your donation is for <em>Regeneración Lab</em>.' );

/**
 * Wrap the first letter of the intro's first paragraph for the growing drop cap
 * (assets/home.js toggles .dropcap-grown on #dropcapContainer).
 */
$intro = '';
while ( have_posts() ) {
    the_post();
    $intro = apply_filters( 'the_content', get_the_content() );
}
$intro = preg_replace(
    '/<p([^>]*)>(\s*)([A-Za-zÀ-ÿ])/u',
    '<p class="dropcap-paragraph" id="dropcapContainer"><span class="dropcap-letter" id="dropcapLetter">$3</span>',
    $intro,
    1
);
?>

<nav class="hero-nav" id="heroNav" aria-label="<?php esc_attr_e( 'Main Menu (Landing)', 'regen-wp' ); ?>">
    <?php
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'container'      => false,
        'items_wrap'     => '<ul>%3$s</ul>',
        'depth'          => 1,
        'fallback_cb'    => false,
        'walker'         => new Regen_Nav_Walker(),
    ) );
    ?>
</nav>

<?php get_template_part( 'template-parts/home-hero' ); ?>

<main class="page-content" id="pageContent">
    <header class="page-header">
        <nav class="page-nav" aria-label="<?php esc_attr_e( 'Primary', 'regen-wp' ); ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '<ul>%3$s</ul>',
                'depth'          => 1,
                'fallback_cb'    => false,
                'walker'         => new Regen_Nav_Walker(),
            ) );
            ?>
        </nav>
        <p class="page-tagline"><?php bloginfo( 'description' ); ?></p>
    </header>

    <div class="page-copy" id="about">
        <?php echo $intro; // phpcs:ignore WordPress.Security.EscapeOutput ?>
        <p style="margin-top: 20px;"><a class="page-copy-more" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'Read our full history, research praxis & leadership', 'regen-wp' ); ?> &rarr;</a></p>
    </div>

    <?php
    $projects = new WP_Query( array(
        'post_type'      => 'project',
        'posts_per_page' => 6,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );
    if ( $projects->have_posts() ) :
        ?>
        <section class="page-projects" id="projects">
            <h2><?php esc_html_e( 'Projects', 'regen-wp' ); ?></h2>
            <div class="project-grid">
                <?php
                while ( $projects->have_posts() ) {
                    $projects->the_post();
                    get_template_part( 'template-parts/project-card' );
                }
                wp_reset_postdata();
                ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
    $collabs = new WP_Query( array(
        'post_type'      => 'collaboration',
        'posts_per_page' => 6,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );
    if ( $collabs->have_posts() ) :
        ?>
        <section class="page-section" id="collaborations">
            <h2 class="page-section-header"><?php esc_html_e( 'Collaborations', 'regen-wp' ); ?></h2>
            <div class="three-col-grid">
                <?php
                while ( $collabs->have_posts() ) :
                    $collabs->the_post();
                    $label    = get_post_meta( get_the_ID(), 'collaboration_link_label', true );
                    $url      = get_post_meta( get_the_ID(), 'collaboration_link_url', true );
                    $href     = $url ? $url : get_permalink();
                    $external = $url && preg_match( '/^https?:\/\//i', $url ) && 0 !== strpos( $url, home_url() );
                    ?>
                    <div class="collab-card">
                        <h3><?php the_title(); ?></h3>
                        <?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
                        <a href="<?php echo esc_url( $href ); ?>"<?php echo $external ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $label ? $label : __( 'Learn More', 'regen-wp' ) ); ?> &rarr;</a>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
    $updates = new WP_Query( array(
        'post_type'           => 'post',
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
    ) );
    if ( $updates->have_posts() ) :
        ?>
        <section class="page-section" id="updates">
            <h2 class="page-section-header"><?php esc_html_e( 'Recent Updates', 'regen-wp' ); ?></h2>
            <div class="three-col-grid">
                <?php
                while ( $updates->have_posts() ) :
                    $updates->the_post();
                    $links = get_post_meta( get_the_ID(), 'update_links', true );
                    ?>
                    <div class="update-card">
                        <h3><?php the_title(); ?></h3>
                        <?php if ( has_excerpt() || '' !== trim( get_the_content() ) ) : ?>
                            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32 ) ); ?></p>
                        <?php endif; ?>
                        <div class="update-links">
                            <?php if ( is_array( $links ) && $links ) : ?>
                                <?php foreach ( $links as $link ) : if ( empty( $link['url'] ) ) { continue; } ?>
                                    <a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo preg_match( '/^https?:\/\//i', $link['url'] ) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( ! empty( $link['label'] ) ? $link['label'] : __( 'Read More', 'regen-wp' ) ); ?> &rarr;</a>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'regen-wp' ); ?> &rarr;</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="page-section page-section--support" id="support">
        <div class="support-content">
            <h2 class="page-section-header"><?php echo esc_html( $support_heading ); ?></h2>
            <p class="support-description"><?php echo esc_html( $support_text ); ?></p>
            <div class="support-actions">
                <button type="button" class="support-button" id="supportOpenBtn"><?php echo esc_html( $support_button ); ?> &rarr;</button>
                <p class="support-note"><?php echo wp_kses( $support_note, array( 'em' => array(), 'strong' => array() ) ); ?></p>
            </div>
        </div>
    </section>

    <?php get_template_part( 'template-parts/site-footer' ); ?>
</main>

<?php get_template_part( 'template-parts/support-modal' ); ?>

<?php get_footer(); ?>
