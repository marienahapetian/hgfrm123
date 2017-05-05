<?php
$new_form_link = admin_url( 'admin.php?page=huge_forms&task=create_new_form' );

$new_form_link = wp_nonce_url( $new_form_link, 'huge_forms_create_new_form' );
?>
<tr><td colspan="5"><?php _e('No Forms Found.',HUGE_FORMS_TEXT_DOMAIN);?> <a href="<?php echo $new_form_link;?>"><?php _e('Add New',HUGE_FORMS_TEXT_DOMAIN);?></a></td></tr>