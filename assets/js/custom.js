function lps_embed_shortcode() {
    lps_preview_configures_shortcode();
    wp.media.editor.insert(jQuery('#lps_preview_embed_shortcode').html());
}

function lps_recalculate_width() {
    var ww = jQuery('#TB_window').innerWidth() - 30;
    var hh = jQuery('#TB_window').innerHeight() - 60;
    jQuery('#TB_ajaxContent').css('width', ww + 'px');
    jQuery('#TB_ajaxContent').css('height', hh + 'px');
    jQuery('#TB_ajaxContent').css('max-height', hh + 'px');
    jQuery('#TB_ajaxContent').css('overflow-y', 'auto');
}

function lps_init_embed_shortcode(ed) {
    var selected = '';
    if (tinyMCE.activeEditor !== null) {
        selected = tinyMCE.activeEditor.selection.getContent();
    } else {
        selected = jQuery('#content').val();
    }
    var c = selected.replace('[latest-selected-content ', '');
    c = c.replace(']', '');
    var newTxt = c.match(/[\w-]+=\"[^\"]*\"/g);
    if (newTxt) {
        for (var i = 0; i < newTxt.length; i++) {
            var k = newTxt[i].split('=')[0];
            var v = newTxt[i].split('=')[1].replace('"', '');
            v = v.replace('"', '');
            v = v.replace(']', '');
            switch (k) {
                case 'limit' :
                    jQuery('#lps_limit').val(v);
                    break;
                case 'type' :
                    jQuery('#lps_post_type').val(v);
                    break;
                case 'display' :
                    jQuery('#lps_display').val(v);
                    break;
                case 'chrlimit' :
                    jQuery('#lps_chrlimit').val(v);
                    break;
                case 'image' :
                    jQuery('#lps_image').val(v);
                    break;
                case 'url' :
                    jQuery('#lps_url').val(v);
                    break;
                case 'elements' :
                    jQuery('#lps_elements').val(v);
                    break;
                case 'linktext' :
                    jQuery('#lps_linktext').val(v);
                    break;
                case 'css' :
                    jQuery('#lps_css').val(v);
                    break;
                case 'taxonomy' :
                    jQuery('#lps_taxonomy').val(v);
                    break;
                case 'term' :
                    jQuery('#lps_term').val(v);
                    break;
                case 'tag' :
                    jQuery('#lps_tag').val(v);
                    break;
                case 'id' :
                    jQuery('#lps_post_id').val(v);
                    break;
                case 'parent' :
                    jQuery('#lps_parent_id').val(v);
                    break;

                default :
                    break;
            }
        }
    }
    lps_preview_configures_shortcode();
}

function lps_preview_configures_shortcode() {
    var sc = '[latest-selected-content';
    var limit = jQuery('#lps_limit').val();
    if (limit != '') {
        sc += ' limit="' + limit + '"';
    }
    var type = jQuery('#lps_post_type').val();
    if (type != '') {
        sc += ' type="' + type + '"';
    }
    var display = jQuery('#lps_display').val();
    if (display != '') {
        sc += ' display="' + display + '"';

        if ('title,excerpt-small' == display || 'title,content-small' == display) {
            jQuery('#lps_display_limit').show();

            var chrlimit = jQuery('#lps_chrlimit').val();
            if (chrlimit != '') {
                sc += ' chrlimit="' + chrlimit + '"';
            }
        } else {
            jQuery('#lps_display_limit').hide();
        }
    }

    var image = jQuery('#lps_image').val();
    if (image != '') {
        sc += ' image="' + image + '"';
    }
    var url = jQuery('#lps_url').val();
    if (url != '') {
        sc += ' url="' + url + '"';
        jQuery('#lps_url_options').show();
        jQuery('label.without-link').hide();
        jQuery('label.with-link').show();

        var linktext = jQuery('#lps_linktext').val();
        if (linktext != '') {
            sc += ' linktext="' + linktext + '"';
        }
    } else {
        jQuery('#lps_url_options').hide();
        jQuery('label.with-link').hide();
        jQuery('label.without-link').show();
    }
    var elements = jQuery('#lps_elements').val();
    if (elements != '') {
        sc += ' elements="' + elements + '"';
    }
    var css = jQuery('#lps_css').val();
    if (css != '') {
        sc += ' css="' + css + '"';
    }
    var taxonomy = jQuery('#lps_taxonomy').val();
    if (taxonomy != '') {
        sc += ' taxonomy="' + taxonomy + '"';
    }
    var term = jQuery('#lps_term').val();
    if (term != '') {
        sc += ' term="' + term + '"';
    }
    var tag = jQuery('#lps_tag').val();
    if (tag != '') {
        sc += ' tag="' + tag + '"';
    }
    var id = jQuery('#lps_post_id').val();
    if (id != '') {
        sc += ' id="' + id + '"';
    }
    var parent = jQuery('#lps_parent_id').val();
    if (parent != '') {
        sc += ' parent="' + parent + '"';
    }
    sc += ']';
    jQuery('#lps_preview_embed_shortcode').html(sc);
}

jQuery(document).ready(function () {
    jQuery('#lps_shortcode_button_open').click(function () {
        lps_init_embed_shortcode();
    });
    jQuery('#lps_button_embed_shortcode').click(lps_embed_shortcode);
    setTimeout(function () {
        for (var i = 0; i < tinymce.editors.length; i++) {
            lps_init_embed_shortcode(tinymce.editors[i]);
        }
    }, 2000);

    var visible = false;
    setInterval(function () {
        if (!visible) {
            if (jQuery('#TB_window').is(":visible")) {
                visible = true;
                lps_recalculate_width();
            }
        }
    }, 2000);
});

