const molecule_mediaObject = function(
    title: string,
    secondLine?: string,
    imgUrl?: string,
    imgAlt?: string
): string {
    const image = imgUrl ? `<img class="avorg-molecule-mediaObject__image" src="${imgUrl}" alt="${imgAlt}" />` : '';

    return `<li class="avorg-molecule-mediaObject">
    ${image}
    <div class="avorg-molecule-mediaObject__text">
        <h4 class="avorg-molecule-mediaObject__title">${title}</h4>
        ${secondLine}
    </div>
</li>`;
};

export default molecule_mediaObject