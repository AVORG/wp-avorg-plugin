import {FormEvent} from 'react';

namespace AvorgPlaceholder {
    const blockStyle = {
        // backgroundColor: '#900',
        // color: '#fff',
        // padding: '20px',
    };

    window.wp.blocks.registerBlockType('avorg/block-placeholder', {
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
            } = window.wp.components;

            fetch('/wp-json/avorg/v1/placeholder-ids').then(response => {
                return response.json();
            }).then(response => {
                const el = document.querySelector('#avorg_placeholder_suggestions');

                if (typeof response !== 'undefined' && response.length > 0 && el) {
                    el.innerHTML = response.map((s: string) => {
                        return `<option value="${s}" />`
                    }).join('');
                }
            });

            const form = <form onSubmit={(event: FormEvent<HTMLFormElement>) => event.preventDefault()}>
                <TextControl
                    placeholder={'Placeholder Identifier'}
                    value={id}
                    list={'avorg_placeholder_suggestions'}
                    onChange={(id: string) => setAttributes({id})}
                />
                <datalist id={'avorg_placeholder_suggestions'}>
                    <option value={'Something'} />
                </datalist>
            </form>;

            return <div style={blockStyle} className={className} data-id={id}>
                {isSelected ? form : `Placeholder: ${id}`}
            </div>;
        },
        save: function (props: any) {
            const { attributes: { id } } = props;

            return <div style={blockStyle} data-id={id}>Loading...</div>;
        },
    });
}