<?php
/**
 * Residents archive template
 */

get_header();

// Fetch current residents
$current_query = new WP_Query( array(
    'post_type'      => 'resident',
    'posts_per_page' => -1,
    'meta_query'     => array(
        'relation' => 'OR',
        array(
            'key'     => 'resident_is_past',
            'value'   => '1',
            'compare' => '!=',
        ),
        array(
            'key'     => 'resident_is_past',
            'compare' => 'NOT EXISTS',
        ),
    ),
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );

// Fetch past residents
$past_query = new WP_Query( array(
    'post_type'      => 'resident',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'resident_is_past',
            'value'   => '1',
            'compare' => '=',
        ),
    ),
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );
?>

<div class="fade-in">
    <div class="article-header no-border">
        <h1 class="article-title"><?php post_type_archive_title(); ?></h1>
    </div>

    <div class="nav-tabs">
        <button class="active" data-tab="current">Current</button>
        <button data-tab="past">Past</button>
        <button data-tab="apply">Apply</button>
    </div>

    <!-- Current Tab -->
    <div id="current" class="tab-content active">
        <section class="section">
            <div class="projects-grid">
                <?php if ( $current_query->have_posts() ) : $i = 1; ?>
                    <?php while ( $current_query->have_posts() ) : $current_query->the_post(); 
                        $title = get_post_meta( get_the_ID(), 'resident_title', true );
                        $dates = get_post_meta( get_the_ID(), 'resident_dates', true );
                        $bio   = get_post_meta( get_the_ID(), 'resident_bio', true );
                        $index = str_pad( $i++, 2, '0', STR_PAD_LEFT );
                        $meta_line = array();
                        if ( $title ) $meta_line[] = strtoupper( $title );
                        if ( $dates ) $meta_line[] = $dates;
                        $meta_text = implode( ' • ', $meta_line );
                    ?>
                    <div class="project-card clickable" onclick="window.location.href='<?php the_permalink(); ?>';">
                        <div class="grid-header">
                            <span class="grid-index"><?php echo esc_html( $index ); ?></span>
                            <span class="grid-type"><?php echo esc_html( $meta_text ); ?></span>
                        </div>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large', array( 'class' => 'resident-avatar' ) ); ?>
                        <?php endif; ?>
                        <h3><?php the_title(); ?></h3>
                        <?php if ( $bio ) : ?>
                            <p class="resident-bio"><?php echo esc_html( wp_trim_words( $bio, 30 ) ); ?></p>
                        <?php endif; ?>
                        <div class="item-link resident-read-more">→ READ FULL BIO</div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p>No current residents at this time.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Past Tab -->
    <div id="past" class="tab-content">
        <section class="section">
            <div class="projects-grid">
                <?php if ( $past_query->have_posts() ) : $i = 1; ?>
                    <?php while ( $past_query->have_posts() ) : $past_query->the_post(); 
                        $title = get_post_meta( get_the_ID(), 'resident_title', true );
                        $dates = get_post_meta( get_the_ID(), 'resident_dates', true );
                        $bio   = get_post_meta( get_the_ID(), 'resident_bio', true );
                        $index = str_pad( $i++, 2, '0', STR_PAD_LEFT );
                        $meta_line = array();
                        if ( $title ) $meta_line[] = strtoupper( $title );
                        if ( $dates ) $meta_line[] = $dates;
                        $meta_text = implode( ' • ', $meta_line );
                    ?>
                    <div class="project-card clickable" onclick="window.location.href='<?php the_permalink(); ?>';">
                        <div class="grid-header">
                            <span class="grid-index"><?php echo esc_html( $index ); ?></span>
                            <span class="grid-type"><?php echo esc_html( $meta_text ); ?></span>
                        </div>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large', array( 'class' => 'resident-avatar' ) ); ?>
                        <?php endif; ?>
                        <h3><?php the_title(); ?></h3>
                        <?php if ( $bio ) : ?>
                            <p class="resident-bio"><?php echo esc_html( wp_trim_words( $bio, 30 ) ); ?></p>
                        <?php endif; ?>
                        <div class="item-link resident-read-more">→ READ FULL BIO</div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p>No past residents listed.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Apply Tab -->
    <div id="apply" class="tab-content">
        <div class="apply-container">
            <h3 class="apply-heading">Apply for Residency</h3>

            <p class="mb-20">Our residency offers space, collaboration, and opportunities to engage with a scholarly community of faculty and students curious about critical-creative praxis and research justice methods.</p>

            <p class="mb-25"><strong>Application Periods:</strong> We review applications for the following academic year only in spring, on a rolling basis from April to June. Applications submitted after June 15th will be reviewed during the next cycle.</p>

            <h4 class="apply-subheading">Residency Model</h4>

            <p class="mb-15">Our residency model is a unique opportunity to engage in deep work on research justice initiatives and critical-creative praxis.</p>

            <p class="mb-25">Regeneración Lab currently offers non-paid residencies to artists, activists, and scholars engaged in research justice projects. Residencies can be in person in Santa Barbara, CA or remote. The residency also provides academic library access and office space with desktop computers at the beautiful University of California, Santa Barbara campus within walking distance to the beach. We also support grant writing and project development for projects that align with the lab and are in need of an academic host.</p>

        <?php echo do_shortcode( '[contact-form-7 id="77" title="Residents Apply Form"]' ); ?>
        <p class="form-footer-note">Note: Please configure the Contact Form 7 mail settings to send to asalomon@ucsb.edu</p>
    </div>
</div>
</div>

<script>
(function() {
    function setupTabs() {
        const tabButtons = document.querySelectorAll('.nav-tabs button');
        const tabs = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabName = button.getAttribute('data-tab');
                
                // Remove active classes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabs.forEach(tab => tab.classList.remove('active'));
                
                // Add active class to clicked button and target tab
                button.classList.add('active');
                const targetTab = document.getElementById(tabName);
                if (targetTab) {
                    targetTab.classList.add('active');
                }
            });
        });
    }

    // Initialize on load
    setupTabs();

    // Re-initialize for PJAX loads
    document.addEventListener('pjax:success', setupTabs);
    // Custom event if your pjax uses one, or just check every now and then if tabs are missing listeners
})();
</script>

<?php
get_footer();
