const molecule_mediaObject = function(
    title: string,
    titleUrl?: string,
    secondLine?: string,
    imgUrl?: string,
    imgAlt?: string
): any {
    const titleElement = <h4 className="avorg-molecule-mediaObject__title">{title}</h4>;

    return <div className="avorg-molecule-mediaObject">
    {imgUrl ? <img className="avorg-molecule-mediaObject__image" src={imgUrl} alt={imgAlt} /> : ''}
    <div className="avorg-molecule-mediaObject__text">
        {titleUrl ? <a href={titleUrl}>{titleElement}</a> : titleElement}
        {secondLine}
    </div>
</div>;
};

export default molecule_mediaObject