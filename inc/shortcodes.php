<?php
/**
 * Frontend Posting Shortcodes
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode to display the Frontend Post Form
 */
function santagatesi_frontend_post_shortcode() {

    // Check Permissions
    if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
        return '<div class="tonal-panel-low p-6 text-center text-[var(--color-on-surface-muted)] font-body border border-[var(--color-surface-container-lowest)] rounded-xl bg-white"><p>Devi effettuare l\'accesso come redattore per inserire una notizia.</p></div>';
    }

    ob_start();

    // Feedback Messages
    if ( isset( $_GET['frontend_status'] ) && $_GET['frontend_status'] === 'success' ) {
        echo '<div class="bg-green-50 border border-green-200 text-green-800 p-6 rounded-xl mb-8 font-body shadow-sm flex items-center gap-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <strong>Articolo pubblicato con successo!</strong>
              </div>';
    }

    $errors = get_transient( 'santagatesi_frontend_errors_' . get_current_user_id() );
    if ( ! empty( $errors ) ) {
        echo '<div class="bg-red-50 border border-red-200 text-red-800 p-6 rounded-xl mb-8 font-body shadow-sm">';
        echo '<strong class="block mb-2 flex items-center gap-2"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> Si sono verificati i seguenti errori:</strong>';
        echo '<ul class="list-disc pl-5 space-y-1">';
        foreach ( $errors as $error ) {
            echo '<li>' . esc_html( $error ) . '</li>';
        }
        echo '</ul></div>';
        delete_transient( 'santagatesi_frontend_errors_' . get_current_user_id() );
    }

    // Get Categories for Dropdown
    $categories = get_categories( array(
        'hide_empty' => false,
    ) );
    ?>

    <div class="frontend-post-form-container tonal-panel bg-white p-8 md:p-12 border-none shadow-[var(--shadow-ambient)] rounded-[var(--radius-xl)] max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-[var(--color-primary)] mb-8 font-display border-b border-[var(--color-surface-container-low)] pb-4 flex items-center gap-3">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[var(--color-accent)]"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Pubblica un nuovo articolo
        </h2>

        <form id="frontend-post-form" class="space-y-8" action="" method="post" enctype="multipart/form-data">

            <?php wp_nonce_field( 'santagatesi_frontend_action', 'santagatesi_nonce_field' ); ?>

            <!-- Titolo -->
            <div class="form-group">
                <label for="post_title" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-lg">Titolo dell'Articolo <span class="text-red-500">*</span></label>
                <input type="text" id="post_title" name="post_title" required class="w-full bg-[var(--color-surface-container-low)] border border-[var(--color-surface)] text-[var(--color-on-surface)] rounded-xl p-4 focus:ring-4 focus:ring-[var(--color-accent)]/20 outline-none transition-all placeholder-[var(--color-on-surface-muted)] font-body text-lg shadow-inner" placeholder="Inserisci un titolo accattivante...">
            </div>

            <!-- Categoria -->
            <div class="form-group">
                <label for="post_category" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-lg">Categoria <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="post_category" name="post_category" required class="w-full appearance-none bg-[var(--color-surface-container-low)] border border-[var(--color-surface)] text-[var(--color-on-surface)] rounded-xl p-4 pr-12 focus:ring-4 focus:ring-[var(--color-accent)]/20 outline-none transition-all font-body text-lg shadow-inner cursor-pointer">
                        <option value="" disabled selected>Seleziona una categoria...</option>
                        <?php foreach ( $categories as $cat ) : ?>
                            <option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[var(--color-on-surface-muted)]">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Immagine in Evidenza -->
            <div class="form-group">
                <label for="post_thumbnail" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-lg">Immagine di Copertina</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-[var(--color-surface-container-low)] border-dashed rounded-xl bg-[var(--color-surface)] hover:bg-[var(--color-surface-container-low)] transition-colors relative">
                    <div class="space-y-2 text-center">
                        <svg class="mx-auto h-12 w-12 text-[var(--color-on-surface-muted)]" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-[var(--color-on-surface-muted)] justify-center">
                            <label for="post_thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-[var(--color-accent)] hover:text-[var(--color-primary)] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[var(--color-accent)] px-2 py-1">
                                <span>Carica un file</span>
                                <input id="post_thumbnail" name="post_thumbnail" type="file" accept="image/jpeg, image/png, image/webp" class="sr-only">
                            </label>
                            <p class="pl-1 pt-1">o trascina qui</p>
                        </div>
                        <p class="text-xs text-[var(--color-on-surface-muted)]">PNG, JPG, WEBP fino a 2MB</p>
                        <div id="file-name-display" class="text-sm font-semibold text-[var(--color-primary)] mt-2 hidden"></div>
                    </div>
                </div>
            </div>

            <!-- Contenuto (WP Editor) -->
            <div class="form-group">
                <label for="post_content" class="block text-[var(--color-primary)] font-bold mb-2 font-display text-lg">Contenuto dell'Articolo <span class="text-red-500">*</span></label>
                <div class="wp-editor-wrapper bg-white border border-[var(--color-surface-container-low)] rounded-xl overflow-hidden shadow-inner">
                    <?php
                    $content = '';
                    $editor_id = 'post_content';
                    $settings = array(
                        'media_buttons' => true, // Permette di inserire media all'interno del testo
                        'textarea_rows' => 15,
                        'teeny'         => false,
                        'quicktags'     => true,
                        'editor_class'  => 'font-body text-lg',
                    );
                    wp_editor( $content, $editor_id, $settings );
                    ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-[var(--color-surface-container-low)] flex justify-end">
                <button type="submit" name="santagatesi_frontend_submit" class="btn btn-primary text-lg px-10 py-4 shadow-lg flex items-center gap-2 group w-full md:w-auto">
                    Pubblica Articolo
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </div>

        </form>
    </div>

    <script>
        // Simple script to display selected file name
        document.getElementById('post_thumbnail').addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : '';
            var displayDiv = document.getElementById('file-name-display');
            if (fileName) {
                displayDiv.textContent = 'File selezionato: ' + fileName;
                displayDiv.classList.remove('hidden');
            } else {
                displayDiv.classList.add('hidden');
            }
        });
    </script>
    <?php

    return ob_get_clean();
}
add_shortcode( 'form_inserimento_news', 'santagatesi_frontend_post_shortcode' );
