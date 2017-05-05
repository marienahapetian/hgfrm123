<?php
/**
 * Plugin Name: Huge Forms
 * Author: Mari Nahapetyan <a href="http://huge-it.com">Huge-IT</a>
 * Description: Easy to use Form Plugin for creating simple to custom complex forms
 * Version: 1.0.1
 */

if(!defined('ABSPATH')){
    exit();
}

require('config.php');

if( ! class_exists( 'Huge_Forms' ) ):
    /**
     * Main Huge-IT Forms Class
     */
    final class Huge_Forms
    {
        public static $delete_tables_uninstall = false;

        /**
         * Version of plugin
         * @var string
         */
        public $version = '1.0.0';

        /**
         * Instance of Huge_Forms_Admin class to manage admin
         * @var Huge_Forms_Admin instance
         */
        public $admin = null;

        /**
         * Instance of Huge_Forms_Template_Loader class to manage admin
         * @var Huge_Forms_Template_Loader instance
         */
        public $template_loader = null;

        /**
         * The single instance of the class.
         *
         * @var Huge_Forms
         */
        protected static $_instance = null;

        /**
         * Main Huge_Forms Instance.
         *
         * Ensures only one instance of Huge_Forms is loaded or can be loaded.
         *
         * @static
         * @see Huge_Forms()
         * @return Huge_Forms - Main instance.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Do not let to make clones of this class
         */
        private function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'hugeit-maps' ), '2.2.0' );
        }

        /**
         * Unserializing instances of this class is forbidden.
         */
        private function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'hugeit-maps' ), '2.2.0' );
        }

        /**
         * Huge_Forms Constructor.
         */
        private function __construct() {
            $this->define_constants();
            $this->init_hooks();
            do_action( 'Huge_Forms_loaded' );
        }

        /**
         * Hook into actions and filters.
         */
        private function init_hooks() {
            register_activation_hook( __FILE__, array( 'Huge_Forms_Install', 'install' ) );
            add_action( 'init', array( $this, 'init' ), 0 );
            add_action( 'widgets_init', array( 'Huge_Forms_Widgets', 'init' ) );

            register_uninstall_hook( __FILE__, array('Huge_Forms_Install','uninstall') );
        }

        /**
         * Define Portfolio Gallery Constants.
         */
        private function define_constants() {
            $this->define( 'HUGE_FORMS_PLUGIN_FILE', __FILE__ );
            $this->define( 'HUGE_FORMS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
            $this->define( 'HUGE_FORMS_VERSION', $this->version );
            $this->define( 'HUGE_FORMS_IMAGES_PATH', $this->plugin_path() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR );
            $this->define( 'HUGE_FORMS_IMAGES_URL', untrailingslashit($this->plugin_url() ) . '/assets/images/');
            $this->define( 'HUGE_FORMS_FONTS_URL', untrailingslashit($this->plugin_url() ) . '/assets/fonts/');
            $this->define( 'HUGE_FORMS_FONTS_PATH', $this->plugin_path() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR );
            $this->define( 'HUGE_FORMS_TEMPLATES_PATH', $this->plugin_path() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR);
            $this->define( 'HUGE_FORMS_TEMPLATES_URL', untrailingslashit($this->plugin_url()) . '/templates/');
        }

        /**
         * Define constant if not already set.
         *
         * @param  string $name
         * @param  string|bool $value
         */
        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        /**
         * What type of request is this?
         * string $type ajax, frontend or admin.
         *
         * @return bool
         */
        private function is_request( $type ) {
            switch ( $type ) {
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined( 'DOING_AJAX' );
                case 'cron' :
                    return defined( 'DOING_CRON' );
                case 'frontend' :
                    return  ! is_admin() && ! defined( 'DOING_CRON' );
            }
        }

        /**
         *
         */
        public function init(){

            new Huge_Forms_Shortcode();

            Huge_Forms_Install::init();

            Huge_Forms_Ajax::init();


            if( $this->is_request( 'admin' ) ){

                $this->admin = new Huge_Forms_Admin();
                Huge_Forms_Admin_Assets::init();

            }

            if( $this->is_request( 'frontend' ) ){

                Huge_Forms_Frontend_Scripts::init();

            }

        }

        /**
         * Returns database table names
         * @param $which
         * @return string
         * @throws Exception
         */
        public function get_table_name( $which ){
            $table_name = '';

            switch( $which ){
                case 'forms':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_forms";
                    break;
                case 'fields':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_fields";
                    break;
                case 'formFields':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_form_fields";
                    break;
                case 'fieldTypes':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_field_types";
                    break;
                case 'themes':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_themes";
                    break;
                case 'onsubmitActions':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_onsubmit_actions";
                    break;
                case 'labelPositions':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_label_positions";
                    break;
                case 'subscribers':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_subscribers";
                    break;
                case 'submissions':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_submissions";
                    break;
                case 'submissionFields':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_submission_fields";
                    break;
                case 'blacklist':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_blacklist";
                    break;
                case 'settings':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_settings";
                    break;
                case 'fieldOptions':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_field_options";
                    break;
                case 'addressOptions':
                    $table_name = $GLOBALS['wpdb']->prefix . "hgfrm_address_field_options";
                    break;
            }

            if( !$table_name ){

                throw new Exception( 'Trying to access a non existing database table "'. $which .'"' );

            }

            return $table_name;

        }

        /**
         * Get the template path.
         * @return string
         */
        public function template_path() {
            return apply_filters( 'huge_forms_template_path', 'Huge_Forms/' );
        }

        /**
         * Get the plugin url.
         * @return string
         */
        public function plugin_url() {
            return untrailingslashit( plugins_url( '/', __FILE__ ) );
        }

        /**
         * Get the plugin path.
         * @return string
         */
        public function plugin_path() {
            return untrailingslashit( plugin_dir_path( __FILE__ ) );
        }

        /**
         * @param $settings array
         * @return boolean
         */
        public static function update_settings ( $settings )
        {

            if( is_array($settings) ){
                foreach ($settings as $key=>$value){

                    $option_updated = Huge_Forms_Query::update_option($key, $value );

                }
            }

            return true;
        }

        /**
         * @param $settings array
         * @return array
         */
        public static function get_settings ( )
        {

            $options = Huge_Forms_Query::get_options( );

            return $options;

        }


        /**
         * @param $key array
         * @return string
         */
        public static function get_setting ( $key )
        {

            $option_value = Huge_Forms_Query::get_option( $key );

            return $option_value;

        }

    }

endif;

/**
 * @return Huge_Forms
 */
function Huge_Forms(){
    return Huge_Forms::instance();
}

$GLOBALS['Huge_Forms']  = Huge_Forms();

