<?php
/**
 * Students page (slug: students). Reading groups & labs come from Programs;
 * the page body fills the opportunities section; the join form is Contact Form 7.
 */

get_header();

while ( have_posts() ) :
    the_post();

    $programs = new WP_Query( array(
        'post_type'      => 'program',
        'posts_per_page' => -1,
        'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
    ) );
    $has_body = '' !== trim( wp_strip_all_tags( get_the_content() ) );

    $jump = array();
    if ( $programs->have_posts() ) {
        $jump[ __( 'Reading Groups & Labs', 'regen-wp' ) ] = '#reading-groups';
    }
    if ( $has_body ) {
        $jump[ __( 'Study & Research Opportunities', 'regen-wp' ) ] = '#opportunities';
    }
    $jump[ __( 'Join a Group', 'regen-wp' ) ] = '#join-form';

    regen_wp_masthead( array(
        'eyebrow' => regen_wp_page_field( 'eyebrow' ),
        'title'   => get_the_title(),
        'tagline' => has_excerpt() ? get_the_excerpt() : '',
        'jump'    => $jump,
    ) );

    if ( $programs->have_posts() ) :
        ?>
        <section class="page-section" id="reading-groups">
            <h2 class="section-title"><?php esc_html_e( 'Reading Groups & Study Circles', 'regen-wp' ); ?></h2>
            <?php if ( regen_wp_page_field( 'intro_programs' ) ) : ?>
                <p class="section-intro"><?php echo esc_html( regen_wp_page_field( 'intro_programs' ) ); ?></p>
            <?php endif; ?>

            <div class="reading-group-list">
                <?php
                while ( $programs->have_posts() ) :
                    $programs->the_post();
                    $pid    = get_the_ID();
                    $badge  = get_post_meta( $pid, 'program_badge', true );
                    $style  = 'winter' === get_post_meta( $pid, 'program_badge_style', true ) ? 'winter' : 'active';
                    $meta   = array(
                        __( 'Schedule', 'regen-wp' )    => get_post_meta( $pid, 'program_schedule', true ),
                        __( 'Facilitator', 'regen-wp' ) => get_post_meta( $pid, 'program_facilitator', true ),
                        __( 'Level', 'regen-wp' )       => get_post_meta( $pid, 'program_level', true ),
                    );
                    $topics = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $pid, 'program_topics', true ) ) ) );
                    $cta    = get_post_meta( $pid, 'program_cta', true );
                    $note   = get_post_meta( $pid, 'program_facilitator_note', true );
                    $partner = get_post_meta( $pid, 'program_partner', true );
                    ?>
                    <article class="program-card">
                        <?php if ( $badge || $partner ) : ?>
                            <div class="program-card-header">
                                <?php if ( $badge ) : ?><span class="program-badge program-badge--<?php echo esc_attr( $style ); ?>"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
                                <?php if ( $partner ) : ?><span class="program-partner"><?php echo esc_html( $partner ); ?></span><?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <h3><?php the_title(); ?></h3>
                        <?php if ( array_filter( $meta ) ) : ?>
                            <div class="program-meta">
                                <?php foreach ( $meta as $label => $value ) : if ( ! $value ) { continue; } ?>
                                    <span class="program-meta-item"><strong><?php echo esc_html( $label ); ?>:</strong> <?php echo esc_html( $value ); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="program-desc"><?php the_content(); ?></div>
                        <?php if ( $topics ) : ?>
                            <div class="program-topics">
                                <div class="program-topics-title"><?php echo esc_html( get_post_meta( $pid, 'program_topics_title', true ) ?: __( 'Topics', 'regen-wp' ) ); ?></div>
                                <ul class="program-topics-list">
                                    <?php foreach ( $topics as $topic ) : ?><li><?php echo esc_html( $topic ); ?></li><?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div class="program-actions">
                            <a href="#join-form" class="btn-action">&rarr; <?php echo esc_html( $cta ? $cta : __( 'Join', 'regen-wp' ) ); ?></a>
                            <?php if ( $note ) : ?><span class="program-facilitator"><?php echo esc_html( $note ); ?></span><?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
        <?php
        wp_reset_postdata();
    endif;

    if ( $has_body ) :
        ?>
        <section class="page-section" id="opportunities">
            <h2 class="section-title"><?php esc_html_e( 'Study & Research Opportunities', 'regen-wp' ); ?></h2>
            <div class="prose-column">
                <?php echo regen_wp_dropcap( apply_filters( 'the_content', get_the_content() ) ); // phpcs:ignore ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="page-section form-section" id="join-form">
        <h2 class="section-title"><?php esc_html_e( 'Join a Reading Group / Study Circle', 'regen-wp' ); ?></h2>
        <div class="form-section-container">
            <?php echo regen_wp_cf7( 'Students', 'join-form' ); // phpcs:ignore ?>
        </div>
    </section>
    <?php
endwhile;

get_footer();
