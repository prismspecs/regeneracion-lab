<?php
/**
 * Projects page (slug: projects): editable intro + grid of every Project.
 */

get_header();

while ( have_posts() ) :
    the_post();
    regen_wp_masthead( array(
        'eyebrow' => regen_wp_page_field( 'eyebrow' ),
        'title'   => get_the_title(),
        'tagline' => has_excerpt() ? get_the_excerpt() : '',
    ) );

    if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) :
        ?>
        <section class="page-section">
            <div class="prose-column"><?php the_content(); ?></div>
        </section>
        <?php
    endif;

    $projects = new WP_Query( array(
        'post_type'      => 'project',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );
    ?>
    <section class="page-section" id="projects">
        <?php if ( $projects->have_posts() ) : ?>
            <h2 class="screen-reader-text"><?php esc_html_e( 'All projects', 'regen-wp' ); ?></h2>
            <div class="project-grid">
                <?php
                while ( $projects->have_posts() ) {
                    $projects->the_post();
                    get_template_part( 'template-parts/project-card' );
                }
                wp_reset_postdata();
                ?>
            </div>
        <?php else : ?>
            <p><?php esc_html_e( 'No projects published yet.', 'regen-wp' ); ?></p>
        <?php endif; ?>
    </section>
    <?php
endwhile;

get_footer();
