System.register(["../mediaObject/index.js"], function (exports_1, context_1) {
    "use strict";
    var index_js_1, itemTemplate, molecule_recordingList;
    var __moduleName = context_1 && context_1.id;
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
            molecule_recordingList = function (recordings) {
                return recordings.map(itemTemplate).join("");
            };
            exports_1("default", molecule_recordingList);
        }
    };
});
//# sourceMappingURL=index.js.map