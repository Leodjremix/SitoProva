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

/**
 * Enqueue scripts for the WordPress backend (Admin area)
 */
function santagatesi_enqueue_admin_scripts() {
    $screen = get_current_screen();

    // Enqueue Media Uploader only on our custom CPT edit screens
    if ( $screen && in_array( $screen->id, array( 'personaggi_illustri', 'link_utili', 'santagatesi_team' ) ) ) {
        wp_enqueue_media();

        // Inline JS to trigger the WP Media Library for our custom image fields
        $custom_media_js = '
            jQuery(document).ready(function($){
                var custom_uploader;
                $(".stg-upload-btn").click(function(e) {
                    e.preventDefault();
                    var target_input = $("#" + $(this).data("target"));
                    var target_preview = $("#" + $(this).data("target") + "_preview");
                    var target_container = $("#" + $(this).data("target") + "_preview_container");

                    if (custom_uploader) {
                        custom_uploader.open();
                        return;
                    }
                    custom_uploader = wp.media.frames.file_frame = wp.media({
                        title: "Seleziona o Carica Immagine",
                        button: { text: "Usa questa immagine" },
                        multiple: false
                    });
                    custom_uploader.on("select", function() {
                        var attachment = custom_uploader.state().get("selection").first().toJSON();
                        target_input.val(attachment.url);
                        if(target_preview.length) {
                            target_preview.attr("src", attachment.url);
                            if(target_container.length) { target_container.show(); }
                        }
                    });
                    custom_uploader.open();
                });
            });
        ';
        // We use an empty script handle to attach the inline script to, or attach to media-upload if available.
        wp_register_script( 'santagatesi-admin-media', false, array( 'jquery' ), null, true );
        wp_enqueue_script( 'santagatesi-admin-media' );
        wp_add_inline_script( 'santagatesi-admin-media', $custom_media_js );
    }
}
add_action( 'admin_enqueue_scripts', 'santagatesi_enqueue_admin_scripts' );
