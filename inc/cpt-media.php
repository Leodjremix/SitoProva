<?php
/**
 * Custom Post Type: Media Gallery
 *
 * @package santagatesi
 */

function santagatesi_register_cpt_media() {
	$labels = array(
		'name'                  => _x( 'Galleria Media', 'Post Type General Name', 'santagatesi' ),
		'singular_name'         => _x( 'Elemento Media', 'Post Type Singular Name', 'santagatesi' ),
		'menu_name'             => __( 'Galleria', 'santagatesi' ),
		'all_items'             => __( 'Tutti gli Elementi', 'santagatesi' ),
		'add_new_item'          => __( 'Aggiungi Nuovo Elemento', 'santagatesi' ),
		'add_new'               => __( 'Aggiungi Nuovo', 'santagatesi' ),
		'edit_item'             => __( 'Modifica Elemento', 'santagatesi' ),
		'update_item'           => __( 'Aggiorna Elemento', 'santagatesi' ),
	);
	$args = array(
		'label'                 => __( 'Elemento Media', 'santagatesi' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ), // Solo titolo. Foto/Video e Approvazione gestiti via meta.
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => false, // Non deve avere una singola pagina pubblica visibile (es. /media/nome)
		'show_ui'               => true,
		'show_in_menu'          => 'santagatesi-admin-dashboard', // Sotto il custom admin menu
		'menu_position'         => 20,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'post',
		'capabilities'          => array(
			'create_posts' => 'update_core', // Solo Admin
		),
	);
	register_post_type( 'santagatesi_media', $args );
}
add_action( 'init', 'santagatesi_register_cpt_media', 0 );

/**
 * Aggiungi Meta Box per Media (URL Foto/Video, Tipo, e Approvazione)
 */
function santagatesi_media_add_meta_boxes() {
	add_meta_box(
		'santagatesi_media_details',
		__( 'Dettagli Media (Foolproof)', 'santagatesi' ),
		'santagatesi_media_render_meta_box',
		'santagatesi_media',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'santagatesi_media_add_meta_boxes' );

function santagatesi_media_render_meta_box( $post ) {
	wp_nonce_field( 'santagatesi_media_save', 'santagatesi_media_nonce' );

	$tipo = get_post_meta( $post->ID, '_media_tipo', true );
	$url = get_post_meta( $post->ID, '_media_url', true );
	$thumbnail = get_post_meta( $post->ID, '_media_thumbnail', true );
	$approvato = get_post_meta( $post->ID, '_media_approvato', true );
	?>
	<div style="background:#f0f6fc; padding:20px; border-left:4px solid #2271b1; margin-bottom:20px;">
		<p><strong>Istruzioni:</strong> Scegli se inserire una Foto o un Video (YouTube/Vimeo). Incolla l'URL. Spunta "Approvato" per mostrarlo nella Galleria Pubblica.</p>
	</div>

	<table class="form-table">
		<tr>
			<th><label for="_media_tipo"><?php _e( 'Tipo di Media', 'santagatesi' ); ?></label></th>
			<td>
				<select name="_media_tipo" id="_media_tipo" class="regular-text">
					<option value="foto" <?php selected( $tipo, 'foto' ); ?>>Foto</option>
					<option value="video" <?php selected( $tipo, 'video' ); ?>>Video</option>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="_media_url"><?php _e( 'URL Media (Immagine o Video)', 'santagatesi' ); ?></label></th>
			<td>
				<input type="url" name="_media_url" id="_media_url" value="<?php echo esc_url( $url ); ?>" class="large-text" placeholder="https://..." required>
				<p class="description">URL completo dell'immagine (es. finisce in .jpg) o del video YouTube/Vimeo.</p>
			</td>
		</tr>
		<tr>
			<th><label for="_media_thumbnail"><?php _e( 'Miniatura (Solo per Video)', 'santagatesi' ); ?></label></th>
			<td>
				<input type="url" name="_media_thumbnail" id="_media_thumbnail" value="<?php echo esc_url( $thumbnail ); ?>" class="large-text" placeholder="https://...">
				<p class="description">Opzionale. URL dell'immagine di anteprima da mostrare nella griglia prima del click. Lascia vuoto per usare l'URL Media (se foto).</p>
			</td>
		</tr>
		<tr>
			<th><label for="_media_approvato"><?php _e( 'Approvato per il Pubblico?', 'santagatesi' ); ?></label></th>
			<td>
				<label>
					<input type="checkbox" name="_media_approvato" id="_media_approvato" value="1" <?php checked( $approvato, '1' ); ?>>
					Sì, mostra nella galleria pubblica.
				</label>
			</td>
		</tr>
	</table>
	<?php
}

function santagatesi_media_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['santagatesi_media_nonce'] ) || ! wp_verify_nonce( $_POST['santagatesi_media_nonce'], 'santagatesi_media_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'update_core', $post_id ) ) {
		return;
	}

	$tipo = isset( $_POST['_media_tipo'] ) ? sanitize_text_field( $_POST['_media_tipo'] ) : 'foto';
	update_post_meta( $post_id, '_media_tipo', $tipo );

	if ( isset( $_POST['_media_url'] ) ) {
		update_post_meta( $post_id, '_media_url', esc_url_raw( $_POST['_media_url'] ) );
	}

	if ( isset( $_POST['_media_thumbnail'] ) ) {
		update_post_meta( $post_id, '_media_thumbnail', esc_url_raw( $_POST['_media_thumbnail'] ) );
	}

	$approvato = isset( $_POST['_media_approvato'] ) ? '1' : '0';
	update_post_meta( $post_id, '_media_approvato', $approvato );
}
add_action( 'save_post_santagatesi_media', 'santagatesi_media_save_meta_box' );
