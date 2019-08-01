var AvorgBlockList;
(function (AvorgBlockList) {
    var blocks = wp.blocks;
    var element = wp.element;
    var el = element.createElement;
    var blockStyle = {};
    blocks.registerBlockType('avorg/block-list', {
        title: 'Recordings List',
        icon: 'playlist-audio',
        category: 'widgets',
        attributes: {
            type: {
                type: 'string',
                source: 'attribute',
                attribute: 'data-type',
                selector: '[data-type]'
            }
        },
        edit: function (props) {
            var type = props.attributes.type, setAttributes = props.setAttributes;
            var _a = wp.editor, RichText = _a.RichText, AlignmentToolbar = _a.AlignmentToolbar, BlockControls = _a.BlockControls, BlockAlignmentToolbar = _a.BlockAlignmentToolbar, InspectorControls = _a.InspectorControls;
            var _b = wp.components, Toolbar = _b.Toolbar, Button = _b.Button, Tooltip = _b.Tooltip, PanelBody = _b.PanelBody, PanelRow = _b.PanelRow, FormToggle = _b.FormToggle, SelectControl = _b.SelectControl;
            var select = el(SelectControl, {
                label: 'List Type',
                value: type,
                options: [
                    { value: '', label: 'Recent' },
                    { value: 'featured', label: 'Featured' },
                    { value: 'popular', label: 'Popular' }
                ],
                onChange: function (type) {
                    setAttributes({ type: type });
                }
            });
            var panelRow = el(PanelRow, {}, select);
            var panel = el(PanelBody, { title: "List Type" }, panelRow);
            var inspectorControls = el(InspectorControls, {}, panel);
            return el('p', { style: blockStyle, 'data-type': type, className: props.className }, [
                inspectorControls,
                'Loading...'
            ]);
        },
        save: function (props) {
            var type = props.attributes.type;
            return el('p', { style: blockStyle, 'data-type': type }, 'Loading...');
        },
    });
})(AvorgBlockList || (AvorgBlockList = {}));
//# sourceMappingURL=index.js.map