<?php
/**
 * Registrazione Custom Post Type per gli Eventi e Meta Data
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registra il CPT "Evento"
 */
function santagatesi_register_cpt_eventi() {
    $labels = array(
        'name'                  => _x( 'Eventi', 'Post type general name', 'santagatesi' ),
        'singular_name'         => _x( 'Evento', 'Post type singular name', 'santagatesi' ),
        'menu_name'             => _x( 'Eventi', 'Admin Menu text', 'santagatesi' ),
        'name_admin_bar'        => _x( 'Evento', 'Add New on Toolbar', 'santagatesi' ),
        'add_new'               => __( 'Aggiungi Nuovo', 'santagatesi' ),
        'add_new_item'          => __( 'Aggiungi Nuovo Evento', 'santagatesi' ),
        'new_item'              => __( 'Nuovo Evento', 'santagatesi' ),
        'edit_item'             => __( 'Modifica Evento', 'santagatesi' ),
        'view_item'             => __( 'Visualizza Evento', 'santagatesi' ),
        'all_items'             => __( 'Tutti gli Eventi', 'santagatesi' ),
        'search_items'          => __( 'Cerca Eventi', 'santagatesi' ),
        'not_found'             => __( 'Nessun evento trovato.', 'santagatesi' ),
        'not_found_in_trash'    => __( 'Nessun evento trovato nel cestino.', 'santagatesi' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'evento' ),
        'capability_type'    => 'post',
        'has_archive'        => false, // We use a custom page for the calendar
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => true, // Enable Gutenberg editor if accessed via backend
    );

    register_post_type( 'santagatesi_evento', $args );
}
add_action( 'init', 'santagatesi_register_cpt_eventi' );

/**
 * Registra i Meta Data associati all'Evento per le API REST e i controlli
 */
function santagatesi_register_event_meta() {
    register_post_meta(
        'santagatesi_evento',
        '_event_date',
        array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string', // Formato atteso: YYYY-MM-DD
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        )
    );

    register_post_meta(
        'santagatesi_evento',
        '_event_location',
        array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        )
    );
}
add_action( 'init', 'santagatesi_register_event_meta' );
