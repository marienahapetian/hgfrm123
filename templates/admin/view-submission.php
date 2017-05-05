<?php
/**
 * Template for view submission page
 */
global $wpdb;

if( !isset( $submission ) ){
    throw new Exception( '"submission" variable is not reachable in view-submission template.' );
}

if( !( $submission instanceof Huge_Forms_Submission ) ){
    throw new Exception( '"submission" variable must be instance of Huge_Forms_Submission class.' );
}
?>
<div class="wrap huge_forms_edit_form_container" data-form="<?php echo $submission->get_id();?>">
    <div class="huge_forms_header">
        <?php _e('View Submission',HUGE_FORMS_TEXT_DOMAIN);?>
        <span id="full-width-button">
            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
        </span>
    </div>
    <h1>
        <input type="hidden" id="submission_id" value="<?php echo $submission->get_id(); ?>">
    </h1>

    <div class="huge_forms_content">

        <table>
            <tr>
                <td>
                    <b><?php _e('User',HUGE_FORMS_TEXT_DOMAIN);?></b>
                </td>
                <td>
                    <?php echo $submission->get_user()->get_username();?>
                </td>
            </tr>
            <tr>
                <td>
                    <b><?php _e('Submission Date',HUGE_FORMS_TEXT_DOMAIN);?></b>
                </td>
                <td>
                    <?php echo $submission->get_date();?>
                </td>
            </tr>
            <tr>
                <td>
                    <b><?php _e('IP',HUGE_FORMS_TEXT_DOMAIN);?></b>
                </td>
                <td>
                    <?php echo $submission->get_user()->get_ip();?>
                </td>
            </tr>
        <?php
        $submission_fields = $submission->get_submission_fields();
        foreach ($submission_fields as $submission_field) { ?>
            <tr>
                <td>
                    <b>
                    <?php $field = $submission_field['field'];
                    echo $field->get_label();
                    ?>
                    </b>
                </td>

                <td><?php echo $submission_field['value'];?></td>
            </tr>
        <?php }
        ?>
        </table>
    </div>

</div>