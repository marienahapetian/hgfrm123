<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Query handler for Huge-IT Google Maps
 */
class Huge_Forms_Query {

    /**
     * Returns forms by arguments
     *
     * @param $args
     *
     * @return Huge_Forms_Form[]
     */
    public static function get_forms( $args = array() ) {
        global $wpdb;

        $forms_array = array();

        $args = wp_parse_args(
            $args,
            array(
                'search'  => '',
                'orderby' => 'id',
                'order'   => 'ASC'
            )
        );

        $search_str = ! empty( $args['search'] ) ? "WHERE name LIKE '%{$args['search']}%'" : "";

        $ordering = "ORDER BY {$args['orderby']} {$args['order']}";

        $query = "SELECT id FROM " . Huge_Forms()->get_table_name( 'forms' ) . " {$search_str} {$ordering}";

        $forms = $wpdb->get_results( $query );

        foreach ( $forms as $form ) {

            $forms_array[] = new Huge_Forms_Form( $form->id );

        }

        return $forms_array;
    }

    /**
     * Returns form by id
     *
     * @param $args
     *
     * @return Huge_Forms_Form[]
     */
    public static function get_form( $id ) {
        global $wpdb;

        $id = absint( $id );

        $query = "SELECT * FROM " . Huge_Forms()->get_table_name( 'forms' ) . "  WHERE id={$id}";

        $form = $wpdb->get_results( $wpdb->prepare($query) );

        $form = new Huge_Forms_Form($form->id);

        return $form;
    }

    /**
 * Returns form fields by form id
 *
 * @param $args
 *
 * @return Huge_Forms_Field[]
 */
    public static function get_form_fields( $id ) {
        global $wpdb;

        $id = absint( $id );

        $query = "SELECT * FROM " . Huge_Forms()->get_table_name( 'formFields' ) . " as formfields INNER JOIN " . Huge_Forms()->get_table_name( 'fields' ) . " as fields on fields.id=formfields.field INNER JOIN " . Huge_Forms()->get_table_name( 'fieldTypes' ) . " as fieldTypes on fields.type=fieldTypes.id  WHERE formfields.form={$id} ORDER BY fields.ordering";

        $fields = $wpdb->get_results( $query );

        $fieldsArray = array();

        foreach ( $fields as $field ){
            $class_name = 'Huge_Forms_Field_'.ucfirst($field->name);
            $fieldsArray[] = new $class_name($field->field);
        }

        return $fieldsArray;
    }

    /**
     * Returns field options by field id
     *
     * @param $field_id
     *
     * @return Huge_Forms_Field_Option[]
     */
    public static function get_field_options( $field_id ) {
        global $wpdb;

        $id = absint( $field_id );

        $query = "SELECT * FROM " . Huge_Forms()->get_table_name( 'fieldOptions' ) . "  WHERE field ={$id} ORDER BY ordering";

        $options = $wpdb->get_results( $query );

        $optionsArray = array();

        foreach ( $options as $option ){
            $optionsArray[] = new Huge_Forms_Field_Option($option->id);
        }

        return $optionsArray;
    }

    /**
     * Returns themes by arguments
     *
     * @param $args
     *
     * @return Huge_Forms_Theme[]
     */
    public static function get_themes( $args = array() ) {
        global $wpdb;

        $themes_array = array();

        $args = wp_parse_args(
            $args,
            array(
                'search'  => '',
                'orderby' => 'id',
                'order'   => 'ASC'
            )
        );

        $search_str = ! empty( $args['search'] ) ? "WHERE name LIKE '%{$args['search']}%'" : "";

        $ordering = "ORDER BY {$args['orderby']} {$args['order']}";

        $query = "SELECT id FROM " . Huge_Forms()->get_table_name( 'themes' ) . " {$search_str} {$ordering}";

        $themes = $wpdb->get_results( $query );

        foreach ( $themes as $theme ) {

            $themes_array[] = new Huge_Forms_Theme( $theme->id );

        }

        return $themes_array;
    }

