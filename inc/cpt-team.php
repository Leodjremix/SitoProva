<?php
/**
 * Gestione Custom Post Type: Team / Collaboratori
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registrazione CPT "Team / Collaboratori"
 */
function santagatesi_register_cpt_team() {
	$labels = array(
		'name'                  => _x( 'Collaboratori', 'Post Type General Name', 'santagatesi' ),
		'singular_name'         => _x( 'Collaboratore', 'Post Type Singular Name', 'santagatesi' ),
		'menu_name'             => __( 'Team & Staff', 'santagatesi' ),
		'name_admin_bar'        => __( 'Collaboratore', 'santagatesi' ),
		'add_new_item'          => __( 'Aggiungi Nuovo Collaboratore', 'santagatesi' ),
		'add_new'               => __( 'Aggiungi Nuovo', 'santagatesi' ),
		'new_item'              => __( 'Nuovo Collaboratore', 'santagatesi' ),
		'edit_item'             => __( 'Modifica Collaboratore', 'santagatesi' ),
		'update_item'           => __( 'Aggiorna Collaboratore', 'santagatesi' ),
		'view_item'             => __( 'Vedi Collaboratore', 'santagatesi' ),
		'search_items'          => __( 'Cerca Collaboratore', 'santagatesi' ),
		'not_found'             => __( 'Nessun collaboratore trovato', 'santagatesi' ),
		'featured_image'        => __( 'Foto Profilo', 'santagatesi' ),
		'set_featured_image'    => __( 'Imposta Foto', 'santagatesi' ),
		'remove_featured_image' => __( 'Rimuovi Foto', 'santagatesi' ),
	);
	$args = array(
		'label'                 => __( 'Collaboratore', 'santagatesi' ),
		'description'           => __( 'Gestione del Team e della Redazione', 'santagatesi' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail', 'page-attributes' ), // Nome, Foto, e Ordinamento (Drag & Drop)
		'hierarchical'          => false,
		'public'                => false, // Non serve pagina singola, mostrati solo in lista
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 22,
		'menu_icon'             => 'dashicons-groups',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'post',
		'show_in_rest'          => false, // Niente Gutenberg, form guidato semplice
	);
	register_post_type( 'santagatesi_team', $args );
}
add_action( 'init', 'santagatesi_register_cpt_team', 0 );

/**
 * Registrazione Meta Boxes (Ruolo e Visibilità)
 */
function santagatesi_add_team_meta_boxes() {
	add_meta_box(
		'santagatesi_team_details',
		__( 'Dettagli Collaboratore', 'santagatesi' ),
		'santagatesi_team_details_callback',
		'santagatesi_team',
		'normal',
		'high'
	);

    add_meta_box(
		'santagatesi_team_visibility',
		__( 'Stato Pubblicazione (Visibilità)', 'santagatesi' ),
		'santagatesi_team_visibility_callback',
		'santagatesi_team',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'santagatesi_add_team_meta_boxes' );

/**
 * Callback Meta Box: Dettagli (Ruolo)
 */
function santagatesi_team_details_callback( $post ) {
	wp_nonce_field( 'santagatesi_team_nonce_action', 'santagatesi_team_nonce' );
	$ruolo = get_post_meta( $post->ID, '_team_ruolo', true );
	?>
	<table class="form-table">
        <tr>
            <th><label for="team_ruolo"><?php esc_html_e( 'Ruolo / Incarico', 'santagatesi' ); ?></label></th>
            <td>
                <input type="text" id="team_ruolo" name="team_ruolo" value="<?php echo esc_attr( $ruolo ); ?>" class="regular-text" placeholder="es. Direttore Responsabile" required>
                <p class="description"><?php esc_html_e( 'Il ruolo apparirà sotto il nome del collaboratore nella pagina Chi Siamo.', 'santagatesi' ); ?></p>
            </td>
        </tr>
    </table>
	<?php
}

/**
 * HTML Callback per Meta Box Visibilità (Toggle Switch)
 */
function santagatesi_team_visibility_callback( $post ) {
	$current_visibility = get_post_meta( $post->ID, '_visibilita_frontend', true );
	if ( $current_visibility === '' ) { $current_visibility = '1'; } // Default attivo

	echo '<p class="description">' . esc_html__( 'Decidi se questo membro del team deve apparire pubblicamente.', 'santagatesi' ) . '</p>';
	echo '<label style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">';
	echo '<input type="checkbox" name="team_visibility_toggle" value="1" ' . checked( $current_visibility, '1', false ) . ' />';
	echo '<strong>' . esc_html__( 'Mostra sul Sito Pubblico', 'santagatesi' ) . '</strong>';
	echo '</label>';
}

/**
 * Salvataggio sicuro dei Meta Data
 */
function santagatesi_save_team_meta_boxes( $post_id ) {
	if ( ! isset( $_POST['santagatesi_team_nonce'] ) || ! wp_verify_nonce( $_POST['santagatesi_team_nonce'], 'santagatesi_team_nonce_action' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( isset( $_POST['post_type'] ) && 'santagatesi_team' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
	}

	// Salva Ruolo
	if ( isset( $_POST['team_ruolo'] ) ) {
		update_post_meta( $post_id, '_team_ruolo', sanitize_text_field( wp_unslash( $_POST['team_ruolo'] ) ) );
	}

	// Salva Visibilità
	$is_visible = isset( $_POST['team_visibility_toggle'] ) ? '1' : '0';
	update_post_meta( $post_id, '_visibilita_frontend', $is_visible );
}
add_action( 'save_post', 'santagatesi_save_team_meta_boxes' );
