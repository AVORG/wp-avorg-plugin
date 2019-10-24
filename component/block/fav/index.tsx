namespace AvorgBlockFav {
    window.wp.blocks.registerBlockType('avorg/block-fav', {
        title: 'Favorite Toggle',
        icon: 'star-half',
        category: 'widgets',
        edit: (props: any) => <div className={props.className}>Favorite Toggle Backend</div>,
        save: (props: any) => {
            return <div className={props.className}>Favorite Toggle Frontend</div>
        }
    });
}
