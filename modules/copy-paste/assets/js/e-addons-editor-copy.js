/*
 * NERD COPY/PASTE from Clipboard
 * nerds.farm
 */

(function ($) {
//jQuery(window).on('load', function () {
    //console.log('e add menu context');
    elementor.hooks.addFilter('elements/widget/contextMenuGroups', function (groups, widget) {
        return eAddPasteAction(groups, widget);
    });
    elementor.hooks.addFilter('elements/column/contextMenuGroups', function (groups, column) {
        return eAddPasteAction(groups, column);
    });
    elementor.hooks.addFilter('elements/section/contextMenuGroups', function (groups, section) {
        return eAddPasteAction(groups, section);
    });
})(jQuery);

// add context menu item to add-section
jQuery(window).on('load', function () {
    setInterval(function () {
        if (!jQuery('.elementor-context-menu-list__group-paste .elementor-context-menu-list__item-e_paste').length) {
            jQuery('.elementor-context-menu-list__group-paste .elementor-context-menu-list__item-paste').after(
                    '<div class="elementor-context-menu-list__item elementor-context-menu-list__item-e_paste"><div class="elementor-context-menu-list__item__icon"></div><div class="elementor-context-menu-list__item__title">Paste from Clipboard</div></div>'
                    );
        }
    }, 1000);
    jQuery(document).on('click', '.elementor-context-menu-list__group-paste .elementor-context-menu-list__item-e_paste', function () {
        //console.log('e paste start - add section');
        ePasteFromClipboard(false, this);
    });
});

jQuery(window).on('load', function () {

    // COPY
    jQuery(document).on('click', '.elementor-context-menu-list__item-copy, .elementor-context-menu-list__item-copy_all_content', function () {
        //console.log('e copy start');
        var transferData = elementorCommon.storage.get('clipboard');
        if (!transferData) {
            transferData = elementorCommon.storage.get('transfer');
        }
        //console.log(transferData);
        var jTransferData = JSON.stringify(transferData);
        if (navigator.clipboard) {
            navigator.clipboard.writeText(jTransferData)
                    .then(() => {
                        // Success!
                        //console.log('e copied');
                    })
                    .catch(err => {
                        console.log('Something went wrong', err);
                    });
        } else {
            // fallback
            eAddCopyPasteFallback(jTransferData);
            var clipboard = new ClipboardJS('#e_copy_paste__btn');

            clipboard.on('success', function (e) {
                console.info('Action:', e.action);
                console.info('Text:', e.text);
                console.info('Trigger:', e.trigger);
                e.clearSelection();
            });
            clipboard.on('error', function (e) {
                console.error('Action:', e.action);
                console.error('Trigger:', e.trigger);
            });

            jQuery('#e_copy_paste__btn').trigger('click');

            jQuery('#e_copy_paste').remove();
            // Success!
            console.log('e copied fallback');
        }
    });

});

function eAddPasteAction(groups, element) {
    //console.log('add paste action');
    var transferGroup = _.findWhere(groups, {name: 'clipboard'});
    if (!transferGroup) {
        transferGroup = _.findWhere(groups, {name: 'transfer'});
    }
    if (!transferGroup) {
        return groups;
    }
    jQuery.each(groups, function (index, value) {
        if (value.name == 'transfer' || value.name == 'clipboard' || value.name == 'paste') {
            //console.log(value.name);
            groups[index].actions.push(
                    {
                        name: 'e_paste',
                        title: 'Paste from Clipboard',
                        callback: function () {
                            //console.log('Paste from Clipboard');
                            pasteAction = _.findWhere(transferGroup.actions, {name: 'paste'});
                            return ePasteFromClipboard(pasteAction);
                        }
                    },
                    {
                        name: 'e_paste_style',
                        title: 'Paste Style from Clipboard',
                        callback: function () {
                            // do your stuff, element should be available here
                            //console.log('Paste Style from Clipboard');
                            pasteStyleAction = _.findWhere(transferGroup.actions, {name: 'pasteStyle'});
                            return ePasteFromClipboard(pasteStyleAction);
                        }
                    }
            );
        }
    });

    return groups;
}

