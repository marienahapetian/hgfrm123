<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Map
 */
class Huge_Forms_Field_Map extends Huge_Forms_Field
{

    /**
     * map center lat,lng
     *
     * @var string
     */
    private $map_center;

    /**
     * map draggable on or off
     *
     * @var int
     */
    private $draggable;

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
        $this->set_if_not_null( 'map_center', $this->map_center, $field_data );
        $this->set_checkbox( 'draggable', $this->draggable, $field_data );

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

        $settings_block_html .= Huge_Forms_Admin_Setting::map_center_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::map_draggable_setting_row( $this );

        $settings_block_html .= Huge_Forms_Admin_Setting::class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::container_class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::helptext_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::order_setting_row( $this );
        $settings_block_html.='</div>';

        return $settings_block_html;
    }

    public function field_html()
    {
        $map_api_key = Huge_Forms::get_setting('gmap-api-key');
        wp_enqueue_script('huge_forms_gmap','https://maps.googleapis.com/maps/api/js?key='.$map_api_key.'&callback=huge_forms_initMap',array('jquery'),'1.0.0',false);

        ?>
        <div class="hgfrm-form-field <?php echo $this->field_class();?> <?php echo $this->container_class;?>">
            <label for=""><?php echo $this->get_label();?></label>
            <?php $this->help_text_block();?>

            <div class="huge-forms-map-block <?php echo $this->class;?>" draggable="<?php echo $this->draggable;?>" center_lat="<?php echo $this->get_map_center_lat();?>" center_lng="<?php echo $this->get_map_center_lng();?>" id="huge_forms_map_<?php echo $this->id;?>" style="height:300px;"></div>
        </div>
        <?php
    }

    /**
     * @return string
     */
    public function get_map_center( )
    {

        return $this->map_center;

    }

    /**
     * @param string $latlng
     *
     * @return Huge_Forms_Field_Map
     */
    public function set_map_center( $latlng )
    {
        $this->map_center =  $latlng ;

        return $this;
    }


    /**
     * @return string
     */
    public function get_map_center_lat( )
    {

        $center = $this->map_center;

        $center_lat_lng = explode(',lng:',$center);

        $center_lat = str_replace('{lat:','',$center_lat_lng[0]);

        return $center_lat;

    }

    /**
     * @return string
     */
    public function get_map_center_lng( )
    {

        $center = $this->map_center;

        $center_lat_lng = explode(',lng:',$center);

        $center_lng = str_replace('}','',$center_lat_lng[1]);

        return $center_lng;

    }

    /**
     * @return int 0|1
     */
    public function get_draggable( )
    {

        return $this->draggable;

    }

    /**
     * @param int 0|1 $draggable
     *
     * @return Huge_Forms_Field_Map
     */
    public function set_draggable( $draggable )
    {
        if(in_array($draggable,array(0,1))){

            $this->draggable =  $draggable ;

        }

        return $this;
    }


    public function set_properties($fields_settings, $field_id)
    {
        parent::set_properties($fields_settings, $field_id);

        $this->set_map_center($fields_settings['map_center-'.$field_id]);
        $this->set_draggable($fields_settings['draggable-'.$field_id]);

    }
}