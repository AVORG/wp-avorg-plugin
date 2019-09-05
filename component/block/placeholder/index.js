var AvorgPlaceholder;
(function (AvorgPlaceholder) {
    var blocks = wp.blocks;
    var element = wp.element;
    var el = element.createElement;
    var blockStyle = {};
    blocks.registerBlockType('avorg/block-placeholder', {
        title: 'Placeholder',
        icon: 'location',
        category: 'widgets',
        attributes: {
            id: {
                type: 'string',
                source: 'attribute',
                attribute: 'data-id',
                selector: '[data-id]',
            },
        },
        edit: function (props) {
            var id = props.attributes.id, isSelected = props.isSelected, setAttributes = props.setAttributes, className = props.className;
            var TextControl = wp.components.TextControl;
            var input = el(TextControl, {
                placeholder: 'Placeholder Identifier',
                value: id,
                onChange: function (id) { return setAttributes({ id: id }); }
            });
            var form = el('form', {
                onSubmit: function (event) { return event.preventDefault(); }
            }, input);
            return el('div', { style: blockStyle, className: className, 'data-id': id }, isSelected ? form : "Placeholder: " + id);
        },
        save: function (props) {
            var id = props.attributes.id;
            return el('div', { style: blockStyle, 'data-id': id }, 'Loading...');
        },
    });
})(AvorgPlaceholder || (AvorgPlaceholder = {}));
//# sourceMappingURL=index.js.map