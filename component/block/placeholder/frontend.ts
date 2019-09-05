namespace AvorgPlaceholder {
    interface bit {
        meta: {
            avorgBitIdentifier?: string
        }
    }

    interface Avorg {
        recordings: {
            id: string
        }[]
    }

    declare var avorg: Avorg;

    const loadContent = function (className: string) {
        const elements = document.querySelectorAll(`.${className}`);

        if (!elements) return;

        elements.forEach(el => {
            const identifier: string = el.getAttribute('data-id'),
                media_id: string = avorg.recordings ? avorg.recordings[0].id : '',
                url: string = `/wp-json/avorg/v1/placeholder-content/${identifier}/${media_id}`;

            fetch(url).then(response => {
                return response.json();
            }).then(response => {
                console.log(`matches for ${identifier}`, response);

                if (typeof response !== 'undefined' && response.length > 0) {
                    const i = Math.floor(Math.random() * response.length);

                    el.innerHTML = response[i].post_content
                } else {
                    console.warn( `No content found for placeholder ID '${identifier}'` );

                    el.innerHTML = '';
                }
            });
        });
    };

    loadContent('wp-block-avorg-block-placeholder');
}