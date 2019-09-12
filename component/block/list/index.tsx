import { loadRecordings } from "./shared";

namespace AvorgBlockList {
    const blockStyle = {
        // backgroundColor: '#900',
        // color: '#fff',
        // padding: '20px',
    };

    const className = 'wp-block-avorg-block-list';

    window.wp.blocks.registerBlockType('avorg/block-list', {
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
            const { attributes: { type }, setAttributes } = props;

            loadRecordings(className);

            const {
                InspectorControls,
            } = window.wp.editor;

            const {
                PanelBody,
                PanelRow,
                SelectControl,
            } = window.wp.components;

            return <p style={blockStyle} data-type={type} className={props.className}>
                <InspectorControls>
                    <PanelBody title={"List Type"}>
                        <PanelRow>
                            <SelectControl
                                label={"List Type"}
                                value={type}
                                options={[
                                    { value: '', label: 'Recent' },
                                    { value: 'featured', label: 'Featured' },
                                    { value: 'popular', label: 'Popular' }
                                ]}
                                onChange={(type: string) => {
                                    setAttributes({ type });
                                }}
                            />
                        </PanelRow>
                    </PanelBody>
                </InspectorControls>
                Loading list of type {type}...
            </p>;
        },
        save: function (props: any) {
            const { attributes: { type } } = props;

            return <p style={blockStyle} data-type={type}>Loading...</p>;
        },
    });

    loadRecordings(className);
}
