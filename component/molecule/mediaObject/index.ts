const molecule_mediaObject = function(
    title: string,
    titleUrl?: string,
    secondLine?: string,
    imgUrl?: string,
    imgAlt?: string
): string {
    const image = imgUrl ? `<img class="avorg-molecule-mediaObject__image" src="${imgUrl}" alt="${imgAlt}" />` : '',
        titleLinkStart = titleUrl ? `<a href="${titleUrl}">` : '',
        titleLinkEnd = titleUrl ? '</a>' : '';

    return `<li class="avorg-molecule-mediaObject">
    ${image}
    <div class="avorg-molecule-mediaObject__text">
        ${titleLinkStart}
        <h4 class="avorg-molecule-mediaObject__title">${title}</h4>
        ${titleLinkEnd}
        ${secondLine}
    </div>
</li>`;
};

export default molecule_mediaObject