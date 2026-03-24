<?php
/**
 * Frontend Posting Logic
 *
 * Handles the POST request for the frontend posting form.
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handle Frontend Post Submission
 */
function santagatesi_handle_frontend_submission() {
    if ( isset( $_POST['santagatesi_frontend_submit'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {

        if ( ! isset( $_POST['santagatesi_nonce_field'] ) || ! wp_verify_nonce( $_POST['santagatesi_nonce_field'], 'santagatesi_frontend_action' ) ) {
            wp_die( 'Errore di sicurezza. La richiesta non può essere verificata.' );
        }

        if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
            wp_die( 'Non hai i permessi necessari per pubblicare articoli.' );
        }

        $title    = isset( $_POST['post_title'] ) ? sanitize_text_field( wp_unslash( $_POST['post_title'] ) ) : '';
        $content  = isset( $_POST['post_content'] ) ? wp_kses_post( wp_unslash( $_POST['post_content'] ) ) : '';
        $category = isset( $_POST['post_category'] ) ? intval( $_POST['post_category'] ) : 0;

        $errors = array();

        if ( empty( $title ) ) {
            $errors[] = 'Il titolo è obbligatorio.';
        }
        if ( empty( $content ) ) {
            $errors[] = 'Il contenuto è obbligatorio.';
        }
        if ( $category === 0 ) {
            $errors[] = 'Devi selezionare una categoria valida.';
        }

        if ( empty( $errors ) ) {
            $post_data = array(
                'post_title'    => $title,
                'post_content'  => $content,
                'post_status'   => 'publish',
                'post_author'   => get_current_user_id(),
                'post_category' => array( $category ),
                'post_type'     => 'post',
            );

            $post_id = wp_insert_post( $post_data, true );

            if ( is_wp_error( $post_id ) ) {
                $errors[] = 'Errore durante la creazione dell\'articolo: ' . $post_id->get_error_message();
            } else {
                if ( ! empty( $_FILES['post_thumbnail']['name'] ) ) {
                    require_once( ABSPATH . 'wp-admin/includes/image.php' );
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    require_once( ABSPATH . 'wp-admin/includes/media.php' );

                    $allowed_mime_types = array(
                        'jpg|jpeg|jpe' => 'image/jpeg',
                        'gif'          => 'image/gif',
                        'png'          => 'image/png',
                        'webp'         => 'image/webp'
                    );

                    $upload_overrides = array(
                        'test_form' => false,
                        'mimes'     => $allowed_mime_types
                    );

                    $attachment_id = media_handle_upload( 'post_thumbnail', $post_id, array(), $upload_overrides );

                    if ( ! is_wp_error( $attachment_id ) ) {
                        set_post_thumbnail( $post_id, $attachment_id );
                    } else {
                        $errors[] = 'L\'articolo è stato creato, ma l\'immagine non è stata caricata: ' . $attachment_id->get_error_message();
                    }
                }

                if ( empty( $errors ) ) {
                    $redirect_url = add_query_arg( 'frontend_status', 'success', wp_get_referer() );
                    wp_safe_redirect( $redirect_url );
                    exit;
                }
            }
        }

        if ( ! empty( $errors ) ) {
            set_transient( 'santagatesi_frontend_errors_' . get_current_user_id(), $errors, 45 );
            wp_safe_redirect( wp_get_referer() );
            exit;
        }
    }
}
add_action( 'template_redirect', 'santagatesi_handle_frontend_submission' );
