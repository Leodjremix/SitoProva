<?php
/**
 * Frontend Settings Module
 *
 * Provides a shortcode [form_impostazioni_sito] to manage site settings
 * (like Webcam URLs and Hero Image) from the frontend for Admins.
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handle Settings Form Submission
 */
function santagatesi_handle_settings_submission() {
    if ( isset( $_POST['santagatesi_settings_submit'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {

        // Security checks
        if ( ! isset( $_POST['santagatesi_settings_nonce'] ) || ! wp_verify_nonce( $_POST['santagatesi_settings_nonce'], 'santagatesi_settings_action' ) ) {
            wp_die( 'Errore di sicurezza (Nonce).' );
        }

        if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) { // Only Admins
            wp_die( 'Privilegi insufficienti per modificare le impostazioni del sito.' );
        }

        $errors = array();
        $success = false;

        // 1. Process Webcam 1
        if ( isset( $_POST['webcam_1_url'] ) ) {
            $url1 = esc_url_raw( wp_unslash( $_POST['webcam_1_url'] ) );
            update_option( 'santagatesi_webcam_1_url', $url1 );
            $success = true;
        }

        // 2. Process Webcam 2
        if ( isset( $_POST['webcam_2_url'] ) ) {
            $url2 = esc_url_raw( wp_unslash( $_POST['webcam_2_url'] ) );
            update_option( 'santagatesi_webcam_2_url', $url2 );
            $success = true;
        }

        // 3. Process Hero Image Upload
        if ( ! empty( $_FILES['hero_image']['name'] ) ) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $allowed_mimes = array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'png'          => 'image/png',
                'webp'         => 'image/webp'
            );

            $attachment_id = media_handle_upload( 'hero_image', 0, array(), array( 'test_form' => false, 'mimes' => $allowed_mimes ) );

            if ( ! is_wp_error( $attachment_id ) ) {
                $image_url = wp_get_attachment_url( $attachment_id );
                if ( $image_url ) {
                    update_option( 'santagatesi_hero_image_url', $image_url );
                    $success = true;
                }
            } else {
                $errors[] = 'Errore upload Hero Image: ' . $attachment_id->get_error_message();
            }
        }

        // Redirect with status
        if ( empty( $errors ) && $success ) {
            $redirect_url = add_query_arg( 'settings_status', 'success', wp_get_referer() );
            wp_safe_redirect( $redirect_url );
            exit;
        } elseif ( ! empty( $errors ) ) {
            set_transient( 'santagatesi_settings_errors_' . get_current_user_id(), $errors, 45 );
            wp_safe_redirect( wp_get_referer() );
            exit;
        } else {
            // Nothing updated
            wp_safe_redirect( wp_get_referer() );
            exit;
        }
    }
}
add_action( 'template_redirect', 'santagatesi_handle_settings_submission' );

/**
 * Shortcode to display the Settings Form
 */
