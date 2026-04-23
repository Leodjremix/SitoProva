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

    // Parametri connessione al DB legacy (Stesso Server, Stesso Utente, Stessa Password, ma NOME DB DIVERSO)
    // Sostituire con il nome esatto del DB (es. Sql123456_1)
    $legacy_db_name = 'INSERISCI_QUI_IL_NOME_DEL_VECCHIO_DB_ES_SQL12345_1';

    // Creiamo una nuova istanza nativa della classe wpdb per il database esterno
    $legacy_db = new wpdb( DB_USER, DB_PASSWORD, $legacy_db_name, DB_HOST );

    // Controllo errori di connessione al db esterno
    if ( ! empty( $legacy_db->error ) ) {
        wp_die( 'Impossibile connettersi al vecchio database: ' . esc_html( $legacy_db->error->get_error_message() ) );
    }

    // A. Lettura dei vecchi articoli dal SECONDO DATABASE
    // Troviamo l'ultimo ID importato con successo per far avanzare la query batch (100 in 100)
    $last_imported_id = get_option( 'stg_last_imported_news_id', 0 );

    // Usa $legacy_db->get_results invece di $wpdb
    $old_articles = $legacy_db->get_results( $legacy_db->prepare(
        "SELECT * FROM tbl_articoli WHERE id_art > %d ORDER BY id_art ASC LIMIT 100",
        $last_imported_id
    ), ARRAY_A );

    if ( empty( $old_articles ) ) {
        wp_die( 'La query è vuota: nessun articolo da importare rimasto o la tabella `tbl_articoli` non esiste nel db specificato.' );
    }

    $count = 0;
    $updated = 0;

    foreach ( $old_articles as $art ) {

        // Risoluzione problema di codifica caratteri tipico dei DB Classic ASP (Windows-1252/ISO-8859-1 -> UTF-8)
        // L'utilizzo di 'auto' fallisce per i DB europei, bisogna specificare esplicitamente Windows-1252/ISO.
        $safe_title   = mb_convert_encoding( $art['titolo'], 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' );
        $safe_content = mb_convert_encoding( $art['articolo'], 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' );
        $safe_sotto   = mb_convert_encoding( $art['sotto'], 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' );
        $safe_dida1   = isset($art['dida1']) ? mb_convert_encoding( $art['dida1'], 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' ) : '';
        $safe_dida2   = isset($art['dida2']) ? mb_convert_encoding( $art['dida2'], 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' ) : '';
        $safe_dida3   = isset($art['dida3']) ? mb_convert_encoding( $art['dida3'], 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' ) : '';

        // B. Controllo Duplicati nel DB WordPress
        $esiste = get_posts(array(
            'post_type'  => 'post',
            'meta_key'   => '_old_id_art',
            'meta_value' => $art['id_art'],
            'fields'     => 'ids' // Query leggera
        ));

        // C. Gestione Contenuto e Allegati
        $final_content = $safe_content;

        // Cerca gli allegati associati a questo articolo nel DB LEGACY (idd = id_art e tipo = 1)
        $allegati = $legacy_db->get_results( $legacy_db->prepare(
            "SELECT * FROM tbl_allegati WHERE idd = %d AND tipo = 1",
            $art['id_art']
        ), ARRAY_A );

        if ( ! empty( $allegati ) ) {
            $final_content .= '<div class="allegati-box mt-8 p-6 bg-blue-50 border border-blue-100 rounded-xl">';
            $final_content .= '<h4 class="text-xl font-bold text-blue-900 mb-4">Allegati da Scaricare:</h4>';
            $final_content .= '<ul class="list-disc pl-5 space-y-2">';
            foreach ( $allegati as $all ) {
                $nome_file = isset($all['nome_file']) ? $all['nome_file'] : $all['file'];
                // Assicuriamoci che anche i nomi file allegati siano ben encodati
                $safe_nome_file = mb_convert_encoding( $nome_file, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' );
                $allegato_url = home_url( '/public/allegati/' . $safe_nome_file );
                $final_content .= '<li><a href="' . esc_url( $allegato_url ) . '" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:underline font-semibold">' . esc_html( $safe_nome_file ) . '</a></li>';
            }
            $final_content .= '</ul></div>';
        }

                // Autore mapping
        $author_id = get_current_user_id();
        $author_name = '';
        if ( isset( $art['autore'] ) ) {
            $author_row = $legacy_db->get_row( $legacy_db->prepare( "SELECT * FROM tbl_autori WHERE id_autore = %d", intval( $art['autore'] ) ), ARRAY_A );
            if ( ! empty( $author_row ) ) {
                if ( isset( $author_row['nome'] ) ) {
                    $author_name = $author_row['nome'];
                } elseif ( isset( $author_row['autore'] ) ) {
                    $author_name = $author_row['autore'];
                } else {
                    $author_name = reset( $author_row );
                }
            }
        }

        $post_data = array(
            'post_title'    => $safe_title,
            'post_content'  => wp_kses_post( $final_content ),
            'post_status'   => 'publish',
            'post_type'     => 'post',
            'post_date'     => date('Y-m-d H:i:s', strtotime($art['data'])),
            'post_author'   => get_current_user_id()
        );

        if ( ! empty( $esiste ) ) {
            // L'articolo esiste già. AGGIORNIAMO il contenuto invece di saltarlo.
            // Questo risolve il problema dei post importati in precedenza che risultavano vuoti per via dell'errore di codifica.
            $post_id = $esiste[0];
            $post_data['ID'] = $post_id; // Passiamo l'ID esistente per forzare l'aggiornamento
            wp_update_post( $post_data );
            $updated++;
        } else {
            // È un nuovo post, crealo.
            $post_id = wp_insert_post( $post_data );
            if ( ! is_wp_error( $post_id ) ) {
                $count++;
            }
        }

        if ( ! is_wp_error( $post_id ) ) {

            // E. Mappatura Dati Specifici nei Custom Fields
                        // Categoria mapping
            if ( isset( $art['catg'] ) && ! empty( $art['catg'] ) ) {
                $term = term_exists( $art['catg'], 'category' );
                if ( ! $term ) {
                    $term = wp_insert_term( $art['catg'], 'category' );
                }
                if ( ! is_wp_error( $term ) && isset( $term['term_taxonomy_id'] ) ) {
                    wp_set_post_categories( $post_id, array( $term['term_taxonomy_id'] ) );
                }
            }

            update_post_meta( $post_id, '_old_id_art', intval( $art['id_art'] ) );
            update_post_meta( $post_id, '_news_subtitle', sanitize_textarea_field( $safe_sotto ) );

            // Gestione didascalie
            update_post_meta( $post_id, '_news_dida_1', sanitize_text_field( $safe_dida1 ) );
            update_post_meta( $post_id, '_news_dida_2', sanitize_text_field( $safe_dida2 ) );
            update_post_meta( $post_id, '_news_dida_3', sanitize_text_field( $safe_dida3 ) );

                        // Salva autore
            if ( ! empty( $author_name ) ) {
                update_post_meta( $post_id, '_news_author_name', sanitize_text_field( $author_name ) );
            }

            // F. Importazione Immagine in Evidenza (Featured Image)
            if ( ! empty( $art['img1'] ) ) {
                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/image.php' );

                $img_url = home_url( '/public/news/' . ltrim( $art['img1'], '/' ) );
                $img_id = media_sideload_image( $img_url, $post_id, $art['titolo'], 'id' );

                if ( ! is_wp_error( $img_id ) ) {
                    set_post_thumbnail( $post_id, $img_id );
                }
            }

            // G. Importazione Immagine Extra
            if ( ! empty( $art['img2'] ) ) {
                $img2_url = home_url( '/public/news/' . ltrim( $art['img2'], '/' ) );
                update_post_meta( $post_id, '_news_extra_image_path', esc_url_raw( $img2_url ) );
            } else {
                update_post_meta( $post_id, '_news_extra_image_path', '' );
            }

            // Avanzamento Batch
            update_option( 'stg_last_imported_news_id', intval( $art['id_art'] ) );

            $count++;
        }
    }

    echo "<h1>Migrazione News Completata (Batch)</h1>";
    echo "<p>Creati {$count} nuovi articoli in WordPress.</p>";
    echo "<p>Aggiornati/Riparati {$updated} articoli esistenti (Risolto bug contenuti vuoti).</p>";
    echo "<a href='" . admin_url('edit.php') . "' style='padding: 10px 20px; background: #00529B; color: white; text-decoration: none; border-radius: 5px;'>Torna alla Bacheca</a>";
    die();
}
add_action( 'template_redirect', 'santagatesi_run_news_migration', 1 );


/**
 * 2. REDIRECT 301 SEO-SAFE (news.asp?id=X -> /nuovo-permalink/)
 *
 * Intercetta le richieste ai vecchi URL ASP e cerca il nuovo post associato
 * nel database di WordPress usando il postmeta '_old_id_art'.
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
                        'key'     => '_old_id_art',
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


/**
 * 3. SCRIPT DI ALLINEAMENTO META
 *
 * Scorre tutti i post esistenti per assicurarsi che abbiano i campi custom
 * (_news_subtitle, _news_video_url, _news_extra_image, didascalie) creati nel database,
 * anche se vuoti, per omogeneità strutturale.
 * Attivabile via: ?run_news_alignment=1
 */
function santagatesi_align_news_meta() {
    if ( ! isset( $_GET['run_news_alignment'] ) || $_GET['run_news_alignment'] !== '1' ) {
        return;
    }
    if ( ! current_user_can( 'administrator' ) ) {
        wp_die( 'Non sei autorizzato.' );
    }

    $all_posts = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids' // Molto più leggero sulla RAM
    ));

    $count = 0;
    $meta_keys_to_check = array(
        '_news_subtitle',
        '_news_video_data',
        '_news_extra_image_path',
        '_news_author_name',
        '_old_id_art',
        '_news_dida_1',
        '_news_dida_2',
        '_news_dida_3'
    );

    foreach ( $all_posts->posts as $post_id ) {
        foreach ( $meta_keys_to_check as $meta_key ) {
            // Add_post_meta restituisce false se la chiave esiste già (grazie al 4° parametro $unique=true)
            $added = add_post_meta( $post_id, $meta_key, '', true );
            if ( $added ) {
                $count++;
            }
        }
    }

    echo "<h1>Allineamento Meta Completato</h1>";
    echo "<p>Creati {$count} nuovi campi vuoti (struttura standardizzata) nei post esistenti.</p>";
    echo "<a href='" . admin_url('edit.php') . "' style='padding: 10px 20px; background: #00529B; color: white; text-decoration: none; border-radius: 5px;'>Torna alla Bacheca</a>";
    die();
}
add_action( 'template_redirect', 'santagatesi_align_news_meta', 2 );
