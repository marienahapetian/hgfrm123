<?php
/**
 *
 */

$forms = Huge_Forms_Query::get_forms();

?>
<style>
    .tb_popup_form {
        position: relative;
        display: block;
    }

    .tb_popup_form li {
        display: block;
        height: 35px;
        width: 70%;
    }

    .tb_popup_form li label {
        float: left;
        width: 35%
    }

    .tb_popup_form li input {
        float: left;
        width: 60%;
    }

    .slider, .slider-container {
        display: block;
        position: relative;
        height: 35px;
        line-height: 35px;
    }


</style>
<div id="huge_forms" style="display:none;">
    <?php

    if( $forms && !empty($forms) ){
        Huge_Forms_Template_Loader::get_template('admin/inline-popup-form.php', array( 'forms' => $forms ));
    }else{
        printf(
            '<p>%s<a class="button" href="%s">%s</a></p>',
            __('You have not created any forms yet', HUGE_FORMS_TEXT_DOMAIN),
            admin_url('admin.php?page=huge_forms&task=create_new_form'),
            __( 'Create New Form', HUGE_FORMS_TEXT_DOMAIN )
        );
    }

    ?>
</div>
