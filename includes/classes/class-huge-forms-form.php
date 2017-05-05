<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Form
 */
class Huge_Forms_Form
{
    /**
     * Form ID
     *
     * @var int
     */
    private $id;

    /**
     * Form Name
     *
     * @var string
     */
    private $name;

    /**
     * Fields of current form
     * Array of Huge_Forms_Field instances
     *
     * @var array
     */
    private $fields;

    /**
     * Theme of current form
     *
     * @var string
     */
    private $theme;

    /**
     * show form title
     *
     * @var int
     */
    private $display_title;

    /**
     * action on submit
     *
     * @var Huge_Forms_Onsubmit_Action
     */
    private $action_onsubmit;

    /**
     * success message
     *
     * @var string
     */
    private $success_message;

    /**
     * admin email
     *
     * @var string
     */
    private $admin_email;

    /**
     * admin subject
     *
     * @var string
     */
    private $admin_subject;

    /**
     * admin message
     *
     * @var string
     */
    private $admin_message;

    /**
     * user mail subject
     *
     * @var string
     */
    private $user_subject;

    /**
     * user mail message
     *
     * @var string
     */
    private $user_message;

    /**
     * Emails are Sent from this Name
     *
     * @var string
     */
    private $from_name;

    /**
     * Emails are Sent from this Email
     *
     * @var string
     */
    private $from_email;

    /**
     * send email to users
     *
     * @var int 0,1
     */
    private $email_users;

    /**
     * send email to admin
     *
     * @var int 0,1
     */
    private $email_admin;

    /**
     * labels position
     *
     * @var int
     */
    private $labels_position;

    /**
     * email format wrong error message
     *
     * @var string
     */
    private $email_format_error;

    /**
     * required field empty error message
     *
     * @var string
     */
    private $required_empty_error;

    /**
     * upload size exceeded error message
     *
     * @var string
     */
    private $upload_size_error;

    /**
     * upload file format error message
     *
     * @var string
     */
    private $upload_format_error;

    /**
     * Huge_Forms_Form constructor.
     *
     * @param null|int $id
     *
     * @throws Error
     */
    public function __construct( $id = null ) {

        if ( $id !== null && absint( $id ) == $id ) {
            global $wpdb;

            $form = $wpdb->get_row( $wpdb->prepare(
                " SELECT * FROM " . Huge_Forms()->get_table_name( 'forms' ) . " WHERE id=%d ",
                $id
            ), ARRAY_A );

            if ( ! is_null( $form ) ) {

                $this->id = $id;

                foreach ( $form as $form_option_name => $form_option_value ) {

                    $function_name = 'set_' . $form_option_name;

                    if ( method_exists( $this, $function_name ) ) {

                        call_user_func( array( $this, $function_name ), $form_option_value );

                    }

                }

                $this->fields  = Huge_Forms_Query::get_form_fields( $this->id  );

            }

        } else {

            $this->name = __( 'New Form', HUGE_FORMS_TEXT_DOMAIN );

            $this->display_title = 1;

        }

    }

    /**
     * Sets $array[$key] = $value if $value is not NULL.
     *
     * @param $key
     * @param $value
     * @param $array
     */
    private function set_if_not_null( $key, $value, &$array )
    {
        if ( $value !== null ) {
            $array[ $key ] = $value;
        }
    }

    /**
     * Sets $array[$key] = $value if $value is not NULL.
     *
     * @param $key
     * @param $value
     * @param $array
     */
    private function set_checkbox( $key, $value, &$array )
    {
        if ( $value == null ) {
            $array[ $key ] = '0';
        } else {
            $array[ $key ] = '1';
        }
    }

    /**
     * @return mixed
     */
    public function get_id() {
        return $this->id;
    }

    /**
     *
     */
    public function unset_id() {
         $this->id = null;

         return $this;
    }

    /**
     * @return string
     */
    public function get_name() {
        return (!empty($this->name) ? $this->name : __( '(no title)', HUGE_FORMS_TEXT_DOMAIN ) );
    }

    /**
     * @param string $name
     *
     * @return Huge_Forms_Form
     */
    public function set_name( $name ) {
        $this->name = sanitize_text_field( $name );

        return $this;
    }

    /**
     * Edit link for current form
     */
    public function get_edit_link() {

        if ( is_null( $this->id ) ) {
            return false;
        }

        $link = admin_url( 'admin.php?page=huge_forms&task=edit_form&id=' . $this->id );

        $link = wp_nonce_url( $link, 'huge_forms_edit_form_' . $this->id );

        $link = html_entity_decode( $link );

        return $link;

    }

    /**
     * @return Huge_Forms_Field[]
     */
    public function get_fields() {
        return $this->fields;
    }

