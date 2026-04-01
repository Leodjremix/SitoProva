<?php
/**
 * Gestione Custom Post Type: Personaggi Illustri
 * Foolproof: Solo campi custom guidati, niente editor predefinito.
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
		'name_admin_bar'        => __( 'Personaggio', 'santagatesi' ),
		'add_new_item'          => __( 'Aggiungi Nuovo Personaggio', 'santagatesi' ),
		'add_new'               => __( 'Aggiungi Nuovo', 'santagatesi' ),
		'new_item'              => __( 'Nuovo Personaggio', 'santagatesi' ),
		'edit_item'             => __( 'Modifica Personaggio', 'santagatesi' ),
		'update_item'           => __( 'Aggiorna Personaggio', 'santagatesi' ),
		'view_item'             => __( 'Vedi Personaggio', 'santagatesi' ),
		'search_items'          => __( 'Cerca Personaggio', 'santagatesi' ),
		'not_found'             => __( 'Nessun personaggio trovato', 'santagatesi' ),
	);

	$args = array(
		'label'                 => __( 'Personaggio Illustre', 'santagatesi' ),
		'description'           => __( 'Schede dei Santagatesi Illustri', 'santagatesi' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'page-attributes' ), // Supporto per Menu Order (Drag & Drop)
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 21,
		'menu_icon'             => 'dashicons-businessman',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false, // Gestito tramite Page Template dedicato
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
        'rewrite'               => array( 'slug' => 'personaggio-illustre' ),
        'show_in_rest'          => false, // Disabilita Gutenberg completamente

        // Sicurezza: Solo gli amministratori possono gestire questo CPT
        'capability_type'       => 'post',
        'capabilities'          => array(
            'edit_post'          => 'update_core', // Trucco WP: update_core è solo per gli admin
            'read_post'          => 'update_core',
            'delete_post'        => 'update_core',
            'edit_posts'         => 'update_core',
            'edit_others_posts'  => 'update_core',
            'publish_posts'      => 'update_core',
            'read_private_posts' => 'update_core',
        ),
	);
	register_post_type( 'personaggi_illustri', $args );
}
add_action( 'init', 'santagatesi_register_cpt_illustri', 0 );

/**
 * Aggiunta Meta Box "Dati Personaggio"
 */
