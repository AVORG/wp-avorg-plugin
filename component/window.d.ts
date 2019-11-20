interface Window {
    wp: {
        blocks: {
            registerBlockType: any
        }
        editor: any,
        element: any,
        components: any,
    };
    avorg: {
        query: any,
        session: {
            email: string
        },
        post_id: number
    };
}

declare const wp: any;