    /**
     * @param Huge_Forms_Field[] $fields
     *
     * @return Huge_Forms_Form
     * @throws Exception
     */
    public function set_fields( $fields ) {
        foreach ( $fields as $field ) {
            if ( ! ( $field instanceof Huge_Forms_Field ) ) {
                throw new Exception( 'Field must be an instance of Huge_Forms_Field class.' );
            }

        }

        $this->fields = $fields;

        return $this;
    }

    /**
     * return string 0|1
     */
    public function get_display_title(){
        return $this->display_title;
    }

    /**
     * @param $value int 0,1
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_display_title($value)
    {
        if ( ! in_array($value,array(0,1)) ) {
            throw new Exception( 'Wrong value for show title. Value must be int 0|1' );
        }

        $this->display_title=$value;

        return $this;
    }

    /**
     * return Huge_Forms_Onsubmit_Action
     */
    public function get_action_onsubmit(){

        return $this->action_onsubmit;

    }

    /**
     * @param $value int
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_action_onsubmit($value)
    {
        if ( absint( $value ) != $value) {

            throw new Exception( 'Wrong value for onsubmit action. Value must be int non negative' );

        } else{

            $this->action_onsubmit = new Huge_Forms_Onsubmit_Action( $value );

        }

        return $this;
    }

    /**
     * return string
     */
    public function get_success_message(){
        return $this->success_message;
    }

    /**
     * @param $value string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_success_message($value)
    {

        $this->success_message=sanitize_text_field($value);

        return $this;
    }

    /**
     * return Huge_Forms_Theme
     */
    public function get_theme(){
        return $this->theme;
    }

    /**
     * @param $id int
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_theme($id)
    {

        if( absint($id) == $id ){
            $this->theme = new Huge_Forms_Theme( $id );
        }

        return $this;
    }

    /**
     * return string
     */
    public function get_email_user(){
        return $this->email_users;
    }

    /**
     * @param $value int
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_email_user($value)
    {

        if( in_array($value,array(0,1))){

            $this->email_users=$value;

        }

        return $this;
    }

    /**
     * return string
     */
    public function get_email_admin(){
        return $this->email_admin;
    }

    /**
     * @param $value int
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_email_admin($value)
    {

        if( in_array($value,array(0,1))){

            $this->email_admin=$value;

        }

        return $this;
    }

    /**
     * return string
     */
    public function get_from_name(){
        return $this->from_name;
    }

    /**
     * @param $value string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_from_name($value)
    {

        $this->from_name=sanitize_text_field($value);

        return $this;
    }

    /**
     * return string
     */
    public function get_from_email(){
        return $this->from_email;
    }

    /**
     * @param $email string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_from_email($email)
    {

        $this->from_email=sanitize_email( $email );

        return $this;
    }

    /**
     * return string
     */
    public function get_admin_email(){
        return $this->admin_email;
    }

    /**
     * @param $email string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_admin_email($email)
    {

        $this->admin_email=sanitize_email( $email );

        return $this;
    }

    /**
     * return string
     */
    public function get_admin_mail_subject(){
        return $this->admin_subject;
    }

    /**
     * @param $subject string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_admin_mail_subject($subject)
    {

        $this->admin_subject = sanitize_text_field( $subject );

        return $this;
    }

    /**
     * return string
     */
    public function get_admin_mail_message(){
        return $this->admin_message;
    }

    /**
     * @param $message string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_admin_mail_message($message)
    {

        $this->admin_message = sanitize_text_field( $message );

        return $this;
    }

    /**
     * return string
     */
    public function get_user_mail_subject(){
        return $this->user_subject;
    }

    /**
     * @param $subject string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_user_mail_subject($subject)
    {

        $this->user_subject = sanitize_text_field( $subject );

        return $this;
    }

    /**
     * return string
     */
    public function get_user_mail_message(){
        return $this->user_message;
    }

    /**
     * @param $message string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_user_mail_message($message)
    {

        $this->user_message = sanitize_text_field( $message );

        return $this;
    }

    /**
     * return Huge_Forms_Label_Position
     */
    public function get_labels_position(){
        return $this->labels_position;
    }

    /**
     * @param $position int
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_labels_position($position)
    {

        if(absint($position)==$position){

            $this->labels_position = new Huge_Forms_Label_Position( $position );

        }

        return $this;
    }

    /**
     * return string
     */
    public function get_email_format_error()
    {
        return $this->email_format_error;
    }

    /**
     * @param $email_format_error
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_email_format_error($email_format_error)
    {

        $this->email_format_error = sanitize_text_field( $email_format_error );

        return $this;
    }

    /**
     * return string
     */
    public function get_required_field_error()
    {
        return $this->required_empty_error;
    }

    /**
     * @param $required_error string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_required_field_error($required_error)
    {

        $this->required_empty_error = sanitize_text_field( $required_error );

        return $this;
    }

    /**
     * return string
     */
    public function get_upload_size_error()
    {
        return $this->upload_size_error;
    }

