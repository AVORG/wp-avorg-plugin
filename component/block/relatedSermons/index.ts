import { loadRecordings } from "./shared.js";

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
    title: 'Example: Basic',
    icon: 'universal-access-alt',
    category: 'layout',
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
