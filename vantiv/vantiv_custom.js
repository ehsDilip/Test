jQuery(document).ready(function($) {
    
    $(".wpcf7-submit").click('mailsent.wpcf7', function() {
        if (!$('.wpcf7-not-valid-tip').is(":visible")) {
            //var data = {action: "my_wpcf7_save"};
            var data = $('.wpcf7-form').serialize();

            $.post(MyAjax.ajaxurl, data, function(response) {

                var obj = jQuery.parseJSON(response);
                //alert(obj.ErrorMessage);
                var err = obj.ErrorMessage;
                $('#form_err_msg').html("<span style='color:red'>" + err + "</span>");
            });

        }

    });

});
