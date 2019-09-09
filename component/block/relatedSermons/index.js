System.register(["./shared.js"], function (exports_1, context_1) {
    "use strict";
    var shared_js_1, AvorgRelatedSermons;
    var __moduleName = context_1 && context_1.id;
    return {
        setters: [
            function (shared_js_1_1) {
                shared_js_1 = shared_js_1_1;
            }
        ],
        execute: function () {
            (function (AvorgRelatedSermons) {
                var blocks = wp.blocks;
                var element = wp.element;
                var el = element.createElement;
                var blockStyle = {};
                blocks.registerBlockType('avorg/block-relatedsermons', {
                    title: 'Related Sermons',
                    icon: 'excerpt-view',
                    category: 'widgets',
                    edit: function (props) {
                        shared_js_1.loadRecordings(props.className);
                        return el('p', { style: blockStyle, className: props.className }, 'Related Sermons');
                    },
                    save: function (props) {
                        return el('p', { style: blockStyle }, 'Loading...');
                    },
                });
            })(AvorgRelatedSermons || (AvorgRelatedSermons = {}));
        }
    };
});
//# sourceMappingURL=index.js.map