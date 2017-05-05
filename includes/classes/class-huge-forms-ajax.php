<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Ajax
 */
class Huge_Forms_Ajax
{

    public static function init()
    {
        add_action( 'wp_ajax_huge_forms_submit_form', array( __CLASS__, 'submit_form' ) );

        add_action( 'wp_ajax_huge_forms_save_form', array( __CLASS__, 'save_form' ) );

        add_action( 'wp_ajax_huge_forms_save_form_settings', array( __CLASS__, 'save_form_settings' ) );

        add_action( 'wp_ajax_huge_forms_copy_form', array( __CLASS__, 'copy_form' ) );

        add_action( 'wp_ajax_huge_forms_save_field', array( __CLASS__, 'save_field' ) );

        add_action( 'wp_ajax_huge_forms_edit_field', array( __CLASS__, 'edit_field' ) );

        add_action( 'wp_ajax_huge_forms_add_field', array( __CLASS__, 'add_field' ) );

        add_action( 'wp_ajax_huge_forms_remove_field', array( __CLASS__, 'remove_field' ) );

        add_action( 'wp_ajax_huge_forms_add_field_option', array( __CLASS__, 'add_field_option' ) );

        add_action( 'wp_ajax_huge_forms_remove_field_option', array( __CLASS__, 'remove_field_option' ) );

        add_action( 'wp_ajax_huge_forms_import_options', array( __CLASS__, 'import_options' ) );

        add_action( 'wp_ajax_huge_forms_duplicate_field', array( __CLASS__, 'duplicate_field' ) );

        add_action( 'wp_ajax_huge_forms_save_settings', array( __CLASS__, 'save_plugin_settings' ) );

        add_action( 'wp_ajax_huge_forms_refresh_simple_captcha', array( __CLASS__, 'create_simple_captcha' ) );

    }

