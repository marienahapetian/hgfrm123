<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Date
 */
class Huge_Forms_Field_Date extends Huge_Forms_Field
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function settings_block()
    {
        $settings_block_html='<div class="settings-block" id="'.$this->id.'">';

        $settings_block_html .= Huge_Forms_Admin_Setting::label_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::label_position_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::default_setting_row( $this );

        $settings_block_html .= Huge_Forms_Admin_Setting::date_setting_row( $this );

        $settings_block_html .= Huge_Forms_Admin_Setting::placeholder_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::date_range_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::container_class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::helptext_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::required_setting_row( $this );

        $settings_block_html .= Huge_Forms_Admin_Setting::readonly_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::order_setting_row( $this );
        $settings_block_html.='</div>';

        return $settings_block_html;
    }

    public function field_html()
    {
        ?>
            <div class="hgfrm-form-field <?php echo $this->field_class();?> <?php echo $this->container_class;?>">
                <label>
                    <?php echo $this->get_label();?>
                    <?php echo $this->required_block();?>
                </label>
                <?php $this->help_text_block();?>

                <input type="text" class="datepicker <?php echo $this->class;?>" name="<?php echo $this->id;?>" placeholder="<?php echo $this->get_placeholder();?>" id="datepicker-<?php echo $this->get_id();?>">
            </div>
        <?php
    }
}