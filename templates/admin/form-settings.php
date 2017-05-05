<?php
/**
 * Template for edit form settings page
 */
global $wpdb;

if( !isset( $form ) ){
    throw new Exception( '"form" variable is not reachable in maps-list-single-item template.' );
}

if( !( $form instanceof Huge_Forms_Form ) ){
    throw new Exception( '"form" variable must be instance of Huge_Forms_Form class.' );
}

$edit_form_link = admin_url( 'admin.php?page=huge_forms&task=edit_form&id='.$form->get_id() );

$edit_form_link = wp_nonce_url( $edit_form_link, 'huge_forms_edit_form_' . $form->get_id()  );

?>

<div class="wrap huge_forms_edit_form_container <?php if( isset($_COOKIE['hugeFormsFullWidth']) && $_COOKIE['hugeFormsFullWidth'] == "yes" ){ echo 'fullwidth-view'; } ?>" data-form="<?php echo $form->get_id();?>">
    <div class="huge_forms_header">
        <?php _e('Form Settings',HUGE_FORMS_TEXT_DOMAIN);?>
        <span id="full-width-button">
            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
        </span>

        <a id="edit-form" href="<?php echo $edit_form_link;?>">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        </a>
    </div>

    <h1>
        <input type="text" id="form_name" value="<?php echo $form->get_name(); ?>">
        <input type="hidden" id="form_id" value="<?php echo $form->get_id(); ?>">
        <span id="save-form-button"><i class="fa fa-check" aria-hidden="true"></i> <?php _e('Save');?></span>
    </h1>

    <div class="huge_forms_content ">
        <form id="huge-form-settings">
            <div id="tabs" class="tabs">
                <nav>
                    <ul>
                        <li><a href="#section-1" class="icon-shop"><span><?php _e('Mailing',HUGE_FORMS_TEXT_DOMAIN);?></span></a></li>
                        <li><a href="#section-2" class="icon-cup"><span><?php _e('Display',HUGE_FORMS_TEXT_DOMAIN);?></span></a></li>
                        <li><a href="#section-3" class="icon-food"><span><?php _e('Conditional Fields',HUGE_FORMS_TEXT_DOMAIN);?></span></a></li>
                    </ul>
                </nav>
                <div class="content">
                    <section id="section-1">
                        <div class="mediabox">
                            <h1><?php _e('Send Emails From',HUGE_FORMS_TEXT_DOMAIN);?></h1>
                            <div class="settings-row">
                                <label><?php _e('Name',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="text" name="email-from-name" value="<?php echo $form->get_from_name(); ?>" >
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Email',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="email" name="email-from-address" value="<?php echo $form->get_from_email(); ?>" >
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Email Users',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="checkbox" name="email-users"  <?php checked(1,$form->get_email_user()); ?> >
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Email Admin',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="checkbox" name="email-admin" <?php checked(1,$form->get_email_admin()); ?> >
                            </div>
                        </div>
                        <div class="mediabox">
                            <h1><?php _e('Admin Settings',HUGE_FORMS_TEXT_DOMAIN);?></h1>

                            <div class="settings-row">
                                <label><?php _e('Email',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="email" name="admin-email" value="<?php echo $form->get_admin_email(); ?>" >
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Mail Subject',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="text" name="admin-subject" value="<?php echo $form->get_admin_mail_subject(); ?>" >
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Mail Message',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <textarea name="admin-message"><?php echo $form->get_admin_mail_message(); ?></textarea>
                            </div>
                        </div>
                        <div class="mediabox">
                            <h1><?php _e('User Settings',HUGE_FORMS_TEXT_DOMAIN);?></h1>
                            <div class="settings-row">
                                <label><?php _e('Mail Subject',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="text" name="user-subject" value="<?php echo $form->get_user_mail_subject(); ?>">
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Mail Message',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <textarea name="user-message"><?php echo $form->get_user_mail_message(); ?></textarea>
                            </div>
                        </div>
                    </section>
                    <section id="section-2">
                        <div class="mediabox">
                            <h1><?php _e('General',HUGE_FORMS_TEXT_DOMAIN);?></h1>
                            <div class="settings-row">
                                <label><?php _e('Display Title',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="checkbox" name="display-title" <?php checked(1,$form->get_display_title()); ?>>
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Action on Submit',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <select name="action-onsubmit">
                                    <?php $actions= Huge_Forms_Query::get_onsubmit_actions();?>
                                    <?php foreach ($actions as $action){ ?>
                                        <option value="<?php echo $action->get_id();?>" <?php selected($action->get_id(),$form->get_action_onsubmit()->get_id());?> >
                                            <?php echo $action->get_name();?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Success Message',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <textarea name="success-message"><?php echo $form->get_success_message(); ?></textarea>
                            </div>
                        </div>
                        <div class="mediabox">
                            <h1><?php _e('Style Settings',HUGE_FORMS_TEXT_DOMAIN);?></h1>
                            <div class="settings-row">
                                <label><?php _e('Form Theme',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <select name="form-theme">
                                    <?php $themes= Huge_Forms_Query::get_themes();?>
                                    <?php foreach ($themes as $theme){ ?>
                                        <option value="<?php echo $theme->get_id();?>" <?php selected($theme->get_id(),$form->get_theme()->get_id());?> >
                                            <?php echo $theme->get_name();?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Labels Position',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <select name="labels-position">
                                    <?php $label_positions= Huge_Forms_Query::get_label_positions();?>
                                    <?php foreach ($label_positions as $label_position){ ?>
                                        <option value="<?php echo $label_position->get_id();?>" <?php selected($label_position->get_id(),$form->get_labels_position()->get_id());?> ><?php echo ucfirst($label_position->get_name());?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mediabox">
                            <h1><?php _e('Alerts & Messages',HUGE_FORMS_TEXT_DOMAIN);?></h1>

                            <div class="settings-row">
                                <label><?php _e('Wrong Email Format',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="text" name="email-format-error" value="<?php echo $form->get_email_format_error(); ?>">
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Required Field Is Empty',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="text" name="required-field-error" value="<?php echo $form->get_required_field_error(); ?>">
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Uploaded Size Exceeded',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="text" name="upload-size-error" value="<?php echo $form->get_upload_size_error(); ?>">
                            </div>
                            <div class="settings-row">
                                <label><?php _e('Wrong File Format',HUGE_FORMS_TEXT_DOMAIN);?></label>
                                <input type="text" name="upload-format-error" value="<?php echo $form->get_upload_format_error(); ?>">
                            </div>
                        </div>
                    </section>
                    <section id="section-3">
                        <div class="mediabox">
                            <h3>Noodle Curry</h3>
                            <p>Lotus root water spinach fennel kombu maize bamboo shoot green bean swiss chard seakale pumpkin onion chickpea gram corn pea.Sushi gumbo beet greens corn soko endive gumbo gourd.</p>
                        </div>
                        <div class="mediabox">
                            <h3>Noodle Curry</h3>
                            <p>Lotus root water spinach fennel kombu maize bamboo shoot green bean swiss chard seakale pumpkin onion chickpea gram corn pea.Sushi gumbo beet greens corn soko endive gumbo gourd.</p>
                        </div>
                        <div class="mediabox">
                            <h3>Noodle Curry</h3>
                            <p>Lotus root water spinach fennel kombu maize bamboo shoot green bean swiss chard seakale pumpkin onion chickpea gram corn pea.Sushi gumbo beet greens corn soko endive gumbo gourd.</p>
                        </div>
                    </section>
                </div><!-- /content -->
            </div><!-- /tabs -->
        </form>
    </div>

</div>