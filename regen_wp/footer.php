<?php
/**
 * Site footer. Inner pages: the footer sits inside <main> so it shares the
 * page column width (header.php opens <main>). The homepage prints its own.
 */
if ( ! is_front_page() ) :
    get_template_part( 'template-parts/site-footer' );
    ?>
    </main>
<?php endif; ?>

    <?php wp_footer(); ?>
</body>

</html>
