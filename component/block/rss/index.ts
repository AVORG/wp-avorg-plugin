declare const wp: any;

const blocks = wp.blocks;
const element = wp.element;
const el = element.createElement;

const blockStyle = {
    // backgroundColor: '#900',
    // color: '#fff',
    // padding: '20px',
};

const createLink = (url: string) => {
    const {
        Icon
    } = wp.components;

    return el('p', {}, el('a', {href: url, target: "_blank"}, el(Icon, {icon: 'rss'})));
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

        const urlInput = el(URLInput, {
            placeholder: 'RSS Url',
            value: url,
            onChange: (url: string) => setAttributes({url})
        });

        const form = el('form', {
            onSubmit: (event: Event) => event.preventDefault()
        }, urlInput);

        return el(
            'div',
            {style: blockStyle, className: props.className},
            isSelected ? form : createLink(url)
        );
    },
    save: function (props: any) {
        const { attributes: { url } } = props;

        return createLink(url);
    },
});
