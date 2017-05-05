
jQuery(document).ready(function () {

    new CBPFWTabs( document.getElementById( 'tabs' ) );

    jQuery('.huge_forms_edit_form_container').on("click","#save-form-button", function () {
        var name = jQuery("#form_name").val();
        var id = jQuery("#form_id").val();
        var hugeFormSettings = jQuery('#huge-form-settings');

        formSettingsData = hugeFormSettings.serializeArray();
        var general_data = {
            action: "huge_forms_save_form_settings",
            nonce: form.saveSettingsNonce,
            form_id: id,
            form_name: name,
            formSettingsData:formSettingsData
        };

        jQuery(this).find('.fa-check').removeClass('fa-check').addClass('fa-spinner');
        jQuery.post(ajaxurl, general_data, function (response) {
            if (response.success) {
                jQuery('#save-form-button').find('.fa-spinner').removeClass('fa-spinner').addClass('fa-check');

            } else {
                alert('not done');
            }
        }, "json");

        return false;
    });

})
