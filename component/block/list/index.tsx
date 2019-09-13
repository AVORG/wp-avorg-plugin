namespace AvorgBlockList {
    window.wp.blocks.registerBlockType('avorg/block-list', {
        title: 'Recordings List',
        icon: 'playlist-audio',
        category: 'widgets',
        attributes: {
            type: {
                type: 'string',
            }
        },
        edit: function (props: any) {
            const { attributes: { type }, setAttributes } = props;

            const {
                InspectorControls,
            } = window.wp.editor;

            const {
                PanelBody,
                PanelRow,
                SelectControl,
            } = window.wp.components;

            return <p className={props.className}>
                <InspectorControls>
                    <PanelBody title={"List Type"}>
                        <PanelRow>
                            <SelectControl
                                label={"List Type"}
                                value={type}
                                options={[
                                    { value: 'recent', label: 'Recent' },
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
                Recordings List: {type}
            </p>;
        },
        save: (): null => null
    });
}
