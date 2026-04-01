<?php
/**
 * Integrations & API Module
 *
 * Handles external APIs (OpenWeatherMap, YouTube) and global counters.
 * Caches results using WordPress Transient API to prevent rate-limiting and improve performance.
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* -------------------------------------------------------------------------- */
/* 1. OpenWeatherMap - Shortcode: [meteo_santagata]
/* -------------------------------------------------------------------------- */

function santagatesi_meteo_shortcode() {
    // Check if API key is configured
    $api_key = get_option( 'santagatesi_owm_api_key', '' );
    if ( empty( $api_key ) ) {
        return '<!-- OpenWeatherMap API Key non configurata -->';
    }

    // Try to get cached data first (Transient API)
    $transient_key = 'santagatesi_meteo_data';
    $weather_data = get_transient( $transient_key );

    if ( false === $weather_data ) {
        // Data is not cached, fetch it from the API
        // coordinate per Sant'Agata di Puglia: lat = 41.1517, lon = 15.3817
        $lat = '41.1517';
        $lon = '15.3817';
        $url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units=metric&lang=it&appid={$api_key}";

        $response = wp_remote_get( $url, array( 'timeout' => 10 ) );

        if ( is_wp_error( $response ) ) {
            return '<!-- Errore di connessione a OpenWeatherMap -->';
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( isset( $data['main']['temp'] ) ) {
            $weather_data = array(
                'temp' => round( $data['main']['temp'] ),
                'desc' => ucfirst( $data['weather'][0]['description'] ),
                'icon' => $data['weather'][0]['icon'], // es: "01d", "04n"
            );
            // Cache per 2 ore (7200 secondi)
            set_transient( $transient_key, $weather_data, 2 * HOUR_IN_SECONDS );
        } else {
            return '<!-- Errore nei dati ricevuti da OpenWeatherMap -->';
        }
    }

    // Output Widget (Modern UI)
    ob_start();
    ?>
    <div class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--color-surface-container-low)] backdrop-blur-md rounded-full border border-[var(--color-surface-container-low)] text-[var(--color-primary)] shadow-sm transition-transform duration-300 hover:scale-105" title="<?php echo esc_attr( $weather_data['desc'] ); ?> a Sant'Agata di Puglia">
        <img src="https://openweathermap.org/img/wn/<?php echo esc_attr( $weather_data['icon'] ); ?>.png" alt="Meteo" class="w-8 h-8 filter drop-shadow-sm">
        <span class="font-display font-extrabold text-lg flex items-center gap-1"><?php echo esc_html( $weather_data['temp'] ); ?>&deg;C <span class="text-xs uppercase font-body font-bold text-[var(--color-on-surface-muted)] hidden sm:inline ml-1">Sant'Agata</span></span>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'meteo_santagata', 'santagatesi_meteo_shortcode' );


/* -------------------------------------------------------------------------- */
/* 2. YouTube View Counter - Shortcode: [youtube_views]
/* -------------------------------------------------------------------------- */

function santagatesi_youtube_views_shortcode() {
    $api_key = get_option( 'santagatesi_yt_api_key', '' );
    $channel_id = get_option( 'santagatesi_yt_channel_id', '' );

    if ( empty( $api_key ) || empty( $channel_id ) ) {
        return '<!-- API Key YouTube o ID Canale non configurati -->';
    }

    $transient_key = 'santagatesi_yt_views_' . $channel_id;
    $views = get_transient( $transient_key );

    if ( false === $views ) {
        // Fetch from YouTube Data API v3
        $url = "https://www.googleapis.com/youtube/v3/channels?part=statistics&id={$channel_id}&key={$api_key}";

        $response = wp_remote_get( $url, array( 'timeout' => 10 ) );

        if ( is_wp_error( $response ) ) {
            return '<!-- Errore connessione YouTube API -->';
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( isset( $data['items'][0]['statistics']['viewCount'] ) ) {
            $views = intval( $data['items'][0]['statistics']['viewCount'] );
            // Cache per 24 ore per evitare limiti di quota gratuiti (10,000 unit/day limit on free tier)
            set_transient( $transient_key, $views, 24 * HOUR_IN_SECONDS );
        } else {
            return '<!-- Dati YouTube API non validi -->';
        }
    }

    // Formatta numero (es: 1.345.678)
    $formatted_views = number_format_i18n( $views );

    ob_start();
    ?>
    <div class="youtube-stats-widget text-center">
        <p class="text-[var(--color-on-surface-muted)] text-sm uppercase tracking-wider font-semibold mb-1">Oltre</p>
        <p class="text-3xl font-extrabold text-[var(--color-primary)] font-display flex items-center justify-center gap-2">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="#FF0000" xmlns="http://www.w3.org/2000/svg"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
            <?php echo esc_html( $formatted_views ); ?>
        </p>
        <p class="text-[var(--color-on-surface-muted)] text-sm mt-1">visualizzazioni storiche web-tv</p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'youtube_views', 'santagatesi_youtube_views_shortcode' );


/* -------------------------------------------------------------------------- */
/* 3. Global Visit Counter Tracker
/* -------------------------------------------------------------------------- */

/**
 * Leggero tracker visite su hook "template_redirect" (viene eseguito solo nel frontend, non backend).
 * Usa un cookie di sessione ('stg_visited') per evitare conteggi multipli al refresh.
 */
function santagatesi_track_unique_visit() {
    // Do not track admin users or bots (basic check) if logged in, but we want a global count
    // The easiest and most performant way is a simple cookie.

    // Check if this is the frontend
    if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
        return;
    }

    // Se il cookie non esiste, incrementa e salva il cookie
    if ( ! isset( $_COOKIE['stg_visited'] ) ) {

        $current_count = (int) get_option( 'santagatesi_visite_totali', 1300000 );
        $new_count = $current_count + 1;

        // Update database
        update_option( 'santagatesi_visite_totali', $new_count );

        // Set cookie valid for the current browser session
        // (will expire when browser is closed, so returning tomorrow counts as new visit)
        setcookie( 'stg_visited', '1', 0, COOKIEPATH, COOKIE_DOMAIN );
    }
}
add_action( 'template_redirect', 'santagatesi_track_unique_visit' );

/**
 * Shortcode per stampare il contatore totale: [contatore_totale]
 */
function santagatesi_contatore_shortcode() {
    $current_count = (int) get_option( 'santagatesi_visite_totali', 1300000 );
    $formatted_count = number_format_i18n( $current_count );

    ob_start();
    ?>
    <div class="visit-counter-widget text-center">
        <p class="text-[var(--color-on-surface-muted)] text-sm uppercase tracking-wider font-semibold mb-1">Visite totali al portale</p>
        <p class="text-3xl font-extrabold text-[var(--color-accent)] font-display flex items-center justify-center gap-2">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            <?php echo esc_html( $formatted_count ); ?>
        </p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'contatore_totale', 'santagatesi_contatore_shortcode' );
