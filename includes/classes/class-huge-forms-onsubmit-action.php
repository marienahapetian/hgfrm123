<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Onsubmit_Action
 */
class Huge_Forms_Onsubmit_Action
{
    /**
     * Onsubmit Action ID
     *
     * @var int
     */
    private $id;

    /**
     * Onsubmit Action Name
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
     * Huge_Forms_Onsubmit_Action constructor.
     *
     * @param null|int $id
     *
     * @throws Error
     */
    public function __construct( $id = null )
    {

        if ( $id !== null && absint( $id ) == $id ) {
            global $wpdb;

            $action = $wpdb->get_row( $wpdb->prepare(
                " SELECT * FROM " . Huge_Forms()->get_table_name( 'onsubmitActions' ) . " WHERE id=%d ",
                $id
            ), ARRAY_A );

            if ( ! is_null( $action ) ) {

                $this->id = $id;

                foreach ( $action as $action_option_name => $action_option_value ) {

                    $function_name = 'set_' . $action_option_name;

                    if ( method_exists( $this, $function_name ) ) {

                        call_user_func( array( $this, $function_name ), $action_option_value );

                    }

                }

            }

        } else {

            $this->name = __( 'New Action', HUGE_FORMS_TEXT_DOMAIN );

        }

    }


    /**
     * @return mixed
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return  Huge_Forms_Onsubmit_Action
     */
    public function set_id( $id )
    {
        if( absint($id) != $id) {

            throw new Exception( 'Wrong value for onsubmit action. Value must be int non negative' );

        } else {

            $this->id= $id;

        }

        return $this;
    }

    /**
     * @return string
     */
    public function get_name()
    {
        return (!empty($this->name) ? $this->name : __( '(no title)', HUGE_FORMS_TEXT_DOMAIN ) );
    }

    /**
     * @param string $name
     *
     * @return Huge_Forms_Onsubmit_Action
     */
    public function set_name( $name )
    {
        $this->name = sanitize_text_field( $name );

        return $this;
    }

    /**
     * onsubmit action data
     */

    public function save( $onsubmit_action_id=null )
    {

        global $wpdb;

        $onsubmit_action_data = array();

        $this->set_if_not_null('name', $this->name, $onsubmit_action_data);

        $this->set_if_not_null('id', $onsubmit_action_id, $onsubmit_action_data);

        $onsubmit_action_data = is_null($this->id)
            ? $wpdb->insert(Huge_Forms()->get_table_name('onsubmitActions'), $onsubmit_action_data)
            : $wpdb->update(Huge_Forms()->get_table_name('onsubmitActions'), $onsubmit_action_data, array('id' => $this->id));


        if ($onsubmit_action_data !== false && !isset($this->id)) {

            $this->id = $wpdb->insert_id;

            return $this->id;

        } elseif ($onsubmit_action_data !== false && isset($this->id)) {

            return $this->id;

        } else {

            return false;

        }
    }

}