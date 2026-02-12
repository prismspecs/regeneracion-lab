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

            <form class="contact-form mt-30" action="https://formspree.io/f/YOUR_FORM_ID" method="POST" enctype="multipart/form-data">
                <div class="form-floating">
                    <input type="text" id="res-name" name="name" class="form-input" placeholder=" " required>
                    <label for="res-name" class="form-label">Name</label>
                </div>
                <div class="form-floating">
                    <input type="email" id="res-email" name="email" class="form-input" placeholder=" " required>
                    <label for="res-email" class="form-label">Email</label>
                </div>
                <div class="form-floating">
                    <input type="tel" id="res-phone" name="phone" class="form-input" placeholder=" " required>
                    <label for="res-phone" class="form-label">Phone Number</label>
                </div>
                <div class="form-floating">
                    <input type="url" id="res-website" name="website" class="form-input" placeholder=" ">
                    <label for="res-website" class="form-label">Website(s)</label>
                </div>
                <div class="form-floating">
                    <input type="text" id="res-location" name="location" class="form-input" placeholder=" " required>
                    <label for="res-location" class="form-label">Location</label>
                </div>
                <div class="form-floating">
                    <select id="res-type" name="residency_type" class="form-select" required>
                        <option value="" disabled selected>Select preference</option>
                        <option value="in-person">In-Person</option>
                        <option value="remote">Remote</option>
                        <option value="flexible">Flexible</option>
                    </select>
                    <label for="res-type" class="form-label">Preference for In-Person or Remote Residency</label>
                </div>
                <div class="form-floating">
                    <input type="text" id="res-titles" name="project_titles" class="form-input" placeholder=" " required>
                    <label for="res-titles" class="form-label">Proposed Project Titles</label>
                </div>
                <div class="form-floating">
                    <textarea id="res-desc" name="project_description" class="form-textarea" placeholder=" " required rows="4"></textarea>
                    <label for="res-desc" class="form-label">Brief Project Description</label>
                </div>
                <div class="form-floating">
                    <textarea id="res-collab" name="collaborations" class="form-textarea" placeholder=" " required rows="4"></textarea>
                    <label for="res-collab" class="form-label">Describe the kinds of collaborations you are interested in</label>
                </div>
                <div class="form-floating">
                    <textarea id="res-support" name="support" class="form-textarea" placeholder=" " required rows="4"></textarea>
                    <label for="res-support" class="form-label">Describe the kinds of support, academic resources, and mentorship you are looking for</label>
                </div>
                <div class="form-floating">
                    <textarea id="res-justice" name="research_justice" class="form-textarea" placeholder=" " required rows="4"></textarea>
                    <label for="res-justice" class="form-label">Describe how your projects engage either research justice, critical-creative praxis, or both</label>
                </div>
                <div class="form-floating">
                    <textarea id="res-community" name="community_involvement" class="form-textarea" placeholder=" " required rows="6"></textarea>
                    <label for="res-community" class="form-label">Does your project involve communities? If so, please describe how and what your collaboration with the communities looks like...</label>
                </div>
                <div class="form-file-group">
                    <label class="form-label">Attach Documents (CV/Resume, Writing Sample, Methodology Statement)</label>
                    <p class="form-note">Please attach your resume/CV, writing or project sample, methodology statement, and contact information for two-three references.</p>
                    <input type="file" name="documents" multiple class="form-input">
                </div>
                <button type="submit" class="btn">→ SUBMIT APPLICATION</button>
            </form>
            <p class="form-footer-note">Note: To enable form submission, configure Formspree to send to asalomon@ucsb.edu</p>
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
