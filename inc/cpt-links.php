<?php
/**
 * Gestione Custom Post Type: Link Utili
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registrazione CPT "Link Utili"
 */
function santagatesi_register_cpt_links() {
	$labels = array(
		'name'                  => _x( 'Link Utili', 'Post Type General Name', 'santagatesi' ),
		'singular_name'         => _x( 'Link', 'Post Type Singular Name', 'santagatesi' ),
		'menu_name'             => __( 'Link Utili', 'santagatesi' ),
		'name_admin_bar'        => __( 'Link Utile', 'santagatesi' ),
		'add_new_item'          => __( 'Aggiungi Nuovo Link', 'santagatesi' ),
		'add_new'               => __( 'Aggiungi Nuovo', 'santagatesi' ),
		'new_item'              => __( 'Nuovo Link', 'santagatesi' ),
		'edit_item'             => __( 'Modifica Link', 'santagatesi' ),
		'update_item'           => __( 'Aggiorna Link', 'santagatesi' ),
		'view_item'             => __( 'Vedi Link', 'santagatesi' ),
		'search_items'          => __( 'Cerca Link', 'santagatesi' ),
		'not_found'             => __( 'Nessun link trovato', 'santagatesi' ),
	);
	$args = array(
		'label'                 => __( 'Link Utile', 'santagatesi' ),
		'description'           => __( 'Gestione dei collegamenti esterni', 'santagatesi' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ), // Solo il titolo del link
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 23,
		'menu_icon'             => 'dashicons-admin-links',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'post',
		'show_in_rest'          => false,
	);
	register_post_type( 'santagatesi_links', $args );

    // Registra la Tassonomia (Categoria Link) per raggruppare visivamente i link
	$tax_labels = array(
		'name'              => _x( 'Categorie Link', 'taxonomy general name', 'santagatesi' ),
		'singular_name'     => _x( 'Categoria Link', 'taxonomy singular name', 'santagatesi' ),
		'search_items'      => __( 'Cerca Categoria', 'santagatesi' ),
		'all_items'         => __( 'Tutte le Categorie', 'santagatesi' ),
		'parent_item'       => __( 'Categoria Genitore', 'santagatesi' ),
		'parent_item_colon' => __( 'Categoria Genitore:', 'santagatesi' ),
		'edit_item'         => __( 'Modifica Categoria', 'santagatesi' ),
		'update_item'       => __( 'Aggiorna Categoria', 'santagatesi' ),
		'add_new_item'      => __( 'Aggiungi Nuova Categoria', 'santagatesi' ),
		'new_item_name'     => __( 'Nuovo Nome Categoria', 'santagatesi' ),
		'menu_name'         => __( 'Categorie', 'santagatesi' ),
	);

	$tax_args = array(
		'hierarchical'      => true, // Come le categorie normali, per avere la checkbox
		'labels'            => $tax_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'link_category' ),
	);

	register_taxonomy( 'link_category', array( 'santagatesi_links' ), $tax_args );
}
add_action( 'init', 'santagatesi_register_cpt_links', 0 );

/**
 * Registrazione Meta Boxes (Dettagli e Visibilità)
 */
