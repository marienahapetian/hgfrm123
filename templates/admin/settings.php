<?php
/**
 * Template for Huge Forms Settings Page
 */
global $wpdb;
?>

<div class="wrap" id="hgfrm-settings">
    <div class="huge_forms_header">
        <?php _e('Settings',HUGE_FORMS_TEXT_DOMAIN);?>
        <span id="full-width-button">
            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
        </span>
    </div>


    <div class="huge_forms_content">

        <form id="huge-form">
            <span id="save-form-button"><i class="fa fa-check" aria-hidden="true"></i> <?php _e('Save');?></span>

            <div class="one-third">
                <div class="setting-row">
                    <label>Recaptcha Secret Key</label>
                    <input type="text" value="<?php echo Huge_Forms::get_setting('recaptcha-secret-key'); ?>" name="recaptcha-secret-key">
                </div>

                <div class="setting-row">
                    <label>Recaptcha Public Key</label>
                    <input type="text" value="<?php echo Huge_Forms::get_setting('recaptcha-public-key'); ?>" name="recaptcha-public-key">
                </div>

                <div class="setting-row">
                    <label>Google Map Api Key</label>
                    <input type="text" value="<?php echo Huge_Forms::get_setting('gmap-api-key'); ?>" name="gmap-api-key" >
                </div>
            </div>

            <div class="one-third">

                <div class="setting-row">
                    <label>Date Format</label>
                    <select name="date-format">
                        <option>d/m/y</option>
                        <option>dd-mm-yy</option>
                    </select>
                </div>

                <div class="setting-row">
                    <label class="inline-label">Remove all data on plugin uninstall</label>
                    <input type="checkbox" name="remove-tables-uninstall">
                </div>
            </div>
        </form>

    </div>

</div>
<?php
?>