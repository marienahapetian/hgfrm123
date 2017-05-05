jQuery( function() {

    function HugeFormsSetCookie(name, value, options) {
        options = options || {};

        var expires = options.expires;

        if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }


        if(typeof value == "object"){
            value = JSON.stringify(value);
        }
        value = encodeURIComponent(value);
        var updatedCookie = name + "=" + value;

        for (var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }

        document.cookie = updatedCookie;
    }

    jQuery(document).on('click','#full-width-button',function () {
        jQuery(this).closest('.wrap').toggleClass('fullwidth-view');

        if(jQuery(this).closest('.wrap').hasClass('fullwidth-view')){
            HugeFormsSetCookie( 'hugeFormsFullWidth', 'yes', {expires:86400} );
        } else{
            HugeFormsSetCookie( 'hugeFormsFullWidth', 'no', {expires:86400} );
        }

    })




    jQuery(document).on('click','.huge_form_template',function () {
        jQuery('.huge_form_template').removeClass('selected');
        jQuery(this).toggleClass('selected');
    })


} );

jQuery( function() {
    jQuery( "#fields-list" ).sortable({
        connectWith: "div",
    });

} );

jQuery( function() {
    jQuery( ".options" ).sortable({
        connectWith: ".option",
    });

} );



jQuery('.huge_forms_delete_form').on('click',function(){
    if( !confirm( "Are you sure you want to delete this item?" ) ){
        return false;
    }
});




