<?php
/**
 * Script di Migrazione Automatica: Da Classic ASP a WordPress (News)
 *
 * Si presuppone che le tabelle legacy (tbl_articoli, tbl_autori, tbl_allegati)
 * condividano lo stesso database di WordPress.
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. SCRIPT DI IMPORTAZIONE AUTOMATICA DELLE NEWS
 * Eseguibile visitando (solo come admin): https://tuosito.it/?run_news_migration=1
 */
function santagatesi_run_news_migration() {

    // Sicurezza: esegui solo se esplicitamente richiesto
    if ( ! isset( $_GET['run_news_migration'] ) || $_GET['run_news_migration'] !== '1' ) {
        return;
    }

    // Sicurezza: solo gli amministratori possono eseguire lo script
    if ( ! current_user_can( 'administrator' ) ) {
        wp_die( 'Non sei autorizzato a eseguire migrazioni.' );
    }

    global $wpdb;

    // A. Lettura dei vecchi articoli
    // Usa $wpdb->get_results per pescare dalla vecchia tabella 'tbl_articoli'
    // Limita o usa l'offset (es. LIMIT 0, 50) se hai migliaia di record per evitare timeout del server
    $old_articles = $wpdb->get_results( "SELECT * FROM tbl_articoli ORDER BY id_art ASC LIMIT 100", ARRAY_A );

    if ( empty( $old_articles ) ) {
        wp_die( 'Nessun articolo trovato in tbl_articoli o tabella inesistente.' );
    }

    $count = 0;

    foreach ( $old_articles as $art ) {

        // B. Controllo Duplicati
        // Verifichiamo se l'articolo è già stato importato cercando il vecchio ID nei meta di WP
        $esiste = get_posts(array(
            'post_type'  => 'post',
            'meta_key'   => '_vecchio_id',
            'meta_value' => $art['id_art'],
            'fields'     => 'ids' // Query leggera
        ));

        if ( ! empty( $esiste ) ) {
            continue; // L'articolo esiste già, saltiamo
        }

        // C. Gestione Contenuto e Allegati
        $content = $art['articolo'];

        // Accoda il sottotitolo all'inizio del contenuto (opzionale, formattato in corsivo)
        if ( ! empty( $art['sotto'] ) ) {
            $content = '<p class="lead italic text-lg text-gray-600 mb-6">' . wp_kses_post( $art['sotto'] ) . '</p>' . $content;
        }

        // Cerca gli allegati associati a questo articolo (idd = id_art e tipo = 1)
        // Nota: Assumi che il nome del file salvato nel db sia nella colonna 'nome_file'
        $allegati = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM tbl_allegati WHERE idd = %d AND tipo = 1",
            $art['id_art']
        ), ARRAY_A );

        if ( ! empty( $allegati ) ) {
            $content .= '<div class="allegati-box mt-8 p-6 bg-blue-50 border border-blue-100 rounded-xl">';
            $content .= '<h4 class="text-xl font-bold text-blue-900 mb-4">Allegati da Scaricare:</h4>';
            $content .= '<ul class="list-disc pl-5 space-y-2">';
            foreach ( $allegati as $all ) {
                // Genera l'URL assoluto del vecchio allegato
                // (Assicurati che la colonna del nome file si chiami 'nome_file' o cambiala di conseguenza)
                $nome_file = isset($all['nome_file']) ? $all['nome_file'] : $all['file'];
                $allegato_url = home_url( '/public/allegati/' . $nome_file );
                $content .= '<li><a href="' . esc_url( $allegato_url ) . '" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:underline font-semibold">' . esc_html( $nome_file ) . '</a></li>';
            }
            $content .= '</ul></div>';
        }

        // D. Creazione del Post in WordPress
        $post_data = array(
            'post_title'    => wp_strip_all_tags( $art['titolo'] ),
            'post_content'  => wp_kses_post( $content ),
            'post_status'   => 'publish',
            'post_type'     => 'post', // Standard WordPress News
            'post_date'     => date('Y-m-d H:i:s', strtotime($art['data'])), // Mantiene la data storica
            'post_author'   => get_current_user_id() // Oppure mappare dinamicamente da tbl_autori
        );

        $post_id = wp_insert_post( $post_data );

        if ( ! is_wp_error( $post_id ) ) {

            // E. Salva il Vecchio ID (Fondamentale per i Redirect 301)
            update_post_meta( $post_id, '_vecchio_id', intval( $art['id_art'] ) );

            // Salva anche il vecchio ID Categoria se in futuro vuoi mappare le categorie
            update_post_meta( $post_id, '_vecchio_cat', intval( $art['cat'] ) );

            // F. Importazione Immagine in Evidenza (Featured Image)
            if ( ! empty( $art['img1'] ) ) {
                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/image.php' );

                // URL assoluto dell'immagine legacy sul server
                $img_url = home_url( '/public/news/' . ltrim( $art['img1'], '/' ) );

                // media_sideload_image la scarica, crea le miniature WP e la attacca al post_id
                $img_id = media_sideload_image( $img_url, $post_id, $art['titolo'], 'id' );

                if ( ! is_wp_error( $img_id ) ) {
                    set_post_thumbnail( $post_id, $img_id );
                }
            }

            // (Opzionale) Se c'è una seconda immagine, puoi salvarla come meta e usarla nel template
            if ( ! empty( $art['img2'] ) ) {
                update_post_meta( $post_id, '_news_img2', esc_url_raw( home_url( '/public/news/' . ltrim( $art['img2'], '/' ) ) ) );
            }

            // (Opzionale) Se c'è un video, puoi salvarlo per usarlo al posto dell'immagine
            if ( ! empty( $art['video'] ) ) {
                update_post_meta( $post_id, '_news_video', esc_url_raw( home_url( '/public/video/' . ltrim( $art['video'], '/' ) ) ) );
            }

            $count++;
        }
    }

    echo "<h1>Migrazione News Completata (Batch)</h1>";
    echo "<p>Importati {$count} articoli in WordPress.</p>";
    echo "<a href='" . admin_url('edit.php') . "' style='padding: 10px 20px; background: #00529B; color: white; text-decoration: none; border-radius: 5px;'>Torna alla Bacheca</a>";
    die();
}
add_action( 'template_redirect', 'santagatesi_run_news_migration', 1 );


