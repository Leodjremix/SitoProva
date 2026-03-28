<?php
/**
 * Theme Setup and Site Architecture
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Setup Defaults
 */
function santagatesi_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'santagatesi' ),
		'footer'  => esc_html__( 'Footer Menu', 'santagatesi' ),
	) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'custom-logo', array( 'height' => 250, 'width' => 250, 'flex-width' => true, 'flex-height' => true ) );
}
add_action( 'after_setup_theme', 'santagatesi_setup' );

/**
 * Custom Theme Initialization Script (Advanced Layouts)
 */
function santagatesi_site_architecture_setup() {
    // V2 Check: runs only once to scaffold the new frontend dashboard pages and flush rules.
    if ( get_option( 'santagatesi_architecture_v2_setup_complete' ) ) {
        return;
    }

    $created_pages = array();

    // 1. Definisci le Pagine Principali con Blocchi Nativi Gutenberg Complessi
    $parent_pages = array(
        'Chi Siamo' => array(
            'slug' => 'chi-siamo',
            'content' => '<!-- wp:heading {"textAlign":"center","level":1} --><h1 class="wp-block-heading has-text-align-center">Associazione Santagatesi nel Mondo</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center","fontSize":"large"} --><p class="has-text-align-center has-large-font-size">Un ponte vitale tra la nostra amata Sant\'Agata di Puglia e i Santagatesi sparsi per il mondo. L\'Associazione nasce dalla volontà di mantenere vive le radici, le tradizioni e il legame indissolubile con il nostro paese d\'origine.</p><!-- /wp:paragraph -->'
        ),
        'Santagatesi Illustri' => array(
            'slug' => 'santagatesi-illustri',
            'content' => '<!-- wp:heading {"textAlign":"center","level":1} --><h1 class="wp-block-heading has-text-align-center">Santagatesi Illustri</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Le figure storiche che hanno dato lustro al nostro paese.</p><!-- /wp:paragraph --><!-- wp:columns --> <div class="wp-block-columns"><!-- wp:column --> <div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_stylesheet_directory_uri() . '/images/placeholder-illustre.jpg" alt="Personaggio 1"/></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Toni Santagata</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Cantautore, cabarettista e attore italiano, indimenticabile voce di Sant\'Agata.</p><!-- /wp:paragraph --></div> <!-- /wp:column --> <!-- wp:column --> <div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_stylesheet_directory_uri() . '/images/placeholder-illustre.jpg" alt="Personaggio 2"/></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Enzo Del Re</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Figura di spicco e orgoglio per tutta la comunità.</p><!-- /wp:paragraph --></div> <!-- /wp:column --></div> <!-- /wp:columns -->'
        ),
        'Archivio Storico' => array(
            'slug' => 'archivio-storico',
            'content' => '<!-- wp:heading {"textAlign":"center","level":1} --><h1 class="wp-block-heading has-text-align-center">Archivio Storico</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Documenti, racconti e memorie del nostro passato.</p><!-- /wp:paragraph --><!-- wp:columns --> <div class="wp-block-columns"><!-- wp:column --> <div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Il Castello Imperiale</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Testo storico in aggiornamento sulla rocca fortificata...</p><!-- /wp:paragraph --></div> <!-- /wp:column --> <!-- wp:column --> <div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Le Antiche Chiese</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Memorie sulle strutture religiose storiche...</p><!-- /wp:paragraph --></div> <!-- /wp:column --></div> <!-- /wp:columns -->'
        ),
        'Foto-Video Gallery' => array(
            'slug' => 'foto-video-gallery',
            'content' => '<!-- wp:heading {"textAlign":"center","level":1} --><h1 class="wp-block-heading has-text-align-center">Galleria Multimediale</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Le immagini e i video più belli di Sant\'Agata.</p><!-- /wp:paragraph --><!-- wp:gallery {"linkTo":"none"} --><figure class="wp-block-gallery has-nested-images columns-default is-cropped"><!-- wp:image --><figure class="wp-block-image"><img src="' . get_stylesheet_directory_uri() . '/images/gallery-placeholder.jpg" alt="Panorama"/></figure><!-- /wp:image --><!-- wp:image --><figure class="wp-block-image"><img src="' . get_stylesheet_directory_uri() . '/images/gallery-placeholder2.jpg" alt="Centro Storico"/></figure><!-- /wp:image --></figure><!-- /wp:gallery --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Video d\'Epoca</h2><!-- /wp:heading --><!-- wp:html --><div class="tonal-panel bg-[var(--color-surface-container-low)] p-6 rounded-xl text-center"><p class="mb-4">Per visualizzare i documentari YouTube è necessario accettare i Cookie.</p><button class="accept-cookies-btn btn btn-primary bg-[#FF0000] text-white">Sblocca Video</button></div><!-- /wp:html -->'
        ),
        'Produzioni' => array(
            'slug' => 'produzioni',
            'content' => '<!-- wp:heading {"textAlign":"center","level":1} --><h1 class="wp-block-heading has-text-align-center">Produzioni Editoriali e TV</h1><!-- /wp:heading --><!-- wp:list --><ul><li><strong>Artemisium Web-TV:</strong> Copertura degli eventi locali in diretta.</li><li><strong>Il Giornale di Sant\'Agata:</strong> Pubblicazione mensile storica.</li><li><strong>Documentari Culturali:</strong> Tradizioni e folklore.</li></ul><!-- /wp:list -->'
        ),
        'Utilità e Links' => array(
            'slug' => 'utilita-e-links',
            'content' => '<!-- wp:heading {"textAlign":"center","level":1} --><h1 class="wp-block-heading has-text-align-center">Link Utili</h1><!-- /wp:heading --><!-- wp:columns --> <div class="wp-block-columns"><!-- wp:column --> <div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Istituzioni</h3><!-- /wp:heading --><!-- wp:list --><ul><li><a href="#">Comune di Sant\'Agata di Puglia</a></li><li><a href="#">Pro Loco</a></li></ul><!-- /wp:list --></div> <!-- /wp:column --> <!-- wp:column --> <div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Trasporti</h3><!-- /wp:heading --><!-- wp:list --><ul><li><a href="#">Orari Autobus</a></li><li><a href="#">Come Raggiungerci</a></li></ul><!-- /wp:list --></div> <!-- /wp:column --></div> <!-- /wp:columns -->'
        ),
        'Piazza Affari' => array(
            'slug' => 'piazza-affari',
            'content' => '<!-- wp:heading {"textAlign":"center","level":1} --><h1 class="wp-block-heading has-text-align-center">Bacheca Annunci (Piazza Affari)</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Spazio dedicato allo scambio, cerco/offro lavoro e immobili tra i Santagatesi.</p><!-- /wp:paragraph --><!-- wp:html --><div class="bg-[var(--color-surface-container-low)] p-6 rounded-xl border border-gray-200 mt-8"><h3 class="text-xl font-bold mb-2">Vendesi Casa nel Centro Storico</h3><p class="text-sm text-gray-600 mb-2">Data: 10/10/2023</p><p>Vendesi abitazione ristrutturata in Vico V. Emanuele. Contattare ore pasti.</p></div><!-- /wp:html -->'
        ),
        'Contatti' => array(
            'slug' => 'contatti',
            'content' => '<!-- wp:heading {"textAlign":"center","level":1} --><h1 class="wp-block-heading has-text-align-center">Contattaci</h1><!-- /wp:heading --><!-- wp:columns --> <div class="wp-block-columns"><!-- wp:column --> <div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">I nostri recapiti</h3><!-- /wp:heading --><!-- wp:paragraph --><p><strong>Indirizzo:</strong> Via G. Garibaldi, 44 - 71028 Sant\'Agata di Puglia (FG)</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><strong>Email:</strong> redazione@santagatesinelmondo.it</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><strong>Telefono:</strong> +39 328 4595122 / +39 345 9555117</p><!-- /wp:paragraph --></div> <!-- /wp:column --> <!-- wp:column --> <div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Modulo di Contatto</h3><!-- /wp:heading --><!-- wp:paragraph --><p><em>[Contact form shortcode goes here - es. WPForms id="1"]</em></p><!-- /wp:paragraph --></div> <!-- /wp:column --></div> <!-- /wp:columns -->'
        ),
        'Privacy Policy' => array(
            'slug' => 'privacy-policy',
            'content' => '<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Privacy Policy</h1><!-- /wp:heading --><!-- wp:paragraph --><p>La presente Privacy Policy descrive le modalità di gestione dei dati personali degli utenti del sito Santagatesi nel Mondo, in ottemperanza al Regolamento UE 2016/679 (GDPR).</p><!-- /wp:paragraph --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Titolare del Trattamento</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Associazione Santagatesi nel Mondo, Via G. Garibaldi 44, Sant\'Agata di Puglia.</p><!-- /wp:paragraph -->'
        ),
        'Cookie Policy' => array(
            'slug' => 'cookie-policy',
            'content' => '<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Cookie Policy</h1><!-- /wp:heading --><!-- wp:paragraph --><p>Il nostro sito web utilizza i Cookie per migliorare l\'esperienza dell\'utente. Questo include Cookie tecnici necessari al funzionamento del sito e Cookie di terze parti per servizi come YouTube e Webcam live.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Per gestire le tue preferenze, utilizza il banner presente a fondo pagina.</p><!-- /wp:paragraph -->'
        ),
    );

    foreach ( $parent_pages as $page_title => $data ) {
        // Find existing page or create
        $page_check = get_page_by_path( $data['slug'] );

        $page_args = array(
            'post_type'    => 'page',
            'post_title'   => $page_title,
            'post_name'    => $data['slug'],
            'post_content' => $data['content'],
            'post_status'  => 'publish',
            'post_author'  => 1,
        );

        if ( isset( $page_check->ID ) ) {
            // Update existing placeholder content
            $page_args['ID'] = $page_check->ID;
            wp_update_post( $page_args );
            $created_pages[$page_title] = $page_check->ID;
        } else {
            $page_id = wp_insert_post( $page_args );
            $created_pages[$page_title] = $page_id;
        }
    }

    // 2. Create Child Pages and Private Dashboard Pages
    $child_pages = array(
        'Redazione' => array(
            'slug'   => 'redazione',
            'parent' => $created_pages['Chi Siamo'],
            'content' => '<!-- wp:heading {"textAlign":"center","level":1} --><h1 class="wp-block-heading has-text-align-center">La Redazione</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Il team dietro Artemisium Web TV e il portale.</p><!-- /wp:paragraph --><!-- wp:columns --> <div class="wp-block-columns"><!-- wp:column {"className":"tonal-panel bg-white p-6 rounded-2xl text-center border shadow-sm"} --> <div class="wp-block-column tonal-panel bg-white p-6 rounded-2xl text-center border shadow-sm"><!-- wp:image {"align":"center","width":120,"height":120,"sizeSlug":"thumbnail","linkDestination":"none","className":"is-style-rounded"} --><figure class="wp-block-image aligncenter size-thumbnail is-resized is-style-rounded"><img src="' . get_stylesheet_directory_uri() . '/images/avatar-1.jpg" alt="Samantha Berardino" width="120" height="120"/></figure><!-- /wp:image --><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="wp-block-heading has-text-align-center">Samantha Berardino</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center","textColor":"vivid-red"} --><p class="has-text-align-center has-vivid-red-color has-text-color"><strong>Direttore Responsabile</strong></p><!-- /wp:paragraph --></div> <!-- /wp:column --> <!-- wp:column {"className":"tonal-panel bg-white p-6 rounded-2xl text-center border shadow-sm"} --> <div class="wp-block-column tonal-panel bg-white p-6 rounded-2xl text-center border shadow-sm"><!-- wp:image {"align":"center","width":120,"height":120,"sizeSlug":"thumbnail","linkDestination":"none","className":"is-style-rounded"} --><figure class="wp-block-image aligncenter size-thumbnail is-resized is-style-rounded"><img src="' . get_stylesheet_directory_uri() . '/images/avatar-2.jpg" alt="Staff" width="120" height="120"/></figure><!-- /wp:image --><!-- wp:heading {"textAlign":"center","level":3} --><h3 class="wp-block-heading has-text-align-center">Staff Editoriale</h3><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Redattori e Collaboratori</p><!-- /wp:paragraph --></div> <!-- /wp:column --></div> <!-- /wp:columns -->'
        ),
        'Area Redazione (News)' => array(
            'slug'   => 'area-redazione',
            'parent' => 0,
            'content' => '<!-- wp:shortcode -->[form_inserimento_news]<!-- /wp:shortcode -->'
        ),
        'Area Redazione (Eventi)' => array(
            'slug'   => 'area-redazione-eventi',
            'parent' => 0,
            'content' => '<!-- wp:shortcode -->[form_inserimento_evento]<!-- /wp:shortcode -->'
        ),
        'Impostazioni Front-end' => array(
            'slug'   => 'impostazioni-sito',
            'parent' => 0,
            'content' => '<!-- wp:shortcode -->[form_impostazioni_sito]<!-- /wp:shortcode -->'
        ),
    );

    foreach ( $child_pages as $page_title => $data ) {
        $page_check = get_page_by_path( $data['slug'] );
        $page_args = array(
            'post_type'    => 'page',
            'post_title'   => $page_title,
            'post_name'    => $data['slug'],
            'post_content' => $data['content'],
            'post_status'  => 'publish',
            'post_author'  => 1,
            'post_parent'  => $data['parent']
        );

        if ( isset( $page_check->ID ) ) {
            $page_args['ID'] = $page_check->ID;
            wp_update_post( $page_args );
        } else {
            wp_insert_post( $page_args );
        }
    }

    // 3. Rename Default Category to "Artemisium News"
    $default_cat_id = get_option( 'default_category' );
    if ( $default_cat_id ) {
        wp_update_term( $default_cat_id, 'category', array(
            'name' => 'Artemisium News',
            'slug' => 'artemisium-news'
        ) );
    }

    // 4. Create Additional Historical Categories
    $categories_to_create = array( 'Cultura', 'Sport', 'Primo Piano', 'Videonotiziario' );
    foreach ( $categories_to_create as $cat_name ) {
        if ( ! term_exists( $cat_name, 'category' ) ) {
            wp_insert_term( $cat_name, 'category' );
        }
    }

    // Update permalink structure one time only for the new CPTs and pages
    flush_rewrite_rules( false );

    // Mark advanced setup v2 as complete
    update_option( 'santagatesi_architecture_v2_setup_complete', true );
}
add_action( 'init', 'santagatesi_site_architecture_setup' );

/**
 * Custom Comment Walker per Guestbook (Stile "The Luminous Horizon")
 */
