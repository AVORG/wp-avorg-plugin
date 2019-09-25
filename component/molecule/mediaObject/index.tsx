import * as React from 'react';

const molecule_mediaObject = function (
    title: string,
    titleUrl?: string,
    secondLine?: string,
    imgUrl?: string,
    imgAlt?: string
): any {
    const titleElement = <h4 className="avorg-molecule-mediaObject__title">{title}</h4>;

    const hideImage = (e: React.SyntheticEvent) => {
        const target = e.target as HTMLImageElement;

        target.style.display = "none";
    };

    return <div className="avorg-molecule-mediaObject">
        {
            imgUrl
                ? <img className="avorg-molecule-mediaObject__image" src={imgUrl} alt={imgAlt} onError={hideImage}/>
                : ''
        }
        <div className="avorg-molecule-mediaObject__text">
            {titleUrl ? <a href={titleUrl}>{titleElement}</a> : titleElement}
            {secondLine}
        </div>
    </div>;
};

export default molecule_mediaObject