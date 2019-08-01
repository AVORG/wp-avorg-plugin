System.register(["./shared.js"], function (exports_1, context_1) {
    "use strict";
    var shared_js_1, AvorgBlockList;
    var __moduleName = context_1 && context_1.id;
    return {
        setters: [
            function (shared_js_1_1) {
                shared_js_1 = shared_js_1_1;
            }
        ],
        execute: function () {
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
                        var type = props.attributes.type, setAttributes = props.setAttributes, className = props.className;
                        shared_js_1.loadRecordings(className);
                        var InspectorControls = wp.editor.InspectorControls;
                        var _a = wp.components, PanelBody = _a.PanelBody, PanelRow = _a.PanelRow, SelectControl = _a.SelectControl;
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
        }
    };
});
//# sourceMappingURL=index.js.map