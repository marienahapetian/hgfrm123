<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Option
 */
class Huge_Forms_Field_Option
{
    /**
     * Option ID
     *
     * @var int
     */
    private $id;

    /**
     * Option Name
     *
     * @var string
     */
    private $name;

    /**
     * Option Value
     *
     * @var string
     */
    private $value;

    /**
     * Option Field ID
     *
     * @var int
     */
    private $field;

    /**
     * Option checked
     *
     * @var int 0,1
     */
    private $checked;

    /**
     * Option order
     *
     * @var int
     */
    private $ordering;


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
    protected function set_checkbox( $key, $value, &$array )
    {
        if ( $value == null ) {
            $array[ $key ] = '0';
        } else {
            $array[ $key ] = '1';
        }
    }

    /**
     * Huge_Forms_Field_Option constructor.
     *
     * @param null $id
     *
     * @throws Error
     */
    public function __construct( $id = null ) {

        if ( $id !== null && absint( $id ) == $id ) {
            global $wpdb;

            $option = $wpdb->get_row( $wpdb->prepare(
                "SELECT *
                FROM " . Huge_Forms()->get_table_name( 'fieldOptions' ) . "
                WHERE id=%d
                ",
                $id
            ), ARRAY_A );

            if ( ! is_null( $option ) ) {

                $this->id = $id;

                foreach ( $option as $option_option_name => $option_option_value ) {

                    $function_name = 'set_' . $option_option_name;

                    if ( method_exists( $this, $function_name ) ) {

                        call_user_func( array( $this, $function_name ), $option_option_value );

                    }

                }

            }
        } else {
            $this->name = __( 'New Option', HUGE_FORMS_TEXT_DOMAIN );
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
     * @return Huge_Forms_Field_Option
     */
    public function set_name( $name ) {
        $this->name = sanitize_text_field( $name );

        return $this;
    }

    /**
     * @return int
     */
    public function get_ordering() {
        return intval($this->ordering);
    }

    /**
     * @param int $order
     *
     * @return Huge_Forms_Field_Option
     */
    public function set_ordering( $order ) {
        if( absint($order) == $order ) $this->ordering = intval( $order );

        return $this;
    }

    /**
     * @return string
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * @param string $name
     *
     * @return Huge_Forms_Field_Option
     */
    public function set_value( $value ) {
        $this->value = sanitize_text_field( $value );

        return $this;
    }

    /**
     * @return int
     */
    public function get_field() {

        return $this->field;

    }

    /**
     * @param int $field
     *
     * @return Huge_Forms_Field_Option
     */
    public function set_field( $field ) {

        $this->field = $field ;

        return $this;
    }

    /**
     * @return int 0,1
     */
    public function get_checked() {

        return $this->checked;

    }

    /**
     * @param string $name
     *
     * @return Huge_Forms_Field_Option
     */
    public function set_checked( $value ) {
        if(in_array($value,array(0,1))){

            $this->checked =  $value ;

        }

        return $this;
    }

    /**
     * unset option id
     */
    public function unset_id() {
        $this->id = null;

        return $this;
    }

    /**
     *option data
     */
    public function save( $option_id=null )
    {

        global $wpdb;

        $option_data = array();

        $this->set_if_not_null( 'name', $this->name, $option_data );
        $this->set_if_not_null( 'value', $this->value, $option_data );
        $this->set_if_not_null( 'field', $this->field, $option_data );
        $this->set_if_not_null( 'ordering', $this->ordering, $option_data );
        $this->set_checkbox( 'checked', $this->checked, $option_data );

        $this->set_if_not_null( 'id', $option_id, $option_data );

        $option_data = is_null( $this->id )
            ? $wpdb->insert( Huge_Forms()->get_table_name( 'fieldOptions' ), $option_data )
            : $wpdb->update( Huge_Forms()->get_table_name( 'fieldOptions' ), $option_data, array( 'id' => $this->id ) );


        if ( $option_data !== false && ! isset( $this->id ) ) {

            $this->id = $wpdb->insert_id;

            return $this->id;

        } elseif ( $option_data !== false && isset( $this->id ) ) {

            return $this->id;

        } else {

            return false;

        }
    }

    public function option_row()
    {
        return '<div class="option" data-option-id="'.$this->id.'"><input type="checkbox" '.checked(1,$this->checked,false).' name="checked-'.$this->id.'"><input type="hidden" class="setting-option-order" name="order-option-'.$this->id.'" value="'.$this->ordering.'"><input type="text" class="checkbox-option" name="optionName-'.$this->id.'" placeholder="Option Name" value="'.$this->name.'"><input type="text" class="checkbox-option" placeholder="Option Value" name="optionValue-'.$this->id.'" value="'.$this->value.'"><i class="fa fa-trash-o hgfrm-remove-option" aria-hidden="true" data-option="'.$this->id.'"></i><i class="fa fa-plus hgfrm-add-option" aria-hidden="true" ></i></div>';
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

            throw new Exception( 'Trying to delete an Option with wrong "id" parameter. Parameter "id" must be not negative integer.' );

        }

        return $wpdb->query( $wpdb->prepare( "DELETE FROM " . Huge_Forms()->get_table_name( 'fieldOptions' ) . " WHERE id =%d", $id ) );
    }


    public function set_properties($fields_settings)
    {

        $this->set_name($fields_settings['optionName-'.$this->id.''])
                ->set_ordering($fields_settings['order-option-'.$this->id.''])
                ->set_checked($fields_settings['checked-'.$this->id.''])
                ->set_value($fields_settings['optionValue-'.$this->id.'']);


    }
}