function santagatesi_add_links_meta_boxes() {
	add_meta_box(
		'santagatesi_link_details',
		__( 'Dettagli dell\'Attività / Link', 'santagatesi' ),
		'santagatesi_link_details_callback',
		'santagatesi_links',
		'normal',
		'high'
	);

    add_meta_box(
		'santagatesi_link_visibility',
		__( 'Stato Pubblicazione (Visibilità)', 'santagatesi' ),
		'santagatesi_link_visibility_callback',
		'santagatesi_links',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'santagatesi_add_links_meta_boxes' );

/**
 * Callback Meta Box: Dettagli (URL, Descrizione, Telefono)
 */
function santagatesi_link_details_callback( $post ) {
	wp_nonce_field( 'santagatesi_link_nonce_action', 'santagatesi_link_nonce' );
	$url = get_post_meta( $post->ID, '_link_url', true );
	$descrizione = get_post_meta( $post->ID, '_link_descrizione', true );
	$telefono = get_post_meta( $post->ID, '_link_telefono', true );
	?>
	<table class="form-table">
        <tr>
            <th><label for="link_descrizione"><?php esc_html_e( 'Breve Descrizione', 'santagatesi' ); ?></label></th>
            <td>
                <textarea id="link_descrizione" name="link_descrizione" rows="3" class="large-text" placeholder="Es. Ottimo ristorante tipico nel centro storico."><?php echo esc_textarea( $descrizione ); ?></textarea>
                <p class="description"><?php esc_html_e( 'Descrivi brevemente l\'attività o il servizio fornito dal link.', 'santagatesi' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="link_telefono"><?php esc_html_e( 'Numero di Telefono', 'santagatesi' ); ?></label></th>
            <td>
                <input type="text" id="link_telefono" name="link_telefono" value="<?php echo esc_attr( $telefono ); ?>" class="regular-text" placeholder="es. +39 0881 123456">
                <p class="description"><?php esc_html_e( 'Opzionale. Utile per attività commerciali (Ristoranti, B&B).', 'santagatesi' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="link_url"><?php esc_html_e( 'Indirizzo Web (Sito o Pagina FB)', 'santagatesi' ); ?></label></th>
            <td>
                <input type="url" id="link_url" name="link_url" value="<?php echo esc_attr( $url ); ?>" class="regular-text" placeholder="https://www.esempio.it">
                <p class="description"><?php esc_html_e( 'Inserisci il link completo, includendo https://. Lascia vuoto se non hanno un sito web.', 'santagatesi' ); ?></p>
            </td>
        </tr>
    </table>
	<?php
}

/**
 * HTML Callback per Meta Box Visibilità (Toggle Switch)
 */
function santagatesi_link_visibility_callback( $post ) {
	$current_visibility = get_post_meta( $post->ID, '_visibilita_frontend', true );
	if ( $current_visibility === '' ) { $current_visibility = '1'; } // Default attivo

	echo '<p class="description">' . esc_html__( 'Decidi se questo link deve essere visibile sul sito.', 'santagatesi' ) . '</p>';
	echo '<label style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">';
	echo '<input type="checkbox" name="link_visibility_toggle" value="1" ' . checked( $current_visibility, '1', false ) . ' />';
	echo '<strong>' . esc_html__( 'Mostra sul Sito Pubblico', 'santagatesi' ) . '</strong>';
	echo '</label>';
}

/**
 * Salvataggio sicuro dei Meta Data
 */
function santagatesi_save_links_meta_boxes( $post_id ) {
	if ( ! isset( $_POST['santagatesi_link_nonce'] ) || ! wp_verify_nonce( $_POST['santagatesi_link_nonce'], 'santagatesi_link_nonce_action' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( isset( $_POST['post_type'] ) && 'santagatesi_links' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
	}

	// Salva URL
	if ( isset( $_POST['link_url'] ) ) {
		update_post_meta( $post_id, '_link_url', esc_url_raw( wp_unslash( $_POST['link_url'] ) ) );
	}

    // Salva Descrizione
	if ( isset( $_POST['link_descrizione'] ) ) {
		update_post_meta( $post_id, '_link_descrizione', sanitize_textarea_field( wp_unslash( $_POST['link_descrizione'] ) ) );
	}

    // Salva Telefono
	if ( isset( $_POST['link_telefono'] ) ) {
		update_post_meta( $post_id, '_link_telefono', sanitize_text_field( wp_unslash( $_POST['link_telefono'] ) ) );
	}

	// Salva Visibilità
	$is_visible = isset( $_POST['link_visibility_toggle'] ) ? '1' : '0';
	update_post_meta( $post_id, '_visibilita_frontend', $is_visible );
}
add_action( 'save_post', 'santagatesi_save_links_meta_boxes' );
