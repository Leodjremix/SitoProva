<?php
/**
 * Enqueue scripts and styles.
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function santagatesi_scripts() {
	// Enqueue main stylesheet.
	wp_enqueue_style( 'santagatesi-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

    // Add Noto Serif and Manrope fonts from Google Fonts
    wp_enqueue_style( 'santagatesi-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Noto+Serif:ital,wght@0,400;0,700;1,400&display=swap', array(), null );

    // Enqueue Tailwind CSS via CDN for utility classes
    wp_enqueue_script( 'tailwindcss', 'https://cdn.tailwindcss.com', array(), null, false );
}
add_action( 'wp_enqueue_scripts', 'santagatesi_scripts' );
