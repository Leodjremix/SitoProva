<?php
/**
 * Gestione Custom Post Type: Personaggi Illustri
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registrazione CPT "Personaggi Illustri"
 */
function santagatesi_register_cpt_illustri() {
	$labels = array(
		'name'                  => _x( 'Personaggi Illustri', 'Post Type General Name', 'santagatesi' ),
		'singular_name'         => _x( 'Personaggio Illustre', 'Post Type Singular Name', 'santagatesi' ),
		'menu_name'             => __( 'Personaggi Illustri', 'santagatesi' ),
		'name_admin_bar'        => __( 'Personaggio Illustre', 'santagatesi' ),
		'archives'              => __( 'Archivio Personaggi', 'santagatesi' ),
		'attributes'            => __( 'Attributi Personaggio', 'santagatesi' ),
		'parent_item_colon'     => __( 'Personaggio Genitore:', 'santagatesi' ),
		'all_items'             => __( 'Tutti i Personaggi', 'santagatesi' ),
		'add_new_item'          => __( 'Aggiungi Nuovo Personaggio', 'santagatesi' ),
		'add_new'               => __( 'Aggiungi Nuovo', 'santagatesi' ),
		'new_item'              => __( 'Nuovo Personaggio', 'santagatesi' ),
		'edit_item'             => __( 'Modifica Personaggio', 'santagatesi' ),
		'update_item'           => __( 'Aggiorna Personaggio', 'santagatesi' ),
		'view_item'             => __( 'Vedi Personaggio', 'santagatesi' ),
		'view_items'            => __( 'Vedi Personaggi', 'santagatesi' ),
		'search_items'          => __( 'Cerca Personaggio', 'santagatesi' ),
		'not_found'             => __( 'Non trovato', 'santagatesi' ),
		'not_found_in_trash'    => __( 'Non trovato nel Cestino', 'santagatesi' ),
		'featured_image'        => __( 'Foto Personaggio', 'santagatesi' ),
		'set_featured_image'    => __( 'Imposta Foto', 'santagatesi' ),
		'remove_featured_image' => __( 'Rimuovi Foto', 'santagatesi' ),
		'use_featured_image'    => __( 'Usa come Foto', 'santagatesi' ),
		'insert_into_item'      => __( 'Inserisci nel personaggio', 'santagatesi' ),
		'uploaded_to_this_item' => __( 'Caricato su questo personaggio', 'santagatesi' ),
		'items_list'            => __( 'Lista Personaggi', 'santagatesi' ),
		'items_list_navigation' => __( 'Navigazione lista personaggi', 'santagatesi' ),
		'filter_items_list'     => __( 'Filtra lista personaggi', 'santagatesi' ),
	);
	$args = array(
		'label'                 => __( 'Personaggio Illustre', 'santagatesi' ),
		'description'           => __( 'Gestione delle card dei Santagatesi Illustri', 'santagatesi' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ), // Name, Bio/Descrizione, Foto
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 21,
		'menu_icon'             => 'dashicons-businessman',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true, // Supporto Gutenberg
	);
	register_post_type( 'santagatesi_illustri', $args );
}
add_action( 'init', 'santagatesi_register_cpt_illustri', 0 );

/**
 * Registrazione Meta Box: Visibilità Frontend
 */
function santagatesi_add_illustri_meta_boxes() {
	add_meta_box(
		'santagatesi_illustri_visibility',
		__( 'Stato Pubblicazione (Visibilità Card)', 'santagatesi' ),
		'santagatesi_illustri_visibility_callback',
		'santagatesi_illustri',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'santagatesi_add_illustri_meta_boxes' );

/**
 * HTML Callback per Meta Box Visibilità (Toggle Switch)
 */
function santagatesi_illustri_visibility_callback( $post ) {
	// Aggiunge un nonce field per sicurezza (CSRF protection)
	wp_nonce_field( 'santagatesi_illustri_visibility_nonce_action', 'santagatesi_illustri_visibility_nonce' );

	// Recupera il valore attuale (di default è 1, cioè visibile)
	$current_visibility = get_post_meta( $post->ID, '_visibilita_frontend', true );
	if ( $current_visibility === '' ) {
		$current_visibility = '1'; // Default on creation
	}

	echo '<p class="description">' . esc_html__( 'Decidi se questa card deve essere visibile al pubblico sul frontend.', 'santagatesi' ) . '</p>';

	// Checkbox styled as a basic toggle
	echo '<label style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">';
	echo '<input type="checkbox" name="illustri_visibility_toggle" value="1" ' . checked( $current_visibility, '1', false ) . ' />';
	echo '<strong>' . esc_html__( 'Mostra sul Sito Pubblico', 'santagatesi' ) . '</strong>';
	echo '</label>';
}

/**
 * Salvataggio sicuro del Meta Data Visibilità
 */
function santagatesi_save_illustri_meta_boxes( $post_id ) {
	// 1. Controllo Nonce (Sicurezza Base)
	if ( ! isset( $_POST['santagatesi_illustri_visibility_nonce'] ) || ! wp_verify_nonce( $_POST['santagatesi_illustri_visibility_nonce'], 'santagatesi_illustri_visibility_nonce_action' ) ) {
		return;
	}

	// 2. Controllo Autosave (Evita salvataggi accidentali vuoti)
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// 3. Controllo Permessi Utente (Sicurezza Assoluta)
	if ( isset( $_POST['post_type'] ) && 'santagatesi_illustri' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// 4. Salvataggio del Dato Booleano (1 = Visibile, 0 = Nascosto)
	// Se la checkbox non è flaggata, $_POST['illustri_visibility_toggle'] non sarà settato.
	$is_visible = isset( $_POST['illustri_visibility_toggle'] ) ? '1' : '0';

	update_post_meta( $post_id, '_visibilita_frontend', $is_visible );
}
add_action( 'save_post', 'santagatesi_save_illustri_meta_boxes' );
