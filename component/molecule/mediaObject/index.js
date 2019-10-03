System.register([], function (exports_1, context_1) {
    "use strict";
    var molecule_mediaObject;
    var __moduleName = context_1 && context_1.id;
    return {
        setters: [],
        execute: function () {
            molecule_mediaObject = function (title, titleUrl, secondLine, imgUrl, imgAlt) {
                var image = imgUrl ? "<img class=\"avorg-molecule-mediaObject__image\" src=\"" + imgUrl + "\" alt=\"" + imgAlt + "\" />" : '', titleLinkStart = titleUrl ? "<a href=\"" + titleUrl + "\">" : '', titleLinkEnd = titleUrl ? '</a>' : '';
                return "<li class=\"avorg-molecule-mediaObject\">\n    " + image + "\n    <div class=\"avorg-molecule-mediaObject__text\">\n        " + titleLinkStart + "\n        <h4 class=\"avorg-molecule-mediaObject__title\">" + title + "</h4>\n        " + titleLinkEnd + "\n        " + secondLine + "\n    </div>\n</li>";
            };
            exports_1("default", molecule_mediaObject);
        }
    };
});
//# sourceMappingURL=index.js.map