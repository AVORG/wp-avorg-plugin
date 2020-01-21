namespace AvorgRelatedSermons {
    window.wp.blocks.registerBlockType('avorg/block-relatedsermons', {
        title: 'Related Sermons',
        icon: 'excerpt-view',
        category: 'avorg',
        edit: function (props: any) {
            return <p className={props.className}>Related Sermons</p>;
        },
        save: (): null => null
    });
}
