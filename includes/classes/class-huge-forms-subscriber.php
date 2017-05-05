<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Subscriber
 */
class Huge_Forms_Subscriber
{
    /**
     * Subscriber ID
     *
     * @var int
     */
    private $id;

    /**
     * Subscriber username
     *
     * @var string
     */
    private $username;

    /**
     * Subscriber email
     *
     * @var string
     */
    private $email;

    /**
     * Subscriber IP
     *
     * @var string
     */
    private $ip;

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

            $subscriber = $wpdb->get_row( $wpdb->prepare(
                " SELECT * FROM " . Huge_Forms()->get_table_name( 'subscribers' ) . " WHERE id=%d ",
                $id
            ), ARRAY_A );

            if ( ! is_null( $subscriber ) ) {

                $this->id = $id;

                foreach ( $subscriber as $subscriber_option_name => $subscriber_option_value ) {

                    $function_name = 'set_' . $subscriber_option_name;

                    if ( method_exists( $this, $function_name ) ) {

                        call_user_func( array( $this, $function_name ), $subscriber_option_value );

                    }

                }

            }

        }
        else{

            $this->username = 'guest';

            $this->ip = $_SERVER['REMOTE_ADDR'];

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
    public function get_username() {
        return $this->username;
    }

    /**
     * @param string $label
     *
     * @return Huge_Forms_Subscriber
     */
    public function set_username( $username ) {
        $this->username = sanitize_text_field( $username );

        return $this;
    }

    /**
     * @return string
     */
    public function get_email( ) {

        return $this->email;

    }

    /**
     * @param string $email
     *
     * @return Huge_Forms_Subscriber
     */
    public function set_email( $email ) {
        $this->email = sanitize_email( $email );

        return $this;
    }

    /**
     * @return string
     */
    public function get_ip( ) {

        return $this->ip;

    }

    /**
     * @param string $ip
     *
     * @return Huge_Forms_Subscriber
     */
    public function set_ip( $ip ) {
        $this->ip = sanitize_text_field( $ip );

        return $this;
    }

    /**
     * subscriber data
     */

    public function save( $subscriber_id = null )
    {

        global $wpdb;

        $subscriber_data = array();

        $this->set_if_not_null( 'username', $this->username, $subscriber_data );
        $this->set_if_not_null( 'email', $this->email, $subscriber_data );
        $this->set_if_not_null( 'ip', $this->ip, $subscriber_data );


        $subscriber_data = is_null( $this->id )
            ? $wpdb->insert( Huge_Forms()->get_table_name( 'subscribers' ), $subscriber_data )
            : $wpdb->update( Huge_Forms()->get_table_name( 'subscribers' ), $subscriber_data, array( 'id' => $this->id ) );


        if ( $subscriber_data !== false && ! isset( $this->id ) ) {
            $this->id = $wpdb->insert_id;

            return $this->id;

        } elseif ( $subscriber_data !== false && isset( $this->id ) ) {

            return $this->id;

        } else {

            return false;

        }
    }

}