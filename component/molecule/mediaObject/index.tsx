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

    const prepareSecondLine = (secondLine: string) => {
        const div = document.createElement("div");
        div.innerHTML = secondLine;
        const text = div.textContent || div.innerText || '';

        return text.length <= 200 ? text : text.substring(0, 200) + '...';
    };

    return <div className="avorg-molecule-mediaObject">
        {
            imgUrl
                ? <img className="avorg-molecule-mediaObject__image" src={imgUrl} alt={imgAlt} onError={hideImage}/>
                : ''
        }
        <div className="avorg-molecule-mediaObject__text">
            {titleUrl ? <a href={titleUrl}>{titleElement}</a> : titleElement}
            {secondLine ? <div className="avorg-molecule-mediaObject__description">
                {prepareSecondLine(secondLine)}
            </div> : ''}
        </div>
    </div>;
};

export default molecule_mediaObject