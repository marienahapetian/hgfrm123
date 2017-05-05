<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Frontend_Scripts
 */
class Huge_Forms_Frontend_Scripts
{

    public static function init()
    {
        add_action( 'huge_forms_shortcode_scripts', array( __CLASS__, 'add_scripts' ) );

        add_action( 'huge_forms_shortcode_scripts', array( __CLASS__, 'add_styles' ) );

        add_action( 'wp_head', array( __CLASS__, 'add_ajax_url_js' ) );
    }

    /**
     * Add Scripts
     *
     * @param $form_id int
     */
    public static function add_scripts( $form_id ) {

        wp_enqueue_script('jquery-ui','https://code.jquery.com/ui/1.12.1/jquery-ui.js');
        wp_enqueue_script( 'huge_forms_frontend', Huge_Forms()->plugin_url() . '/assets/js/frontend/main.js',
            array(
            'jquery', 'jquery-ui'
            ),
            false, true
        );

    }

    /**
     * Define the 'ajaxurl' JS variable, used by themes and plugins as an AJAX endpoint.
     *
     */
    public static function add_ajax_url_js() {
        ?>

        <script
            type="text/javascript">var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', is_ssl() ? 'admin' : 'http' ); ?>';</script>

        <?php
    }

    /**
     * Add Styles
     *
     * @param $form_id int
     */
    public static function add_styles( $form_id ) {
        wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
        wp_enqueue_style( 'flavors-font', 'https://fonts.googleapis.com/css?family=Flavors' );
        wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
        wp_enqueue_style( 'huge_forms_frontend_css', Huge_Forms()->plugin_url() . '/assets/css/frontend/main.css' ,array('jquery-ui'));
    }

}