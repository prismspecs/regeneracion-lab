<?php
/**
 * Programs: reading groups, labs and study circles shown on the Students page.
 * Title = program name, editor = description, meta below = the card details.
 */

function regen_wp_register_program_cpt() {
    register_post_type( 'program', array(
        'labels' => array(
            'name'          => __( 'Programs', 'regen-wp' ),
            'singular_name' => __( 'Program', 'regen-wp' ),
            'add_new_item'  => __( 'Add New Program', 'regen-wp' ),
            'menu_name'     => __( 'Programs', 'regen-wp' ),
        ),
        'public'             => false,
        'show_ui'            => true,
        'show_in_rest'       => true,
        'menu_position'      => 9,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array( 'title', 'editor', 'page-attributes' ),
        'rewrite'            => false,
    ) );
}
add_action( 'init', 'regen_wp_register_program_cpt' );

function regen_wp_program_fields() {
    return array(
        'program_badge'            => array( 'Badge (e.g. "Active" or "Winter 2026 Cohort")', 'text' ),
        'program_badge_style'      => array( 'Badge color: active (green) or winter (teal)', 'text' ),
        'program_partner'          => array( 'Partner / co-convener line (top right)', 'text' ),
        'program_schedule'         => array( 'Schedule', 'text' ),
        'program_facilitator'      => array( 'Facilitator', 'text' ),
        'program_level'            => array( 'Level', 'text' ),
        'program_topics_title'     => array( 'Topics heading', 'text' ),
        'program_topics'           => array( 'Topics (one per line)', 'textarea' ),
        'program_cta'              => array( 'Button label (default: "Join")', 'text' ),
        'program_facilitator_note' => array( 'Note beside the button', 'text' ),
    );
}

function regen_wp_program_metabox() {
    add_meta_box( 'regen_program_meta', __( 'Program Details', 'regen-wp' ), 'regen_wp_program_metabox_render', 'program', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'regen_wp_program_metabox' );

function regen_wp_program_metabox_render( $post ) {
    wp_nonce_field( 'regen_wp_save_program_meta', 'regen_wp_program_nonce' );
    foreach ( regen_wp_program_fields() as $key => $def ) {
        $value = get_post_meta( $post->ID, $key, true );
        echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $def[0] ) . '</strong></label></p>';
        if ( 'textarea' === $def[1] ) {
            echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="5" style="width:100%">' . esc_textarea( $value ) . '</textarea>';
        } else {
            echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%" />';
        }
    }
    echo '<p class="description">' . esc_html__( 'Programs are listed by "Order" (right sidebar), lowest first.', 'regen-wp' ) . '</p>';
}

function regen_wp_program_save( $post_id ) {
    if ( ! isset( $_POST['regen_wp_program_nonce'] ) || ! wp_verify_nonce( $_POST['regen_wp_program_nonce'], 'regen_wp_save_program_meta' ) ) {
        return;
    }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    foreach ( array_keys( regen_wp_program_fields() ) as $key ) {
        if ( isset( $_POST[ $key ] ) && '' !== trim( wp_unslash( $_POST[ $key ] ) ) ) {
            update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
        } else {
            delete_post_meta( $post_id, $key );
        }
    }
}
add_action( 'save_post_program', 'regen_wp_program_save' );
