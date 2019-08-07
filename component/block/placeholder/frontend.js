var AvorgPlaceholder;
(function (AvorgPlaceholder) {
    var loadContent = function (className) {
        var elements = document.querySelectorAll("." + className);
        if (!elements)
            return;
        var url = "/wp-json/wp/v2/avorg-content-bits";
        fetch(url).then(function (response) {
            return response.json();
        }).then(function (response) {
            elements.forEach(function (el) {
                var identifier = el.getAttribute('data-id'), matches = response.filter(function (item) {
                    return item.meta.avorgBitIdentifier === identifier;
                }), i = Math.floor(Math.random() * matches.length);
                el.innerHTML = matches[i].content.rendered;
            });
        });
    };
    loadContent('wp-block-avorg-block-placeholder');
})(AvorgPlaceholder || (AvorgPlaceholder = {}));
//# sourceMappingURL=frontend.js.map