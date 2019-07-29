console.log(wp, wp.element);
var blocks = wp.blocks;
var element = wp.element;
var el = element.createElement;
var blockStyle = {};
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
var itemTemplate = function (recording) {
    var imageUrl = recording.presenters[0] ? recording.presenters[0].photo : null;
    var imageAlt = recording.presenters[0] ?
        recording.presenters[0].name.first + " " + recording.presenters[0].name.last + " " + recording.presenters[0].name.suffix : null;
    var presenters = recording.presenters.map(function (presenter) {
        return presenter.name.first + " " + presenter.name.last + " " + presenter.name.suffix;
    }).join(", ");
    return molecule_mediaObject(recording.title, presenters, imageUrl, imageAlt);
};
blocks.registerBlockType('avorg/block-relatedsermons', {
    title: 'Example: Basic',
    icon: 'universal-access-alt',
    category: 'layout',
    edit: function () {
        console.log('hello back');
        var id = 'avorg-block-relatedSermons-' + Math.floor(Math.random() * Math.floor(1000));
        var url = 'http://localhost:8000/api/related/20047';
        fetch(url).then(function (response) {
            return response.json();
        }).then(function (response) {
            console.log(response);
            var el = document.querySelector('#' + id);
            console.log(el);
            el.textContent = 'Data loaded';
            var recordings = getRandomSubarray(Object.values(response), 3);
            el.innerHTML = recordings.map(itemTemplate).join("");
        });
        return el('p', {
            style: blockStyle,
            id: id
        }, 'Loading...');
    },
    save: function () {
        console.log('hello front');
        return el('p', { style: blockStyle }, 'Hello World, step 1 (from the frontend).');
    },
});
//# sourceMappingURL=block-relatedSermons.js.map