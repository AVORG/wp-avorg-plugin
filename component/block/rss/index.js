var blocks = wp.blocks;
var element = wp.element;
var el = element.createElement;
var blockStyle = {};
var createLink = function (url) {
    var Icon = wp.components.Icon;
    return el('p', {}, el('a', { href: url, target: "_blank" }, el(Icon, { icon: 'rss' })));
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
        return createLink(url);
    },
});
//# sourceMappingURL=index.js.map