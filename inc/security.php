<?php
/**
 * Security and Login Customizations
 *
 * @package santagatesi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Login Page Logo
 */
function santagatesi_custom_login_logo() {
    // Attempt to get custom logo from theme mods, otherwise use a generic style
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );

    if ( has_custom_logo() && ! empty( $logo ) ) {
        $logo_url = $logo[0];
    } else {
        // Fallback or generic text approach if no logo is set in Customizer
        $logo_url = '';
    }

    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            <?php if ( ! empty( $logo_url ) ) : ?>
                background-image: url(<?php echo esc_url( $logo_url ); ?>);
                background-size: contain;
                background-position: center center;
                background-repeat: no-repeat;
                height: 100px;
                width: 100%;
            <?php else : ?>
                background-image: none;
                content: "Santagatesi nel Mondo";
                display: block;
                font-size: 24px;
                color: #003d6c; /* --color-primary */
                text-indent: 0;
                width: auto;
                height: auto;
                padding-bottom: 20px;
            <?php endif; ?>
        }
        body.login {
            background-color: #f7fafe; /* --color-surface */
        }
        .login form {
            border-radius: 16px;
            box-shadow: 0px 20px 40px rgba(24, 28, 31, 0.06);
            border: none;
        }
        .wp-core-ui .button-primary {
            background-color: #003d6c !important;
            border-color: #003d6c !important;
            color: #fff !important;
            border-radius: 100px;
        }
    </style>
    <?php
}
add_action( 'login_enqueue_scripts', 'santagatesi_custom_login_logo' );

function santagatesi_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'santagatesi_login_logo_url' );

function santagatesi_login_logo_url_title() {
    return 'Santagatesi nel Mondo';
}
add_filter( 'login_headertext', 'santagatesi_login_logo_url_title' );
