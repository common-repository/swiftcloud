jQuery(function ($) {

    //clientid
    /*if (sma_data.form_id != "" && sma_data.file_field_id != "") {
     $('#' + sma_data.form_id + ' #' + sma_data.file_field_id).on('blur', function (e) {
     var client_id = $.trim($(this).val());
     if (client_id != '') {
     var data = {
     'action': 'sma_save_log',
     'client_id': client_id
     };
     jQuery.post(sma_data.ajax_url, data, function (response) {
     });
     }
     });
     }*/

    //client name
    /*if (sma_data.form_id != "" && sma_data.name_field_id != "") {
     $('#' + sma_data.form_id + ' #' + sma_data.name_field_id).on('blur', function (e) {
     var client_name = $.trim($(this).val());
     if (client_name != '') {
     var data = {
     'action': 'sma_save_log_name',
     'client_name': client_name
     };
     jQuery.post(sma_data.ajax_url, data, function (response) {
     });
     }
     });
     }*/

    //client email
    /*if (sma_data.form_id != "" && sma_data.email_field_id != "") {
     $('#' + sma_data.form_id + ' #' + sma_data.email_field_id).on('blur', function (e) {
     var client_email = $.trim($(this).val());
     if (client_email != '') {
     var data = {
     'action': 'sma_save_log_email',
     'client_email': client_email
     };
     jQuery.post(sma_data.ajax_url, data, function (response) {
     });
     }
     });
     }*/

    //client phone
    /*if (sma_data.form_id != "" && sma_data.phone_field_id != "") {
     $('#' + sma_data.form_id + ' #' + sma_data.phone_field_id).on('blur', function (e) {
     var client_phone = $.trim($(this).val());
     if (client_phone != '') {
     var data = {
     'action': 'sma_save_log_phone',
     'client_phone': client_phone
     };
     jQuery.post(sma_data.ajax_url, data, function (response) {
     });
     }
     });
     }*/

    //client submit id
    if (sma_data.form_id != "" && sma_data.submit_field_id != "") {
        $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).after('<input type="reset" name="btnSwiftFormReset" id="btnSwiftFormReset" style="display: none" />');
        $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).on('click', function (e) {
            e.preventDefault();

            $('.swift_local_capture_success, .swift_local_capture_error').remove();
            var client_name = '';
            var client_email = '';

            if (sma_data.name_field_id != "") {
                client_name = $.trim($('#' + sma_data.form_id + ' #' + sma_data.name_field_id).val());
            }

            if (sma_data.email_field_id != "") {
                client_email = $.trim($('#' + sma_data.form_id + ' #' + sma_data.email_field_id).val());
            }

            $('#' + sma_data.form_id).attr("required")
            var form = document.getElementById(sma_data.form_id);
            var inputs = form.getElementsByTagName("input"), input = null, select = null, textarea = null, not_pass = false;
            var selects = form.getElementsByTagName("select");
            var textareas = form.getElementsByTagName("textarea");
            for (var i = 0, len = inputs.length; i < len; i++) {
                input = inputs[i];
                if ($(input).attr('required')) {
                    $(input).removeClass('swift_form_error');
                    var inp_val = $.trim(input.value);

                    /*if (input.type == "text" && !inp_val) {
                     not_pass = true;
                     $(input).addClass('swift_form_error');
                     }
                     
                     if (input.type == "number" && !inp_val) {
                     not_pass = true;
                     $(input).addClass('swift_form_error');
                     }
                     
                     if (input.type == "email" && !inp_val) {
                     not_pass = true;
                     $(input).addClass('swift_form_error');
                     }
                     
                     if (input.type == "email" && inp_val) {
                     not_pass = false;
                     break;
                     }*/

                    if (input.type == "checkbox" && !input.checked) {
//                        not_pass = true;
//                        $(input).addClass('swift_form_error');
                    } else if (input.type == "radio" && !input.checked) {
//                        not_pass = true;
//                        $(input).addClass('swift_form_error');
                    } else if (input.type == "email" && inp_val) {
                        if (!ValidateEmail(inp_val)) {
                            not_pass = true;
                            $(input).addClass('swift_form_error');
                        }
                    } else if (!inp_val) {
                        not_pass = true;
                        $(input).addClass('swift_form_error');
                    }
                }
            }

            for (var i = 0, len = selects.length; i < len; i++) {
                select = selects[i];
                $(select).removeClass('swift_form_error');
                if ($(select).attr('required')) {
                    if (!select.value) {
                        not_pass = true;
                        $(select).addClass('swift_form_error');
                        break;
                    }
                }
            }
            for (var i = 0, len = textareas.length; i < len; i++) {
                textarea = textareas[i];
                $(textarea).removeClass('swift_form_error');
                if ($(textarea).attr('required')) {
                    var textarea_val = $.trim(textarea.value);
                    if (!textarea_val) {
                        not_pass = true;
                        $(textarea).addClass('swift_form_error');
                        break;
                    }
                }
            }

            if (not_pass) {
                return false;
            } else {
                var data = {
                    action: 'sma_save_local_capture',
                    name: client_name,
                    email: client_email,
                    form_data: $('#' + sma_data.form_id).serialize()
                };
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: sma_data.ajax_url,
                    data: data,
                    beforeSend: function (xhr) {
                        if ($('#form_submit_btn').is(":input")) {
                            $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).attr('data-title', $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).val());
                            $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).attr('disabled', 'disabled');
                        } else {
                            $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).attr('data-title', $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).html());
                            $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>').attr('disabled', 'disabled');
                        }
                    },
                    success: function (response) {
                        if (response.type == "success") {
                            $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).after('<span class="swift_local_capture_success">Your request has been submitted successfully</span>');
                        } else {
                            $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).after('<span class="swift_local_capture_error">There was an error while submitting your request! Please try again.</span>');
                        }

                        if ($('#form_submit_btn').is(":input")) {
                            $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).val($('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).attr('data-title')).removeAttr('disabled');
                        } else {
                            $('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).html($('#' + sma_data.form_id + ' #' + sma_data.submit_field_id).attr('data-title')).removeAttr('disabled');
                        }
                        
                        $('#btnSwiftFormReset').trigger('click');
                    }
                });
            }
        });
    }

    //Virtual page views.
//    dataLayer.push({
//        'event': 'VirtualPageview',
//        'virtualPageURL': '/order/step1',
//        'virtualPageTitle': 'Order Step 1 - Contact Information'
//    });

    if ($(".TTWForm").length > 0) {
        var data = {
            'action': 'sma_set_leadpage',
            'page_id': $('#sma_lead_page_id').val()
        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(sma_data.ajax_url, data, function (response) {
            //alert('Got this from the server: ' + response);
        });
    }

});

//Email validation
function ValidateEmail(mail) {
    if (/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,6}|[0-9]{1,3})(\]?)$/.test(mail)) {
        return (true);
    }
    return (false);
}