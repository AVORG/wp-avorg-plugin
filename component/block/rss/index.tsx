import {FormEvent} from 'react';

namespace AvorgBlockRss {
    let optionsLoaded = false;

    window.wp.blocks.registerBlockType('avorg/block-rss', {
        title: 'RSS Link',
        icon: 'rss',
        category: 'widgets',
        attributes: {
            feeds: {
                type: 'object',
            },
            feed: {
                type: 'string',
            },
        },
        edit: function (props: any) {
            const { attributes: { feeds, feed }, isSelected, setAttributes } = props;

            const {
                SelectControl,
            } = window.wp.components;

            if (!optionsLoaded) {
                fetch('/wp-json/avorg/v1/feeds')
                    .then(r => {return r.json()})
                    .then(feeds => {
                        const options = Object.values(feeds).map((entry: string) => {
                            return {
                                value: entry,
                                label: entry.split('\\').slice(-1)[0]
                            }
                        });

                        setAttributes({ feeds: options });
                    });
                optionsLoaded = true;
            }

            const form = <form onSubmit={(event: FormEvent<HTMLFormElement>) => event.preventDefault()}>
                <SelectControl
                    label={"List Type"}
                    value={feed}
                    options={feeds ? feeds : []}
                    onChange={(feed: string) => {
                        setAttributes({ feed });
                    }}
                />
            </form>;

            return <div className={props.className}>
                {isSelected ? form : 'RSS link: ' + feed.split('\\').slice(-1)[0]}
            </div>;
        },
        save: (): null => null
    });
}
