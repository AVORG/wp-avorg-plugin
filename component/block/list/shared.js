System.register(["../../molecule/recordingList/index.js"], function (exports_1, context_1) {
    "use strict";
    var index_js_1, loadRecordings;
    var __moduleName = context_1 && context_1.id;
    return {
        setters: [
            function (index_js_1_1) {
                index_js_1 = index_js_1_1;
            }
        ],
        execute: function () {
            exports_1("loadRecordings", loadRecordings = function (className) {
                var elements = document.querySelectorAll("." + className);
                elements.forEach(function (el) {
                    var list = el.getAttribute('data-type'), url = "http://localhost:8000/api/presentation/" + list;
                    fetch(url).then(function (response) {
                        return response.json();
                    }).then(function (response) {
                        el.innerHTML = index_js_1.default(response);
                    });
                });
            });
        }
    };
});
//# sourceMappingURL=shared.js.map