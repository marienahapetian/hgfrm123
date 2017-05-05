<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Hidden
 */
class Huge_Forms_Field_Hidden extends Huge_Forms_Field
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function settings_block()
    {
        $settings_block_html='<div class="settings-block" id="'.$this->id.'">';
        $settings_block_html .= Huge_Forms_Admin_Setting::label_setting_row( $this );
        $settings_block_html.='<div class="setting-row"><label>Label Position </label><select class="setting-label-pos" name="position-'.$this->id.'" >';

        $label_positions = Huge_Forms_Query::get_label_positions();
        foreach ($label_positions as $position){
            $settings_block_html.='<option value="'.$position->get_id().'" '.selected($position->get_id(),$this->label_position,false).'>'.strtoupper($position->get_name()).'</option>';
        }

        $settings_block_html.='</select></div>';
        $settings_block_html.='<div class="setting-row"><label>Default Value</label> <input type="text" class="setting-default" name="default-'.$this->id.'" value="'.$this->default.'"></div>';
        $settings_block_html.='<div class="setting-row"><label>Placeholder </label><input type="text" class="setting-placeholder" name="placeholder-'.$this->id.'" value="'.$this->placeholder.'"></div>';
        $settings_block_html.='<div class="setting-row"><label>Element Class </label><input type="text" class="setting-class" name="class-'.$this->id.'" value="'.$this->class.'"></div>';
        $settings_block_html.='<div class="setting-row"><label>Container Class </label><input type="text" class="setting-cont-class" name="contclass-'.$this->id.'" value="'.$this->container_class.'"></div>';
        $settings_block_html.='<div class="setting-row"><label>Help Text </label><textarea class="setting-help-text" name="helptext-'.$this->id.'" >'.$this->helper_text.'</textarea></div>';
        $settings_block_html.='<div class="setting-row"><label>Is Required <input type="checkbox" class="setting-required" '.checked('1',$this->required,false).' name="required-'.$this->id.'" ></label></div>';
        $settings_block_html.='<div class="setting-row"><label>Read Only <input type="checkbox" class="setting-disabled" '.checked('1',$this->disabled,false).' name="disabled-'.$this->id.'" ></label></div>';
        $settings_block_html.='<div class="setting-row"><input type="hidden" class="setting-order" name="order-'.$this->id.'" value="'.$this->ordering.'"></div>';
        $settings_block_html.='</div>';

        return $settings_block_html;
    }

    public function field_html()
    {
        ?>
            <div class="hgfrm-form-field ">
                <input type="hidden" >
            </div>
        <?php
    }
}