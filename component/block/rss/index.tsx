import {FormEvent} from 'react';

namespace AvorgBlockRss {
    const blockStyle = {
        // backgroundColor: '#900',
        // color: '#fff',
        // padding: '20px',
    };

    const createLink = (url: string) => {
        return <a href={url} target={'_blank'} rel={'noopener noreferrer'}>
            <svg aria-hidden="true" role="img" focusable="false" className="dashicon dashicons-rss"
                 xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                <path
                    d="M14.92 18H18C18 9.32 10.82 2.25 2 2.25v3.02c7.12 0 12.92 5.71 12.92 12.73zm-5.44 0h3.08C12.56 12.27 7.82 7.6 2 7.6v3.02c2 0 3.87.77 5.29 2.16C8.7 14.17 9.48 16.03 9.48 18zm-5.35-.02c1.17 0 2.13-.93 2.13-2.09 0-1.15-.96-2.09-2.13-2.09-1.18 0-2.13.94-2.13 2.09 0 1.16.95 2.09 2.13 2.09z"></path>
            </svg>
        </a>;
    };

    window.wp.blocks.registerBlockType('avorg/block-rss', {
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
            } = window.wp.editor;

            const form = <form onSubmit={(event: FormEvent<HTMLFormElement>) => event.preventDefault()}>
                <URLInput placeholder={'RSS Url'} value={url} onChange={(url: string) => setAttributes({url})}/>
            </form>;

            return <div style={blockStyle} className={props.className}>
                {isSelected ? form : createLink(url)}
            </div>;
        },
        save: function (props: any) {
            const { attributes: { url } } = props;

            return <div style={blockStyle}>{createLink(url)}</div>
        },
    });
}
