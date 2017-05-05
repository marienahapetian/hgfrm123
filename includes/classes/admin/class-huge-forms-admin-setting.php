<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Admin_Setting
 */
class Huge_Forms_Admin_Setting extends Huge_Forms_Admin_Listener
{

    /**
     * Huge_Forms_Admin_Setting constructor.
     */
    public function __construct()
    {
    }



    public static function label_setting_row( Huge_Forms_Field $field)
    {
           return '<div class="setting-row"><label>Label </label><input type="text" class="setting-label" name="label-'.$field->get_id().'" value="'.$field->get_label().'"></div>';
    }

    public static function label_position_setting_row( Huge_Forms_Field $field)
    {
        $row = '<div class="setting-row"><label>Label Position </label><select class="setting-label-pos" name="position-' . $field->get_id() . '" >';

        $label_positions = Huge_Forms_Query::get_label_positions();
        foreach ($label_positions as $position) {
            $row .= '<option value="' . $position->get_id() . '" ' . selected($position->get_id(), $field->get_label_position(), false) . '>' . strtoupper($position->get_name()) . '</option>';
        }

        $row .= '</select></div>';

        return $row;
    }

    public static function default_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Default Value</label> <input type="text" class="setting-default" name="default-' . $field->get_id() . '" value="' . esc_html($field->get_default()) . '"></div>';
    }

    public static function placeholder_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Placeholder </label><input type="text" class="setting-placeholder" name="placeholder-' . $field->get_id() . '" value="' . $field->get_placeholder() . '"></div>';

    }

    public static function class_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Element Class </label><input type="text" class="setting-class" name="class-'.$field->get_id().'" value="'.$field->get_class().'"></div>';
    }

    public static function container_class_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Container Class </label><input type="text" class="setting-cont-class" name="contclass-' . $field->get_id() . '" value="' . $field->get_container_class() . '"></div>';
    }

    public static function helptext_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Help Text </label><textarea class="setting-help-text" name="helptext-'.$field->get_id().'" >'.$field->get_helper_text().'</textarea></div>';
    }

    public static function required_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Is Required <input type="checkbox" class="setting-required" '.checked('1',$field->get_required(),false).' name="required-'.$field->get_id().'" ></label></div>';
    }

    public static function readonly_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Read Only <input type="checkbox" class="setting-disabled" '.checked('1',$field->get_disabled(),false).' name="disabled-'.$field->get_id().'" ></label></div>';
    }

    public static function order_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><input type="hidden" class="setting-order" name="order-' . $field->get_id() . '" value="' . $field->get_ordering() . '"></div>';
    }

    public static function map_center_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Map Center LatLng </label><input type="text" name="map_center-'.$field->get_id().'" placeholder="{lat:14.2451,lng:32.455}" value="'.$field->get_map_center().'"></div>';
    }

    public static function map_draggable_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Map Draggable <input type="checkbox" '.checked('1',$field->get_draggable(),false).' name="draggable-'.$field->get_id().'" ></label></div>';
    }

    public static function recaptcha_type_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Recaptcha Type </label><select name="recaptcha_type-'.$field->get_id().'"><option value="regular" '.selected('regular',$field->get_recaptcha_type(),false).'>Regular</option><option value="hidden" '.selected('hidden',$field->get_recaptcha_type(),false).'>Hidden</option></select></div>';
    }

    public static function recaptcha_style_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Recaptcha Style </label><select name="recaptcha_style-'.$field->get_id().'"><option value="light" '.selected('light',$field->get_recaptcha_style(),false).'>Light</option><option value="dark" '.selected('dark',$field->get_recaptcha_style(),false).'>Dark</option></select></div>';
    }

    public static function date_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"></div>';
    }


    public static function address_fields_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label><input type="checkbox" '.checked('1',$field->get_show_country(),false).' name="show_country-'.$field->get_id().'">Country</label><label><input type="checkbox" '.checked('1',$field->get_show_state(),false).' name="show_state-'.$field->get_id().'">State</label><label><input type="checkbox" '.checked('1',$field->get_show_city(),false).' name="show_city-'.$field->get_id().'">City</label><label><input type="checkbox" '.checked('1',$field->get_show_zip(),false).' name="show_zip-'.$field->get_id().'">Zip</label><label><input type="checkbox" '.checked('1',$field->get_show_address(),false).' name="show_address-'.$field->get_id().'">Address Lines</label></div>';
    }

    public static function countries_list_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><label>Countries List</label><textarea name="countries-'.$field->get_id().'">'.$field->get_countries().'</textarea></div>';
    }

    public static function date_range_setting_row( Huge_Forms_Field $field)
    {
        return '<div class="setting-row"><div class="one-half"><label>Min</label><input type="text" name="minDate-'.$field->get_id().'" class="datepicker"></div><div class="one-half"><label>Max</label><input type="text" name="maxDate-'.$field->get_id().'" class="datepicker"></div></div>';
    }
}