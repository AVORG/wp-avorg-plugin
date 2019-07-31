var blocks = wp.blocks;
var element = wp.element;
var el = element.createElement;
var blockStyle = {};
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
        return el('p', { style: blockStyle, className: props.className }, isSelected ? el(URLInput, {
            placeholder: 'RSS Url',
            value: url,
            onChange: function (url) { return setAttributes({ url: url }); }
        }) : 'RSS');
    },
    save: function (props) {
        return el('p', { style: blockStyle }, 'RSS');
    },
});
//# sourceMappingURL=index.js.map