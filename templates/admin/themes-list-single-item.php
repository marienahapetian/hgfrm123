<?php
/**
 * Template for themes list single item
 *
 * @uses $theme Huge_Forms_Theme
 */

if( !isset( $theme ) ){
    throw new Exception( '"theme" variable is not reachable in themes-list-single-item template.' );
}

if( !( $theme instanceof Huge_Forms_Theme ) ){
    throw new Exception( '"theme" variable must be instance of Huge_Forms_Theme class.' );
}

$theme_id = $theme->get_id();

$edit_url = admin_url( 'admin.php?page=huge_forms_themes&task=edit_theme&id='.$theme_id );

$edit_url = wp_nonce_url( $edit_url, 'huge_forms_edit_theme_'.$theme_id );

$remove_url = admin_url( 'admin.php?page=huge_forms_themes&task=remove_theme&id='.$theme_id );

$remove_url = wp_nonce_url( $remove_url, 'huge_forms_remove_theme_'.$theme_id );

$duplicate_url = admin_url( 'admin.php?page=huge_forms_themes&task=duplicate_theme&id='.$theme_id );

$duplicate_url = wp_nonce_url( $duplicate_url, 'huge_forms_duplicate_theme_'.$theme_id );
?>
<tr>
    <td class="theme-id"><?php echo $theme_id; ?></td>
    <td class="theme-name"><a href="<?php echo $edit_url; ?>" ><?php echo esc_html( stripslashes( $theme->get_name() ) ); ?></a></td>
    <td class="theme-actions">
        <a class="huge_forms_delete_form" href="<?php echo $remove_url; ?>" ><?php _e( 'Delete', HUGE_FORMS_TEXT_DOMAIN ); ?></a> |
        <a class="huge_forms_edit_form" href="<?php echo $edit_url; ?>" ><?php _e( 'Edit', HUGE_FORMS_TEXT_DOMAIN ); ?></a> |
        <a class="huge_forms_duplicate_form" href="<?php echo $duplicate_url; ?>" ><?php _e( 'Duplicate', HUGE_FORMS_TEXT_DOMAIN ); ?></a>
    </td>
</tr>
