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

    function unFavorite(el: Element) {
        el.classList.remove('faved');
        fetch(url, {method: 'DELETE'})
            .then(res => res.json())
            .then((res) => {
                console.log('fav deleted', res);
                if (!res) {
                    el.classList.add('faved');
                }
            });
    }

    function addFavorite(el: Element) {
        el.classList.add('faved');
        fetch(url, {method: 'POST'})
            .then(res => res.json())
            .then((res) => {
                console.log('fav created', res);
                if (!res) {
                    el.classList.remove('faved');
                }
            });
    }

    function handleClick(el: Element) {
        if (!window.avorg.session.email) {
            alert('Please log in before performing this action.');
            return;
        }

        if (el.classList.contains('faved')) {
            unFavorite(el);
        } else {
            addFavorite(el);
        }
    }

    function setClickHandlers() {
        blocks.forEach((el: Element) => {
            el.addEventListener('click', () => handleClick(el));
        });
    }

    setClickHandlers();

    if (window.avorg.session.email) {
        fetch(url)
            .then(res => res.json())
            .then((was_favorited_on_load) => {
                console.log('update', was_favorited_on_load);
                setInitialState(was_favorited_on_load);

            });
    }
};

