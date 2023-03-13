$(function() {
    tinymce.init({
        selector: 'textarea#message_to_customer',
        toolbar: false,
        statusbar: false,
        menubar: false,
        skin: 'tinymce-5',
        content_style: "body { font-size: .8rem; }",
        paste_as_text: true,
        height: 250,
        setup: function (editor) {
            const onAction = function (autocompleteApi, rng, value) {
                editor.selection.setRng(rng);
                editor.insertContent(value);
                autocompleteApi.hide();
            };

            const getMatchedChars = function (pattern) {
                return messageVariables.filter(function (char) {
                    return char.text.toLowerCase().indexOf(pattern.toLowerCase()) !== -1
                        || char.value.toLowerCase().indexOf(pattern.toLowerCase()) !== -1
                        || char.templateVar.toLowerCase().indexOf(pattern.toLowerCase()) !== -1
                });
            };

            editor.ui.registry.addAutocompleter('specialchars_cardmenuitems', {
                ch: '{',
                minChars: 0,
                columns: 1,
                highlightOn: ['char_name'],
                onAction: onAction,
                fetch: function (pattern) {
                    return new Promise(function (resolve) {
                        var results = getMatchedChars(pattern).map(function (char) {
                            return {
                                type: 'cardmenuitem',
                                value: char.templateVar,
                                label: char.text,
                                items: [
                                    {
                                        type: 'cardcontainer',
                                        direction: 'vertical',
                                        items: [
                                            {
                                                type: 'cardtext',
                                                text: char.text,
                                                name: 'char_name'
                                            },
                                            {
                                                type: 'cardtext',
                                                text: char.value
                                            }
                                        ]
                                    }
                                ]
                            }
                        });
                        resolve(results);
                    });
                }
            });
        }
    });

})
