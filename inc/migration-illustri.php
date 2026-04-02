<?php
/**
 * Script di Migrazione Personaggi Illustri & Fallback SEO (301)
 *
 * Script per importare da vecchi db o array e garantire i redirect 301.
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. SCRIPT DI IMPORTAZIONE AUTOMATICA
 * Eseguibile visitando: https://www.santagatesinelmondo.it/?run_stg_migration=1
 * Nota: È protetto da privilegi di amministratore.
 */
function santagatesi_run_illustri_migration() {
    // 1. Controllo base
    if ( ! isset( $_GET['run_stg_migration'] ) || $_GET['run_stg_migration'] !== '1' ) {
        return;
    }

    // 2. Sicurezza: Solo admin
    if ( ! current_user_can( 'administrator' ) ) {
        wp_die( 'Non sei autorizzato a eseguire migrazioni.' );
    }

    // 3. (ESEMPIO) Dati dal vecchio sito (sostituire con lettura DB o parse JSON/CSV reale)
    $old_data_example = array(
        array(
            'nome' => 'Tony Santagata',
            'descrizione' => 'Cantante e cabarettista noto in tutta Italia.',
            'foto_url' => 'https://www.vecchiosito.it/uploads/tonysantagata.jpg',
            'vecchio_url' => '/personaggi.php?id=12' // La vecchia stringa o permalink da reindirizzare
        ),
        array(
            'nome' => 'Antonio Ricci',
            'descrizione' => 'Celebre studioso e filantropo.',
            'foto_url' => 'https://www.vecchiosito.it/uploads/antonio.jpg',
            'vecchio_url' => '/personaggi.php?id=15'
        ),
    );

    $count = 0;

    foreach ( $old_data_example as $personaggio ) {

        // Controllo se esiste già tramite il vecchio URL per non duplicare
        $esiste = get_posts(array(
            'post_type' => 'personaggi_illustri',
            'meta_key'  => '_vecchio_url',
            'meta_value'=> $personaggio['vecchio_url']
        ));

        if ( ! empty( $esiste ) ) {
            continue; // Salta se l'ho già importato
        }

        // A. Crea il Post (Custom Post Type)
        $post_data = array(
            'post_title'    => wp_strip_all_tags( $personaggio['nome'] ),
            'post_status'   => 'publish',
            'post_type'     => 'personaggi_illustri',
            'post_author'   => get_current_user_id()
        );
        $post_id = wp_insert_post( $post_data );

        if ( ! is_wp_error( $post_id ) ) {

            // B. Salva la descrizione custom
            update_post_meta( $post_id, '_illustri_descrizione', wp_kses_post( $personaggio['descrizione'] ) );

            // Imposta lo stato visibile di default per il frontend
            update_post_meta( $post_id, '_visibilita_frontend', '1' );

            // C. Salva il VECCHIO URL per il redirect SEO 301
            update_post_meta( $post_id, '_vecchio_url', sanitize_text_field( $personaggio['vecchio_url'] ) );

            // D. Importazione dell'Immagine
            // Usa 'media_sideload_image' per scaricarla fisicamente dentro i media di WordPress
            if ( ! empty( $personaggio['foto_url'] ) ) {
                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/image.php' );

                // Sideload scarica l'immagine e restituisce l'URL finale o l'ID (se pasiamo 'id')
                $img_id = media_sideload_image( $personaggio['foto_url'], $post_id, $personaggio['nome'], 'id' );

                if ( ! is_wp_error( $img_id ) ) {
                    // Nel nostro sistema i template pescano da get_post_meta('_illustri_foto')
                    $new_img_url = wp_get_attachment_url( $img_id );
                    update_post_meta( $post_id, '_illustri_foto', $new_img_url );

                    // La setta anche come featured image nativa per sicurezza/condivisioni Facebook
                    set_post_thumbnail( $post_id, $img_id );
                }
            }

            $count++;
        }
    }

    echo "<h1>Migrazione completata.</h1>";
    echo "<p>Importati {$count} nuovi personaggi illustri.</p>";
    echo "<a href='" . admin_url('edit.php?post_type=personaggi_illustri') . "'>Torna alla dashboard</a>";
    die();
}
add_action( 'template_redirect', 'santagatesi_run_illustri_migration', 1 );


/**
 * 2. MAPPATURA SEO & REDIRECT 301 AUTOMATICO
 *
 * Agganciato su template_redirect. Controlla se l'URL richiesto dal visitatore
 * (o da GoogleBot) corrisponde a uno dei vecchi URL salvati nel meta `_vecchio_url`.
 */
function santagatesi_seo_fallback_redirect() {

    // Ignoriamo le chiamate admin o ajax
    if ( is_admin() || wp_doing_ajax() ) {
        return;
    }

    // Ricostruiamo l'URI che l'utente sta cercando di visitare (es. /personaggi.php?id=12)
    $requested_url = $_SERVER['REQUEST_URI'];

    // Evitiamo query inutili in homepage
    if ( $requested_url === '/' || empty( $requested_url ) ) {
        return;
    }

    // Cerchiamo se questo esatto URL è registrato nel database come _vecchio_url
    // Cache array approach is better if huge, but direct meta query is fine for < 1000 items on a 404 page
    $args = array(
        'post_type'      => 'personaggi_illustri',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => '_vecchio_url',
                'value'   => $requested_url,
                'compare' => '='
            )
        )
    );

    $old_posts = get_posts( $args );

    if ( ! empty( $old_posts ) ) {
        // Se c'è un match, estraiamo l'ID del nuovo post di WordPress
        $new_post_id = $old_posts[0]->ID;
        $new_permalink = get_permalink( $new_post_id );

        // Eseguiamo il redirect permanente 301 verso il nuovo link SEO Friendly
        wp_redirect( $new_permalink, 301 );
        exit;
    }
}
add_action( 'template_redirect', 'santagatesi_seo_fallback_redirect', 5 );