function santagatesi_frontend_settings_shortcode() {

    if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
        return '<div class="tonal-panel-low p-6 text-center text-gray-500 font-body"><p>Area riservata agli Amministratori.</p></div>';
    }

    // Default Fallbacks
    $current_hero = get_option( 'santagatesi_hero_image_url', get_stylesheet_directory_uri() . '/images/cripta-placeholder.jpg' );
    $current_webcam_1 = get_option( 'santagatesi_webcam_1_url', 'https://www.liveincam.com/?w=588' );
    $current_webcam_2 = get_option( 'santagatesi_webcam_2_url', 'https://www.skylinewebcams.com/webcam/italia/puglia/foggia/santagata-di-puglia.html?w=453' );

    ob_start();

    // Feedback
    if ( isset( $_GET['settings_status'] ) && $_GET['settings_status'] === 'success' ) {
        echo '<div class="bg-green-50 border border-green-200 text-green-800 p-6 rounded-xl mb-8 font-body shadow-sm">
                <strong>Impostazioni salvate con successo!</strong>
              </div>';
    }

    $errors = get_transient( 'santagatesi_settings_errors_' . get_current_user_id() );
    if ( ! empty( $errors ) ) {
        echo '<div class="bg-red-50 border border-red-200 text-red-800 p-6 rounded-xl mb-8 font-body">';
        foreach ( $errors as $error ) { echo '<li>' . esc_html( $error ) . '</li>'; }
        echo '</div>';
        delete_transient( 'santagatesi_settings_errors_' . get_current_user_id() );
    }

    ?>
    <div class="frontend-post-form-container tonal-panel bg-white p-8 md:p-12 shadow-[var(--shadow-ambient)] rounded-3xl max-w-4xl mx-auto border-none">
        <h2 class="text-3xl font-bold text-[var(--color-primary)] mb-8 font-display border-b border-[var(--color-surface-container-low)] pb-4">
            Gestione Impostazioni Sito
        </h2>

        <form action="" method="post" enctype="multipart/form-data" class="space-y-10">
            <?php wp_nonce_field( 'santagatesi_settings_action', 'santagatesi_settings_nonce' ); ?>

            <!-- Hero Image -->
            <div class="form-group border border-[var(--color-surface-container-low)] p-6 rounded-2xl bg-[var(--color-surface)]">
                <h3 class="text-xl font-bold text-[var(--color-primary)] mb-4 font-display">Immagine Home Page (Hero)</h3>

                <div class="mb-4">
                    <p class="text-sm text-[var(--color-on-surface-muted)] mb-2 font-body">Immagine Attuale:</p>
                    <img src="<?php echo esc_url( $current_hero ); ?>" class="h-32 w-auto object-cover rounded-lg shadow-sm border-2 border-white">
                </div>

                <label class="block text-[var(--color-primary)] font-bold mb-2 font-display text-sm">Carica Nuova Immagine (JPG, WEBP) - Max 2MB</label>
                <input type="file" name="hero_image" accept="image/jpeg, image/png, image/webp" class="w-full bg-white border border-[var(--color-surface-container-low)] text-[var(--color-on-surface)] rounded-xl p-3 focus:ring-2 focus:ring-[var(--color-accent)] outline-none font-body">
            </div>

            <!-- Webcams -->
            <div class="form-group border border-[var(--color-surface-container-low)] p-6 rounded-2xl bg-[var(--color-surface)]">
                <h3 class="text-xl font-bold text-[var(--color-primary)] mb-4 font-display">Gestione Streaming Webcam</h3>

                <div class="mb-6">
                    <label for="webcam_1_url" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-sm">Webcam 1 (URL Iframe) - Vico V. Emanuele</label>
                    <input type="url" id="webcam_1_url" name="webcam_1_url" value="<?php echo esc_attr( $current_webcam_1 ); ?>" class="w-full bg-white border border-[var(--color-surface-container-low)] text-[var(--color-on-surface)] rounded-xl p-4 focus:ring-2 focus:ring-[var(--color-accent)] outline-none font-body font-mono text-sm">
                </div>

                <div>
                    <label for="webcam_2_url" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-sm">Webcam 2 (URL Iframe) - Piazza Toni Santagata</label>
                    <input type="url" id="webcam_2_url" name="webcam_2_url" value="<?php echo esc_attr( $current_webcam_2 ); ?>" class="w-full bg-white border border-[var(--color-surface-container-low)] text-[var(--color-on-surface)] rounded-xl p-4 focus:ring-2 focus:ring-[var(--color-accent)] outline-none font-body font-mono text-sm">
                </div>
            </div>

            <div class="pt-6 border-t border-[var(--color-surface-container-low)] flex justify-end">
                <button type="submit" name="santagatesi_settings_submit" class="btn btn-primary text-lg px-8 py-3 shadow-md w-full md:w-auto">
                    Salva Impostazioni
                </button>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'form_impostazioni_sito', 'santagatesi_frontend_settings_shortcode' );
