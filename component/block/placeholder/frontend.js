var AvorgPlaceholder;
(function (AvorgPlaceholder) {
    var loadContent = function (className) {
        var elements = document.querySelectorAll("." + className);
        if (!elements)
            return;
        elements.forEach(function (el) {
            var identifier = el.getAttribute('data-id'), media_id = avorg.recordings ? avorg.recordings[0].id : '', url = "/wp-json/avorg/v1/placeholder-content/" + identifier + "/" + media_id;
            fetch(url).then(function (response) {
                return response.json();
            }).then(function (response) {
                console.log("matches for " + identifier, response);
                if (typeof response !== 'undefined' && response.length > 0) {
                    var i = Math.floor(Math.random() * response.length);
                    el.innerHTML = response[i].post_content;
                }
                else {
                    console.warn("No content found for placeholder ID '" + identifier + "'");
                    el.innerHTML = '';
                }
            });
        });
    };
    loadContent('wp-block-avorg-block-placeholder');
})(AvorgPlaceholder || (AvorgPlaceholder = {}));
//# sourceMappingURL=frontend.js.map