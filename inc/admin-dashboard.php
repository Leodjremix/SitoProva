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
    $hook = add_menu_page(
        __( 'Gestione Sito Santagatesi', 'santagatesi' ),
        __( 'Gestione Sito', 'santagatesi' ),
        'administrator', // Strict capability check
        'santagatesi-admin-dashboard',
        'santagatesi_admin_dashboard_callback',
        'dashicons-admin-generic',
        3
    );
    // Enqueue media uploader script only on this specific admin page
    add_action( "admin_print_scripts-{$hook}", 'santagatesi_enqueue_admin_media_uploader' );
}
add_action( 'admin_menu', 'santagatesi_register_custom_admin_page' );

function santagatesi_enqueue_admin_media_uploader() {
    wp_enqueue_media();
}

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
            <a href="?page=santagatesi-admin-dashboard&tab=immagini_sezione" class="nav-tab <?php echo $active_tab == 'immagini_sezione' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Immagini e Testi', 'santagatesi' ); ?></a>
            <a href="?page=santagatesi-admin-dashboard&tab=webcam" class="nav-tab <?php echo $active_tab == 'webcam' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Webcam', 'santagatesi' ); ?></a>
            <a href="?page=santagatesi-admin-dashboard&tab=gestione_contenuti" class="nav-tab <?php echo $active_tab == 'gestione_contenuti' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Gestione Contenuti Rapida', 'santagatesi' ); ?></a>
        </h2>

        <div class="santagatesi-dashboard-content" style="margin-top: 20px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">

            <?php if ( 'immagini_sezione' === $active_tab ) : ?>
                <!-- TAB 1: IMMAGINI SEZIONE & TESTI HERO -->
                <h2><?php esc_html_e( 'Gestione Sezioni Principali: Hero e Pagine', 'santagatesi' ); ?></h2>
                <p><?php esc_html_e( 'Modifica i testi della pagina principale e seleziona le immagini di sfondo.', 'santagatesi' ); ?></p>

                <form method="post" action="">
                    <?php wp_nonce_field( 'santagatesi_save_section_images', 'santagatesi_admin_nonce' ); ?>
                    <input type="hidden" name="action" value="save_section_images">

                    <table class="form-table" role="presentation">
                        <tbody>
                            <!-- Hero testuale -->
                            <tr style="border-top: 1px solid #ccc;">
                                <th scope="row"><label for="hero_title"><?php esc_html_e( 'Testo Principale Hero', 'santagatesi' ); ?></label></th>
                                <td>
                                    <input type="text" name="hero_title" id="hero_title" value="<?php echo esc_attr( get_option( 'santagatesi_hero_title', 'Santagatesi nel Mondo' ) ); ?>" class="regular-text" style="width: 100%; max-width: 600px;">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="hero_subtitle"><?php esc_html_e( 'Sottotitolo Hero', 'santagatesi' ); ?></label></th>
                                <td>
                                    <textarea name="hero_subtitle" id="hero_subtitle" rows="2" style="width: 100%; max-width: 600px;"><?php echo esc_textarea( get_option( 'santagatesi_hero_subtitle', 'Un ponte tra le nostre radici e il futuro, ovunque tu sia.' ) ); ?></textarea>
                                </td>
                            </tr>

                            <!-- Hero Image via Media Uploader -->
                            <tr style="border-top: 1px solid #eee;">
                                <th scope="row"><label for="img_hero"><?php esc_html_e( 'Immagine Sfondo Hero', 'santagatesi' ); ?></label></th>
                                <td>
                                    <input type="text" name="img_hero" id="img_hero" value="<?php echo esc_attr( $section_images['hero'] ); ?>" class="regular-text" style="width: 100%; max-width: 500px;">
                                    <input type="button" class="button button-secondary stg-upload-btn" data-target="img_hero" value="<?php esc_attr_e( 'Scegli Immagine', 'santagatesi' ); ?>">
                                    <?php if( $section_images['hero'] ): ?>
                                        <div style="margin-top:10px; max-width: 300px; border: 1px solid #ccc; border-radius: 4px; overflow:hidden;">
                                            <img src="<?php echo esc_url( $section_images['hero'] ); ?>" alt="Anteprima Hero" style="width: 100%; height: auto; display: block;" id="img_hero_preview">
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Chi Siamo Image via Media Uploader -->
                            <tr style="border-top: 1px solid #eee;">
                                <th scope="row"><label for="img_chisiamo"><?php esc_html_e( 'Immagine Intestazione "Chi Siamo"', 'santagatesi' ); ?></label></th>
                                <td>
                                    <input type="text" name="img_chisiamo" id="img_chisiamo" value="<?php echo esc_attr( $section_images['chi_siamo'] ); ?>" class="regular-text" style="width: 100%; max-width: 500px;">
                                    <input type="button" class="button button-secondary stg-upload-btn" data-target="img_chisiamo" value="<?php esc_attr_e( 'Scegli Immagine', 'santagatesi' ); ?>">
                                    <?php if( $section_images['chi_siamo'] ): ?>
                                        <div style="margin-top:10px; max-width: 300px; border: 1px solid #ccc; border-radius: 4px; overflow:hidden;">
                                            <img src="<?php echo esc_url( $section_images['chi_siamo'] ); ?>" alt="Anteprima Chi Siamo" style="width: 100%; height: auto; display: block;" id="img_chisiamo_preview">
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <?php submit_button( __( 'Salva Modifiche', 'santagatesi' ), 'primary', 'submit_images' ); ?>
                </form>

                <!-- JS Script per collegare Media Uploader al bottone -->
                <script>
                    jQuery(document).ready(function($){
                        var custom_uploader;
                        $('.stg-upload-btn').click(function(e) {
                            e.preventDefault();
                            var target_input = $('#' + $(this).data('target'));
                            var target_preview = $('#' + $(this).data('target') + '_preview');

                            // Se il media uploader esiste già
                            if (custom_uploader) {
                                custom_uploader.open();
                                return;
                            }

                            // Crea il uploader
                            custom_uploader = wp.media.frames.file_frame = wp.media({
                                title: 'Scegli o Carica Immagine',
                                button: { text: 'Seleziona' },
                                multiple: false
                            });

                            custom_uploader.on('select', function() {
                                var attachment = custom_uploader.state().get('selection').first().toJSON();
                                target_input.val(attachment.url);
                                if(target_preview.length) {
                                    target_preview.attr('src', attachment.url);
                                } else {
                                    // Aggiunge la preview se non esisteva prima
                                    target_input.parent().append('<div style="margin-top:10px; max-width: 300px; border: 1px solid #ccc; border-radius: 4px; overflow:hidden;"><img src="'+attachment.url+'" style="width: 100%; height: auto; display: block;" id="'+$(this).data('target')+'_preview"></div>');
                                }
                            });

                            custom_uploader.open();
                        });
                    });
                </script>

            <?php elseif ( 'webcam' === $active_tab ) : ?>
                <!-- TAB WEBCAM -->
                <h2><?php esc_html_e( 'Gestione Webcam H24', 'santagatesi' ); ?></h2>
                <p><?php esc_html_e( 'Inserisci l\'URL degli iframe delle webcam (forniti da provider come Skylinewebcams). Puoi anche disattivarle senza doverle cancellare dal database.', 'santagatesi' ); ?></p>
                <form method="post" action="">
                    <?php wp_nonce_field( 'santagatesi_save_webcams', 'santagatesi_admin_nonce' ); ?>
                    <input type="hidden" name="action" value="save_webcams">
                    <table class="form-table">
                        <tbody>
                            <!-- Webcam 1 -->
                            <tr style="border-top: 1px solid #eee;">
                                <th scope="row"><label for="webcam_1_url"><?php esc_html_e( 'Webcam 1: URL', 'santagatesi' ); ?></label></th>
                                <td>
                                    <input type="url" name="webcam_1_url" id="webcam_1_url" value="<?php echo esc_url( get_option( 'santagatesi_webcam_1_url', 'https://www.liveincam.com/?w=588' ) ); ?>" class="regular-text" style="width:100%;">
                                    <div style="margin-top:10px;">
                                        <label>
                                            <input type="checkbox" name="webcam_1_visible" value="1" <?php checked( get_option( 'santagatesi_webcam_1_visible', '1' ), '1' ); ?>>
                                            <strong><?php esc_html_e( 'Mostra Webcam 1 sul frontend', 'santagatesi' ); ?></strong>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <!-- Webcam 2 -->
                            <tr style="border-top: 1px solid #eee;">
                                <th scope="row"><label for="webcam_2_url"><?php esc_html_e( 'Webcam 2: URL', 'santagatesi' ); ?></label></th>
                                <td>
                                    <input type="url" name="webcam_2_url" id="webcam_2_url" value="<?php echo esc_url( get_option( 'santagatesi_webcam_2_url', 'https://www.skylinewebcams.com/webcam/italia/puglia/foggia/santagata-di-puglia.html?w=453' ) ); ?>" class="regular-text" style="width:100%;">
                                    <div style="margin-top:10px;">
                                        <label>
                                            <input type="checkbox" name="webcam_2_visible" value="1" <?php checked( get_option( 'santagatesi_webcam_2_visible', '1' ), '1' ); ?>>
                                            <strong><?php esc_html_e( 'Mostra Webcam 2 sul frontend', 'santagatesi' ); ?></strong>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php submit_button( __( 'Salva Webcam', 'santagatesi' ), 'primary', 'submit_webcams' ); ?>
                </form>

            <?php elseif ( 'gestione_contenuti' === $active_tab ) : ?>
                <!-- TAB GESTIONE CONTENUTI -->
                <h2><?php esc_html_e( 'Gestione Rapida: Santagatesi Illustri', 'santagatesi' ); ?></h2>
                <p>
                    <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=santagatesi_illustri' ) ); ?>" class="button button-primary">
                        <?php esc_html_e( '+ Aggiungi Nuovo Personaggio', 'santagatesi' ); ?>
                    </a>
                </p>

                <?php
                // Function to render generic table for a CPT
                function santagatesi_render_cpt_table( $cpt_slug, $cpt_label ) {
                    $query = new WP_Query([
                        'post_type'      => $cpt_slug,
                        'posts_per_page' => -1,
                        'post_status'    => ['publish', 'draft'],
                        'orderby'        => 'title',
                        'order'          => 'ASC'
                    ]);

                    if ( $query->have_posts() ) : ?>
                        <table class="wp-list-table widefat fixed striped table-view-list" style="margin-bottom: 30px;">
                            <thead>
                                <tr>
                                    <th style="width: 80px;"><?php esc_html_e( 'Media', 'santagatesi' ); ?></th>
                                    <th><?php echo esc_html( $cpt_label ); ?></th>
                                    <th><?php esc_html_e( 'Stato Post', 'santagatesi' ); ?></th>
                                    <th><?php esc_html_e( 'Visibile sul Frontend', 'santagatesi' ); ?></th>
                                    <th><?php esc_html_e( 'Azioni', 'santagatesi' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ( $query->have_posts() ) : $query->the_post();
                                    $post_id = get_the_ID();
                                    $visibility = get_post_meta( $post_id, '_visibilita_frontend', true );
                                    $is_visible = ( $visibility === '' || $visibility === '1' );
                                ?>
                                    <tr>
                                        <td>
                                            <?php if ( has_post_thumbnail() ) {
                                                the_post_thumbnail( [50, 50], ['style' => 'border-radius: 4px; object-fit: cover;'] );
                                            } else {
                                                echo '<span style="color:#ccc;">-</span>';
                                            } ?>
                                        </td>
                                        <td><strong><a href="<?php echo esc_url( get_edit_post_link( $post_id ) ); ?>"><?php the_title(); ?></a></strong></td>
                                        <td><?php echo get_post_status() === 'publish' ? '<span style="color:green;">Pubblicato</span>' : '<span style="color:orange;">Bozza</span>'; ?></td>
                                        <td>
                                            <label class="switch" style="position: relative; display: inline-block; width: 40px; height: 20px;">
                                                <input type="hidden" name="content_visibility[<?php echo $post_id; ?>]" value="0">
                                                <input type="checkbox" name="content_visibility[<?php echo $post_id; ?>]" value="1" <?php checked( $is_visible, true ); ?> style="margin:0;">
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
                    <?php else : ?>
                        <p><?php printf( esc_html__( 'Nessun elemento inserito per %s.', 'santagatesi' ), $cpt_label ); ?></p>
                    <?php endif;
                }
                ?>

                <form method="post" action="">
                    <?php wp_nonce_field( 'santagatesi_save_content_visibility', 'santagatesi_admin_nonce' ); ?>
                    <input type="hidden" name="action" value="save_content_visibility">

                    <h3><?php esc_html_e( 'Santagatesi Illustri', 'santagatesi' ); ?> <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=personaggi_illustri' ) ); ?>" class="button button-small" style="margin-left: 10px;">+ Aggiungi</a></h3>
                    <?php santagatesi_render_cpt_table( 'personaggi_illustri', 'Nome' ); ?>

                    <hr>

                    <h3><?php esc_html_e( 'Staff & Redazione', 'santagatesi' ); ?> <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=santagatesi_team' ) ); ?>" class="button button-small" style="margin-left: 10px;">+ Aggiungi</a></h3>
                    <?php santagatesi_render_cpt_table( 'santagatesi_team', 'Membro' ); ?>

                    <hr>

                    <h3><?php esc_html_e( 'Link Utili', 'santagatesi' ); ?> <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=link_utili' ) ); ?>" class="button button-small" style="margin-left: 10px;">+ Aggiungi</a></h3>
                    <?php santagatesi_render_cpt_table( 'link_utili', 'Titolo Link' ); ?>

                    <div style="margin-top: 20px;">
                        <?php submit_button( __( 'Salva Visibilità Contenuti', 'santagatesi' ), 'primary', 'submit_visibility' ); ?>
                    </div>
                </form>

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

    if ( ! isset( $_POST['santagatesi_admin_nonce'] ) || ( ! wp_verify_nonce( $_POST['santagatesi_admin_nonce'], 'santagatesi_save_section_images' ) && ! wp_verify_nonce( $_POST['santagatesi_admin_nonce'], 'santagatesi_save_webcams' ) && ! wp_verify_nonce( $_POST['santagatesi_admin_nonce'], 'santagatesi_save_content_visibility' ) ) ) {
        wp_die( 'Validazione di sicurezza fallita (Nonce).' );
    }

    $action = isset( $_POST['action'] ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '';

    // 2. Logica di salvataggio basata sull'azione
    if ( 'save_section_images' === $action && isset( $_POST['submit_images'] ) ) {

        $hero_title = isset( $_POST['hero_title'] ) ? sanitize_text_field( wp_unslash( $_POST['hero_title'] ) ) : '';
        $hero_subtitle = isset( $_POST['hero_subtitle'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hero_subtitle'] ) ) : '';
        $img_hero = isset( $_POST['img_hero'] ) ? esc_url_raw( wp_unslash( $_POST['img_hero'] ) ) : '';
        $img_chisiamo = isset( $_POST['img_chisiamo'] ) ? esc_url_raw( wp_unslash( $_POST['img_chisiamo'] ) ) : '';

        update_option( 'santagatesi_hero_title', $hero_title );
        update_option( 'santagatesi_hero_subtitle', $hero_subtitle );
        update_option( 'santagatesi_hero_image_url', $img_hero );
        update_option( 'santagatesi_img_chisiamo', $img_chisiamo );

        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Immagini e Testi aggiornati con successo!', 'santagatesi' ) . '</p></div>';
    }
    elseif ( 'save_webcams' === $action && isset( $_POST['submit_webcams'] ) ) {

        $webcam_1_url = isset( $_POST['webcam_1_url'] ) ? esc_url_raw( wp_unslash( $_POST['webcam_1_url'] ) ) : '';
        $webcam_2_url = isset( $_POST['webcam_2_url'] ) ? esc_url_raw( wp_unslash( $_POST['webcam_2_url'] ) ) : '';
        $webcam_1_visible = isset( $_POST['webcam_1_visible'] ) ? '1' : '0';
        $webcam_2_visible = isset( $_POST['webcam_2_visible'] ) ? '1' : '0';

        update_option( 'santagatesi_webcam_1_url', $webcam_1_url );
        update_option( 'santagatesi_webcam_2_url', $webcam_2_url );
        update_option( 'santagatesi_webcam_1_visible', $webcam_1_visible );
        update_option( 'santagatesi_webcam_2_visible', $webcam_2_visible );

        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Webcam aggiornate con successo!', 'santagatesi' ) . '</p></div>';
    }
    elseif ( 'save_content_visibility' === $action && isset( $_POST['submit_visibility'] ) ) {

        if ( isset( $_POST['content_visibility'] ) && is_array( $_POST['content_visibility'] ) ) {
            $allowed_cpts = ['personaggi_illustri', 'santagatesi_team', 'link_utili'];

            foreach ( $_POST['content_visibility'] as $post_id => $val ) {
                $post_id = intval( $post_id );
                $visibility_val = ( $val === '1' ) ? '1' : '0';

                // Aggiorniamo solo se il post esiste e appartiene ai nostri CPT abilitati
                if ( in_array( get_post_type( $post_id ), $allowed_cpts, true ) ) {
                    update_post_meta( $post_id, '_visibilita_frontend', $visibility_val );
                }
            }
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Stati di visibilità aggiornati con successo!', 'santagatesi' ) . '</p></div>';
        }
    }
}