    /**
     * Returns submit actions by arguments
     *
     * @param $args
     *
     * @return Huge_Forms_Onsubmit_Action[]
     */
    public static function get_onsubmit_actions( $args = array() ) {
        global $wpdb;

        $actions_array = array();

        $args = wp_parse_args(
            $args,
            array(
                'search'  => '',
                'orderby' => 'id',
                'order'   => 'ASC'
            )
        );

        $search_str = ! empty( $args['search'] ) ? "WHERE name LIKE '%{$args['search']}%'" : "";

        $ordering = "ORDER BY {$args['orderby']} {$args['order']}";

        $query = "SELECT id FROM " . Huge_Forms()->get_table_name( 'onsubmitActions' ) . " {$search_str} {$ordering}";

        $actions = $wpdb->get_results( $query );

        foreach ( $actions as $action ) {

            $actions_array[] = new Huge_Forms_Onsubmit_Action( $action->id );

        }

        return $actions_array;
    }

    /**
     * Returns field types
     *
     * @param $args
     *
     * @return Huge_Forms_Field_Type[]
     */
    public static function get_field_types( $args = array() ) {
        global $wpdb;

        $types_array = array();

        $args = wp_parse_args(
            $args,
            array(
                'search'  => '',
                'orderby' => 'id',
                'order'   => 'ASC'
            )
        );

        $search_str = ! empty( $args['search'] ) ? "WHERE name LIKE '%{$args['search']}%'" : "";

        $ordering = "ORDER BY {$args['orderby']} {$args['order']}";

        $query = "SELECT id FROM " . Huge_Forms()->get_table_name( 'fieldTypes' ) . " {$search_str} {$ordering}";

        $types = $wpdb->get_results( $query );

        foreach ( $types as $type ) {

            $types_array[] = new Huge_Forms_Field_Type( $type->id );

        }

        return $types_array;
    }

    /**
     * Returns field type
     *
     * @param int $field
     *
     * @return Huge_Forms_Field_Type[]
     */
    public static function get_field_type( $field ) {
        global $wpdb;

        $type = $wpdb->get_var( $wpdb->prepare("SELECT types.id as type_id FROM " . Huge_Forms()->get_table_name( 'fields' ) . " as fields INNER JOIN  " . Huge_Forms()->get_table_name( 'fieldTypes' ) . " as types ON fields.type=types.id WHERE fields.id=%d",$field) );

        return new Huge_Forms_Field_Type($type);
    }

    public static function  get_label_positions(){

        global $wpdb;

        $positions_array = array();

        $query = "SELECT * FROM " . Huge_Forms()->get_table_name( 'labelPositions' ) . " ";

        $positions = $wpdb->get_results( $query );

        foreach ( $positions as $position ) {

            $positions_array[] = new Huge_Forms_Label_Position( $position->id );

        }

        return $positions_array;
    }

    public static function  get_submissions(){

        global $wpdb;

        $submissions_array = array();

        $query = "SELECT * FROM " . Huge_Forms()->get_table_name( 'submissions' ) . " ";

        $submissions = $wpdb->get_results( $query );

        foreach ( $submissions as $submission ) {

            $submissions_array[] = new Huge_Forms_Submission( $submission->id );

        }

        return $submissions_array;
    }

    public static function  update_option( $key,$value ){

        global $wpdb;


        $option_exists=count($wpdb->get_results('SELECT * FROM '. Huge_Forms()->get_table_name( 'settings' ) .' WHERE name="'.esc_sql($key).'"'));

        if($option_exists) {
                $wpdb->update( Huge_Forms()->get_table_name( 'settings' ) ,
                    array('value' => esc_sql($value)),
                    array('name' => esc_sql($key)),
                    array('%s')
                );
        } else {
                $wpdb->insert( Huge_Forms()->get_table_name( 'settings' ) ,
                    array('value' => esc_sql($value),'name' => esc_sql($key)),
                    array('%s')
                );
        }

    }

    public static function get_options(  ){

        global $wpdb;


        $options=$wpdb->query('SELECT * FROM '.Huge_Forms()->get_table_name( 'settings' ));

        return $options;


    }

    public static function get_option( $name ){

        global $wpdb;

        $option=$wpdb->get_var($wpdb->prepare('SELECT value FROM '.Huge_Forms()->get_table_name( 'settings' ).' WHERE name=%s',$name));

        return $option;


    }

}