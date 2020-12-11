(function ($) {
    jQuery(window).on('elementor/frontend/init', function () {

        //if (!jQuery('body').hasClass('elementor-editor-active')) {
            if (wp.codeEditor) {
                let editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};            
                jQuery('.e-codemirror').each(function(){
                    let settings = jQuery(this).data('code');
                    let textarea = jQuery(this);
                    editorSettings.codemirror = _.extend({}, editorSettings.codemirror,
                            {
                                mode: settings.type,
                                readOnly: Boolean(settings.readonly),
                                theme: settings.theme,
                                onChange: function (cm) {
                                    console.log(cm.getValue());
                                    jQuery(this).val(cm.getValue());
                                }
                            }
                    );
                    let editor = wp.codeEditor.initialize(jQuery(this).attr('id'), editorSettings);
                    editor.codemirror.on('change', function(cm){ textarea.val(cm.getValue()); });
                });
            }
        //}
        
        jQuery('.elementor-widget-copy-button button').each(function(){ //.on('click', function(){
            //console.log(jQuery(this).attr('id'));
            let clipboard = new ClipboardJS('#'+jQuery(this).attr('id'));
            clipboard.on('success', function (e) {
                //console.log(e.trigger);
                e.clearSelection();
                jQuery(e.trigger).addClass('animated').addClass('jello');
                setTimeout(function () {
                    jQuery(e.trigger).removeClass('animated').removeClass('jello');
                }, 3000);
                return false;
            });
            clipboard.on('error', function (e) {
                //console.log(e);
                e.clearSelection();
            });
        });
    
    });
})(jQuery, window);