jQuery(document).ready(function(){
    jQuery('.huge_forms_content').on("click","#save-form-button", function () {
        var hugeForm = jQuery('#huge-form');

        formData = hugeForm.serializeArray();

        console.log(formData);

        var general_data = {
            action: "huge_forms_save_settings",
            nonce: settingsSave.nonce,
            formData:formData
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




});