    /**
     * Save editing field data
     */
    public function edit_field()
    {
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'huge_forms_save_field' ) ) {
            wp_die( __( 'Security check failed', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        if ( ! isset( $_REQUEST['id'] ) ) {
            wp_die( __( 'missing "id" parameter', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $id = $_REQUEST['id'];

        if ( absint( $id ) != $id ) {
            wp_die( __( '"id" parameter must be non negative integer', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        if ( ! isset( $_REQUEST['form'] ) ) {
            wp_die( __( 'missing "form" parameter', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $form = $_REQUEST['form'];

        $field_type = $_REQUEST['type_name'];

        $field_class = 'Huge_Forms_Field_'.ucfirst($field_type);

        $field = new $field_class( $id );

        try {
            $field
                ->set_name( $_REQUEST['field_edit_name'] );
        } catch ( Exception $e ) {
            die( $e->getMessage() );
        }

        $saved = $field->save();

        if ( $saved ) {

            $form = new Huge_Forms_Form( $form );

            echo json_encode( array(
                "success"    => 1,
            ) );
            die();

        } else {
            wp_die( 'something went wrong' );
        }

    }

    public static function submit_form()
    {

        $form_id   = $_REQUEST['form_id'];

        $form_data = $_REQUEST['formData'];

        $submission = new Huge_Forms_Submission();

        $submission -> set_form( $form_id )
                    -> set_fields( $form_data );

        $submission_id = $submission->save();

        if( $submission_id ){

            echo json_encode( array(
                "success"    => 1,
                "submission" => $submission_id,
            ) );
            die();

        } else {
            wp_die( 'Submission was not saved' );
        }


    }

    public static function save_form()
    {

        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'huge_forms_save_form' ) ) {
            die( 'security check failed' );
        }

        $form_name = $_REQUEST['form_name'];
        $form_id   = $_REQUEST['form_id'];

        $form_data = $_REQUEST['formData'];

        $fields_settings =array();

        foreach ( $form_data as $input ){
            $name = $input['name'];
            $value = $input['value'];
            $fields_settings[$name]=$value;
        }


        $form = new Huge_Forms_Form( $form_id );

        $form_fields = $form->get_fields();

        foreach ( $form_fields as $field ){

            $field_id = $field->get_id();

            $field->set_properties($fields_settings,$field_id);

            $field->save();

        }

        $form->set_name( $form_name );

        $saved = $form->save();

        if ( $saved ) {
            echo json_encode( array( "success" => 1 ) );
            die();
        } else {
            die( 'something went wrong' );
        }

    }

    /* update form settings */
    public static function save_form_settings()
    {

        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'huge_forms_save_form_settings' ) ) {
            die( 'security check failed' );
        }

        $form_name = $_REQUEST['form_name'];
        $form_id   = $_REQUEST['form_id'];

        $form_settings_data = $_REQUEST['formSettingsData'];

        $settings_array =array();

        foreach ( $form_settings_data as $setting ){
            $name = $setting['name'];
            $value = $setting['value'];
            $settings_array[$name]=$value;
        }

        $form = new Huge_Forms_Form( $form_id );

        $form->set_display_title($settings_array['display-title'])
             ->set_admin_email($settings_array['admin-email'])
             ->set_admin_mail_subject($settings_array['admin-subject'])
             ->set_admin_mail_message($settings_array['admin-message'])
             ->set_user_mail_subject($settings_array['user-subject'])
             ->set_user_mail_message($settings_array['user-message'])
             ->set_email_user($settings_array['email-users'])
             ->set_email_admin($settings_array['email-admin'])
             ->set_theme($settings_array['theme'])
             ->set_from_name($settings_array['email-from-name'])
             ->set_from_email($settings_array['email-from-address'])
             ->set_success_message($settings_array['success-message'])
             ->set_labels_position($settings_array['labels-position'])
             ->set_email_format_error($settings_array['email-format-error'])
             ->set_required_field_error($settings_array['required-field-error'])
             ->set_upload_size_error($settings_array['upload-size-error'])
             ->set_upload_format_error($settings_array['upload-format-error']);


        $saved = $form->save_settings();

        if ( $saved ) {
            echo json_encode( array( "success" => 1 ) );
            die();
        } else {
            die( 'something went wrong' );
        }

    }

    /**
     * Save field
     */
    public function save_field()
    {
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'huge_forms_save_field' ) ) {
            wp_die( __( 'Security check failed', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        if ( ! isset( $_REQUEST['form'] ) ) {
            wp_die( __( 'missing "form" parameter', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $form = $_REQUEST['form'];

        $type = $_REQUEST['type'];

        $order = $_REQUEST['order'];

        $type_name = $_REQUEST['type_name'];

        $field_class = 'Huge_Forms_Field_'.ucfirst($type_name);

        $field = new $field_class();


        try {
            $field ->set_type( $type )
                   ->set_ordering($order)
                   ->set_form($form);
        } catch ( Exception $e ) {
            die( $e->getMessage() );
        }

        $saved = $field->save();

        if ( $saved ) {
            echo json_encode( array(
                "success"    => 1,
                'last_id'    => $saved,
                'settingsBlock'=> $field->settings_block(),
                'fieldBlock'=> $field->field_block(),
            ) );
            die();

        } else {
            wp_die( 'something went wrong' );
        }

    }

    public function remove_field(){
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'huge_forms_remove_field' ) ) {
            wp_die( __( 'Security check failed', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        if ( ! isset( $_REQUEST['id'] ) ) {
            wp_die( __( 'missing "id" parameter', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $id = $_REQUEST['id'];

        if ( absint( $id ) != $id ) {
            wp_die( __( '"id" parameter must be non negative integer', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $field_removed = Huge_Forms_Field::delete($id);

        if ( $field_removed ) {

            echo json_encode( array(
                "success"    => 1,
            ) );
            die();

        } else {
            wp_die( 'something went wrong' );
        }

    }

    public function duplicate_field(){
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'huge_forms_duplicate_field' ) ) {
            wp_die( __( 'Security check failed', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        if ( ! isset( $_REQUEST['id'] ) ) {
            wp_die( __( 'missing "id" parameter', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $id = $_REQUEST['id'];

        if ( absint( $id ) != $id ) {
            wp_die( __( '"id" parameter must be non negative integer', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $field = Huge_Forms_Field::create_field_object( $id );

        $form = $_REQUEST['form'];

        if(absint($form)!=$form){
            wp_die( __( 'You are trying to edit a wrong form', HUGE_FORMS_TEXT_DOMAIN ) );

        }


        $new_field = $field->unset_id();

        $new_field = $field->set_form( $form );

        $new_field_id = $new_field->save();

        if ( $new_field_id ) {

            echo json_encode( array(
                "success"    => 1,
                "field"      => $new_field_id,
                'settingsBlock'=> $new_field->settings_block(),
                'fieldBlock'=> $new_field->field_block(),
            ) );
            die();

        } else {
            wp_die( 'something went wrong' );
        }

    }

    public function add_field_option()
    {
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'huge_forms_add_field_option' ) ) {
            wp_die( __( 'Security check failed', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        if ( ! isset( $_REQUEST['field'] ) ) {
            wp_die( __( 'missing "field" parameter', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $field = $_REQUEST['field'];

        if ( absint( $field ) != $field ) {
            wp_die( __( '"field" parameter must be non negative integer', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $option = new Huge_Forms_Field_Option();

        $new_option = $option->set_field( $field);

        $new_option_id = $new_option->save();

        if ( $new_option_id ) {

            echo json_encode( array(
                "success"    => 1,
                "option"      => $new_option_id,
                'option_row'  => $new_option->option_row()
            ) );
            die();

        } else {
            wp_die( 'something went wrong' );
        }

    }

    public function import_options()
    {
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'huge_forms_import_options' ) ) {
            wp_die( __( 'Security check failed', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        if ( ! isset( $_REQUEST['field'] ) ) {
            wp_die( __( 'missing "field" parameter', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $field = $_REQUEST['field'];

        if ( absint( $field ) != $field ) {
            wp_die( __( '"field" parameter must be non negative integer', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $options = explode(',',$_REQUEST['options']);

        $options_html = '';

        foreach ( $options as $option ){
            $option_array = explode('#',$option);
            $name = str_replace('{','',$option_array[0]);
            $value = str_replace('}','',$option_array[1]);

            $option_object = new Huge_Forms_Field_Option();

            $new_option = $option_object->set_field( $field);
            $new_option = $new_option->set_name( $name);
            $new_option = $new_option->set_value( $value);

            $new_option_id = $new_option->save();

            if ( $new_option_id ) {

                $options_html .= $new_option->option_row();

            } else {
                wp_die( 'something went wrong during import, check the syntax' );
            }

        }

        echo json_encode( array(
            "success"      => 1,
            "options_rows" => $options_html,
        ) );
        die();





    }

    public function remove_field_option()
    {
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'huge_forms_remove_field_option' ) ) {
            wp_die( __( 'Security check failed', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        if ( ! isset( $_REQUEST['option'] ) ) {
            wp_die( __( 'missing "option" parameter', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $option = $_REQUEST['option'];

        if ( absint( $option ) != $option ) {
            wp_die( __( '"option" parameter must be non negative integer', HUGE_FORMS_TEXT_DOMAIN ) );
        }

        $option_removed = Huge_Forms_Field_Option::delete($option);

        if ( $option_removed ) {

            echo json_encode( array(
                "success"    => 1,
            ) );
            die();

        } else {
            wp_die( 'something went wrong' );
        }


    }

    public function save_plugin_settings()
    {
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'huge_forms_save_settings' ) ) {
            die( 'security check failed' );
        }

        $settings_data = $_REQUEST['formData'];

        $settings =array();

        foreach ( $settings_data as $input ){
            $name = $input['name'];
            $value = $input['value'];
            $settings[$name]=$value;
        }

        $saved = Huge_Forms::update_settings( $settings );

        if ( $saved ) {
            echo json_encode( array( "success" => 1 ) );
            die();
        } else {
            die( 'something went wrong' );
        }

    }

    public static function create_simple_captcha()
    {
        return Huge_Forms_Field_Captcha::huge_forms_create_simple_captcha();
    }


}