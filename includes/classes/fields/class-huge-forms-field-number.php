<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Number
 */
class Huge_Forms_Field_Number extends Huge_Forms_Field
{

    private $minimum;

    private $maximum;

    private $number_type;

    public function __construct($id = null)
    {
        parent::__construct($id);
    }


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
        $this->set_if_not_null( 'number_type', $this->number_type, $field_data );
        $this->set_if_not_null( 'minimum', $this->minimum, $field_data );
        $this->set_if_not_null( 'maximum', $this->maximum, $field_data );
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

    /**
     * @return int
     */
    public function get_maximum()
    {
        return $this->maximum;
    }

    /**
     * @param int $value
     *
     * @return Huge_Forms_Field_Number
     */
    public function set_maximum( $value ) {
        if( absint($value) == $value){

            $this->maximum = intval( $value );
        }

        return $this;
    }

    /**
     * @return int
     */
    public function get_minimum()
    {
        return $this->minimum;
    }

    /**
     * @param int $value
     *
     * @return Huge_Forms_Field_Number
     */
    public function set_minimum( $value ) {
        if( absint($value) == $value){

            $this->minimum = intval( $value );
        }

        return $this;
    }

    /**
     * @return string int/float
     */
    public function get_number_type()
    {
        return $this->number_type;
    }

    /**
     * @param string $value
     *
     * @return Huge_Forms_Field_Number
     */
    public function set_number_type( $value ) {
        if(in_array($value,array('int','float'))){

            $this->number_type =  $value ;

        }

        return $this;
    }


    public function settings_block()
    {
        $settings_block_html  = '<div class="settings-block" id="'.$this->id.'">';

        $settings_block_html .= Huge_Forms_Admin_Setting::label_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::label_position_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::default_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::placeholder_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::container_class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::helptext_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::required_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::readonly_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::order_setting_row( $this );

        $settings_block_html .= '<div class="setting-row"><label>Min</label><input type="number" class="setting-min" name="min-'.$this->id.'" value="'.$this->minimum.'"><label>Max</label><input type="number" class="setting-max" name="max-'.$this->id.'" value="'.$this->maximum.'"><select name="numberType-'.$this->id.'"><option value="int" '.selected('int',$this->number_type,false).'>Integer</option><option value="float" '.selected('float',$this->number_type,false).'>Float</option></select></div>';
        $settings_block_html .= '</div>';

        return $settings_block_html;
    }

    public function field_html()
    {
        ?>
        <div class="hgfrm-form-field <?php echo $this->field_class();?> <?php echo $this->container_class;?>">
            <label for="">
                <?php echo $this->get_label();?>
                <?php echo $this->required_block();?>
            </label>
            <?php $this->help_text_block();?>

            <input type="number" class="<?php echo $this->class;?>" placeholder="<?php echo $this->get_placeholder();?>" name="field-<?php echo $this->id;?>" value="<?php echo $this->get_default();?>">
        </div>
        <?php
    }

    public function set_properties($fields_settings, $field_id)
    {
        parent::set_properties($fields_settings, $field_id);

        $this->set_minimum($fields_settings['min-'.$field_id]);
        $this->set_maximum($fields_settings['max-'.$field_id]);
        $this->set_number_type($fields_settings['numberType-'.$field_id]);

    }
}