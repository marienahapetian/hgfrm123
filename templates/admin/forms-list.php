<?php
/**
 * Template for main forms list
 */
global $wpdb;

$new_form_link = admin_url( 'admin.php?page=huge_forms&task=create_new_form' );

$new_form_link = wp_nonce_url( $new_form_link, 'huge_forms_create_new_form' );

$form_templates_link = admin_url( 'admin.php?page=huge_forms&task=choose_form_template' );

$form_templates_link = wp_nonce_url( $form_templates_link, 'huge_forms_choose_form_template' );

?>
<div class="wrap huge_forms_list_container ">
    <div class="huge_forms_header">
        <span><?php _e( 'Huge Forms', HUGE_FORMS_TEXT_DOMAIN ); ?></span>
        <a class="page-title-action" href="<?php echo $new_form_link; ?>">
                <?php _e( 'New Form', HUGE_FORMS_TEXT_DOMAIN ); ?>
        </a>

        <a class="page-title-action" href="<?php echo $form_templates_link; ?>">
                <?php _e( 'Form Templates', HUGE_FORMS_TEXT_DOMAIN ); ?>
        </a>

        <span id="full-width-button">
            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
        </span>
    </div>


    <table class="widefat striped fixed forms_table">
        <thead>
        <tr>
            <th scope="col" id="header-id" style="width:30px"><span><?php _e( 'ID', HUGE_FORMS_TEXT_DOMAIN ); ?></span></span></th>
            <th scope="col" id="header-name" style="width:85px"><span><?php _e( 'Name', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th scope="col" id="header-fields" style="width:85px"><span><?php _e( 'Fields', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th scope="col" id="header-shortcode" style="width:85px"><span><?php _e( 'Shortcode', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th style="width:40px"><?php _e( 'Actions', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php

        $forms = Huge_Forms_Query::get_forms();
        if ( !empty( $forms ) ) {
            foreach ( $forms as $form ) {

                Huge_Forms_Template_Loader::get_template( 'admin/forms-list-single-item.php', array( 'form'=>$form ) );

            }
        } else {

            Huge_Forms_Template_Loader::get_template( 'admin/forms-list-no-items.php' );

        }

        ?>
        </tbody>
        <tfoot>
        <tr>
            <th scope="col" class="footer-id" style="width:30px"><span><?php _e( 'ID', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
            <th scope="col" class="footer-name" style="width:85px"><span><?php _e( 'Name', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th scope="col" class="footer-fields" style="width:85px"><span><?php _e( 'Fields', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th scope="col" class="footer-shortcode" style="width:85px"><span><?php _e( 'Shortcode', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th style="width:40px"><?php _e( 'Actions', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
        </tr>
        </tfoot>
    </table>
</div>