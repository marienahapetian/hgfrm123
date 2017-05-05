<?php
/**
 * Template for themes list
 */
global $wpdb;

$new_theme_link = admin_url( 'admin.php?page=huge_forms_themes&task=create_new_theme' );

$new_theme_link = wp_nonce_url( $new_theme_link, 'huge_forms_create_new_theme' );

?>
<div class="wrap huge_forms_list_container ">
    <div class="huge_forms_header">
        <span><?php _e( 'Huge Themes', HUGE_FORMS_TEXT_DOMAIN ); ?></span>
        <a class="page-title-action" href="<?php echo $new_theme_link; ?>">
            <?php _e( 'New Theme', HUGE_FORMS_TEXT_DOMAIN ); ?>
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
            <th style="width:40px"><?php _e( 'Actions', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php

        $themes = Huge_Forms_Query::get_themes();
        if ( !empty( $themes ) ) {
            foreach ( $themes as $theme ) {

                Huge_Forms_Template_Loader::get_template( 'admin/themes-list-single-item.php', array( 'theme'=>$theme ) );

            }
        } else {

            Huge_Forms_Template_Loader::get_template( 'admin/themes-list-no-items.php' );

        }

        ?>
        </tbody>
        <tfoot>
        <tr>
            <th scope="col" class="footer-id" style="width:30px"><span><?php _e( 'ID', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
            <th scope="col" class="footer-name" style="width:85px"><span><?php _e( 'Name', HUGE_FORMS_TEXT_DOMAIN ); ?></span></th>
            <th style="width:40px"><?php _e( 'Actions', HUGE_FORMS_TEXT_DOMAIN ); ?></th>
        </tr>
        </tfoot>
    </table>
</div>