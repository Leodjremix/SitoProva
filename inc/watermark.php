<?php
/**
 * Automatic Image Watermarking System
 *
 * Hooks into WordPress image uploads to physically apply a watermark
 * onto images before they are processed for thumbnails.
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Apply watermark to newly uploaded images.
 *
 * Hooked to `wp_handle_upload` to intercept the file immediately after
 * it's uploaded to the tmp directory and moved to the uploads folder,
 * but before WordPress generates attachment metadata and thumbnails.
 *
 * @param array $upload An array containing 'file', 'url', 'type', 'error'.
 * @return array
 */
function santagatesi_apply_watermark_on_upload( $upload ) {
    // 1. Check if watermark is globally enabled
    $is_active = get_option( 'santagatesi_watermark_active', '0' );
    if ( $is_active !== '1' ) {
        return $upload;
    }

    // 2. Check if the upload was successful and is an image
    if ( isset( $upload['error'] ) && $upload['error'] ) {
        return $upload;
    }

    $mime_type = $upload['type'];
    $allowed_mimes = array( 'image/jpeg', 'image/png', 'image/webp' );

    if ( ! in_array( $mime_type, $allowed_mimes ) ) {
        return $upload;
    }

    // 3. Get Watermark Configuration
    $watermark_url = get_option( 'santagatesi_watermark_logo', '' );
    if ( empty( $watermark_url ) ) {
        return $upload;
    }

    // Attempt to resolve the physical path of the watermark logo from the URL
    // (Assuming the logo is hosted on the same WordPress installation in wp-content/uploads)
    $upload_dir = wp_upload_dir();
    $watermark_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $watermark_url );

    if ( ! file_exists( $watermark_path ) ) {
        error_log( 'Santagatesi Watermark: File logo non trovato al percorso: ' . $watermark_path );
        return $upload;
    }

    $opacity = (int) get_option( 'santagatesi_watermark_opacity', 100 );
    if ( $opacity < 0 ) $opacity = 0;
    if ( $opacity > 100 ) $opacity = 100;

    $size_pct = (int) get_option( 'santagatesi_watermark_size', 20 );
    if ( $size_pct < 1 ) $size_pct = 1;
    if ( $size_pct > 100 ) $size_pct = 100;

    $position = get_option( 'santagatesi_watermark_position', 'bottom_center' );

    // 4. Check if we are trying to watermark the watermark logo itself
    // We prevent this by checking filenames or sizes, or setting a transient/flag during logo upload.
    // However, the easiest way is checking if the uploaded file is exactly the same as the watermark logo.
    if ( $upload['file'] === $watermark_path ) {
        return $upload;
    }

    // 5. Apply the Watermark
    $result = santagatesi_process_image_watermark( $upload['file'], $mime_type, $watermark_path, $opacity, $size_pct, $position );

    if ( is_wp_error( $result ) ) {
        error_log( 'Santagatesi Watermark Error: ' . $result->get_error_message() );
    }

    return $upload;
}
add_filter( 'wp_handle_upload', 'santagatesi_apply_watermark_on_upload' );


/**
 * Core Watermarking Logic using PHP GD.
 *
 * @param string $target_image_path Physical path to the uploaded image.
 * @param string $mime_type Mime type of the uploaded image.
 * @param string $watermark_path Physical path to the watermark PNG.
 * @param int $opacity Opacity level (0-100).
 * @param int $size_pct Percentage size of the watermark relative to target image (1-100).
 * @param string $position Where to position the watermark.
 * @return bool|WP_Error True on success, WP_Error on failure.
 */
