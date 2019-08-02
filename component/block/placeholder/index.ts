namespace AvorgPlaceholder {
    declare const wp: any;

    const blocks = wp.blocks;
    const element = wp.element;
    const el = element.createElement;

    const blockStyle = {
        // backgroundColor: '#900',
        // color: '#fff',
        // padding: '20px',
    };

    blocks.registerBlockType('avorg/block-placeholder', {
        title: 'Placeholder',
        icon: 'location',
        category: 'widgets',
        attributes: {
            id: {
                type: 'string',
                source: 'attribute',
                attribute: 'data-id',
                selector: '[data-id]',
            },
        },
        edit: function (props: any) {
            const {
                attributes: { id },
                isSelected,
                setAttributes,
                className
            } = props;

            const {
                TextControl
            } = wp.components;

            const input = el(TextControl, {
                placeholder: 'Placeholder Identifier',
                value: id,
                onChange: (id: string) => setAttributes({id})
            });

            const form = el('form', {
                onSubmit: (event: Event) => event.preventDefault()
            }, input);

            return el(
                'div',
                {style: blockStyle, className: className, 'data-id': id},
                isSelected ? form : 'Loading...'
            );
        },
        save: function (props: any) {
            const { attributes: { id } } = props;

            return el(
                'div',
                {style: blockStyle, 'data-id': id},
                'Loading...'
            );
        },
    });
}