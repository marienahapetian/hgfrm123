<?php
if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Theme
 */
class Huge_Forms_Theme
{
    /**
     * Theme ID
     *
     * @var int
     */
    private $id;

    /**
     * Theme Name
     *
     * @var string
     */
    private $name;


    public function __construct($id=null)
    {
        if ( $id !== null && absint( $id ) == $id ) {
            global $wpdb;

            $theme = $wpdb->get_row( $wpdb->prepare(
                " SELECT * FROM " . Huge_Forms()->get_table_name( 'themes' ) . " WHERE id=%d ",
                $id
            ), ARRAY_A );

            if ( ! is_null( $theme ) ) {

                $this->id = $id;

                foreach ( $theme as $theme_option_name => $theme_option_value ) {

                    $function_name = 'set_' . $theme_option_name;

                    if ( method_exists( $this, $function_name ) ) {

                        call_user_func( array( $this, $function_name ), $theme_option_value );

                    }

                }

            }

        } else {

            $this->name = __( 'New Theme', HUGE_FORMS_TEXT_DOMAIN );

        }
    }

    /**
     * Sets $array[$key] = $value if $value is not NULL.
     *
     * @param $key
     * @param $value
     * @param $array
     */
    private function set_if_not_null( $key, $value, &$array )
    {
        if ( $value !== null ) {
            $array[ $key ] = $value;
        }
    }

    /**
     * @return mixed
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function get_name() {
        return (!empty($this->name) ? $this->name : __( '(no title)', HUGE_FORMS_TEXT_DOMAIN ) );
    }

    /**
     * @param string $name
     *
     * @return Huge_Forms_Theme
     */
    public function set_name( $name ) {
        $this->name = sanitize_text_field( $name );

        return $this;
    }

    /**
     * theme data
     */

    public function save( $theme_id=null )
    {

        global $wpdb;

        $theme_data = array();

        $this->set_if_not_null('name', $this->name, $theme_data);

        $this->set_if_not_null('id', $theme_id, $theme_data);

        $theme_data = is_null($this->id)
            ? $wpdb->insert(Huge_Forms()->get_table_name('themes'), $theme_data)
            : $wpdb->update(Huge_Forms()->get_table_name('themes'), $theme_data, array('id' => $this->id));


        if ($theme_data !== false && !isset($this->id)) {

            $this->id = $wpdb->insert_id;

            return $this->id;

        } elseif ($theme_data !== false && isset($this->id)) {

            return $this->id;

        } else {

            return false;

        }
    }

}