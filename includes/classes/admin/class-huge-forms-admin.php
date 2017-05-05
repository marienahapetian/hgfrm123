<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Admin
 */
class Huge_Forms_Admin extends Huge_Forms_Admin_Listener
{
    /**
     * Array of pages in admin
     *
     * @var array
     */
    public $pages;

    /**
     * Huge_Forms_Admin constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     *
     */
    private function init()
    {

        add_action( 'wp_loaded', array( $this, 'wp_loaded_actions' ) , 10, 0);

        add_action( 'admin_menu', array( $this, 'admin_menu' ) , 1);

        add_action( 'admin_init', array( __CLASS__, 'delete_form' ) , 1);

        add_action( 'admin_init', array( __CLASS__, 'duplicate_form' ) , 1);

        add_action( 'admin_init', array( __CLASS__, 'create_form' ) , 1);

    }

    /**
     * Add admin menu pages
     */
    public function admin_menu()
    {

        $this->pages['main_page'] = add_menu_page( __( 'Huge Forms', HUGE_FORMS_TEXT_DOMAIN ), __( 'Huge Forms', HUGE_FORMS_TEXT_DOMAIN ), 'manage_options', 'huge_forms', array(
            $this,
            'init_main_page'
        ),  Huge_Forms()->plugin_url() . '/assets/images/forms_logo.png' );

        $this->pages['settings'] = add_submenu_page( 'huge_forms', __( 'Settings', HUGE_FORMS_TEXT_DOMAIN ), __( 'Settings', HUGE_FORMS_TEXT_DOMAIN ), 'manage_options', 'huge_forms_settings', array(
            $this,
            'init_settings_page'
        ) );

        $this->pages['themes'] = add_submenu_page( 'huge_forms', __( 'Form Themes', HUGE_FORMS_TEXT_DOMAIN ), __( 'Form Themes', HUGE_FORMS_TEXT_DOMAIN ), 'manage_options', 'huge_forms_themes', array(
            $this,
            'init_themes_page'
        ) );

        $this->pages['submissions'] = add_submenu_page( 'huge_forms', __( 'Submissions', HUGE_FORMS_TEXT_DOMAIN ), __( 'Submissions', HUGE_FORMS_TEXT_DOMAIN ), 'manage_options', 'huge_forms_submissions', array(
            $this,
            'init_submissions_page'
        ) );


        $this->pages['addons'] = add_submenu_page( 'huge_forms', __( 'AddOns', HUGE_FORMS_TEXT_DOMAIN ), __( 'AddOns', HUGE_FORMS_TEXT_DOMAIN ), 'manage_options', 'huge_forms_addons', array(
            $this,
            'init_addons_page'
        ) );

        $this->pages['featured_plugins'] = add_submenu_page( 'huge_forms', __( 'Featured Plugins', HUGE_FORMS_TEXT_DOMAIN ), __( 'Featured plugins', HUGE_FORMS_TEXT_DOMAIN ), 'manage_options', 'huge_forms_featured_plugins', array(
            $this,
            'init_featured_plugins_page'
        ) );
    }

