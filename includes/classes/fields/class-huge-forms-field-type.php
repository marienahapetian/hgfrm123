<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Type
 */
class Huge_Forms_Field_Type
{
    /**
     * Field Type ID
     *
     * @var int
     */
    private $id;

    /**
     * Field Type Name
     *
     * @var string
     */
    private $name;


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
     * Huge_Forms_Field_Type constructor.
     *
     * @param null $id
     *
     * @throws Error
     */
    public function __construct( $id = null ) {

        if ( $id !== null && absint( $id ) == $id ) {
            global $wpdb;

            $type = $wpdb->get_row( $wpdb->prepare(
                "SELECT *
                FROM " . Huge_Forms()->get_table_name( 'fieldTypes' ) . "
                WHERE id=%d
                ",
                $id
            ), ARRAY_A );

            if ( ! is_null( $type ) ) {

                $this->id = $id;

                foreach ( $type as $type_option_name => $type_option_value ) {

                    $function_name = 'set_' . $type_option_name;

                    if ( method_exists( $this, $function_name ) ) {

                        call_user_func( array( $this, $function_name ), $type_option_value );

                    }

                }

            }
        } else {
            $this->name = __( 'New Field', HUGE_FORMS_TEXT_DOMAIN );
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
    public function get_name() {
        return (!empty($this->name) ? $this->name : __( '(no title)', HUGE_FORMS_TEXT_DOMAIN ) );
    }

    /**
     * @param string $name
     *
     * @return Huge_Forms_Field_Type
     */
    public function set_name( $name ) {
        $this->name = sanitize_text_field( $name );

        return $this;
    }

    /**
     *field type data
     */

    public function save( $field_type_id=null )
    {

        global $wpdb;

        $field_type_data = array();

        $this->set_if_not_null( 'name', $this->name, $field_type_data );

        $this->set_if_not_null( 'id', $field_type_id, $field_type_data );

        $field_type_data = is_null( $this->id )
            ? $wpdb->insert( Huge_Forms()->get_table_name( 'fieldTypes' ), $field_type_data )
            : $wpdb->update( Huge_Forms()->get_table_name( 'fieldTypes' ), $field_type_data, array( 'id' => $this->id ) );


        if ( $field_type_data !== false && ! isset( $this->id ) ) {

            $this->id = $wpdb->insert_id;

            return $this->id;

        } elseif ( $field_type_data !== false && isset( $this->id ) ) {

            return $this->id;

        } else {

            return false;

        }
    }

}