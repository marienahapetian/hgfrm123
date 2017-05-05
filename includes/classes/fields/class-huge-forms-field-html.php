<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Html
 */
class Huge_Forms_Field_Html extends Huge_Forms_Field
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

        ob_start();
        wp_editor($this->get_default(),'default-'.$this->id, array('editor_class'=>'setting-row'));
        $wp_editor=ob_get_clean();

        $settings_block_html.='<div class="setting-row"><label>Code</label>'.$wp_editor.'</div>';
        $settings_block_html.='<div class="setting-row"><label>Element Class </label><input type="text" class="setting-class" name="class-'.$this->id.'" value="'.$this->class.'"></div>';
        $settings_block_html.='<div class="setting-row"><label>Container Class </label><input type="text" class="setting-cont-class" name="contclass-'.$this->id.'" value="'.$this->container_class.'"></div>';
        $settings_block_html.='<div class="setting-row"><input type="hidden" class="setting-order" name="order-'.$this->id.'" value="'.$this->ordering.'"></div>';
        $settings_block_html.='</div>';

        return $settings_block_html;
    }

    public function field_html()
    {
        ?>
        <div class="hgfrm-form-field <?php echo $this->field_class();?> <?php echo $this->container_class;?>">
            <label><?php echo $this->get_label();?></label>
            <?php $this->help_text_block();?>

            <div class="<?php echo $this->class;?>"><?php echo do_shortcode(wp_unslash($this->default));?></div>
        </div>
        <?php
    }
}