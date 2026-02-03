<?php
/**
 * Front Page Template
 */

get_header();

$home_id          = get_queried_object_id();
$hero_image_id    = get_theme_mod( 'regen_hero_image' );
$hero_image_url   = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'full' ) : get_the_post_thumbnail_url( $home_id, 'full' );
$hero_quote       = get_theme_mod( 'regen_hero_quote', '"THEY TRIED TO BURY US BUT THEY DIDN\'T KNOW WE WERE SEEDS"' );
$hero_attribution = get_theme_mod( 'regen_hero_attribution', 'Mexican revolutionary dicho circa. 1910' );
$support_heading  = get_theme_mod( 'regen_support_heading', 'Support Our Work' );
$support_text     = get_theme_mod( 'regen_support_text', 'Regeneración Lab operates through community support and grant funding. Your contribution helps us maintain this platform, support resident scholars, and keep these resources freely accessible.' );
$support_url      = get_theme_mod( 'regen_support_url', 'https://give.ucsb.edu/campaigns/58594/donations/new' );
$support_button   = get_theme_mod( 'regen_support_button_label', 'CONTRIBUTE' );
$support_pop_msg  = get_theme_mod( 'regen_support_popover_message', 'When you check out, specify the donation is for Regeneracion Lab.' );
$support_pop_cta  = get_theme_mod( 'regen_support_popover_button', 'Continue' );
?>

<div class="hero-image-container">
    <div class="hero-image fade-in" <?php echo $hero_image_url ? 'style="background-image: url(' . esc_url( $hero_image_url ) . ');"' : ''; ?>>
        <div class="quote-overlay">
            <div class="quote-label"><?php echo esc_html( $hero_quote ); ?><br>—<?php echo esc_html( $hero_attribution ); ?></div>
        </div>
    </div>
</div>

<div class="intro-text fade-in">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<section class="section">
    <h2 class="section-header">Projects</h2>
    <?php
    $projects_query = new WP_Query( array(
        'post_type'      => 'project',
        'posts_per_page' => 6,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );
    ?>
    <?php if ( $projects_query->have_posts() ) : ?>
        <div class="projects-grid">
            <?php while ( $projects_query->have_posts() ) : $projects_query->the_post(); ?>
                <?php
                    $badge        = get_post_meta( get_the_ID(), 'project_badge', true );
                    $meta         = get_post_meta( get_the_ID(), 'project_meta', true );
                    $link_label   = get_post_meta( get_the_ID(), 'project_link_label', true );
                    $link_url     = get_post_meta( get_the_ID(), 'project_link_url', true );
                    $style        = get_post_meta( get_the_ID(), 'project_style', true );
                    $style_class  = $style ? ' project-card--' . sanitize_html_class( $style ) : ' project-card--turquoise';
                    $cta_label    = $link_label ? $link_label : 'Explore';
                    $link_href    = $link_url ? esc_url( $link_url ) : get_permalink();
                    $is_external  = $link_url && preg_match( '/^https?:\/\//i', $link_url );
                    $link_target  = $is_external ? ' target="_blank" rel="noopener"' : '';
                ?>
                <div class="project-card<?php echo esc_attr( $style_class ); ?>">
                    <?php if ( $badge && 0 === strcasecmp( trim( $badge ), 'ongoing' ) ) : ?><div class="item-badge"><?php echo esc_html( $badge ); ?></div><?php endif; ?>
                    <h3><?php the_title(); ?></h3>
                    <?php if ( $meta ) : ?><p class="item-meta"><?php echo esc_html( $meta ); ?></p><?php endif; ?>
                    <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <a href="<?php echo esc_url( $link_href ); ?>" class="item-link"<?php echo $link_target; ?>>→ <?php echo esc_html( $cta_label ); ?></a>
                </div>
            <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    <?php else : ?>
        <p>No projects published yet.</p>
    <?php endif; ?>
</section>

<?php get_template_part( 'template-parts/content', 'collaborations' ); ?>

<section class="section">
    <h2 class="section-header">Recent Updates</h2>
    <?php
    $updates_query = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
    ) );
    ?>
    <?php if ( $updates_query->have_posts() ) : ?>
        <?php while ( $updates_query->have_posts() ) : $updates_query->the_post(); ?>
            <div class="home-section-box">
                <h3 class="home-section-title"><?php the_title(); ?></h3>
                <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                <?php
                    $update_links = get_post_meta( get_the_ID(), 'update_links', true );
                ?>
                <div class="update-links">
                    <?php if ( is_array( $update_links ) && ! empty( $update_links ) ) : ?>
                        <?php foreach ( $update_links as $link ) :
                            $label = isset( $link['label'] ) ? $link['label'] : '';
                            $url   = isset( $link['url'] ) ? $link['url'] : '';
                            if ( ! $url ) {
                                continue;
                            }
                            $is_external = preg_match( '/^https?:\/\//i', $url );
                            $target_attr = $is_external ? ' target="_blank" rel="noopener"' : '';
                        ?>
                            <a href="<?php echo esc_url( $url ); ?>" class="item-link"<?php echo $target_attr; ?>>→ <?php echo esc_html( $label ? $label : 'Read More' ); ?></a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <a href="<?php the_permalink(); ?>" class="item-link home-link-button">→ Read More</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    <?php else : ?>
        <p>No updates posted yet.</p>
    <?php endif; ?>
</section>

<div class="support-box">
    <h3><?php echo esc_html( $support_heading ); ?></h3>
    <p style="margin-bottom: 20px;"><?php echo esc_html( $support_text ); ?></p>
    <a href="<?php echo esc_url( $support_url ); ?>" target="_blank" rel="noopener" class="btn" data-support-open>→ <?php echo esc_html( $support_button ); ?></a>
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

<?php get_footer(); ?>
