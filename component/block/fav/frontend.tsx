window.onload = () => {
    console.log('fav frontend');

    const blocks = document.querySelectorAll('.wp-block-avorg-block-fav'),
        id = window.avorg.query.entity_id,
        url = `/wp-json/avorg/v1/favorites?presentationId=${id}`;

    function setInitialState(was_favorited_on_load: boolean) {
        if (was_favorited_on_load) {
            blocks.forEach((el: Element) => {
                el.classList.add('faved');
                el.classList.remove('loading');
            })
        }
    }

    function handleClick(el: Element) {
        if (el.classList.contains('faved')) {
            el.classList.remove('faved');
        } else {
            el.classList.add('faved');
        }
    }

    function setClickHandlers() {
        blocks.forEach((el: Element) => {
            el.addEventListener('click', () => handleClick(el));
        });
    }

    fetch(url)
        .then(res => res.json())
        .then((was_favorited_on_load) => {
            console.log('update', was_favorited_on_load);
            setInitialState(was_favorited_on_load);
            setClickHandlers();
        });
};