    /**
     * @param $upload_size_error string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_upload_size_error($upload_size_error)
    {

        $this->upload_size_error = sanitize_text_field( $upload_size_error );

        return $this;
    }

    /**
     * return string
     */
    public function get_upload_format_error()
    {
        return $this->upload_format_error;
    }

    /**
     * @param $upload_format_error string
     * return Huge_Forms_Form
     * @throws Exception
     */
    public function set_upload_format_error($upload_format_error)
    {

        $this->upload_format_error = sanitize_text_field( $upload_format_error );

        return $this;
    }

    /**
     * @param $id
     *
     * @return false|int
     * @throws Exception
     */
    public static function delete( $id )
    {
        global $wpdb;

        if ( absint( $id ) != $id ) {

            throw new Exception( 'Trying to delete a Form with wrong "id" parameter. Parameter "id" must be not negative integer.' );

        }

        return $wpdb->query( $wpdb->prepare( "DELETE FROM " . Huge_Forms()->get_table_name( 'forms' ) . " WHERE id =%d", $id ) );
    }

    /**
    * form data
    */
    public function save( $form_id=null )
    {

        global $wpdb;

        $form_data = array();

        $this->set_if_not_null( 'name', $this->name, $form_data );

        $this->set_if_not_null( 'id', $form_id, $form_data );

        $form_data = is_null( $this->id )
            ? $wpdb->insert( Huge_Forms()->get_table_name( 'forms' ), $form_data )
            : $wpdb->update( Huge_Forms()->get_table_name( 'forms' ), $form_data, array( 'id' => $this->id ) );


        if ( $form_data !== false && ! isset( $this->id ) ) {

            $this->id = $wpdb->insert_id;

            return $this->id;

        } elseif ( $form_data !== false && isset( $this->id ) ) {

            return $this->id;

        } else {

            return false;

        }
    }

    /**
     * form settings save
     */
    public function save_settings( $form_id )
    {

        global $wpdb;

        $form_settings_data = array();

        $this->set_checkbox( 'display_title', $this->display_title, $form_settings_data );
        $this->set_if_not_null( 'admin_email', $this->admin_email, $form_settings_data );
        $this->set_if_not_null( 'admin_mail_subject', $this->admin_mail_subject, $form_settings_data );
        $this->set_if_not_null( 'admin_mail_message', $this->admin_mail_message, $form_settings_data );
        $this->set_if_not_null( 'user_mail_message', $this->user_mail_message, $form_settings_data );
        $this->set_if_not_null( 'user_mail_subject', $this->user_mail_subject, $form_settings_data );
        $this->set_checkbox( 'email_user', $this->email_user, $form_settings_data );
        $this->set_checkbox( 'email_admin', $this->email_admin, $form_settings_data );
        $this->set_if_not_null( 'theme', $this->theme->get_id(), $form_settings_data );
        $this->set_if_not_null( 'from_name', $this->from_name, $form_settings_data );
        $this->set_if_not_null( 'from_email', $this->from_email, $form_settings_data );
        $this->set_if_not_null( 'success_message', $this->success_message, $form_settings_data );
        $this->set_if_not_null( 'labels_position', $this->labels_position->get_id(), $form_settings_data );
        $this->set_if_not_null( 'email_format_error', $this->email_format_error, $form_settings_data );
        $this->set_if_not_null( 'required_field_error', $this->required_field_error, $form_settings_data );
        $this->set_if_not_null( 'upload_size_error', $this->upload_size_error, $form_settings_data );
        $this->set_if_not_null( 'upload_format_error', $this->upload_format_error, $form_settings_data );

        $form_data = is_null( $this->id )
            ? $wpdb->insert( Huge_Forms()->get_table_name( 'forms' ), $form_settings_data )
            : $wpdb->update( Huge_Forms()->get_table_name( 'forms' ), $form_settings_data, array( 'id' => $this->id ) );


        if ( $form_data !== false && ! isset( $this->id ) ) {

            $this->id = $wpdb->insert_id;

            return $this->id;

        } elseif ( $form_data !== false && isset( $this->id ) ) {

            return $this->id;

        } else {

            return false;

        }
    }

    /**
     * @param $id
     *
     * @return false|int
     * @throws Exception
     */
    public static function copy_form( $id )
    {
        if ( absint($id) != $id ) {
            die( '"id" parameter must be not negative integer.' );
        }

        $form = new Huge_Forms_Form( $id );

        $new_form = clone $form;

        $new_form->set_name( 'Copy of ' . $new_form->get_name() );

        $new_form->unset_id();

        $new_form->save();

        $fields = $form->get_fields();

        if ( ! empty( $fields ) ) {
            foreach ( $fields as $field ) {
                $new_field = clone $field;

                $new_field->set_form( $new_form->get_id() );

                $new_field->save();

            }
        }


        echo json_encode( array(
            "success"           => 1,
            'new_form_id'        => $new_form->get_id(),
            "new_form_edit_link" => $new_form->get_edit_link()
        ) );
        die();
    }

}