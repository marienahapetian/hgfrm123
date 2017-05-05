<?php
/**
 * Template for edit form page
 */
global $wpdb;

if( !isset( $form ) ){
    throw new Exception( '"form" variable is not reachable in edit-form template.' );
}

if( !( $form instanceof Huge_Forms_Form ) ){
    throw new Exception( '"form" variable must be instance of Huge_Forms_Form class.' );
}

$fields = $form->get_fields();

$form_settings_link = admin_url( 'admin.php?page=huge_forms&task=edit_form_settings&id='.$form->get_id() );

$form_settings_link = wp_nonce_url( $form_settings_link, 'huge_forms_edit_form_settings_'.$form->get_id() );
?>
<div class="wrap huge_forms_edit_form_container <?php if( isset($_COOKIE['hugeFormsFullWidth']) && $_COOKIE['hugeFormsFullWidth'] == "yes" ){ echo 'fullwidth-view'; } ?>" data-form="<?php echo $form->get_id();?>">
    <div class="huge_forms_header">
        <?php _e('Edit Form',HUGE_FORMS_TEXT_DOMAIN);?>
        <span id="full-width-button">
            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
        </span>

        <a id="form-settings" href="<?php echo $form_settings_link;?>">
            <i class="fa fa-cogs" aria-hidden="true"></i>
        </a>
    </div>
    <h1>
        <input type="text" id="form_name" value="<?php echo $form->get_name(); ?>">
        <input type="hidden" id="form_id" value="<?php echo $form->get_id(); ?>">
        <span id="save-form-button"><i class="fa fa-check" aria-hidden="true"></i> <?php _e('Save');?></span>
    </h1>

    <div class="huge_forms_content">
        <form id="huge-form" name="hugeFormEdit">
                <div class="left-col">
                    <div class="droptrue one-third" id="fields-list">
                        <?php foreach ($fields as $key=>$field){
                            if($key==0) $class='selected';
                            else $class='';
                            echo '<div class="field-block ui-state-default '.$class.'" data-field-id="'.$field->get_id().'" data-field-type="'.$field->get_type()->get_id().'"><span>'.$field->get_label().'</span><i class="fa fa-clone" aria-hidden="true"></i> <i class="fa fa-trash-o" aria-hidden="true"></i></div>';
                        }?>
                    </div>
                    <div class="droptrue two-third" id="settings-list">
                        <?php foreach ($fields as $key=>$field){
                            echo $field->settings_block();
                        }?>
                    </div>

                </div>

                <div class="right-col">
                    <div class="block-title"><?php _e('Add New Field',HUGE_FORMS_TEXT_DOMAIN);?></div>
                    <?php $fieldTypes=Huge_Forms_Query::get_field_types();
                    foreach ($fieldTypes as $key=>$fieldType){
                        echo '<div class="type-block hg-'.$fieldType->get_name().'" type-id="'.$fieldType->get_id().'" >'.ucfirst($fieldType->get_name()).'</div>';
                    }
                    ?>
                </div>
        </form>
    </div>

</div>