jQuery(function($) {
    if ($(".TTWForm").length > 0) {
        var data = {
            'action': 'sma_set_leadpage',
            'page_id':$('#sma_lead_page_id').val()
        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(sma_data.ajax_url, data, function(response) {
            //alert('Got this from the server: ' + response);
        });
    }
});