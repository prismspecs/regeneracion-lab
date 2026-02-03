<?php
/**
 * Page template
 */

get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post(); ?>
        <article <?php post_class( 'content-article page-article' ); ?>>
            <div class="article-header">
                <h1 class="article-title"><?php the_title(); ?></h1>
            </div>
            <div class="article-content"><?php the_content(); ?></div>
        </article>
    <?php endwhile;
else :
    echo '<p>No content found.</p>';
endif;

get_footer();
