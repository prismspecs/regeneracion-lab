<?php
/**
 * Site footer element (inner pages wrap it in <main>; the homepage places it in its own main).
 */
?>
<footer class="site-footer">
            <div class="footer-inner">
                <div class="footer-brand">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo"><?php bloginfo( 'name' ); ?></a>
                    <p class="footer-tagline"><?php bloginfo( 'description' ); ?></p>
                </div>
                <div class="footer-meta">
                    <p class="footer-copy">&copy; <span id="currentYear"><?php echo esc_html( gmdate( 'Y' ) ); ?></span> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
                    <p class="footer-credit">Website designed by <a href="https://hire.graysonearle.com" target="_blank" rel="noopener noreferrer">Grayson Earle</a></p>
                    <a href="#top" class="footer-back-to-top" id="backToTopBtn">Back to top &uarr;</a>
                </div>
            </div>
        </footer>
