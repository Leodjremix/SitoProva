<?php
/**
 * Santagatesi nel Mondo Theme Functions
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Theme Setup
 */
function santagatesi_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Register Navigation Menus
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'santagatesi' ),
			'footer'  => esc_html__( 'Footer Menu', 'santagatesi' ),
		)
	);

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for core custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'santagatesi_setup' );

/**
 * Custom Theme Initialization Script
 * Runs once to build out the site architecture (Pages, Categories, Menus).
 */
function santagatesi_site_architecture_setup() {
    // Run this only once by checking an option flag
    if ( get_option( 'santagatesi_architecture_setup_complete' ) ) {
        return;
    }

    $created_pages = array();

    // 1. Create Parent Pages
    $parent_pages = array(
        'Chi Siamo'            => 'chi-siamo',
        'Santagatesi Illustri' => 'santagatesi-illustri',
        'Archivio Storico'     => 'archivio-storico',
        'Foto-Video Gallery'   => 'foto-video-gallery',
        'Produzioni'           => 'produzioni',
        'Utilità e Links'      => 'utilita-e-links',
        'Piazza Affari'        => 'piazza-affari',
        'Contatti'             => 'contatti',
        'Privacy Policy'       => 'privacy-policy',
        'Cookie Policy'        => 'cookie-policy',
    );

    foreach ( $parent_pages as $page_title => $page_slug ) {
        $page_check = get_page_by_path( $page_slug );
        if ( ! isset( $page_check->ID ) ) {
            $new_page = array(
                'post_type'    => 'page',
                'post_title'   => $page_title,
                'post_name'    => $page_slug,
                'post_content' => '<!-- wp:heading {"textAlign":"center"} --><h2 class="wp-block-heading has-text-align-center">Contenuto in aggiornamento</h2><!-- /wp:heading -->',
                'post_status'  => 'publish',
                'post_author'  => 1,
            );
            $page_id = wp_insert_post( $new_page );
            $created_pages[$page_title] = $page_id;
        } else {
            $created_pages[$page_title] = $page_check->ID;
        }
    }

    // 2. Create Child Pages
    $child_pages = array(
        'Redazione' => array(
            'slug'   => 'redazione',
            'parent' => $created_pages['Chi Siamo']
        ),
    );

    foreach ( $child_pages as $page_title => $data ) {
        $page_check = get_page_by_path( $data['slug'] );
        if ( ! isset( $page_check->ID ) ) {
            $new_page = array(
                'post_type'    => 'page',
                'post_title'   => $page_title,
                'post_name'    => $data['slug'],
                'post_content' => '<!-- wp:heading {"textAlign":"center"} --><h2 class="wp-block-heading has-text-align-center">La Redazione</h2><!-- /wp:heading -->',
                'post_status'  => 'publish',
                'post_author'  => 1,
                'post_parent'  => $data['parent']
            );
            $page_id = wp_insert_post( $new_page );
            $created_pages[$page_title] = $page_id;
        } else {
            $created_pages[$page_title] = $page_check->ID;
        }
    }

    // 3. Create Private Editorial Page
    $editor_page_slug = 'area-redazione';
    $editor_page_check = get_page_by_path( $editor_page_slug );
    if ( ! isset( $editor_page_check->ID ) ) {
        $new_editor_page = array(
            'post_type'    => 'page',
            'post_title'   => 'Area Redazione',
            'post_name'    => $editor_page_slug,
            'post_content' => '<!-- wp:shortcode -->[form_inserimento_news]<!-- /wp:shortcode -->',
            'post_status'  => 'publish', // Publish, but logic inside shortcode restricts access
            'post_author'  => 1,
        );
        $editor_page_id = wp_insert_post( $new_editor_page );
        $created_pages['Area Redazione'] = $editor_page_id;
    } else {
        $created_pages['Area Redazione'] = $editor_page_check->ID;
    }

    // 4. Rename Default Category to "Artemisium News"
    $default_cat_id = get_option( 'default_category' );
    if ( $default_cat_id ) {
        wp_update_term( $default_cat_id, 'category', array(
            'name' => 'Artemisium News',
            'slug' => 'artemisium-news'
        ) );
    }

    // 5. Create Additional Historical Categories
    $categories_to_create = array( 'Cultura', 'Sport', 'Primo Piano', 'Videonotiziario' );
    foreach ( $categories_to_create as $cat_name ) {
        if ( ! term_exists( $cat_name, 'category' ) ) {
            wp_insert_term( $cat_name, 'category' );
        }
    }

    // 6. Delete Dummy Posts (like "Hello World")
    $dummy_post = get_page_by_path( 'hello-world', OBJECT, 'post' );
    if ( $dummy_post ) {
        wp_delete_post( $dummy_post->ID, true );
    }

    // Mark setup as complete
    update_option( 'santagatesi_architecture_setup_complete', true );
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

/**
 * Include Frontend Posting Module
 */
require get_stylesheet_directory() . '/inc/frontend-posting.php';

/**
 * Enqueue scripts and styles.
 */
function santagatesi_scripts() {
	// Enqueue main stylesheet.
	wp_enqueue_style( 'santagatesi-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

    // Add Noto Serif and Manrope fonts from Google Fonts
    wp_enqueue_style( 'santagatesi-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Noto+Serif:ital,wght@0,400;0,700;1,400&display=swap', array(), null );

    // Enqueue Tailwind CSS via CDN for utility classes
    wp_enqueue_script( 'tailwindcss', 'https://cdn.tailwindcss.com', array(), null, false );
}
add_action( 'wp_enqueue_scripts', 'santagatesi_scripts' );

/**
 * Custom Login Page Logo
 */
function santagatesi_custom_login_logo() {
    // Attempt to get custom logo from theme mods, otherwise use a generic style
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );

    if ( has_custom_logo() && ! empty( $logo ) ) {
        $logo_url = $logo[0];
    } else {
        // Fallback or generic text approach if no logo is set in Customizer
        // For simplicity we will style the header text natively
        $logo_url = '';
    }

    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            <?php if ( ! empty( $logo_url ) ) : ?>
                background-image: url(<?php echo esc_url( $logo_url ); ?>);
                background-size: contain;
                background-position: center center;
                background-repeat: no-repeat;
                height: 100px;
                width: 100%;
            <?php else : ?>
                background-image: none;
                content: "Santagatesi nel Mondo";
                display: block;
                font-size: 24px;
                color: #003d6c; /* --color-primary */
                text-indent: 0;
                width: auto;
                height: auto;
                padding-bottom: 20px;
            <?php endif; ?>
        }
        body.login {
            background-color: #f7fafe; /* --color-surface */
        }
        .login form {
            border-radius: 16px;
            box-shadow: 0px 20px 40px rgba(24, 28, 31, 0.06);
            border: none;
        }
        .wp-core-ui .button-primary {
            background-color: #003d6c !important;
            border-color: #003d6c !important;
            color: #fff !important;
            border-radius: 100px;
        }
    </style>
    <?php
}
add_action( 'login_enqueue_scripts', 'santagatesi_custom_login_logo' );

