<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field
 */
abstract class Huge_Forms_Field
{
    /**
     * Field ID
     *
     * @var int
     */
    protected $id;

    /**
     * Field Label
     *
     * @var string
     */
    protected $label;

    /**
     * Field Order
     *
     * @var int
     */
    protected $ordering;

    /**
     * Field Label Position
     *
     * @var int
     */
    protected $label_position;

    /**
     * Field Default Value
     *
     * @var string
     */
    protected $default;

    /**
     * Field Class
     *
     * @var string
     */
    protected $class;

    /**
     * Field Container Class
     *
     * @var string
     */
    protected $container_class;

    /**
     * Field Placeholder
     *
     * @var string
     */
    protected $placeholder;

    /**
     * Field Help Text
     *
     * @var string
     */
    protected $helper_text;

    /**
     * Field is required
     *
     * @var int(0,1)
     */
    protected $required;

    /**
     * Field is disabled
     *
     * @var int(0,1)
     */
    protected $disabled;

    /**
     * Field Form
     *
     * @var int
     */
    protected $form;

    /**
     * Field Type
     *
     * @var object Huge_Forms_Field_Type
     */
    protected $type;


    /**
     * Huge_Forms_Field constructor.
     *
     * @param null $id
     *
     * @throws Error
     */
    public function __construct( $id = null ) {

        $id = absint($id);
        if ( $id !== null && absint( $id ) == $id ) {
            global $wpdb;

            $field = $wpdb->get_row( $wpdb->prepare(
                "SELECT *
                FROM " . Huge_Forms()->get_table_name( 'fields' ) . " as fields INNER JOIN " . Huge_Forms()->get_table_name( 'formFields' ) . " as formFields ON fields.id=formFields.field
                WHERE fields.id=%d
                ",
                $id
            ), ARRAY_A );

            if ( ! is_null( $field ) ) {

                $this->id = $id;

                foreach ( $field as $field_option_name => $field_option_value ) {

                    $function_name = 'set_' . $field_option_name;

                    if ( method_exists( $this, $function_name ) ) {

                        call_user_func( array( $this, $function_name ), $field_option_value );

                    }

                }

            }
        } else {
            $this->name = __( 'New Field', HUGE_FORMS_TEXT_DOMAIN );
        }

    }

    /**
     *
     */
    public function unset_id() {
        $this->id = null;

        return $this;
    }

    /**
     * When cloning an instance of Field id and form are changed to be null in order to have a clear copy of this field
     */
    public function __clone() {
        $this->id     = null;
        $this->form = null;
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

        return $wpdb->query( $wpdb->prepare( "DELETE FROM " . Huge_Forms()->get_table_name( 'fields' ) . " WHERE id =%d", $id ) );
    }


    /**
     * Sets $array[$key] = $value if $value is not NULL.
     *
     * @param $key
     * @param $value
     * @param $array
     */
    protected function set_if_not_null( $key, $value, &$array )
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
     * @return mixed
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function get_default() {
        return wp_unslash($this->default);
    }

    /**
     * @param string $default
     *
     * @return Huge_Forms_Field
     */
    public function set_default_value( $default ) {
        $this->default =  $default ;

        return $this;
    }

    /**
     * @return mixed
     */
    public function get_helper_text() {
        return wp_unslash($this->helper_text);
    }

    /**
     * @param string $help_text
     *
     * @return Huge_Forms_Field
     */
    public function set_helper_text( $help_text ) {

        $this->helper_text = sanitize_text_field( $help_text );

        return $this;
    }

    /**
     * @return string
     */
    public function get_placeholder( ) {

        return wp_unslash($this->placeholder);

    }

    /**
     * @param string $placeholder
     *
     * @return Huge_Forms_Field
     */
    public function set_placeholder( $placeholder ) {

        $this->placeholder = sanitize_text_field($placeholder);

        return $this;
    }

    /**
     * @return int
     */
    public function get_ordering()
    {
        return $this->ordering;
    }