    /**
     * Initialize main page
     */
    public function init_main_page() {

        Huge_Forms_Template_Loader::get_template('admin/header-banner.php');

        if ( ! isset( $_GET['task'] ) ) {

            Huge_Forms_Template_Loader::get_template( 'admin/forms-list.php' );

        } else {

            $task = $_GET['task'];

            switch ( $task ) {
                case 'edit_form':

                    if ( ! isset( $_GET['id'] ) ) {

                        Huge_Forms()->admin->print_error( __( 'Missing "id" parameter.', HUGE_FORMS_TEXT_DOMAIN ) );

                    }

                    $id = absint( $_GET['id'] );

                    if ( ! $id ) {

                        Huge_Forms()->admin->print_error( __( '"id" parameter must be not negative integer.', HUGE_FORMS_TEXT_DOMAIN ) );

                    }

                    if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'huge_forms_edit_form_' . $id ) ) {

                        Huge_Forms()->admin->print_error( __( 'Security check failed.', HUGE_FORMS_TEXT_DOMAIN ) );

                    }

                    $form = new Huge_Forms_Form( $id );

                    Huge_Forms_Template_Loader::get_template( 'admin/edit-form.php', array( 'form' => $form  ) );

                    break;
                case 'choose_form_template':

                    if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'huge_forms_choose_form_template'  ) ) {

                        Huge_Forms()->admin->print_error( __( 'Security check failed.', HUGE_FORMS_TEXT_DOMAIN ) );

                    }

                    $form = new Huge_Forms_Form( );

                    Huge_Forms_Template_Loader::get_template( 'admin/choose-form-template.php', array( 'form' => $form ) );

                    break;
                case 'edit_form_settings':
                    $id = $_GET['id'];

                    if( absint($id)!=$id){

                        Huge_Forms()->admin->print_error( __( 'Watcha tryna do here?!', HUGE_FORMS_TEXT_DOMAIN ) );

                    }

                    if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'huge_forms_edit_form_settings_'.$id  ) ) {

                        Huge_Forms()->admin->print_error( __( 'Security check failed.', HUGE_FORMS_TEXT_DOMAIN ) );

                    }

                    $form = new Huge_Forms_Form( $id );

                    Huge_Forms_Template_Loader::get_template( 'admin/form-settings.php', array( 'form' => $form ) );

                    break;

            }

        }

    }

    /**
     * Handle some actions when wordpress is loaded
     * We call this functions when wp is loaded as we will redirect user to another page so we have to do our staff before headers are sent that's why we use wp_loaded hook
     */
    public function wp_loaded_actions() {

        if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'huge_forms' || ! isset( $_GET['task'] ) ) {
            return;
        }

        $task = $_GET['task'];


    }

    /**
     * Initialize featured plugins page
     */
    public function init_featured_plugins_page()
    {

        Huge_Forms_Template_Loader::get_template( 'admin/featured-plugins.php' );

    }

    public function init_licensing_plugins_page()
    {

        Huge_Forms_Template_Loader::get_template( 'admin/licensing.php' );

    }

    public function init_submissions_page()
    {

        if (!isset($_GET['task'])) {

            Huge_Forms_Template_Loader::get_template('admin/submissions.php');

        } else {

            $task = $_GET['task'];

            switch ($task) {
                case "remove_submission":
                    if (!isset($_GET['id'])) {
                        wp_die(__('"id" parameter is required', HUGE_FORMS_TEXT_DOMAIN));
                    }

                    $id = $_GET['id'];

                    if (absint($id) != $id) {
                        wp_die(__('"id" parameter must be non negative integer', HUGE_FORMS_TEXT_DOMAIN));
                    }

                    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'huge_forms_remove_submission_' . $id)) {
                        wp_die(__('Security check failed', HUGE_FORMS_TEXT_DOMAIN));
                    }

                    Huge_Forms_Submission::delete($id);

                    $location = admin_url('admin.php?page=huge_forms_submissions');


                    header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
                    header("Location: $location");

                    exit;

                    break;
                case "view_submission":
                    if (!isset($_GET['id'])) {
                        wp_die(__('"id" parameter is required', HUGE_FORMS_TEXT_DOMAIN));
                    }

                    $id = $_GET['id'];

                    if (absint($id) != $id) {
                        wp_die(__('"id" parameter must be non negative integer', HUGE_FORMS_TEXT_DOMAIN));
                    }

                    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'huge_forms_view_submission_' . $id)) {
                        wp_die(__('Security check failed', HUGE_FORMS_TEXT_DOMAIN));
                    }


                    $submission = new Huge_Forms_Submission( $id );

                    Huge_Forms_Template_Loader::get_template( 'admin/view-submission.php', array( 'submission' => $submission ) );

                    break;
            }

        }
    }

    public function init_addons_page()
    {

        Huge_Forms_Template_Loader::get_template( 'admin/addons.php' );

    }

    public function init_settings_page()
    {

        Huge_Forms_Template_Loader::get_template( 'admin/settings.php' );

    }

    public function init_themes_page()
    {

        Huge_Forms_Template_Loader::get_template('admin/header-banner.php');

        if ( ! isset( $_GET['task'] ) ) {

            Huge_Forms_Template_Loader::get_template( 'admin/themes.php' );

        } else {

            $task = $_GET['task'];

            switch ( $task ) {

                case 'edit_theme':

                    if ( ! isset( $_GET['id'] ) ) {

                        Huge_Forms()->admin->print_error( __( 'Missing "id" parameter.', HUGE_FORMS_TEXT_DOMAIN ) );

                    }

                    $id = absint( $_GET['id'] );

                    if ( ! $id ) {

                        Huge_Forms()->admin->print_error( __( '"id" parameter must be not negative integer.', HUGE_FORMS_TEXT_DOMAIN ) );

                    }

                    if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'huge_forms_edit_theme_' . $id ) ) {

                        Huge_Forms()->admin->print_error( __( 'Security check failed.', HUGE_FORMS_TEXT_DOMAIN ) );

                    }

                    $theme = new Huge_Forms_Theme( $id );

                    Huge_Forms_Template_Loader::get_template( 'admin/edit-theme.php', array( 'theme' => $theme ) );

                    break;


            }

        }

    }


    public function print_error( $error_message, $die = true )
    {

        $str = sprintf( '<div class="error"><p>%s&nbsp;<a href="#" onclick="window.history.back()">%s</a></p></div>', $error_message, __( 'Go back', HUGE_FORMS_TEXT_DOMAIN ) );

        if ( $die ) {

            wp_die( $str );

        } else {
            echo $str;
        }

    }

    public static function delete_form(){
        if(!self::is_request('huge_forms','remove_form','GET')){
            return;
        }

        if ( ! isset( $_GET['id'] ) ) {
            wp_die( __( '"id" parameter is required', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $id = $_GET['id'];

        if ( absint( $id ) != $id ) {
            wp_die( __( '"id" parameter must be non negative integer', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'huge_forms_remove_form_' . $id ) ) {
            wp_die( __( 'Security check failed', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        Huge_Forms_Form::delete( $id );

        $location = admin_url( 'admin.php?page=huge_forms' );


        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header("Location: $location");

        exit;

    }


    public static function duplicate_form(){
        if(!self::is_request('huge_forms','duplicate_form','GET')){
            return;
        }

        if ( ! isset( $_GET['id'] ) ) {

            Huge_Forms()->admin->print_error( __( 'Missing "id" parameter.', HUGE_FORMS_TEXT_DOMAIN ) );

        }

        $id = absint( $_GET['id'] );

        if ( ! $id ) {

            Huge_Forms()->admin->print_error( __( '"id" parameter must be not negative integer.', HUGE_FORMS_TEXT_DOMAIN ) );

        }

        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'huge_forms_duplicate_form_'.$id  ) ) {

            Huge_Forms()->admin->print_error( __( 'Security check failed.', HUGE_FORMS_TEXT_DOMAIN ) );

        }

        $form = new Huge_Forms_Form( $id );

        $fields = $form -> get_fields();

        $form->unset_id();

        $form->set_name('Copy of '.$form->get_name());

        $form = $form->save();

        /**
         * after the form is created we need to redirect user to the edit page
         */
        if ( $form && is_int( $form ) ) {
            /* copy form fields to the new form */
            if( ! empty($fields) ) {
                foreach ($fields as $field){
                    $newfield = clone $field;

                    $newfield -> set_form( $form );

                    $newfield -> save();
                }
            }

            $location = admin_url( 'admin.php?page=huge_forms&task=edit_form&id=' . $form );

            $location = wp_nonce_url( $location, 'huge_forms_edit_form_' . $form );

            $location = html_entity_decode( $location );

            header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            header("Location: $location");

            exit;

        } else {

            wp_die( __( 'Problems occured while creating new form.', HUGE_FORMS_TEXT_DOMAIN ) );

        }

    }

    public static function create_form()
    {
        if(!self::is_request('huge_forms','create_new_form','GET')){
            return;
        }

        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'huge_forms_create_new_form'  ) ) {

            Huge_Forms()->admin->print_error( __( 'Security check failed.', HUGE_FORMS_TEXT_DOMAIN ) );

        }

        $form = new Huge_Forms_Form( );

        $form = $form->save();

        /**
         * after the form is created we need to redirect user to the edit page
         */
        if ( $form && is_int( $form ) ) {

            $location = admin_url( 'admin.php?page=huge_forms&task=edit_form&id=' . $form );

            $location = wp_nonce_url( $location, 'huge_forms_edit_form_' . $form );

            $location = html_entity_decode( $location );

            header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            header("Location: $location");

            exit;

        } else {

            wp_die( __( 'Problems occured while creating new form.', HUGE_FORMS_TEXT_DOMAIN ) );

        }

    }

}