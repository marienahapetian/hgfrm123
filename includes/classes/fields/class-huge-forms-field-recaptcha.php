<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Recaptcha
 */
class Huge_Forms_Field_Recaptcha extends Huge_Forms_Field
{

    /**
     * recaptcha_type
     *
     * @var string regular|hidden
     */
    private $recaptcha_type;

    /**
     * recaptcha_style
     *
     * @var string light|dark
     */
    private $recaptcha_style;

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
        $this->set_if_not_null( 'label_position', $this->label_position, $field_data );
        $this->set_if_not_null( 'class', $this->class, $field_data );
        $this->set_if_not_null( 'ordering', $this->ordering, $field_data );
        $this->set_if_not_null( 'container_class', $this->container_class, $field_data );
        $this->set_if_not_null( 'recaptcha_type', $this->recaptcha_type, $field_data );
        $this->set_if_not_null( 'recaptcha_style', $this->recaptcha_style, $field_data );

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
        $settings_block_html='<div class="settings-block" id="'.$this->id.'">';

        $settings_block_html .= Huge_Forms_Admin_Setting::label_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::label_position_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::recaptcha_type_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::recaptcha_style_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::container_class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::helptext_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::order_setting_row( $this );
        $settings_block_html.='</div>';

        return $settings_block_html;
    }

    public function field_html()
    {
        $recaptcha_public_key = Huge_Forms::get_setting('recaptcha-public-key');
        $recaptcha_secret_key = Huge_Forms::get_setting('recaptcha-secret-key');
        wp_enqueue_script( 'huge_forms_recaptcha', 	'https://www.google.com/recaptcha/api.js?onload=huge_forms_onloadCallback&render=explicit', array( 'jquery' ), '1.0.0', true );

        ?>
            <div class="hgfrm-form-field <?php echo $this->field_class();?> <?php echo $this->container_class;?>">
                <label for=""><?php echo $this->get_label();?></label>
                <?php $this->help_text_block();?>

                <div class="huge-forms-captcha-block <?php echo $this->class;?>" data-form-id='<?php echo $this->get_form();?>' data-sitekey="<?php echo $recaptcha_public_key;?>" data-theme="light" data-cname="compact">
                    <div id="huge_forms_captcha_<?php echo $this->get_form();?>"></div>
                </div>
            </div>
        <?php
    }

    /**
     * @return string
     */
    public function get_recaptcha_type( )
    {

        return $this->recaptcha_type;

    }

    /**
     * @param string $recaptcha_type
     *
     * @return Huge_Forms_Field_Recaptcha
     */
    public function set_recaptcha_type( $recaptcha_type )
    {
        if( in_array($recaptcha_type,array('regular','hidden'))){

            $this->recaptcha_type =  $recaptcha_type ;

        }

        return $this;
    }

    /**
     * @return string
     */
    public function get_recaptcha_style( )
    {

        return $this->recaptcha_style;

    }

    /**
     * @param string $recaptcha_style
     *
     * @return Huge_Forms_Field_Recaptcha
     */
    public function set_recaptcha_style( $recaptcha_style )
    {
        if( in_array($recaptcha_style,array('light','dark'))){

            $this->recaptcha_style =  $recaptcha_style ;

        }

        return $this;
    }

    public function set_properties($fields_settings, $field_id)
    {
        parent::set_properties($fields_settings, $field_id);

        $this->set_recaptcha_style($fields_settings['recaptcha_style-'.$field_id]);
        $this->set_recaptcha_type($fields_settings['recaptcha_type-'.$field_id]);

    }

}