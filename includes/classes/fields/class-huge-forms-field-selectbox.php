<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Text
 */
class Huge_Forms_Field_Selectbox extends Huge_Forms_Field
{

    /**
     * Options of current field
     * Array of Huge_Forms_Field_Option instances
     *
     * @var array
     */
    private $options;

    /**
     * Option type
     * string singleselect,multiselect
     *
     * @var array
     */
    private $option_type;



    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->options = Huge_Forms_Query::get_field_options( $id );
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
        $this->set_if_not_null( 'option_type', $this->option_type, $field_data );

        $this->set_if_not_null( 'id', $field_id, $field_data );


        $new_field_data = is_null( $this->id )
            ? $wpdb->insert( Huge_Forms()->get_table_name( 'fields' ), $field_data )
            : $wpdb->update( Huge_Forms()->get_table_name( 'fields' ), $field_data, array( 'id' => $this->id ) );



        if ( $new_field_data !== false && ! isset( $this->id ) ) {
            $this->id = $wpdb->insert_id;

            $this->set_if_not_null('form',$this->form,$form_field_data);
            $this->set_if_not_null('field',$this->id,$form_field_data);

            $form_field_data = $wpdb->insert( Huge_Forms()->get_table_name( 'formFields' ), $form_field_data );

            $options = $this->options;

            $new_field_id = $this->id;

            foreach ( $options as $option ){
                $option->unset_id();
                $option->set_field($new_field_id);
                $option->save();

            }


            return $this->id;

        } elseif ( $field_data !== false && isset( $this->id ) ) {

            return $this->id;

        } else {

            return false;

        }
    }


    public function get_options()
    {

        return $this->options;

    }

    /**
     * @param Huge_Forms_Field_Option[] $options
     *
     * @return Huge_Forms_Field_Selectbox
     * @throws Exception
     */
    public function set_options( $options ) {
        foreach ( $options as $option ) {
            if ( ! ( $option instanceof Huge_Forms_Field_Option ) ) {
                throw new Exception( 'Field must be an instance of Huge_Forms_Field class.' );
            }

        }

        $this->options = $options;

        return $this;
    }

    public function get_option_type()
    {

        return $this->option_type;

    }

    /**
     * @param string $type
     *
     * @return Huge_Forms_Field_Selectbox
     * @throws Exception
     */
    public function set_option_type( $type ) {
        if( in_array($type, array('singleselect','multiselect'))){

            $this->option_type = $type;

        }

        return $this;
    }

    public function settings_block()
    {
        $settings_block_html='<div class="settings-block" id="'.$this->id.'">';
        $settings_block_html .= Huge_Forms_Admin_Setting::label_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::label_position_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::container_class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::helptext_setting_row( $this );

        $settings_block_html.='<div class="setting-row"><label>Dropdown Type </label><select name="optionType-'.$this->id.'" ><option value="singleselect" '.selected('singleselect',$this->option_type,false).'>Single Selection</option><option value="multiselect" '.selected('multiselect',$this->option_type,false).'>Multiselect</option></select></div>';

        $settings_block_html .= Huge_Forms_Admin_Setting::order_setting_row( $this );
        $settings_block_html.='<div class="setting-row"><i class="fa fa-tasks hgfrm-import-options nowidth" aria-hidden="true" >Import Options</i>  <i class="fa fa-plus hgfrm-add-option nowidth" aria-hidden="true" >Add Option</i></div>';
        $settings_block_html.='<div class="setting-row import-block"><p class="description">Follow the pattern below, after you have added all options, click Import Options Button</p><textarea>{Name1#Value1},{Name2#Value2}</textarea><p><span class="import-options">Import Options</span><span class="cancel">Cancel</span></p></div>';

        $field_options = $this->options;

        $settings_block_html.='<div class="setting-row options">';
        foreach ($field_options as $field_option){
            $settings_block_html.= $field_option->option_row();
        }
        $settings_block_html.='</div>';
        $settings_block_html.='</div>';

        return $settings_block_html;
    }

    public function field_html()
    {
        ?>
        <div class="hgfrm-form-field <?php echo $this->field_class();?> <?php echo $this->container_class;?>">

            <label for=""><?php echo $this->get_label();?></label>
            <?php $this->help_text_block();?>

            <select name="<?php echo $this->id;?>" class="<?php echo $this->class;?>">
                <?php $options = $this->options;
                foreach ( $options as $option){ ?>
                    <option value="<?php echo $option->get_value();?>"><?php echo $option->get_name();?></option>
                <?php } ?>
            </select>

        </div>
        <?php
    }

    public function set_properties($fields_settings, $field_id)
    {
        parent::set_properties($fields_settings, $field_id);

        $this->set_option_type($fields_settings['optionType-'.$this->id]);

        $options = $this->options;

        foreach ( $options as $option ){

            $option->set_properties($fields_settings);

            $option->save($option->get_id());

        }
    }
}