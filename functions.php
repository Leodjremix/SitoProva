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
    '/inc/frontend-posting.php',
    '/inc/cpt-eventi.php',
    '/inc/cpt-illustri.php',
    '/inc/cpt-team.php',
    '/inc/cpt-links.php',
    '/inc/frontend-settings.php',
    '/inc/frontend-eventi.php',
    '/inc/admin-dashboard.php'
];

foreach ( $includes as $file ) {
    $filepath = get_stylesheet_directory() . $file;
    if ( file_exists( $filepath ) ) {
        require_once $filepath;
    } else {
        error_log( 'File mancante nel tema child Santagatesi: ' . $filepath );
    }
}

/**
 * Temporary Rewrite Flush logic for CPT structural changes
 * We only want this to run once when the new code hits the live server.
 */
function santagatesi_flush_rewrites_on_update() {
    if ( ! get_option( 'santagatesi_cpt_refactor_flushed' ) ) {
        flush_rewrite_rules();
        update_option( 'santagatesi_cpt_refactor_flushed', true );
    }
}
add_action( 'admin_init', 'santagatesi_flush_rewrites_on_update' );