function santagatesi_process_image_watermark( $target_image_path, $mime_type, $watermark_path, $opacity, $size_pct, $position ) {

    if ( ! extension_loaded('gd') || ! function_exists('gd_info') ) {
        return new WP_Error( 'gd_missing', 'Estensione PHP GD non trovata sul server.' );
    }

    // 1. Load the Target Image
    $target_image = false;
    switch ( $mime_type ) {
        case 'image/jpeg':
            $target_image = @imagecreatefromjpeg( $target_image_path );
            break;
        case 'image/png':
            $target_image = @imagecreatefrompng( $target_image_path );
            break;
        case 'image/webp':
            if ( function_exists('imagecreatefromwebp') ) {
                $target_image = @imagecreatefromwebp( $target_image_path );
            }
            break;
    }

    if ( ! $target_image ) {
        return new WP_Error( 'target_load_failed', 'Impossibile caricare l\'immagine di destinazione.' );
    }

    // 2. Load the Watermark Image (Must be PNG for transparency)
    // We assume the logo is a PNG as requested.
    $watermark_info = getimagesize( $watermark_path );
    if ( $watermark_info['mime'] !== 'image/png' ) {
        imagedestroy( $target_image );
        return new WP_Error( 'watermark_not_png', 'Il logo del watermark deve essere un file PNG.' );
    }

    $watermark_image = @imagecreatefrompng( $watermark_path );
    if ( ! $watermark_image ) {
        imagedestroy( $target_image );
        return new WP_Error( 'watermark_load_failed', 'Impossibile caricare l\'immagine del watermark.' );
    }

    // 3. Calculate Dimensions & Scaling
    $target_width  = imagesx( $target_image );
    $target_height = imagesy( $target_image );

    $wm_orig_width  = imagesx( $watermark_image );
    $wm_orig_height = imagesy( $watermark_image );

    // Determine scale based on target image width.
    $scale_percentage = $size_pct / 100.0;

    $wm_new_width  = (int) ( $target_width * $scale_percentage );
    // Ensure it doesn't scale up past its original size and get blurry
    if ( $wm_new_width > $wm_orig_width ) {
        $wm_new_width = $wm_orig_width;
    }

    // Calculate proportionate height
    $wm_new_height = (int) ( ( $wm_orig_height / $wm_orig_width ) * $wm_new_width );

    // 4. Resize Watermark
    $resized_watermark = imagecreatetruecolor( $wm_new_width, $wm_new_height );

    // Preserve transparency for the resized watermark
    imagealphablending( $resized_watermark, false );
    imagesavealpha( $resized_watermark, true );
    $transparent = imagecolorallocatealpha( $resized_watermark, 255, 255, 255, 127 );
    imagefilledrectangle( $resized_watermark, 0, 0, $wm_new_width, $wm_new_height, $transparent );

    imagecopyresampled(
        $resized_watermark, $watermark_image,
        0, 0, 0, 0,
        $wm_new_width, $wm_new_height,
        $wm_orig_width, $wm_orig_height
    );

    imagedestroy( $watermark_image );

    // 5. Calculate Position
    $margin_x = (int) ( $target_width * 0.05 ); // 5% horizontal margin
    $margin_y = (int) ( $target_height * 0.05 ); // 5% vertical margin

    $pos_x = 0;
    $pos_y = 0;

    switch ( $position ) {
        case 'bottom_right':
            $pos_x = $target_width - $wm_new_width - $margin_x;
            $pos_y = $target_height - $wm_new_height - $margin_y;
            break;
        case 'bottom_left':
            $pos_x = $margin_x;
            $pos_y = $target_height - $wm_new_height - $margin_y;
            break;
        case 'center':
            $pos_x = (int) ( ( $target_width / 2 ) - ( $wm_new_width / 2 ) );
            $pos_y = (int) ( ( $target_height / 2 ) - ( $wm_new_height / 2 ) );
            break;
        case 'top_center':
            $pos_x = (int) ( ( $target_width / 2 ) - ( $wm_new_width / 2 ) );
            $pos_y = $margin_y;
            break;
        case 'top_right':
            $pos_x = $target_width - $wm_new_width - $margin_x;
            $pos_y = $margin_y;
            break;
        case 'top_left':
            $pos_x = $margin_x;
            $pos_y = $margin_y;
            break;
        case 'bottom_center':
        default:
            $pos_x = (int) ( ( $target_width / 2 ) - ( $wm_new_width / 2 ) );
            $pos_y = $target_height - $wm_new_height - $margin_y;
            break;
    }

    // 6. Apply Watermark with Opacity
    // To handle opacity correctly with PNG alpha channels in PHP GD,
    // it's tricky. imagecopymerge doesn't preserve alpha well.
    // We use a custom alpha blending technique if opacity < 100,
    // otherwise imagecopy is perfect.

    if ( $opacity >= 100 ) {
        // Full opacity: preserve PNG alpha channel using imagecopy
        imagealphablending( $target_image, true );
        imagecopy( $target_image, $resized_watermark, $pos_x, $pos_y, 0, 0, $wm_new_width, $wm_new_height );
    } else {
        // Variable opacity: Requires processing pixel by pixel or filtering to adjust alpha
        santagatesi_imagecopymerge_alpha( $target_image, $resized_watermark, $pos_x, $pos_y, 0, 0, $wm_new_width, $wm_new_height, $opacity );
    }

    imagedestroy( $resized_watermark );

    // 7. Save the Resulting Image over the original uploaded file
    $saved = false;
    switch ( $mime_type ) {
        case 'image/jpeg':
            $saved = imagejpeg( $target_image, $target_image_path, 90 ); // 90 is a good quality compromise
            break;
        case 'image/png':
            // Convert PNG images to preserve alpha channel
            imagealphablending( $target_image, false );
            imagesavealpha( $target_image, true );
            $saved = imagepng( $target_image, $target_image_path, 8 ); // compression 0-9
            break;
        case 'image/webp':
            $saved = imagewebp( $target_image, $target_image_path, 85 );
            break;
    }

    imagedestroy( $target_image );

    if ( ! $saved ) {
        return new WP_Error( 'save_failed', 'Impossibile salvare l\'immagine con watermark sovrascrivendo l\'originale.' );
    }

    return true;
}

/**
 * Custom function to merge images with alpha channel and overall opacity.
 * PHP's native imagecopymerge doesn't handle PNG 24-bit alpha + overall opacity well.
 */
function santagatesi_imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
    // Create a cut resource
    $cut = imagecreatetruecolor($src_w, $src_h);
    // Copy relevant section from destination to the cut resource
    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    // Copy relevant section from source to the cut resource
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    // Insert cut resource to destination image with the defined opacity (pct)
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
    imagedestroy($cut);
}
