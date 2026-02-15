<?php
/**
 * Template Name: Support Page
 * The template for displaying the Support / Ways to Support page.
 */

get_header();

$support_heading  = get_theme_mod( 'regen_support_heading', 'Support Our Work' );
$support_url      = get_theme_mod( 'regen_support_url', 'https://give.ucsb.edu/campaigns/58594/donations/new' );
$support_button   = get_theme_mod( 'regen_support_button_label', 'CONTRIBUTE' );
$support_pop_msg  = get_theme_mod( 'regen_support_popover_message', 'When you check out, specify the donation is for Regeneracion Lab.' );
$support_pop_cta  = get_theme_mod( 'regen_support_popover_button', 'Continue' );

if (have_posts()):
    while (have_posts()):
        the_post(); ?>
        <div class="fade-in">
            <div class="article-header no-border">
                <h1 class="article-title"><?php the_title(); ?></h1>
            </div>
        <?php
            $page_id = get_the_ID();
            $s1_heading = get_post_meta( $page_id, 'support_section1_heading', true );
            $s1_body    = get_post_meta( $page_id, 'support_section1_body', true );
            $s2_heading = get_post_meta( $page_id, 'support_section2_heading', true );
            $s2_body    = get_post_meta( $page_id, 'support_section2_body', true );
            $s3_heading = get_post_meta( $page_id, 'support_section3_heading', true );
            $s3_body    = get_post_meta( $page_id, 'support_section3_body', true );
            $cf_heading = get_post_meta( $page_id, 'support_contact_heading', true );

            if ( ! $s1_heading ) $s1_heading = 'Financial Contributions';
            if ( ! $s1_body )    $s1_body    = 'Direct financial support helps us maintain our digital platform, support resident scholars, and develop new educational resources. If you are a funder interested in our work, please connect with us at the Contact Us link below.';
            if ( ! $s2_heading ) $s2_heading = 'Share Our Resources';
            if ( ! $s2_body )    $s2_body    = 'Help us reach broader audiences by sharing our educational materials, research, and resident work with your networks.';
            if ( ! $s3_heading ) $s3_heading = 'Collaborate';
            if ( ! $s3_body )    $s3_body    = 'We welcome collaboration with communities, collectives, organizations, scholars, artists, activists, and organizers working on related projects. Send us a message with your potential partnership ideas.';
            if ( ! $cf_heading ) $cf_heading = 'Contact Form';
        ?>

            <div class="support-box mt-40">
                <div class="mb-40">
                    <h3 class="about-profile-name"><?php echo esc_html( $s1_heading ); ?></h3>
                    <p class="mb-20"><?php echo esc_html( $s1_body ); ?></p>
                    <a href="<?php echo esc_url( $support_url ); ?>" target="_blank" rel="noopener" class="btn" data-support-open>→ <?php echo esc_html( $support_button ); ?></a>
                </div>

                <div class="mb-40">
                    <h3 class="about-profile-name"><?php echo esc_html( $s2_heading ); ?></h3>
                    <p class="mb-20"><?php echo esc_html( $s2_body ); ?></p>
                </div>

                <div class="mb-40">
                    <h3 class="about-profile-name"><?php echo esc_html( $s3_heading ); ?></h3>
                    <p class="mb-20"><?php echo esc_html( $s3_body ); ?></p>
                    <a href="#contact-form" class="btn">→ CONTACT US</a>
                </div>

                <?php
                $content = get_the_content();
                if (!empty(trim($content))): ?>
                    <div class="mb-40 article-content">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

                <div id="contact-form">
                    <h3 class="about-profile-name"><?php echo esc_html( $cf_heading ); ?></h3>
                    <?php echo do_shortcode('[contact-form-7 id="66" title="Contact form 1"]'); ?>
                </div>
            </div>
        </div>

        <div class="support-modal" id="supportModal" hidden>
            <div class="support-modal__backdrop" data-support-close></div>
            <div class="support-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="supportModalTitle">
                <h3 id="supportModalTitle" class="support-modal__title"><?php echo esc_html( $support_heading ); ?></h3>
                <p class="support-modal__message"><?php echo wp_kses_post( $support_pop_msg ); ?></p>
                <div class="support-modal__actions">
                    <button type="button" class="support-modal__close" data-support-close>Cancel</button>
                    <a href="<?php echo esc_url( $support_url ); ?>" target="_blank" rel="noopener" class="btn" data-support-continue><?php echo esc_html( $support_pop_cta ); ?></a>
                </div>
            </div>
        </div>

        <script>
        (function() {
            const openBtn = document.querySelector('[data-support-open]');
            const modal = document.getElementById('supportModal');
            if (!openBtn || !modal) return;

            const closeEls = modal.querySelectorAll('[data-support-close]');
            const dialog = modal.querySelector('.support-modal__dialog');
            const continueBtn = modal.querySelector('[data-support-continue]');
            const supportHref = continueBtn ? continueBtn.getAttribute('href') : '';
            const fadeMs = 180;

            const open = (e) => {
                if (e) e.preventDefault();
                modal.removeAttribute('hidden');
                requestAnimationFrame(() => {
                    modal.classList.add('is-open');
                    dialog?.focus({ preventScroll: true });
                });
            };

            const finishClose = () => {
                modal.setAttribute('hidden', 'hidden');
                modal.classList.remove('is-open');
                modal.classList.remove('is-closing');
            };

            const close = (e) => {
                if (e) e.preventDefault();
                modal.classList.remove('is-open');
                modal.classList.add('is-closing');
                setTimeout(finishClose, fadeMs);
                openBtn.focus({ preventScroll: true });
            };

            openBtn.addEventListener('click', open);
            closeEls.forEach((el) => el.addEventListener('click', close));

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    close(e);
                }
            });

            if (continueBtn) {
                continueBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    modal.classList.remove('is-open');
                    modal.classList.add('is-closing');
                    setTimeout(() => {
                        finishClose();
                        if (supportHref) {
                            window.open(supportHref, '_blank', 'noopener');
                        }
                    }, fadeMs);
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                    close(e);
                }
            });
        })();
        </script>
    <?php
    endwhile;
endif;

get_footer();
