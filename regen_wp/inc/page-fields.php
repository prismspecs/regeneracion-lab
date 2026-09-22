<?php
/**
 * Editable page fields: tagline (excerpt), eyebrow, and section intros used by
 * the Residents / Students templates. Everything shown on those pages is
 * editable from the Pages screen.
 */

add_post_type_support( 'page', 'excerpt' );

function regen_wp_page_fields_list() {
    return array(
        'eyebrow'        => array( 'Eyebrow (small label above the title)', 'text' ),
        'intro_current'  => array( 'Residents page: "Current Residents" intro', 'textarea' ),
        'intro_past'     => array( 'Residents page: "Past Residents" intro', 'textarea' ),
        'intro_programs' => array( 'Students page: "Reading Groups & Labs" intro', 'textarea' ),
    );
}

function regen_wp_page_fields_metabox() {
    add_meta_box(
        'regen_page_fields',
        __( 'Page Header & Sections', 'regen-wp' ),
        'regen_wp_page_fields_render',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'regen_wp_page_fields_metabox' );

function regen_wp_page_fields_render( $post ) {
    wp_nonce_field( 'regen_wp_save_page_fields', 'regen_wp_page_fields_nonce' );
    echo '<p class="description">' . esc_html__( 'The page title is the headline and the Excerpt is the tagline under it. Fields below only appear on templates that use them.', 'regen-wp' ) . '</p>';
    foreach ( regen_wp_page_fields_list() as $key => $def ) {
        $value = get_post_meta( $post->ID, 'regen_' . $key, true );
        echo '<p><label for="regen_' . esc_attr( $key ) . '"><strong>' . esc_html( $def[0] ) . '</strong></label></p>';
        if ( 'textarea' === $def[1] ) {
            echo '<textarea id="regen_' . esc_attr( $key ) . '" name="regen_' . esc_attr( $key ) . '" rows="3" style="width:100%">' . esc_textarea( $value ) . '</textarea>';
        } else {
            echo '<input type="text" id="regen_' . esc_attr( $key ) . '" name="regen_' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%" />';
        }
    }
}

function regen_wp_page_fields_save( $post_id ) {
    if ( ! isset( $_POST['regen_wp_page_fields_nonce'] ) || ! wp_verify_nonce( $_POST['regen_wp_page_fields_nonce'], 'regen_wp_save_page_fields' ) ) {
        return;
    }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    foreach ( array_keys( regen_wp_page_fields_list() ) as $key ) {
        $name = 'regen_' . $key;
        if ( isset( $_POST[ $name ] ) && '' !== trim( wp_unslash( $_POST[ $name ] ) ) ) {
            update_post_meta( $post_id, $name, sanitize_textarea_field( wp_unslash( $_POST[ $name ] ) ) );
        } else {
            delete_post_meta( $post_id, $name );
        }
    }
}
add_action( 'save_post_page', 'regen_wp_page_fields_save' );
