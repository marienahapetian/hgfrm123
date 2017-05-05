jQuery(document).ready(function () {
    jQuery('#huge_form_insert').on('click', function () {
        var id = jQuery('#huge_form_select option:selected').val();

        window.send_to_editor('[huge_forms_form id="' + id + '"]');
        tb_remove();
        var name = jQuery("#map_name").val();
        id = jQuery('#huge_form_select option:selected').val();
        var data = {
            action: "huge_forms_save_shortcode_options",
            nonce: inlinePopup.nonce,
            form_id: id,
            name: name,
        };
        jQuery.post(ajaxurl, data, function (response) {}, "json");
        return false;
    });

    jQuery("#huge_forms_select").on("change", function () {
        var name = jQuery("#form_name").val();
        id = jQuery('#huge_forms_select option:selected').val();
        var data = {
            action: "huge_forms_shortcode_change_form",
            nonce: inlinePopup.nonce,
            form_id: id,
            name: name,
        };
        jQuery.post(ajaxurl, data, function (response) {
            if (response.success) {
                jQuery("#form_name").val(response.name);

            }
        }, "json")
        return false;
    });
});