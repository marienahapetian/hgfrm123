<?php
/**
 * Template for submissions list
 */
global $wpdb;

?>
<div class="wrap huge_submissions_list_container ">
    <div class="huge_forms_header">
        <span><?php _e( 'Huge Submissions', HUGE_FORMS_TEXT_DOMAIN ); ?></span>
        <span id="full-width-button">
            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
        </span>
    </div>


    <table class="widefat striped fixed forms_table">
        <thead>
        <tr>
            <th scope="col" id="header-id" style="width:30px"><span><?php _e( 'ID', HUGE_FORMS_TEXT_DOMAIN ); ?></span></span></th>
            <th scope="col" id="header-user" style="width:30px"><span><?php _e( 'User', HUGE_FORMS_TEXT_DOMAIN ); ?></span></span></th>
            <th scope="col" id="header-ip" style="width:30px"><span><?php _e( 'IP', HUGE_FORMS_TEXT_DOMAIN ); ?></span></span></th>
            <th scope="col" id="header-date" style="width:50px"><span><?php _e( 'Date', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th scope="col" id="header-form" style="width:85px"><span><?php _e( 'Form', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th style="width:40px"><?php _e( 'Actions', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php

        $submissions = Huge_Forms_Query::get_submissions();

        if ( !empty( $submissions ) ) {
            foreach ( $submissions as $submission ) {

                Huge_Forms_Template_Loader::get_template( 'admin/submissions-list-single-item.php', array( 'submission'=>$submission ) );

            }
        } else {

            Huge_Forms_Template_Loader::get_template( 'admin/submissions-list-no-items.php' );

        }

        ?>
        </tbody>
        <tfoot>
        <tr>
            <th scope="col" class="footer-id" style="width:30px"><span><?php _e( 'ID', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
            <th scope="col" class="footer-user" style="width:30px"><span><?php _e( 'User', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
            <th scope="col" class="footer-ip" style="width:30px"><span><?php _e( 'IP', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
            <th scope="col" class="footer-date" style="width:50px"><span><?php _e( 'Date', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th scope="col" class="footer-form" style="width:85px"><span><?php _e( 'Form', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th style="width:40px"><?php _e( 'Actions', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
        </tr>
        </tfoot>
    </table>
</div>