System.register(["../../molecule/mediaObject/index.js"], function (exports_1, context_1) {
    "use strict";
    var index_js_1, itemTemplate, loadRecordings;
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
            itemTemplate = function (recording) {
                var imageUrl = recording.presenters[0] ? recording.presenters[0].photo : null;
                var imageAlt = recording.presenters[0] ?
                    recording.presenters[0].name.first + " " + recording.presenters[0].name.last + " " + recording.presenters[0].name.suffix : null;
                return index_js_1.default(recording.title, recording.url, recording.presentersString, imageUrl, imageAlt);
            };
            exports_1("loadRecordings", loadRecordings = function (className) {
                var entityId = avorg_scripts.query.entity_id;
                if (!entityId)
                    return;
                var url = "http://localhost:8000/api/presentation/related/" + entityId;
                fetch(url).then(function (response) {
                    return response.json();
                }).then(function (response) {
                    var elements = document.querySelectorAll("." + className), recordings = getRandomSubarray(Object.values(response), 3), content = recordings.map(itemTemplate).join("");
                    elements.forEach(function (el) { return el.innerHTML = content; });
                });
            });
        }
    };
});
//# sourceMappingURL=shared.js.map