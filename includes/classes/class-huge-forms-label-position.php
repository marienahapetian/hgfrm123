<?php
if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Label_Position
 */
class Huge_Forms_Label_Position
{
    /**
     * Label Position ID
     *
     * @var int
     */
    private $id;

    /**
     * Label Position Name
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
     * Huge_Forms_Label_Position constructor.
     *
     * @param null|int $id
     *
     * @throws Error
     */
    public function __construct( $id = null )
    {
        if ( $id !== null && absint( $id ) == $id ) {
            global $wpdb;

            $position = $wpdb->get_row( $wpdb->prepare(
                " SELECT * FROM " . Huge_Forms()->get_table_name( 'labelPositions' ) . " WHERE id=%d ",
                $id
            ), ARRAY_A );

            if ( ! is_null( $position ) ) {

                $this->id = $id;

                foreach ( $position as $position_option_name => $position_option_value ) {

                    $function_name = 'set_' . $position_option_name;

                    if ( method_exists( $this, $function_name ) ) {

                        call_user_func( array( $this, $function_name ), $position_option_value );

                    }

                }

            }

        } else {

            $this->name = __( 'New Label Position', HUGE_FORMS_TEXT_DOMAIN );

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
     * @return Huge_Forms_Label_Position
     */
    public function set_name( $name ) {
        $this->name = sanitize_text_field( $name );

        return $this;
    }

    /**
     * label position data
     */

    public function save( $label_position_id=null )
    {

        global $wpdb;

        $label_position_data = array();

        $this->set_if_not_null( 'name', $this->name, $label_position_data );

        $this->set_if_not_null( 'id', $label_position_id, $label_position_data );

        $label_position_data = is_null( $this->id )
            ? $wpdb->insert( Huge_Forms()->get_table_name( 'labelPositions' ), $label_position_data )
            : $wpdb->update( Huge_Forms()->get_table_name( 'labelPositions' ), $label_position_data, array( 'id' => $this->id ) );


        if ( $label_position_data !== false && ! isset( $this->id ) ) {

            $this->id = $wpdb->insert_id;

            return $this->id;

        } elseif ( $label_position_data !== false && isset( $this->id ) ) {

            return $this->id;

        } else {

            return false;

        }
    }

}