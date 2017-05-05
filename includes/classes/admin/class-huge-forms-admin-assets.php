<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Admin_Assets
 */
class Huge_Forms_Admin_Assets
{

    public static function init() {
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );
    }

    /**
     * @param $hook
     */
    public static function admin_styles( $hook ){

        if( $hook === Huge_Forms()->admin->pages['main_page'] || $hook === Huge_Forms()->admin->pages['themes'] || $hook === Huge_Forms()->admin->pages['settings'] || $hook === Huge_Forms()->admin->pages['submissions'] ){
            wp_enqueue_style( "font_awesome", 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false );

            wp_enqueue_style( 'huge_forms_admin_styles', Huge_Forms()->plugin_url().'/assets/css/admin/main.css' );

            wp_enqueue_style( 'roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;subset=cyrillic' );

        }

        if($hook === Huge_Forms()->admin->pages['featured_plugins']){
            wp_enqueue_style( 'hugeit_forms_featured_plugins', Huge_Forms()->plugin_url().'/assets/css/admin/featured-plugins.css' );
        }

        if($hook === Huge_Forms()->admin->pages['settings']){
            wp_enqueue_style( 'huge-forms-settings', Huge_Forms()->plugin_url().'/assets/css/admin/settings.css' );
        }

        if($hook === Huge_Forms()->admin->pages['submissions']){
            wp_enqueue_style( 'huge-forms-submissions', Huge_Forms()->plugin_url().'/assets/css/admin/submissions.css' );
        }

        if(isset($_GET['task']) && $_GET['task']=='edit_form_settings'){

            wp_enqueue_style( 'huge-forms-admin-tabs', Huge_Forms()->plugin_url().'/assets/css/admin/component.css' );

            wp_dequeue_style( 'jquery-ui' );

        }

    }

    /**
     * @param $hook
     */
    public static function admin_scripts( $hook ){

        if( $hook === Huge_Forms()->admin->pages['main_page']  ){

            $suffix = SCRIPT_DEBUG ? '' : '.min';

            wp_enqueue_media();

            wp_enqueue_script( 'jquery' );

            wp_enqueue_script( 'jquery-ui-core' );

            if( isset($_GET['task']) && $_GET['task'] == 'edit_form' ){

                wp_enqueue_script( 'huge-forms-admin-form-save', Huge_Forms()->plugin_url().'/assets/js/admin/form-save.js', array( 'jquery' ), false, true );

            }


            if(isset($_GET['task']) && $_GET['task']=='edit_form_settings'){

                wp_enqueue_script( 'huge-forms-admin-tabs-js', Huge_Forms()->plugin_url().'/assets/js/admin/cbpFWTabs.js', array( 'jquery' ), false, true );

                wp_enqueue_script( 'huge-forms-settings-js', Huge_Forms()->plugin_url().'/assets/js/admin/form-settings.js', array( 'jquery' ), false, true );

                wp_dequeue_script( 'jquery-ui-core' );

            }

            wp_enqueue_script( 'huge-forms-admin-js', Huge_Forms()->plugin_url().'/assets/js/admin/main.js', array( 'jquery' ), false, true );
        }

        if( in_array( $hook, array('post.php', 'post-new.php') ) ){
            wp_enqueue_script( "huge-forms-inline-popup", Huge_Forms()->plugin_url()."/assets/js/admin/inline-popup.js", array( 'jquery' ), false, true );
        }

        if($hook === Huge_Forms()->admin->pages['settings']){
            wp_enqueue_script( 'huge-forms-settings', Huge_Forms()->plugin_url().'/assets/js/admin/settings.js',array( 'jquery' ), false, true );
        }


        self::localize_scripts();

    }

    public static function localize_scripts(){

        wp_localize_script( 'huge-forms-admin-form-save', 'formSave',array(
            'nonce'=>wp_create_nonce( 'huge_forms_save_form' ),
        ) );

        wp_localize_script( 'huge-forms-inline-popup', 'inlinePopup',array(
            'nonce'=>wp_create_nonce( 'huge_forms_save_shortcode_options' ),
        ) );

        wp_localize_script( 'huge-forms-admin-form-save', 'field',array(
            'removeNonce'=>wp_create_nonce( 'huge_forms_remove_field' ),
            'duplicateNonce'=>wp_create_nonce( 'huge_forms_duplicate_field' ),
            'saveNonce'=>wp_create_nonce( 'huge_forms_save_field' ),
            'addOptionNonce'=>wp_create_nonce( 'huge_forms_add_field_option' ),
            'removeOptionNonce'=>wp_create_nonce( 'huge_forms_remove_field_option' ),
            'importOptionsNonce'=>wp_create_nonce( 'huge_forms_import_options' ),
        ) );

        wp_localize_script( 'huge-forms-settings-js', 'form',array(
            'saveSettingsNonce'=>wp_create_nonce( 'huge_forms_save_form_settings' ),
        ) );

        wp_localize_script( 'huge-forms-settings', 'settingsSave',array(
            'nonce'=>wp_create_nonce( 'huge_forms_save_settings' ),
        ) );



    }


}