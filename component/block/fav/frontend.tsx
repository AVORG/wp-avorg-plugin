console.log('fav frontend');

const block = document.querySelector('.wp-block-avorg-block-fav');

window.onload = () => {
    console.log(block);

    const id = window.avorg.query.entity_id,
        url = `/wp-json/avorg/v1/favorites?presentationId=${id}`;

    let was_favorited_on_load = null;

    fetch(url)
        .then(res => res.json())
        .then((data) => {
            was_favorited_on_load = data;
            console.log('update', was_favorited_on_load);
            if (was_favorited_on_load) {
                block.classList.add('faved');
            }
        });

    console.log(id, url, was_favorited_on_load);

    block.addEventListener('click', function() {
        this.classList.toggle('faved');
    });
};