function santagatesi_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'santagatesi_login_logo_url' );

function santagatesi_login_logo_url_title() {
    return 'Santagatesi nel Mondo';
}
add_filter( 'login_headertext', 'santagatesi_login_logo_url_title' );

/**
 * Fetch latest YouTube videos via RSS Feed
 *
 * Modificato per estrarre anche l'ID del video YouTube per il Lightbox
 *
 * @param int $count Number of videos to fetch.
 * @return array Array of video data (title, link, image, date, video_id).
 */
function santagatesi_get_latest_youtube_videos( $count = 4 ) {
    // Transient to cache the feed and avoid rate limiting
    $cache_key = 'santagatesi_yt_videos_lightbox'; // Changed cache key to avoid conflicts with previous version
    $cached_videos = get_transient( $cache_key );

    if ( false !== $cached_videos ) {
        return array_slice( $cached_videos, 0, $count );
    }

    $videos = array();

    // YouTube RSS Feed for User nardino1000
    $feed_url = 'https://www.youtube.com/feeds/videos.xml?user=nardino1000';

    $response = wp_remote_get( $feed_url );

    if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) == 200 ) {
        $body = wp_remote_retrieve_body( $response );

        // Suppress errors for malformed XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string( $body );

        if ( $xml && isset( $xml->entry ) ) {
            foreach ( $xml->entry as $entry ) {
                $ns_media = $entry->children('http://search.yahoo.com/mrss/');
                $ns_yt = $entry->children('http://www.youtube.com/xml/schemas/2015');

                // Get thumbnail from media:group -> media:thumbnail
                $thumbnail = '';
                if ( $ns_media && isset( $ns_media->group ) && isset( $ns_media->group->thumbnail ) ) {
                    $thumbnail_attrs = $ns_media->group->thumbnail->attributes();
                    $thumbnail = (string) $thumbnail_attrs['url'];
                }

                $video_id = (string) $ns_yt->videoId;
                $link = (string) $entry->link->attributes()->href;

                // Fallbacks if XML parsing slightly differs
                if ( empty( $video_id ) && !empty($link) ) {
                    parse_str( parse_url( $link, PHP_URL_QUERY ), $url_params );
                    if ( isset( $url_params['v'] ) ) {
                        $video_id = $url_params['v'];
                    }
                }

                if ( empty( $thumbnail ) && !empty($video_id) ) {
                    $thumbnail = 'https://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg';
                }

                $videos[] = array(
                    'title'     => (string) $entry->title,
                    'link'      => $link,
                    'date'      => date( 'd/m/Y', strtotime( (string) $entry->published ) ),
                    'thumbnail' => $thumbnail,
                    'video_id'  => $video_id,
                );
            }
        }
    }

    // Fallback if feed fails (for demonstration/testing)
    if ( empty( $videos ) ) {
        $videos = array(
            array(
                'title' => 'Processione dei Santi Gerardo e Leonardo',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Dummy
                'date' => '24/10/2022',
                'thumbnail' => 'https://www.santagatesinelmondo.it/public/video/20221024_002729_0000.png',
                'video_id' => 'dQw4w9WgXcQ'
            ),
            array(
                'title' => 'Celebrazione Liturgica per il 500esimo Parrocchia',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'date' => '18/10/2022',
                'thumbnail' => 'https://www.santagatesinelmondo.it/public/video/20221018_185402_0000.png',
                'video_id' => 'dQw4w9WgXcQ'
            ),
             array(
                'title' => 'In Ricordo dei Nostri Cari Defunti',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'date' => '25/01/2020',
                'thumbnail' => 'https://www.santagatesinelmondo.it/public/video/Cattura.PNG',
                'video_id' => 'dQw4w9WgXcQ'
            ),
            array(
                'title' => 'Visita Virtuale Cimitero',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'date' => '01/11/2019',
                'thumbnail' => 'https://www.santagatesinelmondo.it/public/video/vISITA VIRTUALE(1).jpg',
                'video_id' => 'dQw4w9WgXcQ'
            ),
        );
    }

    // Cache for 1 hour
    set_transient( $cache_key, $videos, HOUR_IN_SECONDS );

    return array_slice( $videos, 0, $count );
}