/**
 * 2. REDIRECT 301 SEO-SAFE (news.asp?id=X -> /nuovo-permalink/)
 *
 * Intercetta le richieste ai vecchi URL ASP e cerca il nuovo post associato
 * nel database di WordPress usando il postmeta '_vecchio_id'.
 */
function santagatesi_news_seo_redirect() {

    // Ignoriamo le chiamate admin o ajax
    if ( is_admin() || wp_doing_ajax() ) {
        return;
    }

    // Preleviamo il path dell'URL richiesto (senza dominio)
    $path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );

    // Se la pagina richiesta è il vecchio news.asp
    if ( strpos( strtolower($path), 'news.asp' ) !== false ) {

        // Se c'è il parametro ID articolo nell'URL (es. news.asp?id=123)
        if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
            $vecchio_id = intval( $_GET['id'] );

            // Cerchiamo l'articolo su WordPress tramite Meta Query
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 1,
                'post_status'    => 'publish',
                'meta_query'     => array(
                    array(
                        'key'     => '_vecchio_id',
                        'value'   => $vecchio_id,
                        'compare' => '='
                    )
                )
            );

            $migrated_posts = get_posts( $args );

            if ( ! empty( $migrated_posts ) ) {
                // Post trovato! Troviamo il nuovo permalink
                $new_permalink = get_permalink( $migrated_posts[0]->ID );

                // Eseguiamo il redirect 301 Permanente per istruire Google
                wp_redirect( $new_permalink, 301 );
                exit;
            }
        }

        // Se arrivano su news.asp senza ID (es. pagina archivio generale)
        // reindirizziamoli all'archivio blog di default di WordPress
        if ( ! isset( $_GET['id'] ) ) {
            wp_redirect( home_url('/'), 301 );
            exit;
        }
    }
}
add_action( 'template_redirect', 'santagatesi_news_seo_redirect', 5 );
