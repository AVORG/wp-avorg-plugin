System.register(["../../molecule/mediaObject/index.js"], function (exports_1, context_1) {
    "use strict";
    var index_js_1, blocks, element, el, blockStyle, prepareId, itemTemplate, loadRecordings;
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
            console.log(wp, wp.element);
            blocks = wp.blocks;
            element = wp.element;
            el = element.createElement;
            blockStyle = {};
            prepareId = function (id) {
                return 'avorg-' + id.replace(/-/g, '');
            };
            itemTemplate = function (recording) {
                var imageUrl = recording.presenters[0] ? recording.presenters[0].photo : null;
                var imageAlt = recording.presenters[0] ?
                    recording.presenters[0].name.first + " " + recording.presenters[0].name.last + " " + recording.presenters[0].name.suffix : null;
                var presenters = recording.presenters.map(function (presenter) {
                    return presenter.name.first + " " + presenter.name.last + " " + presenter.name.suffix;
                }).join(", ");
                return index_js_1.default(recording.title, presenters, imageUrl, imageAlt);
            };
            loadRecordings = function (id) {
                var url = 'http://localhost:8000/api/related/20047';
                fetch(url).then(function (response) {
                    return response.json();
                }).then(function (response) {
                    var el = document.querySelector('#' + id);
                    var recordings = getRandomSubarray(Object.values(response), 3);
                    el.innerHTML = recordings.map(itemTemplate).join("");
                });
            };
            blocks.registerBlockType('avorg/block-relatedsermons', {
                title: 'Example: Basic',
                icon: 'universal-access-alt',
                category: 'layout',
                edit: function (props) {
                    console.log('hello back', props);
                    var id = prepareId(props.clientId);
                    loadRecordings(id);
                    return el('p', { style: blockStyle, id: id }, 'Loading...');
                },
                save: function (props) {
                    console.log('hello front');
                    return el('p', { style: blockStyle }, 'Loading...');
                },
            });
        }
    };
});
//# sourceMappingURL=index.js.map