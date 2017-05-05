<?php
/**
 * Template for forms list single item
 *
 * @uses $form Huge_Forms_Form
 */

if( !isset( $form ) ){
    throw new Exception( '"form" variable is not reachable in maps-list-single-item template.' );
}

if( !( $form instanceof Huge_Forms_Form ) ){
    throw new Exception( '"form" variable must be instance of Huge_Forms_Form class.' );
}

$form_id = $form->get_id();

$edit_url = admin_url( 'admin.php?page=huge_forms&task=edit_form&id='.$form_id );

$edit_url = wp_nonce_url( $edit_url, 'huge_forms_edit_form_'.$form_id );

$remove_url = admin_url( 'admin.php?page=huge_forms&task=remove_form&id='.$form_id );

$remove_url = wp_nonce_url( $remove_url, 'huge_forms_remove_form_'.$form_id );

$duplicate_url = admin_url( 'admin.php?page=huge_forms&task=duplicate_form&id='.$form_id );

$duplicate_url = wp_nonce_url( $duplicate_url, 'huge_forms_duplicate_form_'.$form_id );

?>
<tr>
    <td class="form-id"><?php echo $form_id; ?></td>
    <td class="form-name"><a href="<?php echo $edit_url; ?>" ><?php echo esc_html( stripslashes( $form->get_name() ) ); ?></a></td>
    <td class="form-fields"><?php echo count($form->get_fields());; ?></td>
    <td class="form-shortcode">[huge_forms_form id="<?php echo $form_id; ?>"]</td>
    <td class="form-actions">
        <a class="huge_forms_delete_form" href="<?php echo $remove_url; ?>" ><?php _e( 'Delete', HUGE_FORMS_TEXT_DOMAIN ); ?></a> |
        <a class="huge_forms_edit_form" href="<?php echo $edit_url; ?>" ><?php _e( 'Edit', HUGE_FORMS_TEXT_DOMAIN ); ?></a> |
        <a class="huge_forms_duplicate_form" href="<?php echo $duplicate_url;?>"><?php _e( 'Duplicate', HUGE_FORMS_TEXT_DOMAIN ); ?></a>
    </td>
</tr>
