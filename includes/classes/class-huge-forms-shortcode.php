<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Shortcode
 */
class Huge_Forms_Shortcode
{
    /**
     * Huge_Forms_Shortcode constructor.
     */
    public function __construct() {
        add_shortcode( 'huge_forms_form', array( $this, 'run_form_shortcode' ) );

        add_action( 'admin_footer', array( $this, 'inline_popup_content' ) );

        add_action( 'media_buttons_context', array( $this, 'add_editor_media_button' ) );
    }

    /**
     * Run the shortcode on front-end
     *
     * @param $attrs
     *
     * @return string
     * @throws Exception
     */
    public function run_form_shortcode( $attrs ) {
        $attrs = shortcode_atts( array(
            'id' => false,
        ), $attrs );

        if ( ! $attrs['id'] || absint( $attrs['id'] ) != $attrs['id'] ) {
            throw new Exception( '"id" parameter is required and must be not negative integer.' );
        }


        do_action( 'huge_forms_shortcode_scripts', $attrs['id'] );

        return $this->init_frontend( $attrs['id'] );
    }

    /**
     * Initialize the front end
     *
     * @param $id int
     *
     * @return string
     */
    private function init_frontend( $id ) {
        ob_start();

        $form = new Huge_Forms_Form( $id );

        Huge_Forms_Template_Loader::get_template( 'frontend/form.php', array( 'form' => $form ) );

        return ob_get_clean();
    }

    /**
     * Add editor media button
     *
     * @param $context
     *
     * @return string
     */
    public function add_editor_media_button( $context ) {
        $img          = untrailingslashit( Huge_Forms()->plugin_url() ) . "/assets/images/forms_logo.png";
        $container_id = 'huge_forms';
        $title        = __( 'Select Huge Form to insert into post', HUGE_FORMS_TEXT_DOMAIN );
        $button_text  = __( 'Huge Form', HUGE_FORMS_TEXT_DOMAIN );
        $context .= '<a class="button thickbox" title="' . $title . '"    href="#TB_inline?width=400&inlineId=' . $container_id . '">
		<span class="wp-media-buttons-icon" style="background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;background-size: 18px 18px;"></span>' . $button_text . '</a>';

        return $context;
    }

    /**
     * Inline popup contents
     * todo: restrict to posts and pages
     */
    public function inline_popup_content() {
        Huge_Forms_Template_Loader::get_template( 'admin/inline-popup.php' );
    }
}