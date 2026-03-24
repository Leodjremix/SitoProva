<?php
/**
 * Santagatesi nel Mondo - Child Theme Functions
 *
 * Main entry point. All logic is strictly modularized and placed within the /inc/ directory.
 * Ensure to use `get_stylesheet_directory()` to target this child theme.
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Modular Includes Loader
 *
 * Safely require all functional components of the theme.
 */
$includes = [
    '/inc/setup.php',
    '/inc/enqueue.php',
    '/inc/helpers.php',
    '/inc/shortcodes.php',
    '/inc/security.php',
    '/inc/frontend-posting.php'
];

foreach ( $includes as $file ) {
    $filepath = get_stylesheet_directory() . $file;
    if ( file_exists( $filepath ) ) {
        require_once $filepath;
    } else {
        error_log( 'File mancante nel tema child Santagatesi: ' . $filepath );
    }
}
