System.register(["./shared.js"], function (exports_1, context_1) {
    "use strict";
    var shared_js_1;
    var __moduleName = context_1 && context_1.id;
    return {
        setters: [
            function (shared_js_1_1) {
                shared_js_1 = shared_js_1_1;
            }
        ],
        execute: function () {
            shared_js_1.loadRecordings('wp-block-avorg-block-relatedsermons');
        }
    };
});
//# sourceMappingURL=frontend.js.map