declare const wp: any;

const blocks = wp.blocks;
const element = wp.element;
const el = element.createElement;

const blockStyle = {
    // backgroundColor: '#900',
    // color: '#fff',
    // padding: '20px',
};

blocks.registerBlockType('avorg/block-rss', {
    title: 'RSS Link',
    icon: 'rss',
    category: 'widgets',
    attributes: {
        url: {
            type: 'string',
            source: 'attribute',
            attribute: 'href',
            selector: 'a',
        },
    },
    edit: function (props: any) {
        const { attributes: { url }, isSelected, setAttributes } = props;

        const {
            URLInput
        } = wp.editor;

        return el(
            'p',
            {style: blockStyle, className: props.className},
            isSelected ? el(URLInput, {
                placeholder: 'RSS Url',
                value: url,
                onChange: (url: string) => setAttributes({ url })
            }) : 'RSS'
        );
    },
    save: function (props: any) {
        return el(
            'p',
            {style: blockStyle},
            'RSS'
        );
    },
});
