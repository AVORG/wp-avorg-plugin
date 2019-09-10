import { loadRecordings } from "./shared";

namespace AvorgRelatedSermons {
    const blockStyle = {
        // backgroundColor: '#900',
        // color: '#fff',
        // padding: '20px',
    };

    window.wp.blocks.registerBlockType('avorg/block-relatedsermons', {
        title: 'Related Sermons',
        icon: 'excerpt-view',
        category: 'widgets',
        edit: function (props: any) {
            loadRecordings(props.className);

            return <p style={blockStyle} className={props.className}>Related Sermons</p>;
        },
        save: function (props: any) {
            return <p style={blockStyle}>Loading...</p>;
        },
    });
}
