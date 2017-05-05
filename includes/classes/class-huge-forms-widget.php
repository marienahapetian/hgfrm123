<?php

/**
 * Class Huge_Forms_Widget
 */
class Huge_Forms_Widget extends WP_Widget  {
    /**
     * Huge_Forms_Widget constructor.
     */
    public function __construct() {
        parent::__construct(
            'Huge_Forms_Widget',
            __( 'Huge Forms', HUGE_FORMS_TEXT_DOMAIN ),
            array( 'description' => __( 'Huge Forms', HUGE_FORMS_TEXT_DOMAIN ), )
        );
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        extract( $args );

        if ( isset( $instance['hgfrm_form_id'] ) && (absint($instance['hgfrm_form_id'])===$instance['hgfrm_form_id']) ) {
            $hgfrm_form_id = $instance['hgfrm_form_id'];

            $title = apply_filters( 'widget_title', $instance['title'] );

            echo $before_widget;
            if ( ! empty( $title ) ) {
                echo $before_title . $title . $after_title;
            }

            echo do_shortcode( "[hgfrm_form id='{$hgfrm_form_id}']" );
            echo $after_widget;
        } else{
            echo __('Select form to display',HUGE_FORMS_TEXT_DOMAIN);
        }
    }

    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance             = array();
        $instance['hgfrm_form_id'] = strip_tags( $new_instance['hgfrm_form_id'] );
        $instance['title']    = strip_tags( $new_instance['title'] );

        return $instance;
    }

    /**
     * @param array $instance
     */
    public function form( $instance ) {
        $form_instance = ( isset( $instance['hgfrm_form_id'] ) ? $instance['hgfrm_form_id'] : 0 );
        $title        = ( isset( $instance['title'] ) ? $instance['title'] : '' );

        ?>
        <p>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>
        <label for="<?php echo $this->get_field_id( 'hgfrm_form_id' ); ?>"><?php _e( 'Select Form:', HUGE_FORMS_TEXT_DOMAIN ); ?></label>
        <select id="<?php echo $this->get_field_id( 'hgfrm_form_id' ); ?>" name="<?php echo $this->get_field_name( 'hgfrm_form_id' ); ?>">
            <?php
            $forms = Huge_Forms_Query::get_forms();

            if( $forms ){
                foreach( $forms as $form ){
                    ?>
                    <option <?php echo selected( $form_instance, $form->get_id() ); ?> value="<?php echo $form->get_id(); ?>">
                        <?php echo $form->get_name(); ?>
                    </option>
                    <?php
                }
            }


            ?>
        </select>

        </p>
        <?php
    }
}