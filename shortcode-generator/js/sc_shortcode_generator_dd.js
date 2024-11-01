(function() {
    var win;
    var cookie_name = "swiftcloud_preload_form_id";

    tinymce.PluginManager.add('sc_mce_button', function(editor, url) {
        editor.addButton('sc_mce_button', {
            image: url + '/swiftcloud.png',
            tooltip: 'SwiftCloud Shortcodes Generator',
            type: 'menubutton',
            menu: [
                {
                    text: 'swiftform',
                    onclick: function() {
                        editor.windowManager.open({
                            title: 'SwiftCloud Shortcode Generator',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'sc_form_id',
                                    label: 'Enter Swiftform ID*',
                                    value: sc_get_cookie('swiftcloud_preload_form_id')
                                }
                            ],
                            onsubmit: function(e) {
                                if (e.data.sc_form_id === '') {
                                    editor.windowManager.alert('Please, fill product id.');
                                    return false;
                                }
                                sc_set_cookie(cookie_name, e.data.sc_form_id);
                                editor.insertContent(
                                        '[swiftform id=&quot;' + e.data.sc_form_id + '&quot;]'
                                        );
                            }
                        });
                    }
                },
                {
                    text: 'swiftcloud multipass',
                    onclick: function() {
                        editor.insertContent('[swiftcloud_multipass]');
                    }
                },
                {
                    text: 'swiftcloud confirmpage',
                    onclick: function() {
                        editor.insertContent('[swiftcloud_confirmpage]');
                    }
                },
                {
                    text: 'swiftcloud welcome_name',
                    onclick: function() {
                        editor.insertContent('[swiftcloud_welcome_name]');
                    }
                },
                {
                    text: 'swiftcloud topcapture',
                    onclick: function() {
                        editor.windowManager.open({
                            title: 'SwiftCloud Shortcode Generator',
                            body: [
                                {
                                    "label": "Title*",
                                    "name": "sc_topcapture_title",
                                    "value": "",
                                    "type": "textbox",
                                }, {
                                    "label": "Swiftform ID",
                                    "name": "sc_topcapture_swiftformid",
                                    "value": sc_get_cookie('swiftcloud_preload_form_id'),
                                    "type": "textbox",
                                }, {
                                    "label": "Button caption",
                                    "name": "sc_topcapture_btncaption",
                                    "value": "",
                                    "type": "textbox",
                                }, {
                                    "label": "Video URL",
                                    "name": "sc_topcapture_videourl",
                                    "value": "",
                                    "type": "textbox",
                                }, {
                                    "label": "Image URL",
                                    "name": "sc_topcapture_imgurl",
                                    "value": "",
                                    "type": "textbox",
                                }, {
                                    "label": "Backgroung Image",
                                    "name": "sc_topcapture_bgimg",
                                    "value": "",
                                    "type": "textbox",
                                }, {
                                    "label": "Backgroung Color",
                                    "name": "sc_topcapture_bgcolor",
                                    "value": "",
                                    "type": "textbox",
                                }
                            ],
                            onsubmit: function(e) {
                                if (e.data.sc_topcapture_title === '') {
                                    editor.windowManager.alert('Please, fill title.');
                                    return false;
                                }
                                sc_set_cookie(cookie_name, e.data.sc_topcapture_swiftformid);
                                editor.insertContent(
                                        '[swiftcloud_topcapture title=&quot;' + e.data.sc_topcapture_title + '&quot; swiftformid=&quot;' + e.data.sc_topcapture_swiftformid + '&quot; btncaption=&quot;' + e.data.sc_topcapture_btncaption + '&quot; videourl=&quot;' + e.data.sc_topcapture_videourl + '&quot; imgurl=&quot;' + e.data.sc_topcapture_imgurl + '&quot; bgimg=&quot;' + e.data.sc_topcapture_bgimg + '&quot; bgcolor=&quot;' + e.data.sc_topcapture_bgcolor + '&quot;]'
                                        );
                            }
                        });
                    }
                },
                {
                    text: 'swiftcloud tracking',
                    onclick: function() {
                        editor.windowManager.open({
                            title: 'SwiftCloud Shortcode Generator',
                            body: [
                                {
                                    "label": "UTM Source",
                                    "name": "sc_tracking_utm_source",
                                    "value": "",
                                    "type": "textbox",
                                }, {
                                    "label": "UTM Medium*",
                                    "name": "sc_tracking_utm_medium",
                                    "value": "",
                                    "type": "textbox",
                                }, {
                                    "label": "UTM Term",
                                    "name": "sc_tracking_utm_term",
                                    "value": "",
                                    "type": "textbox",
                                }, {
                                    "label": "UTM Content",
                                    "name": "sc_tracking_utm_content",
                                    "value": "",
                                    "type": "textbox",
                                }, {
                                    "label": "UTM Campaign*",
                                    "name": "sc_tracking_utm_campaign",
                                    "value": "",
                                    "type": "textbox",
                                }
                            ],
                            onsubmit: function(e) {
                                if (e.data.sc_tracking_utm_medium === '') {
                                    editor.windowManager.alert('Please, fill UTM Medium.');
                                    return false;
                                }
                                if (e.data.sc_tracking_utm_campaign === '') {
                                    editor.windowManager.alert('Please, fill UTM Campaign.');
                                    return false;
                                }

                                editor.insertContent('[swiftcloud_tracking utm_source=&quot;' + e.data.sc_tracking_utm_source + '&quot; utm_medium=&quot;' + e.data.sc_tracking_utm_medium + '&quot; utm_term=&quot;' + e.data.sc_tracking_utm_term + '&quot; utm_content=&quot;' + e.data.sc_tracking_utm_content + '&quot; utm_campaign=&quot;' + e.data.sc_tracking_utm_campaign + '&quot;]');
                            }
                        });
                    }
                },
                {
                    text: 'swiftcloud inlineoffer',
                    onclick: function() {
                        win = editor.windowManager.open({
                            title: 'SwiftCloud Shortcode Generator',
                            bodyType: "tabpanel",
                            body: [
                                {
                                    title: "Quick",
                                    type: "form",
                                    layout: "flex",
                                    name: 'quick_tab',
                                    items:
                                            [
                                                {
                                                    "type": "textbox",
                                                    "label": "*SwiftCloud WebForm ID #",
                                                    "name": "sc_form_id",
                                                    "id": "sc_form_id",
                                                    "value": sc_get_cookie('swiftcloud_preload_form_id')
                                                },
                                                {
                                                    type: 'container',
                                                    name: 'sc_quick_form_container',
                                                    class: 'sc_quick_form_container_cls',
                                                    html: '<br/><p><a style="text-decoration: underline;cursor: pointer;" href="https://swiftcrm.com/software/forms-generator" target="_blank">Click to generate</a> a new form if needed, or use any of your existing forms.</p><p style="margin-top:10px;"> Visit <a style="text-decoration: underline;cursor: pointer;" href="https://swiftcrm.com/software/forms-generator" target="_blank"> https://swiftcrm.com/software/forms-generator</a> to create a form; this determines <br/>the autoresponder sequence and any automation as well as any tags to <br/>apply to users captured through this form.</p>',
                                                }
                                            ]
                                },
                                {
                                    title: "Advanced",
                                    type: "form",
                                    layout: "flex",
                                    name: 'advanced_tab',
                                    items:
                                            [
                                                {
                                                    "type": "textbox",
                                                    "label": "Popup Headline",
                                                    "name": "sc_popupheadline",
                                                    "value": "",
                                                    "tooltip": "This will show at top of the popup as Headline",
                                                }, {
                                                    "label": "Popup Image URL",
                                                    "name": "sc_popupimage",
                                                    "value": "",
                                                    "type": "textbox",
                                                    "tooltip": "This will show at left side of the popup as Image.",
                                                }, {
                                                    "label": "Popup Button Label",
                                                    "name": "sc_popupbutton",
                                                    "value": "",
                                                    "type": "textbox",
                                                    "tooltip": "This will show as label of the popup submit button.",
                                                }, {
                                                    "label": "Content Background Color",
                                                    "name": "sc_bgcolor",
                                                    "value": "#fffdeb",
                                                    type: 'colorpicker',
                                                    minHeight: 130,
                                                    "placehoder": "#fffdeb",
                                                    "tooltip": "This will show as background color of the content.",
                                                },
                                            ]
                                }
                            ],
                            onsubmit: function(e) {
                                var data = win.toJSON();
                                if (data.sc_form_id === '') {
                                    editor.windowManager.alert('SwiftCloud WebForm ID is required.');
                                    return false;
                                }
                                var inline_popup_headline = data.sc_popupheadline != "" ? ' PopupHeadline="' + data.sc_popupheadline + '"' : '';
                                var inline_popup_bgcolor = data.sc_bgcolor != "" ? ' bgcolor="' + data.sc_bgcolor + '"' : '';
                                var inline_popup_image = data.sc_popupimage != "" ? ' PopupImage="' + data.sc_popupimage + '"' : '';
                                var inline_popup_button = data.sc_popupbutton != "" ? ' PopupButton="' + data.sc_popupbutton + '"' : ' PopupButton="Submit"';

                                var cookie_val = data.sc_form_id;
                                sc_set_cookie(cookie_name, cookie_val);

                                editor.insertContent(
                                        '[swiftcloud_inlineoffer form_id="' + data.sc_form_id + '"' + inline_popup_headline + inline_popup_bgcolor + inline_popup_image + inline_popup_button + ']<p>This text appears before visitor is captured.</p><a href="#">Click Here</a><br/>[swiftcloud_inlineoffer_capturedcontents]This text appears after visitor is captured.[/swiftcloud_inlineoffer_capturedcontents][/swiftcloud_inlineoffer]'
                                        );
                            }
                        });
                    }
                },
                /*{
                 text: 'swiftcloud inlineoffer',
                 onclick: function() {
                 editor.windowManager.open({
                 title: 'SwiftCloud Shortcode Generator',
                 body: [
                 {
                 "label": "Swiftform ID*",
                 "name": "sc_form_id",
                 "value": "",
                 "type": "textbox"
                 },
                 {
                 "label": "Content ID",
                 "name": "sc_content_id",
                 "value": "",
                 "type": "textbox"
                 }, {
                 "label": "Popup Headline",
                 "name": "sc_popupheadline",
                 "value": "",
                 "type": "textbox"
                 }, {
                 "label": "Popup Image",
                 "name": "sc_popupimage",
                 "value": "",
                 "type": "textbox"
                 }, {
                 "label": "Popup Lid Id",
                 "name": "sc_popuplidid",
                 "value": "",
                 "type": "textbox"
                 }, {
                 "label": "Popup Button Label",
                 "name": "sc_popupbutton",
                 "value": "",
                 "type": "textbox"
                 }, {
                 "label": "Content Background Color",
                 "name": "sc_bgcolor",
                 "value": "#fffdeb",
                 type: 'colorpicker',
                 minHeight: 130,
                 "placehoder": "#fffdeb"
                 },
                 ],
                 onsubmit: function(e) {
                 if (e.data.sc_form_id === '') {
                 editor.windowManager.alert('SwiftForm ID is required.');
                 return false;
                 }
                 editor.insertContent(
                 '[swiftcloud_inlineoffer id="' + e.data.sc_content_id + '" bgcolor="' + e.data.sc_bgcolor + '" form_id="' + e.data.sc_form_id + '" PopupHeadline="' + e.data.sc_popupheadline + '" PopupImage="' + e.data.sc_popupimage + '" PopupLidID="' + e.data.sc_popuplidid + '" PopupButton="' + e.data.sc_popupbutton + '"]<p>Add your content here.</p><a href="#">Click Here</a>\n[/swiftcloud_inlineoffer]'
                 );
                 }
                 });
                 }
                 }*/
            ]
        });
    });
})();

function sc_set_cookie(cookie_name, cookie_val) {
    /*set cookie*/
    var d = new Date();
    d.setTime(d.getTime() + (7 * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cookie_name + "=" + cookie_val + "; " + expires;
}
//get cookie value
function sc_get_cookie(cname) {
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