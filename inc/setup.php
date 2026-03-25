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
    if ( get_option( 'santagatesi_advanced_architecture_setup_complete' ) ) {
        return; // Run only once for the new advanced layouts
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

    // Mark advanced setup as complete
    update_option( 'santagatesi_advanced_architecture_setup_complete', true );
}
add_action( 'init', 'santagatesi_site_architecture_setup' );

/**
 * Custom Comment Walker per Guestbook (Stile "The Luminous Horizon")
 */
function santagatesi_guestbook_comment_format( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);
    ?>
    <li <?php comment_class('tonal-panel hover:-translate-y-1 transition-transform duration-300 p-8 rounded-[var(--radius-lg)] shadow-[var(--shadow-ambient)] bg-white relative overflow-hidden group mb-8 list-none border-none'); ?> id="li-comment-<?php comment_ID() ?>">

        <div id="comment-<?php comment_ID(); ?>" class="comment-body relative z-10 flex flex-col sm:flex-row gap-6">

            <!-- Avatar Colonna -->
            <div class="comment-author vcard flex-shrink-0 mx-auto sm:mx-0">
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-full shadow-md group-hover:scale-110 transition-transform duration-500 border-4 border-[var(--color-surface-container-low)]')); ?>
            </div>

            <!-- Content Colonna -->
            <div class="comment-details flex-grow text-center sm:text-left">

                <div class="comment-meta flex flex-col sm:flex-row justify-between items-center mb-4 border-b border-[var(--color-surface-container-low)] pb-4">
                    <b class="fn text-2xl text-[var(--color-primary)] font-bold tracking-wide font-display mb-2 sm:mb-0">
                        <?php echo get_comment_author_link(); ?>
                    </b>

                    <div class="text-sm text-[var(--color-on-surface-muted)] font-body font-semibold flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-accent)]"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>" class="hover:text-[var(--color-primary)] transition-colors">
                            <?php printf(__('%1$s'), get_comment_date('d M Y')); ?>
                        </a>
                    </div>
                </div>

                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation text-amber-600 text-sm italic block mb-4 bg-amber-50 p-3 rounded-xl border border-amber-100 font-body">
                        <?php _e('Il tuo saluto è in attesa di moderazione.') ?>
                    </em>
                <?php endif; ?>

                <div class="comment-text text-[var(--color-on-surface)] leading-relaxed font-body text-lg prose prose-lg max-w-none">
                    <?php comment_text(); ?>
                </div>

            </div>
        </div>

    </li>
    <?php
}
