
jQuery(document).ready(function(){
    jQuery('.huge_forms_edit_form_container').on("click","#save-form-button", function () {
        var name = jQuery("#form_name").val();
        var id = jQuery("#form_id").val();
        var hugeForm = jQuery('#huge-form');

        var k = 0;
        jQuery("#fields-list > div.field-block").each(function() {
            var id=jQuery(this).attr('data-field-id');
            jQuery('.settings-block#'+id+' .setting-row').find('.setting-order').val(k);
            k++;
        });

        jQuery(".settings-block .options").each(function() {
            var l = 0;
            jQuery(this).find('.option').each(function () {
                jQuery(this).find('.setting-option-order').val(l);
                l++;
            });
        });

        var editors = jQuery('.wp-editor-wrap');

        var names = [];

        editors.each(function () {
            names.push(jQuery(this).find('textarea.setting-row').attr('name'));
        })

        formData = hugeForm.serializeArray();

        finalFormData = [];
        formData.forEach(function (entry) {
            if(jQuery.inArray(entry.name,names) != '-1') {
                entry.value = tinyMCE.editors[entry.name].getContent();
            }
            finalFormData.push(entry);
        })

        var general_data = {
            action: "huge_forms_save_form",
            nonce: formSave.nonce,
            form_id: id,
            form_name: name,
            formData: finalFormData
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

    jQuery('.huge_forms_edit_form_container').on("click",".left-col .field-block .fa-trash-o", function () {
        var id = jQuery(this).closest('.field-block').attr('data-field-id');
        var general_data = {
            action: "huge_forms_remove_field",
            nonce: field.removeNonce,
            id: id,
        };
        jQuery.post(ajaxurl, general_data, function (response) {
            if (response.success) {
                jQuery("#fields-list .field-block[data-field-id="+id+"]").remove();
            } else {
                alert('not done');
            }
        }, "json");

        return false;
    });

    jQuery('.huge_forms_edit_form_container').on("click",".left-col .field-block .fa-clone", function () {
        var id = jQuery(this).closest('.field-block').attr('data-field-id');
        var form = jQuery('.huge_forms_edit_form_container').attr('data-form');
        var type = jQuery(this).closest('.field-block').attr('data-field-type');

        var label = jQuery(this).closest('.field-block').find('span').text();
        var general_data = {
            action: "huge_forms_duplicate_field",
            nonce: field.duplicateNonce,
            id: id,
            form: form,
            type: type
        };
        jQuery.post(ajaxurl, general_data, function (response) {
            if (response.success) {
                jQuery("#fields-list").append(response.fieldBlock);
                jQuery("#settings-list").append(response.settingsBlock);
            } else {
                alert('not done');
            }
        }, "json");

        return false;
    });

    jQuery('.huge_forms_edit_form_container').on("click",".left-col .field-block span", function () {
        var id = jQuery(this).closest('.field-block').attr('data-field-id');
        var form = jQuery('.huge_forms_edit_form_container').attr('data-form');

        jQuery('.field-block').removeClass('selected');
        jQuery(this).closest('.field-block').addClass('selected');
        jQuery('.settings-block').hide();
        jQuery('.settings-block[id='+id+']').show();

        return false;
    });

    jQuery('.huge_forms_edit_form_container').on("click",".right-col .type-block", function () {
        var type_id = jQuery(this).attr('type-id');
        var id = jQuery('#form_id').val();
        var type_name = jQuery(this).text();
        var order = jQuery('.huge_forms_content #fields-list .field-block').length;

        var data = {
            action: "huge_forms_save_field",
            nonce: field.saveNonce,
            form: id,
            type: type_id,
            type_name :type_name,
            order: order
        };
        jQuery.post(ajaxurl, data, function (response) {
            if (response.success) {
                jQuery('.huge_forms_content #fields-list').append(response.fieldBlock);
                jQuery("#settings-list").append(response.settingsBlock);
            } else {
                alert('not done');
            }
        }, "json");

        return false;
    });

    jQuery('.huge_forms_edit_form_container').on("click",".hgfrm-add-option", function () {
        var container = jQuery(this).closest('.settings-block').find('.options');

        var field_id = jQuery(this).closest('.settings-block').attr('id');

        var data = {
            action: "huge_forms_add_field_option",
            nonce: field.addOptionNonce,
            field: field_id,
        };
        jQuery.post(ajaxurl, data, function (response) {
            if (response.success) {
                jQuery(container).append(response.option_row);
            } else {
                alert('not done');
            }
        }, "json");

        return false;
    });

    jQuery('.huge_forms_edit_form_container').on("click",".hgfrm-remove-option", function () {
        var option = jQuery(this).attr('data-option');
        var row = jQuery(this).closest('.option');

        var data = {
            action: "huge_forms_remove_field_option",
            nonce: field.removeOptionNonce,
            option: option,
        };
        jQuery.post(ajaxurl, data, function (response) {
            if (response.success) {
                row.remove();
            } else {
                alert('not done');
            }
        }, "json");

        return false;
    });

    jQuery('.huge_forms_edit_form_container').on("click","i.hgfrm-import-options", function () {
        jQuery(this).closest('.settings-block').find('.import-block').show();
    });

    jQuery('.huge_forms_edit_form_container').on("click",".cancel", function () {
        jQuery(this).closest('.settings-block').find('.import-block').hide();
    });

    jQuery('.huge_forms_edit_form_container').on("click","span.import-options", function () {
        var options = jQuery(this).closest('.import-block').find('textarea').val();

        var field_id = jQuery(this).closest('.settings-block').attr('id');

        var container = jQuery(this).closest('.settings-block').find('.options');

        var data = {
            action: "huge_forms_import_options",
            nonce: field.importOptionsNonce,
            options: options,
            field: field_id,
        };
        jQuery.post(ajaxurl, data, function (response) {
            if (response.success) {
                var optionsRows = response.options_rows;
                container.append(optionsRows);
                jQuery('.import-block').hide();
            } else {
                alert('not done');
            }
        }, "json");

        return false;
    });
});
