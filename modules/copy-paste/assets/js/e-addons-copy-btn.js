jQuery(window).on('elementor/frontend/init', () => {

    class CopyButtonHandlerClass extends elementorModules.frontend.handlers.Base {

        getDefaultSettings() {
            return {
                selectors: {
                    button: 'button',
                    code: '.e-codemirror',
                },
            };
        }

        getDefaultElements() {
            const selectors = this.getSettings('selectors');
            return {
                $scope: this.$element,
                $button: this.$element.find(selectors.button),
                $code: this.$element.find(selectors.code),
                $id_scope: this.$element.attr('data-id')
            };
        }

        initCopy() {
            let id_scope = this.elements.$id_scope,
                    elementSettings = this.getElementSettings();

            if (wp.codeEditor) {
                let editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
                this.elements.$code.each(function () {
                    let settings = jQuery(this).data('code');
                    let textarea = jQuery(this);
                    editorSettings.codemirror = _.extend({}, editorSettings.codemirror,
                            {
                                mode: settings.type,
                                readOnly: Boolean(settings.readonly),
                                theme: settings.theme,
                                onChange: function (cm) {
                                    jQuery(this).val(cm.getValue());
                                }
                            }
                    );
                    let editor = wp.codeEditor.initialize(jQuery(this).attr('id'), editorSettings);
                    editor.codemirror.on('change', function (cm) {
                        textarea.val(cm.getValue());
                    });
                });
            }

            this.elements.$button.each(function () {
                let clipboard = new ClipboardJS('#' + jQuery(this).attr('id'));
                clipboard.on('success', function (e) {
                    e.clearSelection();
                    var animation = jQuery(e.trigger).data('animation');
                    if (animation) {
                        jQuery(e.trigger).addClass('animated').addClass(animation);
                        setTimeout(function () {
                            jQuery(e.trigger).removeClass('animated').removeClass(animation);
                        }, 2000);
                    }
                    return false;
                });
                clipboard.on('error', function (e) {
                    e.clearSelection();
                });
            });
        }

        bindEvents() {
            let id_scope = this.elements.$id_scope,
                    elementSettings = this.getElementSettings();

            this.initCopy();
        }
    }

    const CopyButtonHandlerFront = ($element) => {
        elementorFrontend.elementsHandler.addHandler(CopyButtonHandlerClass, {
            $element,
        });
    };
    elementorFrontend.hooks.addAction('frontend/element_ready/copy-button.default', CopyButtonHandlerFront);
});