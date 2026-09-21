<?php
/**
 * Block patterns for the redesigned layouts. Each is plain core blocks with a
 * class, so editors can change every word without touching code.
 */

function regen_wp_register_layout_patterns() {
    if ( ! function_exists( 'register_block_pattern' ) ) {
        return;
    }

    $focus_item = static function ( $accent, $eyebrow, $title, $text ) {
        return '<!-- wp:group {"className":"focus-item focus-item--' . $accent . '"} -->
<div class="wp-block-group focus-item focus-item--' . $accent . '"><!-- wp:paragraph {"className":"focus-eyebrow"} -->
<p class="focus-eyebrow">' . $eyebrow . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . $title . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . $text . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
    };

    register_block_pattern( 'regen/focus-grid', array(
        'title'       => __( 'Focus Grid (3 columns)', 'regen-wp' ),
        'description' => __( 'Grid of short focus areas: colored label, heading, one sentence.', 'regen-wp' ),
        'categories'  => array( 'regen' ),
        'content'     => '<!-- wp:group {"className":"focus-grid"} -->
<div class="wp-block-group focus-grid">'
            . $focus_item( 'turquoise', 'Label', 'Focus area', 'One or two sentences describing this focus.' )
            . $focus_item( 'brown', 'Label', 'Focus area', 'One or two sentences describing this focus.' )
            . $focus_item( 'amber', 'Label', 'Focus area', 'One or two sentences describing this focus.' )
            . '</div>
<!-- /wp:group -->',
    ) );

    $model_col = static function ( $title, $text ) {
        return '<!-- wp:group {"className":"model-col"} -->
<div class="wp-block-group model-col"><!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">' . $title . '</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . $text . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
    };

    register_block_pattern( 'regen/model-grid', array(
        'title'       => __( 'Three-Column Notes', 'regen-wp' ),
        'description' => __( 'Three short titled paragraphs side by side (residency model, criteria...).', 'regen-wp' ),
        'categories'  => array( 'regen' ),
        'content'     => '<!-- wp:group {"className":"model-grid"} -->
<div class="wp-block-group model-grid">'
            . $model_col( 'Heading', 'Short paragraph.' )
            . $model_col( 'Heading', 'Short paragraph.' )
            . $model_col( 'Heading', 'Short paragraph.' )
            . '</div>
<!-- /wp:group -->',
    ) );

    $framework = static function ( $num, $title, $text ) {
        return '<!-- wp:group {"className":"framework-card"} -->
<div class="wp-block-group framework-card"><!-- wp:paragraph {"className":"framework-num"} -->
<p class="framework-num">' . $num . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">' . $title . '</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . $text . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
    };

    register_block_pattern( 'regen/frameworks-grid', array(
        'title'       => __( 'Numbered Commitments (3 columns)', 'regen-wp' ),
        'description' => __( 'Three numbered principles with a heading and short paragraph.', 'regen-wp' ),
        'categories'  => array( 'regen' ),
        'content'     => '<!-- wp:group {"className":"frameworks-grid"} -->
<div class="wp-block-group frameworks-grid">'
            . $framework( '01', 'Commitment', 'Short description.' )
            . $framework( '02', 'Commitment', 'Short description.' )
            . $framework( '03', 'Commitment', 'Short description.' )
            . '</div>
<!-- /wp:group -->',
    ) );

    register_block_pattern( 'regen/pull-quote', array(
        'title'       => __( 'Pull Quote', 'regen-wp' ),
        'description' => __( 'Large italic serif quotation with a small green attribution.', 'regen-wp' ),
        'categories'  => array( 'regen' ),
        'content'     => '<!-- wp:quote {"className":"pull-quote"} -->
<blockquote class="wp-block-quote pull-quote"><!-- wp:paragraph -->
<p>“Quotation text goes here.”</p>
<!-- /wp:paragraph --><cite>Attribution</cite></blockquote>
<!-- /wp:quote -->',
    ) );
}
add_action( 'init', 'regen_wp_register_layout_patterns', 20 );
