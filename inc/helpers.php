<?php
/**
 * Helpers and Utilities
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fetch latest YouTube videos via RSS Feed
 *
 * Modificato per estrarre anche l'ID del video YouTube per il Lightbox
 *
 * @param int $count Number of videos to fetch.
 * @return array Array of video data (title, link, image, date, video_id).
 */
function santagatesi_get_latest_youtube_videos( $count = 4 ) {
    $cache_key = 'santagatesi_yt_videos_lightbox';
    $cached_videos = get_transient( $cache_key );

    if ( false !== $cached_videos ) {
        return array_slice( $cached_videos, 0, $count );
    }

    $videos = array();
    $feed_url = 'https://www.youtube.com/feeds/videos.xml?user=nardino1000';
    $response = wp_remote_get( $feed_url );

    if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) == 200 ) {
        $body = wp_remote_retrieve_body( $response );
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string( $body );

        if ( $xml && isset( $xml->entry ) ) {
            foreach ( $xml->entry as $entry ) {
                $ns_media = $entry->children('http://search.yahoo.com/mrss/');
                $ns_yt = $entry->children('http://www.youtube.com/xml/schemas/2015');

                $thumbnail = '';
                if ( $ns_media && isset( $ns_media->group ) && isset( $ns_media->group->thumbnail ) ) {
                    $thumbnail_attrs = $ns_media->group->thumbnail->attributes();
                    $thumbnail = (string) $thumbnail_attrs['url'];
                }

                $video_id = (string) $ns_yt->videoId;
                $link = (string) $entry->link->attributes()->href;

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

    if ( empty( $videos ) ) {
        $videos = array(
            array(
                'title' => 'Processione dei Santi',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'date' => '24/10/2022',
                'thumbnail' => get_stylesheet_directory_uri() . '/images/placeholder.jpg',
                'video_id' => 'dQw4w9WgXcQ'
            ),
        );
    }

    set_transient( $cache_key, $videos, HOUR_IN_SECONDS );
    return array_slice( $videos, 0, $count );
}
