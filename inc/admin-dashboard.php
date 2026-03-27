<?php
/**
 * Admin Custom Dashboard
 *
 * Provides a secure, tabbed interface to manage section images and Illustrious Citizens.
 * Restricted to Administrators only.
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register the Admin Page under the main Settings/Dashboard menu
 */
function santagatesi_register_custom_admin_page() {
    add_menu_page(
        __( 'Gestione Sito Santagatesi', 'santagatesi' ),
        __( 'Gestione Sito', 'santagatesi' ),
        'administrator', // Strict capability check
        'santagatesi-admin-dashboard',
        'santagatesi_admin_dashboard_callback',
        'dashicons-admin-generic',
        3
    );
}
add_action( 'admin_menu', 'santagatesi_register_custom_admin_page' );

/**
 * Render the Admin Dashboard Interface
 */
function santagatesi_admin_dashboard_callback() {
    // Double check capability (Defense in depth)
    if ( ! current_user_can( 'administrator' ) ) {
        wp_die( __( 'Non hai i permessi necessari per accedere a questa pagina.', 'santagatesi' ) );
    }

    // Process forms if submitted
    santagatesi_process_admin_dashboard_forms();

    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'immagini_sezione';

    // Retrieve saved section images
    $section_images = [
        'hero' => get_option( 'santagatesi_hero_image_url', 'https://www.santagatesinelmondo.it/public/banner/cripta.jpg' ),
        'chi_siamo' => get_option( 'santagatesi_img_chisiamo', '' ),
        'storia' => get_option( 'santagatesi_img_storia', '' ),
    ];
    ?>

    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e( 'Pannello di Gestione: Santagatesi nel Mondo', 'santagatesi' ); ?></h1>
        <hr class="wp-header-end">

        <!-- Tabs Navigation -->
        <h2 class="nav-tab-wrapper">
            <a href="?page=santagatesi-admin-dashboard&tab=immagini_sezione" class="nav-tab <?php echo $active_tab == 'immagini_sezione' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Gestione Immagini Sezioni', 'santagatesi' ); ?></a>
            <a href="?page=santagatesi-admin-dashboard&tab=personaggi_illustri" class="nav-tab <?php echo $active_tab == 'personaggi_illustri' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Gestione Card Personaggi', 'santagatesi' ); ?></a>
        </h2>

        <div class="santagatesi-dashboard-content" style="margin-top: 20px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">

            <?php if ( 'immagini_sezione' === $active_tab ) : ?>
                <!-- TAB 1: IMMAGINI SEZIONE -->
                <h2><?php esc_html_e( 'Gestione Immagini delle Sezioni Principali', 'santagatesi' ); ?></h2>
                <p><?php esc_html_e( 'Aggiorna le immagini di sfondo o di intestazione delle varie pagine del sito. Le immagini devono essere prima caricate nella Libreria Media.', 'santagatesi' ); ?></p>

                <form method="post" action="">
                    <?php wp_nonce_field( 'santagatesi_save_section_images', 'santagatesi_admin_nonce' ); ?>
                    <input type="hidden" name="action" value="save_section_images">

                    <table class="form-table" role="presentation">
                        <tbody>
                            <!-- Hero Image -->
                            <tr>
                                <th scope="row"><label for="img_hero"><?php esc_html_e( 'Immagine Hero (Home Page)', 'santagatesi' ); ?></label></th>
                                <td>
                                    <input type="url" name="img_hero" id="img_hero" value="<?php echo esc_attr( $section_images['hero'] ); ?>" class="regular-text" style="width: 100%; max-width: 600px;">
                                    <p class="description"><?php esc_html_e( 'Inserisci l\'URL completo dell\'immagine (es. https://...).', 'santagatesi' ); ?></p>
                                    <?php if( $section_images['hero'] ): ?>
                                        <div style="margin-top:10px; max-width: 300px; border: 1px solid #ccc; border-radius: 4px; overflow:hidden;">
                                            <img src="<?php echo esc_url( $section_images['hero'] ); ?>" alt="Anteprima Hero" style="width: 100%; height: auto; display: block;">
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Chi Siamo Image -->
                            <tr>
                                <th scope="row"><label for="img_chisiamo"><?php esc_html_e( 'Immagine Pagina "Chi Siamo"', 'santagatesi' ); ?></label></th>
                                <td>
                                    <input type="url" name="img_chisiamo" id="img_chisiamo" value="<?php echo esc_attr( $section_images['chi_siamo'] ); ?>" class="regular-text" style="width: 100%; max-width: 600px;">
                                    <p class="description"><?php esc_html_e( 'URL immagine intestazione Chi Siamo.', 'santagatesi' ); ?></p>
                                    <?php if( $section_images['chi_siamo'] ): ?>
                                        <div style="margin-top:10px; max-width: 300px; border: 1px solid #ccc; border-radius: 4px; overflow:hidden;">
                                            <img src="<?php echo esc_url( $section_images['chi_siamo'] ); ?>" alt="Anteprima Chi Siamo" style="width: 100%; height: auto; display: block;">
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <?php submit_button( __( 'Salva Modifiche Immagini', 'santagatesi' ), 'primary', 'submit_images' ); ?>
                </form>

            <?php elseif ( 'personaggi_illustri' === $active_tab ) : ?>
                <!-- TAB 2: PERSONAGGI ILLUSTRI -->
                <h2><?php esc_html_e( 'Gestione Rapida: Santagatesi Illustri', 'santagatesi' ); ?></h2>
                <p>
                    <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=santagatesi_illustri' ) ); ?>" class="button button-primary">
                        <?php esc_html_e( '+ Aggiungi Nuovo Personaggio', 'santagatesi' ); ?>
                    </a>
                </p>

                <?php
                // Fetch existing records
                $illustri_query = new WP_Query([
                    'post_type'      => 'santagatesi_illustri',
                    'posts_per_page' => -1,
                    'post_status'    => ['publish', 'draft'],
                    'orderby'        => 'title',
                    'order'          => 'ASC'
                ]);

                if ( $illustri_query->have_posts() ) : ?>
                    <form method="post" action="">
                        <?php wp_nonce_field( 'santagatesi_save_illustri_visibility', 'santagatesi_admin_nonce' ); ?>
                        <input type="hidden" name="action" value="save_illustri_visibility">

                        <table class="wp-list-table widefat fixed striped table-view-list">
                            <thead>
                                <tr>
                                    <th style="width: 80px;"><?php esc_html_e( 'Foto', 'santagatesi' ); ?></th>
                                    <th><?php esc_html_e( 'Nome', 'santagatesi' ); ?></th>
                                    <th><?php esc_html_e( 'Stato Post', 'santagatesi' ); ?></th>
                                    <th><?php esc_html_e( 'Visibile sul Frontend', 'santagatesi' ); ?></th>
                                    <th><?php esc_html_e( 'Azioni', 'santagatesi' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ( $illustri_query->have_posts() ) : $illustri_query->the_post();
                                    $post_id = get_the_ID();
                                    $visibility = get_post_meta( $post_id, '_visibilita_frontend', true );
                                    // Default a 1 se vuoto
                                    $is_visible = ( $visibility === '' || $visibility === '1' );
                                ?>
                                    <tr>
                                        <td>
                                            <?php if ( has_post_thumbnail() ) {
                                                the_post_thumbnail( [50, 50], ['style' => 'border-radius: 4px; object-fit: cover;'] );
                                            } else {
                                                echo '<span style="color:#ccc;">No Foto</span>';
                                            } ?>
                                        </td>
                                        <td><strong><a href="<?php echo esc_url( get_edit_post_link( $post_id ) ); ?>"><?php the_title(); ?></a></strong></td>
                                        <td><?php echo get_post_status() === 'publish' ? '<span style="color:green;">Pubblicato</span>' : '<span style="color:orange;">Bozza</span>'; ?></td>
                                        <td>
                                            <!-- Toggle Visibilità (Switch) -->
                                            <label class="switch" style="position: relative; display: inline-block; width: 40px; height: 20px;">
                                                <!-- Valore di fallback (0) inviato se checkbox non è spuntata -->
                                                <input type="hidden" name="illustri_visibility[<?php echo $post_id; ?>]" value="0">
                                                <input type="checkbox" name="illustri_visibility[<?php echo $post_id; ?>]" value="1" <?php checked( $is_visible, true ); ?> style="margin:0;">
                                                <span class="slider" style="margin-left:5px;">Attivo</span>
                                            </label>
                                        </td>
                                        <td>
                                            <a href="<?php echo esc_url( get_edit_post_link( $post_id ) ); ?>" class="button button-small"><?php esc_html_e( 'Modifica', 'santagatesi' ); ?></a>
                                        </td>
                                    </tr>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </tbody>
                        </table>
                        <div style="margin-top: 20px;">
                            <?php submit_button( __( 'Salva Visibilità', 'santagatesi' ), 'primary', 'submit_visibility' ); ?>
                        </div>
                    </form>
                <?php else : ?>
                    <p><?php esc_html_e( 'Nessun personaggio illustre inserito finora.', 'santagatesi' ); ?></p>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Gestore del Salvataggio Dati (Server-Side)
 * Blindato: Controlla capability, nonce e sanitizza tutto.
 */
function santagatesi_process_admin_dashboard_forms() {
    // Esci se non c'è una richiesta POST
    if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
        return;
    }

    // 1. Controllo base sicurezza (Permessi & Nonce)
    if ( ! current_user_can( 'administrator' ) ) {
        wp_die( 'Accesso Negato.' );
    }

    if ( ! isset( $_POST['santagatesi_admin_nonce'] ) || ! wp_verify_nonce( $_POST['santagatesi_admin_nonce'], 'santagatesi_save_section_images' ) && ! wp_verify_nonce( $_POST['santagatesi_admin_nonce'], 'santagatesi_save_illustri_visibility' ) ) {
        wp_die( 'Validazione di sicurezza fallita (Nonce).' );
    }

    $action = isset( $_POST['action'] ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '';

    // 2. Logica di salvataggio basata sull'azione
    if ( 'save_section_images' === $action && isset( $_POST['submit_images'] ) ) {

        $img_hero = isset( $_POST['img_hero'] ) ? esc_url_raw( wp_unslash( $_POST['img_hero'] ) ) : '';
        $img_chisiamo = isset( $_POST['img_chisiamo'] ) ? esc_url_raw( wp_unslash( $_POST['img_chisiamo'] ) ) : '';

        update_option( 'santagatesi_hero_image_url', $img_hero );
        update_option( 'santagatesi_img_chisiamo', $img_chisiamo );

        // Feedback
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Immagini delle sezioni aggiornate con successo!', 'santagatesi' ) . '</p></div>';
    }
    elseif ( 'save_illustri_visibility' === $action && isset( $_POST['submit_visibility'] ) ) {

        if ( isset( $_POST['illustri_visibility'] ) && is_array( $_POST['illustri_visibility'] ) ) {
            foreach ( $_POST['illustri_visibility'] as $post_id => $val ) {
                $post_id = intval( $post_id );
                $visibility_val = ( $val === '1' ) ? '1' : '0'; // Forziamo a stringa 1 o 0

                // Aggiorniamo solo se il post esiste e appartiene al nostro CPT
                if ( get_post_type( $post_id ) === 'santagatesi_illustri' ) {
                    update_post_meta( $post_id, '_visibilita_frontend', $visibility_val );
                }
            }
            // Feedback
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Stato di visibilità aggiornato con successo!', 'santagatesi' ) . '</p></div>';
        }
    }
}
