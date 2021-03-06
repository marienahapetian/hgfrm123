<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Textarea
 */
class Huge_Forms_Field_Textarea extends Huge_Forms_Field
{
    private $resizable;

    private $height;

    private $limit;

    private $limit_type;


    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * @return int(0,1)
     */
    public function get_resizable()
    {
        return $this->resizable;
    }

    /**
     * @param int $value (0,1)
     *
     * @return Huge_Forms_Field_Textarea
     */
    public function set_resizable( $value ) {
        if(in_array($value,array(0,1,'on'))){

            if($value=='on') $value = 1;
            $this->resizable = intval( $value );

        }

        return $this;
    }

    /**
     * @return int
     */
    public function get_limit_number()
    {
        return $this->limit;
    }

    /**
     * @param int $value
     *
     * @return Huge_Forms_Field_Textarea
     */
    public function set_limit_number( $value ) {
        if( absint($value) == $value){

            $this->limit = intval( $value );
        }

        return $this;
    }

    /**
     * @return string char/word
     */
    public function get_limit_type()
    {
        return $this->limit_type;
    }

    /**
     * @param string $value
     *
     * @return Huge_Forms_Field_Textarea
     */
    public function set_limit_type( $value ) {
        if(in_array($value,array('char','word'))){

            $this->limit_type =  $value ;

        }

        return $this;
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
        $this->set_if_not_null( 'limit_number', $this->limit, $field_data );
        $this->set_if_not_null( 'limit_type', $this->limit_type, $field_data );
        $this->set_checkbox( 'required', $this->required, $field_data );
        $this->set_checkbox( 'disabled', $this->disabled, $field_data );
        $this->set_checkbox('resizable',$this->resizable,$field_data);

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



    public function settings_block()
    {
        $settings_block_html  = '<div class="settings-block" id="' . $this->id . '">';

        $settings_block_html .= Huge_Forms_Admin_Setting::label_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::label_position_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::default_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::placeholder_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::container_class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::helptext_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::required_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::readonly_setting_row( $this );

        $settings_block_html .= '<div class="setting-row"><label>Resizable <input type="checkbox" class="setting-resizable" ' . checked('1', $this->resizable, false) . ' name="resizable-' . $this->id . '" ></label></div>';
        $settings_block_html .= '<div class="setting-row"><label>Limit Input to</label><input type="text" class="setting-limit" name="limit-'.$this->id.'" value="'.$this->limit.'"><select name="limitType-'.$this->id.'"><option value="char" '.selected('char',$this->limit_type,false).'>Characters</option><option value="word" '.selected('word',$this->limit_type,false).'>Words</option></select></div>';
        $settings_block_html .= '<div class="setting-row"><label>Text While Typing</label><input type="text" class="setting-limit" name="limitText-'.$this->id.'" value="" placeholder="ex. Words Left"></div>';//todo

        $settings_block_html .= Huge_Forms_Admin_Setting::order_setting_row( $this );
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
            <textarea placeholder="<?php echo $this->placeholder; ?>" class="<?php echo $this->class;?>"></textarea>
        </div>
        <?php
    }

    public function set_properties($fields_settings, $field_id)
    {
        parent::set_properties($fields_settings, $field_id);

        $this->set_resizable($fields_settings['resizable-'.$field_id]);
        $this->set_limit_number($fields_settings['limit-'.$field_id]);
        $this->set_limit_type($fields_settings['limitType-'.$field_id]);

    }
}