// PASTE
function ePasteFromClipboard(pasteAction, pasteBtn) {

    var cid = jQuery(pasteBtn).closest('.elementor-context-menu').attr('data-model-cid');
    if (!cid || cid == 'undefined') {
        cid = jQuery('.elementor-context-menu:visible').attr('data-model-cid');
    }
    //console.log(cid);
    //console.log('e paste start');
    if (eCanJsPaste()) {
        navigator.clipboard.readText()
                .then(text => {
                    // `text` contains the text read from the clipboard
                    ePasteAction(text, pasteAction, pasteBtn, cid);
                })
                .catch(err => {
                    // maybe user didn't grant access to read from clipboard
                    console.log('Something went wrong', err);
                });
    } else {
        jQuery(pasteBtn).closest('.elementor-context-menu').hide()
        eAddCopyPasteFallback('', 'paste', cid, pasteAction, pasteBtn); // create an empty textarea
        jQuery('#e_copy_paste__textarea').select();
        document.execCommand("paste");
        var text = jQuery('#e_copy_paste__textarea').val(); // retrieve the pasted text
        if (text) {
            jQuery('#e_copy_paste__btn').trigger('click');
        }
    }
    return true;
}

function eAddCopyPasteFallback(value = '', action = 'copy', cid, pasteAction, pasteBtn) {
    if (jQuery('#e_copy_paste').length) {
        jQuery('#e_copy_paste').remove();
    }
    jQuery('#elementor-preview-responsive-wrapper').append('<div id="e_copy_paste" class="elementor-context-menu" data-model-cid="' + cid + '"></div>');
    jQuery('#e_copy_paste').append('<p>Sorry, <b>DIRECT Paste is not supported by your browser</b>, to continue <b>MANUALLY Paste</b> content in the below Textarea and <b>click PASTE</b></p>');
    jQuery('#e_copy_paste').append('<textarea id="e_copy_paste__textarea" placeholder="Paste HERE">' + value + '</textarea>');
    jQuery('#e_copy_paste').append('<button id="e_copy_paste__btn" data-clipboard-action="' + action + '" data-clipboard-target="#e_copy_paste__textarea"><span class="icon pull-right ml-1"></span> PASTE</button>');
    jQuery('#e_copy_paste').append('<a id="e_copy_paste__close" href="#"><i class="eicon-close"></i></a>');
    if (action == 'paste') {
        jQuery('#e_copy_paste').attr('data-model-cid', cid);
        jQuery('#e_copy_paste__btn').on('click', function () {
            var text = jQuery('#e_copy_paste__textarea').val();
            ePasteAction(text, pasteAction, pasteBtn, jQuery('#e_copy_paste').attr('data-model-cid'));
            jQuery('#e_copy_paste').remove();
        });
    }
    jQuery('#e_copy_paste__close').on('click', function () {
        jQuery('#e_copy_paste').remove();
    });
}

function ePasteAction(text, pasteAction, pasteBtn, cid) {

    var isJson = true;
    try {
        JSON.parse(text);
    } catch (e) {
        isJson = false;
    }

    if (isJson) {
        var clipboardData = JSON.parse(text);
        //console.log(clipboardData);
        clipboardData = eGenerateUniqueID(clipboardData);
        //if (transferData.elements.length) {
        elementorCommon.storage.set('clipboard', clipboardData); // >= 2.8
        elementorCommon.storage.set('transfer', clipboardData); // <= 2.7

        //console.log('e pasted');
        if (pasteAction) {
            //console.log(pasteAction);
            if (!pasteAction.callback()) {
                // not working on PasteStyle action...so fallback
                //console.log('paste enabled'); console.log(pasteAction.isEnabled());
                if (cid && cid != 'undefined') {
                    var pasteBtnSelector = '.elementor-context-menu[data-model-cid=' + cid + '] .elementor-context-menu-list__item-' + pasteAction.name;
                    var iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
                    iFrameDOM.find('.elementor-element[data-model-cid=' + cid + ']').trigger('contextmenu');
                    //pasteAction.callback()
                    jQuery('.elementor-context-menu[data-model-cid=' + cid + ']').hide();
                    setTimeout(function () {
                        //console.log(pasteBtnSelector);
                        jQuery(pasteBtnSelector).trigger('click');
                    }, 100);

                }
                //return new Commands.PasteStyle().run();
                //$e.run('document/elements/paste-style', {});
            }
        } else {
            jQuery(pasteBtn).prev().trigger('click');
        }
        //}
        jQuery('#e_copy_paste').remove();
    } else {
        alert('Invalid JSON Element saved in Clipboard:\r\n------------------\r\n' + text);
    }
}

function eGenerateUniqueID(elements) {
    elements.forEach(function (item, index) {
        elements[index].id = elementor.helpers.getUniqueID();
        if (item.elements.length > 0) {
            elements[index].elements = eGenerateUniqueID(item.elements);
        }
    });
    return elements;
}

function eCanJsPaste() {
    return navigator.clipboard && typeof navigator.clipboard.readText === "function" && (location.protocol == 'https:' || location.hostname == 'localhost' || location.hostname == '127.0.0.1');
}