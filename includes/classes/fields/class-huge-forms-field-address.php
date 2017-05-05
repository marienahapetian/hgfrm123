<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Address
 */
class Huge_Forms_Field_Address extends Huge_Forms_Field
{

    /**
     * List of countries
     *
     * @var string
     */
    private $countries;

    /**
     * Show/Hide Country Select
     *
     * @var int 0|1
     */
    private $show_country;

    /**
     * Show/Hide City Field
     *
     * @var int 0|1
     */
    private $show_city;

    /**
     * Show/Hide State Select
     *
     * @var int 0|1
     */
    private $show_state;

    /**
     * Show/Hide Zip Field
     *
     * @var int 0|1
     */
    private $show_zip;

    /**
     * Show/Hide Address/Address Line 2 Fields
     *
     * @var int 0|1
     */
    private $show_address;


    public function __construct($id = null)
    {
        $id = absint($id);
        if ( $id !== null && absint( $id ) == $id ) {
            global $wpdb;

            $field = $wpdb->get_row( $wpdb->prepare(
                "SELECT * FROM " . Huge_Forms()->get_table_name( 'fields' ) . " as fields INNER JOIN " . Huge_Forms()->get_table_name( 'formFields' ) . " as formFields ON fields.id=formFields.field INNER JOIN " . Huge_Forms()->get_table_name( 'addressOptions' ) . " as addressOptions ON addressOptions.field=fields.id WHERE fields.id=%d",
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
     * @return int
     */
    public function get_show_country() {
        return $this->show_country;
    }

    /**
     * @param int $show
     *
     * @return Huge_Forms_Field_Address
     */
    public function set_show_country( $show ) {

        if(in_array($show,array(0,1,'on'))){

            if($show=='on') $show=1;
            $this->show_country = intval( $show );

        }

        return $this;
    }

    /**
     * @return int
     */
    public function get_show_state() {
        return $this->show_state;
    }

    /**
     * @param int $show
     *
     * @return Huge_Forms_Field_Address
     */
    public function set_show_state( $show ) {

        if(in_array($show,array(0,1,'on'))){

            if($show=='on') $show=1;
            $this->show_state = intval( $show );

        }

        return $this;
    }

    /**
     * @return int
     */
    public function get_show_city() {
        return $this->show_city;
    }

    /**
     * @param int $show
     *
     * @return Huge_Forms_Field_Address
     */
    public function set_show_city( $show ) {

        if(in_array($show,array(0,1,'on'))){

            if($show=='on') $show=1;
            $this->show_city = intval( $show );

        }

        return $this;
    }

    /**
     * @return int
     */
    public function get_show_zip() {
        return $this->show_zip;
    }

    /**
     * @param int $show
     *
     * @return Huge_Forms_Field_Address
     */
    public function set_show_zip( $show ) {

        if(in_array($show,array(0,1,'on'))){

            if($show=='on') $show=1;
            $this->show_zip = intval( $show );

        }

        return $this;
    }

    /**
     * @return int
     */
    public function get_show_address() {
        return $this->show_address;
    }

    /**
     * @param int $show
     *
     * @return Huge_Forms_Field_Address
     */
    public function set_show_address( $show ) {

        if(in_array($show,array(0,1,'on'))){

            if($show=='on') $show=1;
            $this->show_address = intval( $show );

        }

        return $this;
    }

    /**
     * @return string
     */
    public function get_countries() {
        return $this->countries;
    }

    /**
     * @param string $countires
     *
     * @return Huge_Forms_Field_Address
     */
    public function set_countires( $countries ) {

        $this->countries = sanitize_text_field( $countries );

        return $this;
    }


    public function save( $field_id = null )
    {
        global $wpdb;

        $field_data = array();
        $form_field_data=array();


        $this->set_if_not_null( 'label', $this->label, $field_data );
        $this->set_if_not_null( 'type', $this->type->get_id(), $field_data );
        $this->set_if_not_null( 'label_position', $this->label_position, $field_data );
        $this->set_if_not_null( 'class', $this->class, $field_data );
        $this->set_if_not_null( 'ordering', $this->ordering, $field_data );
        $this->set_if_not_null( 'container_class', $this->container_class, $field_data );

        $this->set_if_not_null( 'id', $field_id, $field_data );

        $field_data = is_null( $this->id )
            ? $wpdb->insert( Huge_Forms()->get_table_name( 'fields' ), $field_data )
            : $wpdb->update( Huge_Forms()->get_table_name( 'fields' ), $field_data, array( 'id' => $this->id ) );



        if ( $field_data !== false && ! isset( $this->id ) ) {
            $this->id = $wpdb->insert_id;

            $this->set_if_not_null('form',$this->form,$form_field_data);
            $this->set_if_not_null('field',$this->id,$form_field_data);

            $form_field_data = $wpdb->insert( Huge_Forms()->get_table_name( 'formFields' ), $form_field_data );

            $this->set_if_not_null('field',$this->id,$address_field_data);
            $this->set_if_not_null('countries',$this->countries,$address_field_data);
            $this->set_checkbox('show_country',$this->show_country,$address_field_data);
            $this->set_checkbox('show_city',$this->show_city,$address_field_data);
            $this->set_checkbox('show_city',$this->show_city,$address_field_data);
            $this->set_checkbox('show_state',$this->show_state,$address_field_data);
            $this->set_checkbox('show_address',$this->show_address,$address_field_data);
            $this->set_checkbox('show_zip',$this->show_zip,$address_field_data);

            $form_field_data = $wpdb->insert( Huge_Forms()->get_table_name( 'addressOptions' ), $address_field_data );

            return $this->id;

        } elseif ( $field_data !== false && isset( $this->id ) ) {

            $this->set_if_not_null('field',$this->id,$address_field_data);
            $this->set_if_not_null('countries',$this->countries,$address_field_data);
            $this->set_checkbox('show_country',$this->show_country,$address_field_data);
            $this->set_checkbox('show_city',$this->show_city,$address_field_data);
            $this->set_checkbox('show_city',$this->show_city,$address_field_data);
            $this->set_checkbox('show_state',$this->show_state,$address_field_data);
            $this->set_checkbox('show_address',$this->show_address,$address_field_data);
            $this->set_checkbox('show_zip',$this->show_zip,$address_field_data);

            $form_field_data = $wpdb->update( Huge_Forms()->get_table_name( 'addressOptions' ), $address_field_data,  array( 'field' => $this->id ) );

            return $this->id;

        } else {

            return false;

        }
    }


    public function settings_block()
    {
        $settings_block_html  = '<div class="settings-block" id="'.$this->id.'">';

        $settings_block_html .= Huge_Forms_Admin_Setting::label_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::label_position_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::placeholder_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::address_fields_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::countries_list_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::container_class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::order_setting_row( $this );

        $settings_block_html .= '</div>';

        return $settings_block_html;
    }

    public function field_html()
    {
        ?>
        <div class="hgfrm-form-field <?php echo $this->field_class();?> <?php echo $this->container_class;?>">
            <label>
                <?php echo $this->get_label();?>
                <?php if($this->show_address):?>
                    <div class="field-row">
                        <input type="text" placeholder="Address" class="one-half">
                        <input type="text" placeholder="Address Line 2" class="one-half">
                    </div>
                <?php endif;?>

                <div class="field-row">
                    <?php if($this->show_country):?>
                        <input type="text" placeholder="Country" class="one-half">
                    <?php endif;?>

                    <?php if($this->show_state):?>
                        <input type="text" placeholder="Region" class="one-half">
                    <?php endif;?>

                    <?php if($this->show_city):?>
                        <input type="text" placeholder="City" class="one-half">
                    <?php endif;?>
                    <?php if($this->show_zip):?>
                        <input type="text" placeholder="zip" class="one-half">
                    <?php endif;?>
                </div>
            </label>
        </div>
        <?php
    }

    public function set_properties($fields_settings, $field_id)
    {
        parent::set_properties($fields_settings, $field_id);

        $this->set_show_country($fields_settings['show_country-'.$field_id]);
        $this->set_show_state($fields_settings['show_state-'.$field_id]);
        $this->set_show_city($fields_settings['show_city-'.$field_id]);
        $this->set_show_address($fields_settings['show_address-'.$field_id]);
        $this->set_show_zip($fields_settings['show_zip-'.$field_id]);
        $this->set_countires($fields_settings['countries-'.$field_id]);

    }

}