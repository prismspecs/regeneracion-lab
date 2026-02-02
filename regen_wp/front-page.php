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
                    $badge       = get_post_meta( get_the_ID(), 'project_badge', true );
                    $meta        = get_post_meta( get_the_ID(), 'project_meta', true );
                    $link_label  = get_post_meta( get_the_ID(), 'project_link_label', true );
                    $cta_label   = $link_label ? $link_label : 'Explore';
                ?>
                <div class="project-card">
                    <?php if ( $badge ) : ?><div class="item-badge"><?php echo esc_html( $badge ); ?></div><?php endif; ?>
                    <h3><?php the_title(); ?></h3>
                    <?php if ( $meta ) : ?><p class="item-meta"><?php echo esc_html( $meta ); ?></p><?php endif; ?>
                    <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <a href="<?php the_permalink(); ?>" class="item-link">→ <?php echo esc_html( $cta_label ); ?></a>
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
        'category_name'  => 'updates',
        'posts_per_page' => 3,
    ) );
    ?>
    <?php if ( $updates_query->have_posts() ) : ?>
        <?php while ( $updates_query->have_posts() ) : $updates_query->the_post(); ?>
            <div class="home-section-box">
                <h3 class="home-section-title"><?php the_title(); ?></h3>
                <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                <a href="<?php the_permalink(); ?>" class="item-link home-link-button">→ Read More</a>
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
    <a href="<?php echo esc_url( $support_url ); ?>" target="_blank" class="btn">→ CONTRIBUTE</a>
</div>

<?php get_footer(); ?>
