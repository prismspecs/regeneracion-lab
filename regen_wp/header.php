<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
    <div class="app-container">
        <div class="mockup">
            <header class="site-header" id="siteHeader">
                <div class="site-title-bar">
                    <h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
                    <button class="nav-toggle" aria-label="Menu" aria-expanded="false">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
                <p class="site-tagline"><?php bloginfo( 'description' ); ?></p>
                <nav class="site-nav" id="siteNav">
                    <?php
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'menu_class'     => 'nav-list',
                            'container'      => false,
                            'fallback_cb'    => false,
                        ) );
                    ?>
                </nav>
            </header>

            <div class="content-wrapper">
                <div class="loading-spinner" id="loadingSpinner"></div>
                <div class="content-area" id="mainContent">
                    <!-- Content will be dynamically loaded here -->
