jQuery(document).ready(function() {
    jQuery('.hgfrm-form').on("submit",function (e) {
        e.preventDefault();
        var form = jQuery(this).closest('form');
        var form_id=form.attr('id');
        formData = form.serializeArray();

        var general_data = {
            action: "huge_forms_submit_form",
            form_id: form_id,
            formData: formData
        };

        jQuery.post(ajaxurl, general_data, function (response) {
            if (response.success) {
               console.log(response);

            } else {
                alert('not done');
            }
        }, "json");

        return false;
    });


    jQuery( ".datepicker" ).datepicker({ minDate: -20, maxDate: "+1M +10D" });
})


var grecatptcha_loaded=0;
var recaptchas=[];
var huge_forms_onloadCallback = function() {
    var form_ids = [];
    var sitekeys = [];
    var themes = [];
    var type= [];
    jQuery(".huge-forms-captcha-block").each(function(i){
        form_ids[i] = jQuery(this).data("form-id");
        sitekeys[i] = jQuery(this).data("sitekey");
        themes[i] = jQuery(this).data("theme");
        type[i] = jQuery(this).data("cname");
    }).promise().done(function(){
        jQuery.each(form_ids,function(i){
            var dom_id = 'huge_forms_captcha_'+form_ids[i];
            var callback = 'verifyCallback_'+form_ids[i];
            var typeofcapt=type[i];
            recaptchas[form_ids[i]] = grecaptcha.render(dom_id,{
                'sitekey':sitekeys[i],
                'callback': function(response) {
                    jQuery( "#huge_it_contact_form_"+form_ids[i]).attr("verified","1");
                },'theme' : themes[i],
                'type' : typeofcapt
            });
        });

    });
};


function huge_forms_initMap() {


        jQuery('.huge-forms-map-block').each(function () {
            var map_lat = jQuery(this).attr('center_lat');
            var map_lng = jQuery(this).attr('center_lng');
            var center = {lat: parseInt(map_lat), lng: parseInt(map_lng)};
            var id = jQuery(this).attr('id');
            var draggable = jQuery(this).attr('draggable');

            var map = new google.maps.Map(document.getElementById(id), {
                zoom: 8,
                center: center,
                draggable: draggable
            });

            var marker = new google.maps.Marker({
                position: center,
                map: map
            });
        })

}



function hugeit_refresh_captcha(e) {
    e.preventDefault();
    captchaid = jQuery(this).attr('captchaid');
    captchacontainer=jQuery(this).closest('.huge-forms-captcha-box');
    img=captchacontainer.find('img').eq(0);

    user='user';


    var general_data = {
        action: "huge_forms_refresh_simple_captcha",
        captchaid: captchaid
    };

    jQuery.post(ajaxurl, general_data, function (response) {
        if (response) {
            img.remove();

            newimg='<img src="'+response+'">';

            jQuery(newimg).prependTo(captchacontainer);

        } else {
            alert('not done');
        }
    }, "json");



}

jQuery('.huge-forms-captcha-box>a').click(hugeit_refresh_captcha);


