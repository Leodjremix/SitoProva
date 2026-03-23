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
 * Custom Comment Walker per Guestbook (Stile "Bacheca / Cards")
 */
function santagatesi_guestbook_comment_format( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);
    ?>
    <li <?php comment_class('glass-panel hover:bg-white/5 transition duration-300 p-6 rounded-2xl border border-white/10 shadow-xl relative overflow-hidden group mb-6 list-none'); ?> id="li-comment-<?php comment_ID() ?>">

        <div id="comment-<?php comment_ID(); ?>" class="comment-body relative z-10 flex gap-6">

            <!-- Avatar Colonna -->
            <div class="comment-author vcard flex-shrink-0">
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-full border-2 border-blue-500/50 shadow-md group-hover:scale-110 transition-transform duration-500')); ?>
            </div>

            <!-- Content Colonna -->
            <div class="comment-details flex-grow">

                <div class="comment-meta flex justify-between items-center mb-3 border-b border-white/5 pb-3">
                    <b class="fn text-xl text-white font-bold tracking-wide">
                        <?php echo get_comment_author_link(); ?>
                    </b>

                    <div class="text-xs text-blue-300/70 font-mono tracking-tighter flex items-center gap-1">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>" class="hover:text-blue-400 transition">
                            <?php printf(__('%1$s'), get_comment_date('d M Y')); ?>
                        </a>
                    </div>
                </div>

                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation text-amber-400 text-sm italic block mb-3 bg-amber-400/10 p-2 rounded-lg border border-amber-400/20">
                        <?php _e('Il tuo saluto è in attesa di moderazione.') ?>
                    </em>
                <?php endif; ?>

                <div class="comment-text text-slate-300 leading-relaxed font-light text-[15px] prose prose-invert">
                    <?php comment_text(); ?>
                </div>

            </div>
        </div>

        <!-- Elemento decorativo hover -->
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-blue-600/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all duration-700 z-0"></div>
    </li>
    <?php
}

/**
 * Enqueue scripts and styles.
 */
function santagatesi_scripts() {
	// Enqueue main stylesheet.
	wp_enqueue_style( 'santagatesi-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

    // Add Inter font from Google Fonts
    wp_enqueue_style( 'santagatesi-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', array(), null );

    // Enqueue Tailwind CSS via CDN for utility classes
    wp_enqueue_script( 'tailwindcss', 'https://cdn.tailwindcss.com', array(), null, false );
}
add_action( 'wp_enqueue_scripts', 'santagatesi_scripts' );

/**
 * Fetch latest YouTube videos via RSS Feed
 *
 * Using YouTube RSS feed instead of API key to keep it simple and avoid API key management.
 * The channel ID for Santagatesi is 'nardino1000' based on the context URL.
 * YouTube user feeds are available at: https://www.youtube.com/feeds/videos.xml?user=nardino1000
 * Or Channel ID feed: https://www.youtube.com/feeds/videos.xml?channel_id=UC...
 *
 * Note: If 'nardino1000' is a username, we use user=. If it fails, fallback mock data is provided.
 *
 * @param int $count Number of videos to fetch.
 * @return array Array of video data (title, link, image, date).
 */
function santagatesi_get_latest_youtube_videos( $count = 4 ) {
    // Transient to cache the feed and avoid rate limiting
    $cache_key = 'santagatesi_yt_videos';
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

                // Get thumbnail from media:group -> media:thumbnail
                $thumbnail = '';
                if ( $ns_media && isset( $ns_media->group ) && isset( $ns_media->group->thumbnail ) ) {
                    $thumbnail_attrs = $ns_media->group->thumbnail->attributes();
                    $thumbnail = (string) $thumbnail_attrs['url'];
                }

                // If thumbnail not found, try getting video ID from link and construct it
                if ( empty( $thumbnail ) && isset( $entry->link ) ) {
                    $link_attrs = $entry->link->attributes();
                    $href = (string) $link_attrs['href'];
                    parse_str( parse_url( $href, PHP_URL_QUERY ), $url_params );
                    if ( isset( $url_params['v'] ) ) {
                        $thumbnail = 'https://img.youtube.com/vi/' . $url_params['v'] . '/hqdefault.jpg';
                    }
                }

                $videos[] = array(
                    'title'     => (string) $entry->title,
                    'link'      => (string) $entry->link->attributes()->href,
                    'date'      => date( 'd/m/Y', strtotime( (string) $entry->published ) ),
                    'thumbnail' => $thumbnail,
                );
            }
        }
    }

    // Fallback if feed fails (for demonstration/testing)
    if ( empty( $videos ) ) {
        $videos = array(
            array(
                'title' => 'Processione dei Santi Gerardo e Leonardo',
                'link' => 'https://www.youtube.com/user/nardino1000',
                'date' => '24/10/2022',
                'thumbnail' => 'https://www.santagatesinelmondo.it/public/video/20221024_002729_0000.png'
            ),
            array(
                'title' => 'Celebrazione Liturgica per il 500esimo Parrocchia',
                'link' => 'https://www.youtube.com/user/nardino1000',
                'date' => '18/10/2022',
                'thumbnail' => 'https://www.santagatesinelmondo.it/public/video/20221018_185402_0000.png'
            ),
             array(
                'title' => 'In Ricordo dei Nostri Cari Defunti',
                'link' => 'https://www.youtube.com/user/nardino1000',
                'date' => '25/01/2020',
                'thumbnail' => 'https://www.santagatesinelmondo.it/public/video/Cattura.PNG'
            ),
            array(
                'title' => 'Visita Virtuale Cimitero',
                'link' => 'https://www.youtube.com/user/nardino1000',
                'date' => '01/11/2019',
                'thumbnail' => 'https://www.santagatesinelmondo.it/public/video/vISITA VIRTUALE(1).jpg'
            ),
        );
    }

    // Cache for 1 hour
    set_transient( $cache_key, $videos, HOUR_IN_SECONDS );

    return array_slice( $videos, 0, $count );
}
