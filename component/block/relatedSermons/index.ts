import { loadRecordings } from "./shared.js";

namespace AvorgRelatedSermons {
    declare const wp: any;

    const blocks = wp.blocks;
    const element = wp.element;
    const el = element.createElement;

    const blockStyle = {
        // backgroundColor: '#900',
        // color: '#fff',
        // padding: '20px',
    };

    blocks.registerBlockType('avorg/block-relatedsermons', {
        title: 'Related Sermons',
        icon: 'excerpt-view',
        category: 'widgets',
        edit: function (props: any) {
            loadRecordings(props.className);

            return el(
                'p',
                {style: blockStyle, className: props.className},
                'Loading...'
            );
        },
        save: function (props: any) {
            return el(
                'p',
                {style: blockStyle},
                'Loading...'
            );
        },
    });
}