function santagatesi_add_illustri_meta_boxes() {
	add_meta_box(
		'santagatesi_illustri_dati',
		__( 'Dati del Personaggio (Form Guidato)', 'santagatesi' ),
		'santagatesi_illustri_dati_callback',
		'personaggi_illustri',
		'normal',
		'high'
	);

    add_meta_box(
		'santagatesi_illustri_visibility',
		__( 'Stato Pubblicazione', 'santagatesi' ),
		'santagatesi_illustri_visibility_callback',
		'personaggi_illustri',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'santagatesi_add_illustri_meta_boxes' );

/**
 * HTML Callback per Meta Box Dati: Foto e Descrizione
 */
function santagatesi_illustri_dati_callback( $post ) {
	wp_nonce_field( 'santagatesi_illustri_nonce_action', 'santagatesi_illustri_nonce' );

	$foto = get_post_meta( $post->ID, '_illustri_foto', true );
	$descrizione = get_post_meta( $post->ID, '_illustri_descrizione', true );
	?>
	<table class="form-table">
        <!-- Campo Foto (Media Uploader) -->
        <tr style="border-bottom: 1px solid #eee; padding-bottom: 15px;">
            <th><label for="illustri_foto"><strong><?php esc_html_e( 'Foto Profilo', 'santagatesi' ); ?></strong></label></th>
            <td>
                <input type="text" name="illustri_foto" id="illustri_foto" value="<?php echo esc_attr( $foto ); ?>" class="regular-text" style="width: 100%; max-width: 400px; margin-bottom: 10px;">
                <input type="button" class="button button-secondary stg-upload-btn" data-target="illustri_foto" value="<?php esc_attr_e( 'Scegli/Carica Immagine', 'santagatesi' ); ?>">
                <p class="description"><?php esc_html_e( 'Carica l\'immagine del personaggio o incolla l\'URL.', 'santagatesi' ); ?></p>

                <?php if( ! empty( $foto ) ): ?>
                    <div style="margin-top:10px; max-width: 200px; border: 1px solid #ccc; border-radius: 4px; overflow:hidden;">
                        <img src="<?php echo esc_url( $foto ); ?>" alt="Anteprima" style="width: 100%; height: auto; display: block;" id="illustri_foto_preview">
                    </div>
                <?php else: ?>
                    <div id="illustri_foto_preview_container" style="display:none; margin-top:10px; max-width: 200px; border: 1px solid #ccc; border-radius: 4px; overflow:hidden;">
                        <img src="" alt="Anteprima" style="width: 100%; height: auto; display: block;" id="illustri_foto_preview">
                    </div>
                <?php endif; ?>
            </td>
        </tr>

        <!-- Campo Descrizione -->
        <tr>
            <th><label for="illustri_descrizione"><strong><?php esc_html_e( 'Biografia / Descrizione', 'santagatesi' ); ?></strong></label></th>
            <td>
                <textarea name="illustri_descrizione" id="illustri_descrizione" rows="6" class="large-text" placeholder="Inserisci qui la biografia..."><?php echo esc_textarea( $descrizione ); ?></textarea>
                <p class="description"><?php esc_html_e( 'Questo testo verrà impaginato automaticamente nella card del frontend.', 'santagatesi' ); ?></p>
            </td>
        </tr>
    </table>
	<?php
}

/**
 * HTML Callback per Meta Box Visibilità (Toggle Switch)
 */
function santagatesi_illustri_visibility_callback( $post ) {
	$current_visibility = get_post_meta( $post->ID, '_visibilita_frontend', true );
	if ( $current_visibility === '' ) { $current_visibility = '1'; } // Default attivo se nuovo

	echo '<p class="description" style="margin-bottom: 10px;">' . esc_html__( 'Usa questo interruttore per nascondere il personaggio dal sito pubblico senza doverlo cancellare.', 'santagatesi' ) . '</p>';

	echo '<label style="display: flex; align-items: center; gap: 10px; background: #fff; padding: 10px; border: 1px solid #ccd0d4; border-radius: 4px;">';
	echo '<input type="checkbox" name="illustri_visibility_toggle" value="1" ' . checked( $current_visibility, '1', false ) . ' />';
	echo '<strong>' . esc_html__( 'Card Visibile sul Front-end', 'santagatesi' ) . '</strong>';
	echo '</label>';
}

/**
 * Salvataggio sicuro dei Meta Data
 */
function santagatesi_save_illustri_meta_boxes( $post_id ) {
	// Sicurezza Base
	if ( ! isset( $_POST['santagatesi_illustri_nonce'] ) || ! wp_verify_nonce( $_POST['santagatesi_illustri_nonce'], 'santagatesi_illustri_nonce_action' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( isset( $_POST['post_type'] ) && 'personaggi_illustri' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'update_core' ) ) { return; } // Solo Admin
	}

	// Salva Foto
	if ( isset( $_POST['illustri_foto'] ) ) {
		update_post_meta( $post_id, '_illustri_foto', esc_url_raw( wp_unslash( $_POST['illustri_foto'] ) ) );
	}

    // Salva Descrizione (Ammesso un minimo di formattazione base come br)
	if ( isset( $_POST['illustri_descrizione'] ) ) {
		update_post_meta( $post_id, '_illustri_descrizione', wp_kses_post( wp_unslash( $_POST['illustri_descrizione'] ) ) );
	}

	// Salva Visibilità (Se il checkbox non c'è nello $_POST, significa che è spento)
	$is_visible = isset( $_POST['illustri_visibility_toggle'] ) ? '1' : '0';
	update_post_meta( $post_id, '_visibilita_frontend', $is_visible );
}
add_action( 'save_post', 'santagatesi_save_illustri_meta_boxes' );
