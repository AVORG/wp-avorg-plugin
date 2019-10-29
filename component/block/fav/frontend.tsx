window.onload = () => {
    console.log('fav frontend');

    const blocks = document.querySelectorAll('.wp-block-avorg-block-fav'),
        id = window.avorg.query.entity_id,
        url = `/wp-json/avorg/v1/favorites?presentationId=${id}`;

    function setInitialState(was_favorited_on_load: boolean) {
        blocks.forEach((el: Element) => {
                if (was_favorited_on_load) {
                    el.classList.add('faved');
                } else {
                    el.classList.remove('faved');
                }
                el.classList.remove('loading');
            }
        );
    }

    function handleClick(el: Element) {
        el.classList.add('loading');
        if (el.classList.contains('faved')) {
            fetch(url, {method: 'DELETE'})
                .then(res => res.json())
                .then((res) => {
                    console.log('fav deleted', res);
                    el.classList.remove('loading');
                    el.classList.remove('faved');
                });
        } else {
            fetch(url, {method: 'POST'})
                .then(res => res.json())
                .then((res) => {
                    console.log('fav created', res);
                    el.classList.remove('loading');
                    el.classList.add('faved');
                });
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