    /**
     * @param int $order
     * return Huge_Forms_Field
     */
    public function set_ordering($order)
    {
        if( absint($order)==$order){
            $this->ordering = $order;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function get_label()
    {
        if(!empty($this->label)){
            return wp_unslash($this->label);
        }
        else{
            $type = $this->type->get_name();

            return ucfirst($type);

        }
    }


    /**
     * @param string $label
     *
     * @return Huge_Forms_Field
     */
    public function set_label( $label )
    {
        $this->label = sanitize_text_field( $label );

        return $this;
    }

    /**
     * @return int
     */
    public function get_label_position( )
    {

        return $this->label_position;

    }

    /**
     * @param int $form
     *
     * @return Huge_Forms_Field
     */
    public function set_label_position( $value )
    {

        if( absint($value)==$value ) {
            $this->label_position = $value;
        }

        return $this;

    }

    /**
     * @return int
     */
    public function get_form( )
    {

        return $this->form;

    }

    /**
     * @param int $form
     *
     * @return Huge_Forms_Field
     */
    public function set_form( $form )
    {
        $this->form = intval( $form );

        return $this;
    }

    /**
     * @return object Huge_Forms_Field_Type
     */
    public function get_type( )
    {

        return $this->type;

    }

    /**
     * @param int $type
     *
     * @return Huge_Forms_Field
     */
    public function set_type( $type )
    {
        if( absint($type) == $type ){

            $this->type = new Huge_Forms_Field_Type( $type );

        }

        return $this;
    }

    /**
     * @return string
     */
    public function get_class( )
    {

        return $this->class;

    }

    /**
     * @param string $class
     *
     * @return Huge_Forms_Field
     */
    public function set_class( $class ) {
        $this->class = sanitize_text_field( $class );

        return $this;
    }

    /**
     * @return string
     */
    public function get_container_class( ) {

        return $this->container_class;

    }

    /**
     * @param string $cont_class
     *
     * @return Huge_Forms_Field
     */
    public function set_container_class( $cont_class ) {
        $this->container_class = sanitize_text_field( $cont_class );

        return $this;
    }

    /**
     * @return int(0,1)
     */
    public function get_required( ) {

        return $this->required;

    }

    /**
     * @param int $required (0,1)
     *
     * @return Huge_Forms_Field
     */
    public function set_required( $required ) {
        if(in_array($required,array(0,1,'on'))){

            if($required=='on') $required=1;
            $this->required = intval( $required );

        }

        return $this;
    }

    /**
     * @return int(0,1)
     */
    public function get_disabled( ) {

        return $this->disabled;

    }

    /**
     * @param int $disabled (0,1)
     *
     * @return Huge_Forms_Field
     */
    public function set_disabled( $disabled ) {
        if(in_array($disabled,array(0,1,'on'))){

            if($disabled=='on') $disabled=1;
            $this->disabled = intval( $disabled );

        }

        return $this;
    }

    public function set_properties($fields_settings,$field_id)
    {
        $this-> set_label($fields_settings['label-'.$field_id])
             -> set_placeholder($fields_settings['placeholder-'.$field_id])
             -> set_default_value($fields_settings['default-'.$field_id])
             -> set_label_position($fields_settings['position-'.$field_id])
             -> set_class($fields_settings['class-'.$field_id])
             -> set_container_class($fields_settings['contclass-'.$field_id])
             -> set_required($fields_settings['required-'.$field_id])
             -> set_disabled($fields_settings['disabled-'.$field_id])
             -> set_ordering($fields_settings['order-'.$field_id])
             -> set_helper_text($fields_settings['helptext-'.$field_id]);
    }


    /**
     * field data
     */

    public function save( $field_id = null )
    {
        global $wpdb;

        $field_data = array();
        $form_field_data=array();


        $this->set_if_not_null( 'label', $this->label, $field_data );
        $this->set_if_not_null( 'type', $this->type->get_id(), $field_data );
        $this->set_if_not_null( 'helper_text', $this->helper_text, $field_data );
        $this->set_if_not_null( 'placeholder', $this->placeholder, $field_data );
        $this->set_if_not_null( 'default_value', $this->default, $field_data );
        $this->set_if_not_null( 'label_position', $this->label_position, $field_data );
        $this->set_if_not_null( 'class', $this->class, $field_data );
        $this->set_if_not_null( 'ordering', $this->ordering, $field_data );
        $this->set_if_not_null( 'container_class', $this->container_class, $field_data );
        $this->set_checkbox( 'required', $this->required, $field_data );
        $this->set_checkbox( 'disabled', $this->disabled, $field_data );

        $this->set_if_not_null( 'id', $field_id, $field_data );

        $field_data = is_null( $this->id )
            ? $wpdb->insert( Huge_Forms()->get_table_name( 'fields' ), $field_data )
            : $wpdb->update( Huge_Forms()->get_table_name( 'fields' ), $field_data, array( 'id' => $this->id ) );



        if ( $field_data !== false && ! isset( $this->id ) ) {
            $this->id = $wpdb->insert_id;

            $this->set_if_not_null('form',$this->form,$form_field_data);
            $this->set_if_not_null('field',$this->id,$form_field_data);

            $form_field_data = $wpdb->insert( Huge_Forms()->get_table_name( 'formFields' ), $form_field_data );

            return $this->id;

        } elseif ( $field_data !== false && isset( $this->id ) ) {

            return $this->id;

        } else {

            return false;

        }
    }

    public function field_block()
    {
        $field_block_html='<div class="field-block ui-state-default ui-sortable-handle" data-field-id="'.$this->id.'" data-field-type="'.$this->get_type()->get_id().'"><span>'.ucfirst($this->get_type()->get_name()).'</span><i class="fa fa-clone" aria-hidden="true"></i> <i class="fa fa-trash-o" aria-hidden="true"></i></div>';

        return $field_block_html;
    }

    /**
     * @param int $field
     *
     * @return Huge_Forms_Field
     */
    public static function create_field_object( $field )
    {
        $field_type = Huge_Forms_Query::get_field_type( $field );
        $field_class = 'Huge_Forms_Field_'.ucfirst($field_type->get_name());
        $field = new $field_class( $field );
        return $field;
    }

    protected function field_class ( )
    {
        $label_position = $this->label_position;

        switch ($label_position){
            case '1': /* default */
                $form = new Huge_Forms_Form( $this->get_form() );
                $form_label_position = $form->get_labels_position()->get_id();
                switch ($form_label_position){
                    case '1':
                        return '';
                    case '2':
                        return 'label-left';
                    case '3':
                        return 'label-right';
                    case '4':
                        return '';
                    case '5':
                        return 'label-inside';
                    case '6':
                        return 'hidden';
                }
            case '2':
                return 'label-left';
            case '3':
                return 'label-right';
            case '4':
                return '';
            case '5':
                return 'label-inside';
            case '6':
                return 'hidden';
        }

    }

    protected function help_text_block()
    {  if($this->get_helper_text()!==''): ?>
        <div class="help-block">
            <?php echo $this->get_helper_text();?>
        </div>
    <?php  endif; }

    protected function required_block()
    {  if($this->required): ?>
        <span class="hgfrm-required">*</span>
    <?php  endif; }

    abstract function settings_block();

    abstract function field_html();

}