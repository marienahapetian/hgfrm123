<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Submission
 */
class Huge_Forms_Submission
{
    /**
     * Submission ID
     *
     * @var int
     */
    private $id;

    /**
     * Submission Fields
     *
     * @var array
     */
    private $fields;

    /**
     * Submission user id
     *
     * @var id
     */
    private $user;

    /**
     * Submission date
     *
     * @var string date
     */
    private $date;

    /**
     * Submission form
     *
     * @var object Huge_Forms_Form $form
     */
    private $form;

    /**
     * Submission data
     *
     * @var
     */
    private $submission;

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

    public function __construct( $id = null )
    {
        if ( $id !== null && absint( $id ) == $id ) {
            global $wpdb;

            $submission = $wpdb->get_row( $wpdb->prepare(
                " SELECT * FROM " . Huge_Forms()->get_table_name( 'submissions' ) . " WHERE id=%d ",
                $id
            ), ARRAY_A );

            if ( ! is_null( $submission ) ) {

                $this->id = $id;

                foreach ( $submission as $submission_option_name => $submission_option_value ) {

                    $function_name = 'set_' . $submission_option_name;

                    if ( method_exists( $this, $function_name ) ) {

                        call_user_func( array( $this, $function_name ), $submission_option_value );

                    }

                }

                $submission_data = $wpdb->get_results( $wpdb->prepare(
                    " SELECT * FROM " . Huge_Forms()->get_table_name( 'submissionFields' ) . " WHERE submission=%d",
                    $id
                ), ARRAY_A );

                $this->set_submission($submission_data);

            }

        }
    }

    /**
     * @return mixed
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function get_user() {
        return $this->user;
    }

    /**
     * @param int $user
     *
     * @return Huge_Forms_Submission
     */
    public function set_user( $user ) {
        if( absint($user)==$user ) {

            $this->user =  new Huge_Forms_Subscriber( $user );

        }

        return $this;
    }

    /**
     * @return array
     */
    public function get_submission_fields() {
        return $this->submission;
    }

    /**
     * @param array $submission
     *
     * @return Huge_Forms_Submission
     */
    public function set_submission( $submission ) {
        $submission_array = array();
        foreach ( $submission as $single_submission ){
            $submission_array[] = array(
                'id'=>$single_submission['id'],
                'field'=>Huge_Forms_Field::create_field_object($single_submission['field']),
                'value'=>$single_submission['value']
            );
        }

        $this->submission = $submission_array;

        return $this;
    }

    /**
     * @return string
     */
    public function get_date() {
        return $this->date;
    }

    /**
     * @param string $date
     *
     * @return Huge_Forms_Submission
     */
    public function set_date( $date ) {

        $this->date =  $date;


        return $this;
    }

    /**
     * @return int
     */
    public function get_form() {
        return $this->form;
    }

    /**
     * @param int $form
     *
     * @return Huge_Forms_Submission
     */
    public function set_form( $form ) {

        if(absint($form)==$form){

            $this->form =  new Huge_Forms_Form( $form );

        }

        return $this;
    }

    /**
     *
     * @return array $fields
     */
    public function get_fields( ) {

        return $this->fields;
    }

    /**
     * @param array $fields
     *
     * @return Huge_Forms_Submission
     */
    public function set_fields( $fields ) {

        if( is_array( $fields )){

            $this->fields =  $fields;

        }

        return $this;
    }



    /**
     * submission data
     */

    public function save( $submission_id = null )
    {

        global $wpdb;

        $submission_data = array();

        $user = new Huge_Forms_Subscriber();

        $user_id = $user->save();

        $this->set_if_not_null( 'form', $this->form->get_id(), $submission_data );

        $this->set_if_not_null( 'user', $user_id, $submission_data );

        $submission_saved = is_null( $this->id )
            ? $wpdb->insert( Huge_Forms()->get_table_name( 'submissions' ), $submission_data )
            : $wpdb->update( Huge_Forms()->get_table_name( 'submissions' ), $submission_data, array( 'id' => $this->id ) );

        if ( $submission_saved !== false && ! isset( $this->id ) ) {

            $this->id = $wpdb->insert_id;

            foreach ($this->fields as $field){

                $submission_field_row = array();

                $submission_field = str_replace('field-','',$field['name']);

                $submission = $field['value'];

                $submission_field_row['submission'] = $this->id;
                $submission_field_row['field'] = $submission_field;
                $submission_field_row['value'] = $submission;

                $wpdb->insert( Huge_Forms()->get_table_name( 'submissionFields' ), $submission_field_row );

            }

            return $this->id;

        } elseif ( $submission_saved !== false && isset( $this->id ) ) {

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
    public function delete( $id )
    {
        global $wpdb;

        if ( absint( $id ) != $id ) {

            throw new Exception( 'Trying to delete a Submission with wrong "id" parameter. Parameter "id" must be not negative integer.' );

        }

        return $wpdb->query( $wpdb->prepare( "DELETE FROM " . Huge_Forms()->get_table_name( 'submissions' ) . " WHERE id =%d", $id ) );
    }

}