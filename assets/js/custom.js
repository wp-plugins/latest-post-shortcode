function lps_embed_shortcode() {
    lps_preview_configures_shortcode();
    wp.media.editor.insert(jQuery('#lps_preview_embed_shortcode').html());
}

function lps_init_embed_shortcode() {
    var selected = tinyMCE.activeEditor.selection.getContent();
    var newTxt = selected.split(' ');
    for (var i = 1; i < newTxt.length; i++) {
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
            case 'image' :
                jQuery('#lps_image').val(v);
                break;
            case 'url' :
                jQuery('#lps_url').val(v);
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
    }
    var image = jQuery('#lps_image').val();
    if (image != '') {
        sc += ' image="' + image + '"';
    }
    var url = jQuery('#lps_url').val();
    if (url != '') {
        sc += ' url="' + url + '"';
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
    jQuery('#lps_button_embed_shortcode').click(lps_embed_shortcode);
    jQuery('#lps_shortcode_button_open').click(lps_init_embed_shortcode);
});

