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
        query: any;
        session: {
            email: string
        }
    };
}

declare const wp: any;