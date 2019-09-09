import { loadRecordings } from "./shared.js";

namespace AvorgBlockList {
    declare const wp: any;

    const blocks = wp.blocks;
    const element = wp.element;
    const el = element.createElement;

    const blockStyle = {
        // backgroundColor: '#900',
        // color: '#fff',
        // padding: '20px',
    };

    blocks.registerBlockType('avorg/block-list', {
        title: 'Recordings List',
        icon: 'playlist-audio',
        category: 'widgets',
        attributes: {
            type: {
                type: 'string',
                source: 'attribute',
                attribute: 'data-type',
                selector: '[data-type]'
            }
        },
        edit: function (props: any) {
            const { attributes: { type }, setAttributes, className } = props;

            loadRecordings(className);

            const {
                InspectorControls,
            } = wp.editor;

            const {
                PanelBody,
                PanelRow,
                SelectControl,
            } = wp.components;

            const select = el(SelectControl, {
                label: 'List Type',
                value: type,
                options: [
                    { value: '', label: 'Recent' },
                    { value: 'featured', label: 'Featured' },
                    { value: 'popular', label: 'Popular' }
                ],
                onChange: (type: string) => {
                    setAttributes({ type });
                }
            });
            const panelRow = el(PanelRow, {}, select);
            const panel = el(PanelBody, {title: "List Type"}, panelRow);
            const inspectorControls = el(InspectorControls, {}, panel);

            return el(
                'p',
                {style: blockStyle, 'data-type': type,  className: props.className},
                [
                    inspectorControls,
                    'Loading...'
                ]
            );
        },
        save: function (props: any) {
            const { attributes: { type } } = props;

            return el(
                'p',
                {style: blockStyle, 'data-type': type},
                'Loading...'
            );
        },
    });
}
