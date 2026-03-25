<?php
/**
 * Frontend Event Posting Module
 *
 * Provides a shortcode [form_inserimento_evento] for Editors
 * to publish Events directly from the frontend.
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handle Frontend Event Submission
 */
function santagatesi_handle_frontend_event_submission() {
    if ( isset( $_POST['santagatesi_event_submit'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {

        // Security checks
        if ( ! isset( $_POST['santagatesi_event_nonce'] ) || ! wp_verify_nonce( $_POST['santagatesi_event_nonce'], 'santagatesi_event_action' ) ) {
            wp_die( 'Errore di sicurezza (Nonce).' );
        }

        if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
            wp_die( 'Privilegi insufficienti per pubblicare eventi.' );
        }

        $title    = isset( $_POST['event_title'] ) ? sanitize_text_field( wp_unslash( $_POST['event_title'] ) ) : '';
        $content  = isset( $_POST['event_content'] ) ? wp_kses_post( wp_unslash( $_POST['event_content'] ) ) : '';
        $date     = isset( $_POST['event_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_date'] ) ) : '';
        $location = isset( $_POST['event_location'] ) ? sanitize_text_field( wp_unslash( $_POST['event_location'] ) ) : '';

        $errors = array();

        if ( empty( $title ) ) { $errors[] = 'Il Titolo è obbligatorio.'; }
        if ( empty( $date ) ) { $errors[] = 'La Data dell\'evento è obbligatoria.'; }

        if ( empty( $errors ) ) {
            $post_data = array(
                'post_title'    => $title,
                'post_content'  => $content,
                'post_status'   => 'publish',
                'post_author'   => get_current_user_id(),
                'post_type'     => 'santagatesi_evento',
            );

            $post_id = wp_insert_post( $post_data, true );

            if ( is_wp_error( $post_id ) ) {
                $errors[] = 'Errore creazione evento: ' . $post_id->get_error_message();
            } else {
                // Update Event Meta Data
                update_post_meta( $post_id, '_event_date', $date );
                update_post_meta( $post_id, '_event_location', $location );

                // Handle Thumbnail (Locandina)
                if ( ! empty( $_FILES['event_thumbnail']['name'] ) ) {
                    require_once( ABSPATH . 'wp-admin/includes/image.php' );
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    require_once( ABSPATH . 'wp-admin/includes/media.php' );

                    $allowed_mimes = array(
                        'jpg|jpeg|jpe' => 'image/jpeg',
                        'png'          => 'image/png',
                        'webp'         => 'image/webp'
                    );

                    $attachment_id = media_handle_upload( 'event_thumbnail', $post_id, array(), array( 'test_form' => false, 'mimes' => $allowed_mimes ) );

                    if ( ! is_wp_error( $attachment_id ) ) {
                        set_post_thumbnail( $post_id, $attachment_id );
                    } else {
                        $errors[] = 'Evento creato, ma errore locandina: ' . $attachment_id->get_error_message();
                    }
                }

                if ( empty( $errors ) ) {
                    $redirect_url = add_query_arg( 'event_status', 'success', wp_get_referer() );
                    wp_safe_redirect( $redirect_url );
                    exit;
                }
            }
        }

        if ( ! empty( $errors ) ) {
            set_transient( 'santagatesi_event_errors_' . get_current_user_id(), $errors, 45 );
            wp_safe_redirect( wp_get_referer() );
            exit;
        }
    }
}
add_action( 'template_redirect', 'santagatesi_handle_frontend_event_submission' );

/**
 * Shortcode to display the Event Form
 */
function santagatesi_frontend_event_shortcode() {

    if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
        return '<div class="tonal-panel-low p-6 text-center text-gray-500 font-body"><p>Area riservata alla Redazione.</p></div>';
    }

    ob_start();

    // Feedback
    if ( isset( $_GET['event_status'] ) && $_GET['event_status'] === 'success' ) {
        echo '<div class="bg-green-50 border border-green-200 text-green-800 p-6 rounded-xl mb-8 font-body shadow-sm">
                <strong>Evento pubblicato con successo in Calendario!</strong>
              </div>';
    }

    $errors = get_transient( 'santagatesi_event_errors_' . get_current_user_id() );
    if ( ! empty( $errors ) ) {
        echo '<div class="bg-red-50 border border-red-200 text-red-800 p-6 rounded-xl mb-8 font-body">';
        foreach ( $errors as $error ) { echo '<li>' . esc_html( $error ) . '</li>'; }
        echo '</div>';
        delete_transient( 'santagatesi_event_errors_' . get_current_user_id() );
    }

    ?>
    <div class="frontend-post-form-container tonal-panel bg-white p-8 md:p-12 shadow-[var(--shadow-ambient)] rounded-3xl max-w-4xl mx-auto border-none mt-12">
        <h2 class="text-3xl font-bold text-[var(--color-primary)] mb-8 font-display border-b border-[var(--color-surface-container-low)] pb-4 flex items-center gap-3">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-[var(--color-accent)]"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Aggiungi Evento al Calendario
        </h2>

        <form action="" method="post" enctype="multipart/form-data" class="space-y-8">
            <?php wp_nonce_field( 'santagatesi_event_action', 'santagatesi_event_nonce' ); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Titolo -->
                <div class="form-group md:col-span-2">
                    <label for="event_title" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-sm">Titolo Evento *</label>
                    <input type="text" id="event_title" name="event_title" required class="w-full bg-white border border-[var(--color-surface-container-low)] rounded-xl p-3 focus:ring-2 focus:ring-[var(--color-accent)] outline-none font-body">
                </div>

                <!-- Data e Luogo -->
                <div class="form-group">
                    <label for="event_date" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-sm">Data Evento *</label>
                    <input type="date" id="event_date" name="event_date" required class="w-full bg-white border border-[var(--color-surface-container-low)] rounded-xl p-3 focus:ring-2 focus:ring-[var(--color-accent)] outline-none font-body text-[var(--color-on-surface-muted)]">
                </div>

                <div class="form-group">
                    <label for="event_location" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-sm">Luogo (opzionale)</label>
                    <input type="text" id="event_location" name="event_location" placeholder="Es. Piazza Castello" class="w-full bg-white border border-[var(--color-surface-container-low)] rounded-xl p-3 focus:ring-2 focus:ring-[var(--color-accent)] outline-none font-body">
                </div>
            </div>

            <!-- Immagine Locandina -->
            <div class="form-group border border-[var(--color-surface-container-low)] p-6 rounded-2xl bg-[var(--color-surface)]">
                <label for="event_thumbnail" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-sm">Locandina Evento (JPG, PNG)</label>
                <input type="file" id="event_thumbnail" name="event_thumbnail" accept="image/jpeg, image/png, image/webp" class="w-full font-body text-sm text-[var(--color-on-surface-muted)]">
            </div>

            <!-- Descrizione -->
            <div class="form-group">
                <label for="event_content" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-sm">Dettagli / Descrizione</label>
                <textarea id="event_content" name="event_content" rows="6" class="w-full bg-white border border-[var(--color-surface-container-low)] rounded-xl p-4 focus:ring-2 focus:ring-[var(--color-accent)] outline-none font-body" placeholder="Inserisci il programma dell'evento..."></textarea>
            </div>

            <div class="pt-6 border-t border-[var(--color-surface-container-low)] flex justify-end">
                <button type="submit" name="santagatesi_event_submit" class="btn btn-primary text-lg px-8 py-3 shadow-md w-full md:w-auto">
                    Pubblica Evento
                </button>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'form_inserimento_evento', 'santagatesi_frontend_event_shortcode' );
