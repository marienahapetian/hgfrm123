<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Buttons
 */
class Huge_Forms_Field_Buttons extends Huge_Forms_Field
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function settings_block()
    {
        $settings_block_html='<div class="settings-block" id="'.$this->id.'">';
        $settings_block_html .= Huge_Forms_Admin_Setting::label_setting_row( $this );
        $settings_block_html.='<div class="setting-row"><label>Element Class </label><input type="text" class="setting-class" name="class-'.$this->id.'" value="'.$this->class.'"></div>';
        $settings_block_html.='<div class="setting-row"><label>Container Class </label><input type="text" class="setting-cont-class" name="contclass-'.$this->id.'" value="'.$this->container_class.'"></div>';
        $settings_block_html.='<div class="setting-row"><input type="hidden" class="setting-order" name="order-'.$this->id.'" value="'.$this->ordering.'"></div>';
        $settings_block_html.='</div>';

        return $settings_block_html;
    }

    public function field_html()
    {
        ?>
            <div class="hgfrm-form-field <?php echo $this->container_class;?>">
                <input type="submit" class="<?php echo $this->class;?>" value="<?php echo $this->get_label();?>">
            </div>
        <?php
    }


}