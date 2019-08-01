var AvorgBlockList;
(function (AvorgBlockList) {
    var blocks = wp.blocks;
    var element = wp.element;
    var el = element.createElement;
    var blockStyle = {};
    blocks.registerBlockType('avorg/block-list', {
        title: 'Recordings List',
        icon: 'playlist-audio',
        category: 'widgets',
        edit: function (props) {
            return el('p', { style: blockStyle, className: props.className }, 'Loading...');
        },
        save: function (props) {
            return el('p', { style: blockStyle }, 'Loading...');
        },
    });
})(AvorgBlockList || (AvorgBlockList = {}));
//# sourceMappingURL=index.js.map