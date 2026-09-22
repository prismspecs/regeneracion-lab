<?php
/**
 * Site header: slim top bar + mobile drawer.
 * Markup and classes come from assets/site.css (single source for all pages).
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> id="top">
<?php wp_body_open(); ?>
    <a class="skip-link" href="#<?php echo is_front_page() ? 'pageContent' : 'mainContent'; ?>"><?php esc_html_e( 'Skip to content', 'regen-wp' ); ?></a>

    <header class="slim-topbar" id="slimTopbar" aria-label="<?php esc_attr_e( 'Site Header', 'regen-wp' ); ?>">
        <div class="slim-topbar-inner">
            <?php // The homepage's own h1 is the pinned title graphic (an SVG mask, not real text); this gives it one, inside a landmark, for screen readers. Inner pages already have a visible h1 in their masthead. ?>
            <?php if ( is_front_page() ) : ?><h1 class="screen-reader-text"><?php bloginfo( 'name' ); ?></h1><?php endif; ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="slim-topbar-title"><?php bloginfo( 'name' ); ?></a>
            <nav class="slim-topbar-nav" aria-label="<?php esc_attr_e( 'Main Menu', 'regen-wp' ); ?>">
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
            <button class="slim-topbar-burger" id="slimTopbarBurger" aria-label="<?php esc_attr_e( 'Open menu', 'regen-wp' ); ?>" aria-expanded="false" aria-controls="mobileNavDrawer">
                <span class="burger-box">
                    <span class="burger-bar burger-bar--top"></span>
                    <span class="burger-bar burger-bar--bot"></span>
                </span>
            </button>
        </div>
    </header>

    <div class="mobile-nav-drawer" id="mobileNavDrawer" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'regen-wp' ); ?>">
        <div class="mobile-nav-panel">
            <nav class="mobile-nav-menu" aria-label="<?php esc_attr_e( 'Mobile Menu', 'regen-wp' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '<ul class="mobile-nav-links">%3$s</ul>',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                    'walker'         => new Regen_Nav_Walker( true ),
                ) );
                ?>
            </nav>
            <div class="mobile-nav-footer">
                <p class="mobile-nav-tagline"><?php bloginfo( 'description' ); ?></p>
            </div>
        </div>
    </div>

    <?php if ( ! is_front_page() ) : ?>
    <main class="page-container" id="mainContent">
    <?php endif; ?>
