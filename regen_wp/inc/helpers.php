<?php
/**
 * Small template helpers.
 */

/**
 * Wrap the first letter of the first paragraph in the animated drop-cap markup
 * that assets/site.js + page.css expect. Leaves content alone if it doesn't
 * open with a plain paragraph.
 */
function regen_wp_dropcap( $html ) {
    return preg_replace_callback(
        '/<p([^>]*)>(\s*)(<[^>]+>\s*)*([A-Za-zÀ-ÿ])/u',
        static function ( $m ) {
            static $done = false;
            if ( $done ) {
                return $m[0];
            }
            $done  = true;
            $attrs = $m[1];
            if ( false !== strpos( $attrs, 'class="' ) ) {
                $attrs = str_replace( 'class="', 'class="dropcap-lead ', $attrs );
            } else {
                $attrs .= ' class="dropcap-lead"';
            }
            $inline = isset( $m[3] ) ? $m[3] : '';
            return '<p' . $attrs . '>' . $m[2] . $inline . '<span class="dropcap-letter">' . $m[4] . '</span>';
        },
        $html,
        1
    );
}

/**
 * Editable per-page fields (see inc/page-fields.php).
 */
function regen_wp_page_field( $key, $post_id = 0 ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    return (string) get_post_meta( $post_id, 'regen_' . $key, true );
}

/**
 * Render the page masthead (eyebrow, title, tagline, optional jump-nav).
 *
 * @param array $args eyebrow, title, tagline, jump (label => #anchor).
 */
function regen_wp_masthead( $args ) {
    get_template_part( 'template-parts/masthead', null, $args );
}

/**
 * Contact Form 7 form by title, rendered with a class on the <form> so the
 * page CSS (.join-form, .apply-form, .contact-form) applies directly.
 */
function regen_wp_cf7( $title, $class ) {
    return do_shortcode( '[contact-form-7 title="' . esc_attr( $title ) . '" html_class="' . esc_attr( $class ) . '"]' );
}

/**
 * Split a comma/line separated CSS accent for cycling profile colors.
 */
function regen_wp_accent( $index ) {
    $accents = array( 'turquoise', 'brown', 'amber' );
    return $accents[ $index % count( $accents ) ];
}
