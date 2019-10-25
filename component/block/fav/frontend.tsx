console.log('fav frontend');

const block = document.querySelector('.wp-block-avorg-block-fav');

console.log(block);

block.addEventListener('click', function() {
    this.classList.toggle('faved');
});