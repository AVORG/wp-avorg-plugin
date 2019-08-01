var blocks = wp.blocks;
var element = wp.element;
var el = element.createElement;
var blockStyle = {};
var createLink = function (url) {
    var icon = '<svg aria-hidden="true" role="img" focusable="false" class="dashicon dashicons-rss" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M14.92 18H18C18 9.32 10.82 2.25 2 2.25v3.02c7.12 0 12.92 5.71 12.92 12.73zm-5.44 0h3.08C12.56 12.27 7.82 7.6 2 7.6v3.02c2 0 3.87.77 5.29 2.16C8.7 14.17 9.48 16.03 9.48 18zm-5.35-.02c1.17 0 2.13-.93 2.13-2.09 0-1.15-.96-2.09-2.13-2.09-1.18 0-2.13.94-2.13 2.09 0 1.16.95 2.09 2.13 2.09z"></path></svg>';
    return el('a', {
        href: url,
        target: "_blank",
        dangerouslySetInnerHTML: { __html: icon },
        rel: "noopener noreferrer"
    });
};
blocks.registerBlockType('avorg/block-rss', {
    title: 'RSS Link',
    icon: 'rss',
    category: 'widgets',
    attributes: {
        url: {
            type: 'string',
            source: 'attribute',
            attribute: 'href',
            selector: 'a',
        },
    },
    edit: function (props) {
        var url = props.attributes.url, isSelected = props.isSelected, setAttributes = props.setAttributes;
        var URLInput = wp.editor.URLInput;
        var urlInput = el(URLInput, {
            placeholder: 'RSS Url',
            value: url,
            onChange: function (url) { return setAttributes({ url: url }); }
        });
        var form = el('form', {
            onSubmit: function (event) { return event.preventDefault(); }
        }, urlInput);
        return el('div', { style: blockStyle, className: props.className }, isSelected ? form : createLink(url));
    },
    save: function (props) {
        var url = props.attributes.url;
        return el('div', { style: blockStyle }, createLink(url));
    },
});
//# sourceMappingURL=index.js.map