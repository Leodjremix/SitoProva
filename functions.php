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
