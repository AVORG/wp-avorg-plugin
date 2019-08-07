namespace AvorgPlaceholder {
    interface bit {
        meta: {
            avorgBitIdentifier?: string
        }
    }

    const loadContent = function (className: string) {
        const elements = document.querySelectorAll(`.${className}`);

        if (!elements) return;

        const url = `/wp-json/wp/v2/avorg-content-bits`;
        fetch(url).then(response => {
            return response.json();
        }).then(response => {
            elements.forEach(el => {
                const identifier: string = el.getAttribute('data-id'),
                    matches = response.filter((item: bit) => {
                        return item.meta.avorgBitIdentifier === identifier
                    }),
                    i = Math.floor(Math.random() * matches.length);

                el.innerHTML = matches[i].content.rendered
            });
        });
    };

    loadContent('wp-block-avorg-block-placeholder');
}