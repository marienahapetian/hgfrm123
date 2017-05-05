<?php
/**
 * Template for edit form page
 */
global $wpdb;

if( !isset( $theme ) ){
    throw new Exception( '"theme" variable is not reachable in edit-theme template.' );
}

if( !( $theme instanceof Huge_Forms_Theme ) ){
    throw new Exception( '"theme" variable must be instance of Huge_Forms_Theme class.' );
}
?>
<div class="wrap huge_forms_edit_form_container">
    <div class="huge_forms_header">
        Edit Theme
        <span id="full-width-button">
            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
        </span>
    </div>
    <h1>
        <input type="text" id="theme_name" value="<?php echo $theme->get_name(); ?>">

        <span id="save-form-button"><?php _e('Save');?></span>
    </h1>

    <div class="huge_forms_content">

    </div>

</div>
