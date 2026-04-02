<?php
/**
 * Modulo Banners Hero Dinamici
 *
 * Custom Post Type nascosto per la gestione delle immagini Hero in Home Page
 * basata su trigger temporali (Date, Orari) e logiche di fallback (Default).
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Registrazione CPT "Banners Hero"
 * Visibile solo come sottomenu del Pannello Admin Custom
 */
function santagatesi_register_cpt_dynamic_hero() {
	$labels = array(
		'name'                  => _x( 'Banners Home', 'Post Type General Name', 'santagatesi' ),
		'singular_name'         => _x( 'Banner Home', 'Post Type Singular Name', 'santagatesi' ),
		'menu_name'             => __( 'Banners Dinamici (Home)', 'santagatesi' ),
		'name_admin_bar'        => __( 'Banner Home', 'santagatesi' ),
		'add_new_item'          => __( 'Aggiungi Nuovo Banner', 'santagatesi' ),
		'add_new'               => __( 'Aggiungi Nuovo', 'santagatesi' ),
		'new_item'              => __( 'Nuovo Banner', 'santagatesi' ),
		'edit_item'             => __( 'Modifica Banner', 'santagatesi' ),
		'update_item'           => __( 'Aggiorna Banner', 'santagatesi' ),
		'view_item'             => __( 'Vedi Banner', 'santagatesi' ),
		'search_items'          => __( 'Cerca Banner', 'santagatesi' ),
		'not_found'             => __( 'Nessun banner trovato', 'santagatesi' ),
	);

	$args = array(
		'label'                 => __( 'Banner Home', 'santagatesi' ),
		'description'           => __( 'Immagini e Testi dinamici per la Home Page', 'santagatesi' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ), // Il Titolo del CPT farà da "Titolo Hero"
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => 'santagatesi-admin-dashboard', // Lo agganciamo al nostro menu custom!
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'show_in_rest'          => false, // Disabilita Gutenberg
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
	register_post_type( 'stg_dynamic_hero', $args );
}
add_action( 'init', 'santagatesi_register_cpt_dynamic_hero', 0 );


/**
 * 2. Meta Box per i Trigger Temporali e Immagine
 */
function santagatesi_add_dynamic_hero_meta_boxes() {
	add_meta_box(
		'stg_hero_settings',
		__( 'Impostazioni Banner Dinamico', 'santagatesi' ),
		'santagatesi_hero_settings_callback',
		'stg_dynamic_hero',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'santagatesi_add_dynamic_hero_meta_boxes' );

function santagatesi_hero_settings_callback( $post ) {
	wp_nonce_field( 'stg_hero_nonce_action', 'stg_hero_nonce' );

    // Recupera i valori esistenti
	$subtitle = get_post_meta( $post->ID, '_hero_subtitle', true );
	$image    = get_post_meta( $post->ID, '_hero_image', true );
	$is_default = get_post_meta( $post->ID, '_hero_is_default', true );

    // Date Trigger (Y-m-d)
    $date_start = get_post_meta( $post->ID, '_hero_date_start', true );
    $date_end   = get_post_meta( $post->ID, '_hero_date_end', true );

    // Time Trigger (H:i)
    $time_start = get_post_meta( $post->ID, '_hero_time_start', true );
    $time_end   = get_post_meta( $post->ID, '_hero_time_end', true );

    // Se è un nuovo post, prepariamo i campi vuoti
    if(empty($time_start)) $time_start = '';
    if(empty($time_end)) $time_end = '';
    if(empty($date_start)) $date_start = '';
    if(empty($date_end)) $date_end = '';
	?>
    <style>
        .stg-hero-table th { width: 25%; text-align: left; vertical-align: top; padding: 15px 10px; border-bottom: 1px solid #eee; }
        .stg-hero-table td { padding: 15px 10px; border-bottom: 1px solid #eee; }
        .stg-hero-box { background: #fafafa; border-left: 4px solid #2271b1; padding: 15px; margin-bottom: 20px; }
        .stg-hero-box-warning { background: #fdf2f2; border-left: 4px solid #d63638; padding: 15px; margin-bottom: 20px; }
    </style>

	<table class="form-table stg-hero-table">
        <!-- 1. Contenuto Base -->
        <tr style="background: #f0f0f1;"><td colspan="2"><h3 style="margin:0;">1. Contenuti Visivi</h3></td></tr>

        <tr>
            <th><label for="hero_image"><strong><?php esc_html_e( 'Immagine di Sfondo', 'santagatesi' ); ?> *</strong></label></th>
            <td>
                <input type="text" name="hero_image" id="hero_image" value="<?php echo esc_attr( $image ); ?>" class="regular-text" style="width: 100%; max-width: 400px;" placeholder="URL Immagine..." required>
                <input type="button" class="button button-secondary stg-upload-btn" data-target="hero_image" value="Scegli Immagine">
                <?php if( $image ): ?>
                    <div style="margin-top:10px; max-width: 250px; border: 1px solid #ccc; border-radius: 4px; overflow:hidden;">
                        <img src="<?php echo esc_url( $image ); ?>" alt="Anteprima" style="width: 100%; display: block;" id="hero_image_preview">
                    </div>
                <?php else: ?>
                    <div id="hero_image_preview_container" style="display:none; margin-top:10px; max-width: 250px; border: 1px solid #ccc; border-radius: 4px; overflow:hidden;">
                        <img src="" alt="Anteprima" style="width: 100%; display: block;" id="hero_image_preview">
                    </div>
                <?php endif; ?>
            </td>
        </tr>

        <tr>
            <th><label for="hero_subtitle"><strong><?php esc_html_e( 'Sottotitolo', 'santagatesi' ); ?></strong></label></th>
            <td>
                <textarea name="hero_subtitle" id="hero_subtitle" rows="3" style="width: 100%; max-width: 500px;" placeholder="Il titolo principale è il nome che dai in alto a questo banner. Inserisci qui il testo secondario..."><?php echo esc_textarea( $subtitle ); ?></textarea>
            </td>
        </tr>

        <!-- 2. Comportamento e Trigger -->
        <tr style="background: #f0f0f1;"><td colspan="2"><h3 style="margin:0;">2. Regole di Visualizzazione</h3></td></tr>

        <tr>
            <th><label><strong><?php esc_html_e( 'Stato di Default (Fallback)', 'santagatesi' ); ?></strong></label></th>
            <td>
                <label>
                    <input type="checkbox" name="hero_is_default" id="hero_is_default" value="1" <?php checked( $is_default, '1' ); ?>>
                    Imposta questo banner come <strong>Predefinito</strong>.
                </label>
                <p class="description">Se attivato, questo banner andrà online quando non ci sono eventi (Date) o orari specifici attivi. Attivandolo qui, disattiverà automaticamente il Default sugli altri banner.</p>
            </td>
        </tr>

        <tr id="row_trigger_date">
            <th><label><strong><?php esc_html_e( 'Priorità 1: Trigger per Data', 'santagatesi' ); ?></strong></label></th>
            <td>
                <div class="stg-hero-box">
                    <p style="margin-top:0;"><strong>Dal:</strong> <input type="date" name="hero_date_start" value="<?php echo esc_attr( $date_start ); ?>">
                       <strong>Al:</strong> <input type="date" name="hero_date_end" value="<?php echo esc_attr( $date_end ); ?>">
                    </p>
                    <p class="description">Es: Dal 20 Dicembre al 6 Gennaio. Durante questi giorni, questo banner batterà tutte le altre regole e orari.</p>
                </div>
            </td>
        </tr>

        <tr id="row_trigger_time">
            <th><label><strong><?php esc_html_e( 'Priorità 2: Trigger Orario (Ricorrente)', 'santagatesi' ); ?></strong></label></th>
            <td>
                <div class="stg-hero-box">
                    <p style="margin-top:0;"><strong>Dalle:</strong> <input type="time" name="hero_time_start" value="<?php echo esc_attr( $time_start ); ?>">
                       <strong>Alle:</strong> <input type="time" name="hero_time_end" value="<?php echo esc_attr( $time_end ); ?>">
                    </p>
                    <p class="description">Es: Dalle 19:00 alle 06:00 (Banner Notturno). Si ripete ogni giorno, se non ci sono date attive in sovrascrittura.</p>
                </div>
            </td>
        </tr>
    </table>

    <script>
        // Media Uploader Script
        jQuery(document).ready(function($){
            var custom_uploader;
            $('.stg-upload-btn').click(function(e) {
                e.preventDefault();
                var target_input = $('#' + $(this).data('target'));
                var target_preview = $('#' + $(this).data('target') + '_preview');
                var target_container = $('#' + $(this).data('target') + '_preview_container');

                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }

                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: 'Scegli Immagine Hero',
                    button: { text: 'Usa questa immagine' },
                    multiple: false
                });

                custom_uploader.on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    target_input.val(attachment.url);
                    if(target_preview.length) {
                        target_preview.attr('src', attachment.url);
                        target_container.show();
                    }
                });

                custom_uploader.open();
            });

            // UI Logic: Se è Default, sbiadisci le regole (perché verranno ignorate dal concetto stesso di default)
            $('#hero_is_default').on('change', function(){
                if($(this).is(':checked')){
                    $('#row_trigger_date, #row_trigger_time').css('opacity', '0.4');
                } else {
                    $('#row_trigger_date, #row_trigger_time').css('opacity', '1');
                }
            }).trigger('change');
        });
    </script>
	<?php
}

/**
 * 3. Salvataggio Sicuro & Generazione della Cache Veloce
 *
 * Salvare regole temporali in post meta è pesantissimo da interrogare in frontend.
 * Il modo più efficiente è usare l'hook 'save_post' per estrarre tutti i banner attivi,
 * calcolare e generare un Array Cache pulito dentro wp_options ('stg_hero_rules_cache').
 * La Homepage leggerà sempre e solo QUESTA opzione (1 query leggerissima).
 */
function santagatesi_save_dynamic_hero( $post_id ) {
	if ( ! isset( $_POST['stg_hero_nonce'] ) || ! wp_verify_nonce( $_POST['stg_hero_nonce'], 'stg_hero_nonce_action' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( 'stg_dynamic_hero' !== $_POST['post_type'] ) { return; }
	if ( ! current_user_can( 'update_core' ) ) { return; }

    // Salva Dati Base
	if ( isset( $_POST['hero_image'] ) ) { update_post_meta( $post_id, '_hero_image', esc_url_raw( $_POST['hero_image'] ) ); }
	if ( isset( $_POST['hero_subtitle'] ) ) { update_post_meta( $post_id, '_hero_subtitle', sanitize_textarea_field( $_POST['hero_subtitle'] ) ); }
    if ( isset( $_POST['hero_date_start'] ) ) { update_post_meta( $post_id, '_hero_date_start', sanitize_text_field( $_POST['hero_date_start'] ) ); }
    if ( isset( $_POST['hero_date_end'] ) ) { update_post_meta( $post_id, '_hero_date_end', sanitize_text_field( $_POST['hero_date_end'] ) ); }
    if ( isset( $_POST['hero_time_start'] ) ) { update_post_meta( $post_id, '_hero_time_start', sanitize_text_field( $_POST['hero_time_start'] ) ); }
    if ( isset( $_POST['hero_time_end'] ) ) { update_post_meta( $post_id, '_hero_time_end', sanitize_text_field( $_POST['hero_time_end'] ) ); }

	// Logica Esclusiva del Default: Se questo è 1, tutti gli altri diventano 0
	$is_default = isset( $_POST['hero_is_default'] ) ? '1' : '0';
    if ( $is_default === '1' ) {
        // Togli il default a tutti gli altri banner
        global $wpdb;
        $wpdb->query( $wpdb->prepare(
            "UPDATE $wpdb->postmeta SET meta_value = '0' WHERE meta_key = '_hero_is_default' AND post_id != %d",
            $post_id
        ) );
    }
	update_post_meta( $post_id, '_hero_is_default', $is_default );

    // --- RICOSTRUZIONE DELLA CACHE PER IL FRONT-END ---
    santagatesi_rebuild_hero_cache();
}
add_action( 'save_post', 'santagatesi_save_dynamic_hero' );
add_action( 'deleted_post', 'santagatesi_rebuild_hero_cache' ); // Ricostruisci anche se elimino un banner

/**
 * Genera l'array cache salvato in `wp_options`.
 */
function santagatesi_rebuild_hero_cache() {
    $heroes_query = new WP_Query([
        'post_type'      => 'stg_dynamic_hero',
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    ]);

    $cache_rules = array(
        'date_triggers' => array(), // Priorità 1
        'time_triggers' => array(), // Priorità 2
        'default'       => null     // Priorità 3
    );

    if ( $heroes_query->have_posts() ) {
        while ( $heroes_query->have_posts() ) {
            $heroes_query->the_post();
            $id = get_the_ID();

            $payload = array(
                'id'        => $id,
                'title'     => get_the_title(),
                'subtitle'  => get_post_meta( $id, '_hero_subtitle', true ),
                'image'     => get_post_meta( $id, '_hero_image', true )
            );

            // È il Default?
            if ( get_post_meta( $id, '_hero_is_default', true ) === '1' ) {
                $cache_rules['default'] = $payload;
                continue; // Il default non ha bisogno di regole, se è default entra nel secchio del default e basta
            }

            // Ha date impostate? (Priorità 1)
            $d_start = get_post_meta( $id, '_hero_date_start', true );
            $d_end   = get_post_meta( $id, '_hero_date_end', true );
            if ( !empty($d_start) && !empty($d_end) ) {
                $payload['start'] = $d_start;
                $payload['end']   = $d_end;
                $cache_rules['date_triggers'][] = $payload;
                continue; // Se è un evento datato, non lo valutiamo per l'orario generico (mutuamente esclusivo per logica richiesta)
            }

            // Ha orari impostati? (Priorità 2)
            $t_start = get_post_meta( $id, '_hero_time_start', true );
            $t_end   = get_post_meta( $id, '_hero_time_end', true );
            if ( !empty($t_start) && !empty($t_end) ) {
                $payload['start'] = $t_start;
                $payload['end']   = $t_end;
                $cache_rules['time_triggers'][] = $payload;
            }
        }
        wp_reset_postdata();
    }

    update_option( 'santagatesi_dynamic_heroes_cache', $cache_rules );
}


/**
 * 4. Funzione Front-End: get_dynamic_hero()
 *
 * Legge l'opzione cache e decide ORA quale banner mostrare.
 * Impostiamo esplicitamente il fuso orario di Roma.
 */
function get_dynamic_hero() {
    // 1. Lettura regole cache
    $rules = get_option( 'santagatesi_dynamic_heroes_cache', false );

    // Fallback d'emergenza se il sistema non è mai stato configurato
    $fallback = array(
        'title'    => 'Santagatesi nel Mondo',
        'subtitle' => 'Un ponte tra le nostre radici e il futuro, ovunque tu sia.',
        'image'    => 'https://www.santagatesinelmondo.it/public/banner/cripta.jpg'
    );

    if ( ! $rules || ! is_array( $rules ) ) {
        return $fallback;
    }

    // 2. Imposta il fuso orario del server a Roma in modo sicuro (senza alterare la config globale in modo permanente se non serve)
    $tz = new DateTimeZone('Europe/Rome');
    $now = new DateTime('now', $tz);

    $today_date = $now->format('Y-m-d'); // Es: 2023-12-25
    $current_time = $now->format('H:i'); // Es: 19:30

    // --- PRIORITÀ 1: DATE TRIGGERS ---
    if ( !empty( $rules['date_triggers'] ) ) {
        foreach ( $rules['date_triggers'] as $banner ) {
            // Controlla se la data odierna cade in mezzo (compresi start ed end)
            if ( $today_date >= $banner['start'] && $today_date <= $banner['end'] ) {
                return $banner; // Match trovato! Restituisci e blocca.
            }
        }
    }

    // --- PRIORITÀ 2: TIME TRIGGERS ---
    if ( !empty( $rules['time_triggers'] ) ) {
        foreach ( $rules['time_triggers'] as $banner ) {
            $start = $banner['start'];
            $end = $banner['end'];

            // Logica oraria complessa: Dalle 19:00 alle 06:00 (cavallo della mezzanotte)
            if ( $start > $end ) {
                // Es: Start 19:00, End 06:00
                // Match se l'ora attuale è >= 19:00 OPPURE <= 06:00
                if ( $current_time >= $start || $current_time <= $end ) {
                    return $banner;
                }
            } else {
                // Standard: Start 08:00, End 12:00
                if ( $current_time >= $start && $current_time <= $end ) {
                    return $banner;
                }
            }
        }
    }

    // --- PRIORITÀ 3: FALLBACK DEFAULT ---
    if ( !empty( $rules['default'] ) ) {
        return $rules['default'];
    }

    return $fallback;
}
