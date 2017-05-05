<?php
/**
 * Template for submissions list single item
 *
 * @uses $submission Huge_Forms_Submission
 */

if( !isset( $submission ) ){
    throw new Exception( '"submission" variable is not reachable in submissions-list-single-item template.' );
}

if( !( $submission instanceof Huge_Forms_Submission ) ){
    throw new Exception( '"submission" variable must be instance of Huge_Forms_Submission class.' );
}

$submission_id = $submission->get_id();

$form_id = $submission->get_form()->get_id();

$view_url = admin_url( 'admin.php?page=huge_forms_submissions&task=view_submission&id='.$submission_id );

$view_url = wp_nonce_url( $view_url, 'huge_forms_view_submission_'.$submission_id );

$edit_form_url = admin_url( 'admin.php?page=huge_forms&task=edit_form&id='. $form_id );

$edit_form_url = wp_nonce_url( $edit_form_url, 'huge_forms_edit_form_'.$form_id );

$remove_url = admin_url( 'admin.php?page=huge_forms_submissions&task=remove_submission&id='.$submission_id );

$remove_url = wp_nonce_url( $remove_url, 'huge_forms_remove_submission_'.$submission_id );

?>
<tr>
    <td class="sub-id"><?php echo $submission_id; ?></td>
    <td class="sub-user"><?php  echo $submission->get_user()->get_username(); ?></td>
    <td class="sub-ip"><?php echo $submission->get_user()->get_ip();?></td>
    <td class="sub-date"><?php echo $submission->get_date();?></td>
    <td class="sub-form"><a href="<?php echo $edit_form_url; ?>" ><?php echo $submission->get_form()->get_name();?></a></td>
    <td class="sub-actions">
        <a class="huge_forms_delete_submission" href="<?php echo $remove_url; ?>" ><?php _e( 'Delete', HUGE_FORMS_TEXT_DOMAIN ); ?></a> |
        <a class="huge_forms_view_submission" href="<?php echo $view_url; ?>" ><?php _e( 'View', HUGE_FORMS_TEXT_DOMAIN ); ?></a>
    </td>
</tr>
