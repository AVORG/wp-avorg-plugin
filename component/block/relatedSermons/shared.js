System.register(["../../molecule/recordingList/index.js"], function (exports_1, context_1) {
    "use strict";
    var index_js_1, loadRecordings;
    var __moduleName = context_1 && context_1.id;
    function getRandomSubarray(arr, size) {
        var shuffled = arr.slice(0), i = arr.length, temp, index;
        while (i--) {
            index = Math.floor((i + 1) * Math.random());
            temp = shuffled[index];
            shuffled[index] = shuffled[i];
            shuffled[i] = temp;
        }
        return shuffled.slice(0, size);
    }
    return {
        setters: [
            function (index_js_1_1) {
                index_js_1 = index_js_1_1;
            }
        ],
        execute: function () {
            exports_1("loadRecordings", loadRecordings = function (className) {
                var entityId = avorg_scripts.query.entity_id, elements = document.querySelectorAll("." + className);
                if (!entityId || !elements)
                    return;
                var url = "/api/presentation/related/" + entityId;
                fetch(url).then(function (response) {
                    console.log(response);
                    return response.json();
                }).then(function (response) {
                    var recordings = getRandomSubarray(Object.values(response), 3), content = index_js_1.default(recordings);
                    elements.forEach(function (el) { return el.innerHTML = content; });
                });
            });
        }
    };
});
//# sourceMappingURL=shared.js.map