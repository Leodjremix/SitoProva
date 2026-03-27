<?php
/**
 * Gestione Custom Post Type: Link Utili
 * Foolproof: Form guidato e niente editor.
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
		'singular_name'         => _x( 'Link Utile', 'Post Type Singular Name', 'santagatesi' ),
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
		'supports'              => array( 'title' ), // SOLO TITOLO. Niente Editor.
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 23,
		'menu_icon'             => 'dashicons-admin-links',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false, // Non serve pagina singola
		'show_in_rest'          => false, // Disabilita Gutenberg

        // Sicurezza: Solo gli amministratori
        'capability_type'       => 'post',
        'capabilities'          => array(
            'edit_post'          => 'update_core',
            'read_post'          => 'update_core',
            'delete_post'        => 'update_core',
            'edit_posts'         => 'update_core',
            'edit_others_posts'  => 'update_core',
            'publish_posts'      => 'update_core',
            'read_private_posts' => 'update_core',
        ),
	);
	register_post_type( 'link_utili', $args );

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
		'hierarchical'      => true, // Permette selezione a spunta nel backend
		'labels'            => $tax_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'categorie_link' ),
        'capabilities'      => array( // Solo l'admin può gestire le categorie
            'manage_terms' => 'update_core',
            'edit_terms'   => 'update_core',
            'delete_terms' => 'update_core',
            'assign_terms' => 'update_core',
        ),
	);

	register_taxonomy( 'categorie_link', array( 'link_utili' ), $tax_args );
}
add_action( 'init', 'santagatesi_register_cpt_links', 0 );

/**
 * Registrazione Meta Boxes (Dettagli e Visibilità)
 */
function santagatesi_add_links_meta_boxes() {
	add_meta_box(
		'santagatesi_link_details',
		__( 'Dettagli dell\'Attività / Link (Form Guidato)', 'santagatesi' ),
		'santagatesi_link_details_callback',
		'link_utili',
		'normal',
		'high'
	);

    add_meta_box(
		'santagatesi_link_visibility',
		__( 'Stato Pubblicazione', 'santagatesi' ),
		'santagatesi_link_visibility_callback',
		'link_utili',
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

    // Recupera valori precedenti
    $url = get_post_meta( $post->ID, '_link_url', true );
	$descrizione = get_post_meta( $post->ID, '_link_descrizione', true );
	$telefono = get_post_meta( $post->ID, '_link_telefono', true );
	?>
	<table class="form-table">
        <tr>
            <th><label for="link_descrizione"><strong><?php esc_html_e( 'Breve Descrizione', 'santagatesi' ); ?></strong></label></th>
            <td>
                <textarea id="link_descrizione" name="link_descrizione" rows="3" class="large-text" placeholder="Es. Ottimo ristorante tipico nel centro storico..."><?php echo esc_textarea( $descrizione ); ?></textarea>
                <p class="description"><?php esc_html_e( 'Testo che apparirà nella card della pagina "Link Utili".', 'santagatesi' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="link_telefono"><strong><?php esc_html_e( 'Numero di Telefono', 'santagatesi' ); ?></strong></label></th>
            <td>
                <input type="text" id="link_telefono" name="link_telefono" value="<?php echo esc_attr( $telefono ); ?>" class="regular-text" placeholder="es. 0881 123456">
                <p class="description"><?php esc_html_e( 'Il sistema aggiungerà automaticamente il link chiamabile sul telefono.', 'santagatesi' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="link_url"><strong><?php esc_html_e( 'Indirizzo Web (URL Sito)', 'santagatesi' ); ?></strong></label></th>
            <td>
                <input type="url" id="link_url" name="link_url" value="<?php echo esc_attr( $url ); ?>" class="regular-text" placeholder="https://www...">
                <p class="description"><?php esc_html_e( 'Inserisci il link completo. Lascia vuoto se non c\'è un sito.', 'santagatesi' ); ?></p>
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

	echo '<p class="description" style="margin-bottom: 10px;">' . esc_html__( 'Usa questo interruttore per nascondere il link senza cancellarlo.', 'santagatesi' ) . '</p>';

	echo '<label style="display: flex; align-items: center; gap: 10px; background: #fff; padding: 10px; border: 1px solid #ccd0d4; border-radius: 4px;">';
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
	if ( isset( $_POST['post_type'] ) && 'link_utili' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'update_core' ) ) { return; } // Solo admin
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
