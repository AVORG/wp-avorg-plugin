System.register(["./shared.js"], function (exports_1, context_1) {
    "use strict";
    var shared_js_1, blocks, element, el, blockStyle;
    var __moduleName = context_1 && context_1.id;
    return {
        setters: [
            function (shared_js_1_1) {
                shared_js_1 = shared_js_1_1;
            }
        ],
        execute: function () {
            blocks = wp.blocks;
            element = wp.element;
            el = element.createElement;
            blockStyle = {};
            blocks.registerBlockType('avorg/block-relatedsermons', {
                title: 'Example: Basic',
                icon: 'universal-access-alt',
                category: 'layout',
                edit: function (props) {
                    shared_js_1.loadRecordings(props.className);
                    return el('p', { style: blockStyle, className: props.className }, 'Loading...');
                },
                save: function (props) {
                    return el('p', { style: blockStyle }, 'Loading...');
                },
            });
        }
    };
});
//# sourceMappingURL=index.js.map