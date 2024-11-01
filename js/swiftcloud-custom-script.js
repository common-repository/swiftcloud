/*------------ swift form -----------------*/
var $compain_var = getUrlVars();
/*Set cookie if compaign vars exists*/
if ($compain_var === undefined) {
    //do nothing
} else {
    setCookie('compain_var', window.location.href);
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
        vars[key] = value;
    });
    return vars;
}
/*Cookie functions*/
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);
        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }
    return "";
}
jQuery(document).ready(function() {
    /* swift form */
    if (jQuery('.SC_fh_timezone').length > 0) {
        /*var offset = new Date().getTimezoneOffset();
        var minutes = Math.abs(offset);
        var hours = (minutes / 60);
        var prefix = offset < 0 ? '+' : '-';
        jQuery('#SC_fh_timezone').val('GMT' + prefix + hours);*/
        jQuery('#SC_fh_timezone').val(jstz.determine().name());
    }
    if (jQuery('.SC_fh_capturepage').length > 0) {
        jQuery('.SC_fh_capturepage').val(window.location.origin + window.location.pathname);
    }
    if (jQuery('.SC_fh_language').length > 0) {
        jQuery('.SC_fh_language').val(window.navigator.userLanguage || window.navigator.language);
    }
    jQuery("#referer").val(document.URL);
    /*check if cookie exists then add the values in variable*/
    if (getCookie('compain_var')) {
        jQuery('.trackingvars').val(getCookie('compain_var'));
    }
});