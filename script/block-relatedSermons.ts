( function( blocks, element ) {
	var el = element.createElement;

	var blockStyle = {
		// backgroundColor: '#900',
		// color: '#fff',
		// padding: '20px',
	};

	function getRandomSubarray(arr: any[], size: number) {
		var shuffled = arr.slice(0), i = arr.length, temp, index;
		while (i--) {
			index = Math.floor((i + 1) * Math.random());
			temp = shuffled[index];
			shuffled[index] = shuffled[i];
			shuffled[i] = temp;
		}
		return shuffled.slice(0, size);
	}

	interface Recording {
		id: number;
		title: string;
		presenters: {
			photo: string;
			name: {
				first: string;
				last: string;
				suffix: string;
			};
		}[];
		videoFiles: {

		}[];
	}

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

	const itemTemplate = function(recording: Recording) {
		const imageUrl = recording.presenters[0] ? recording.presenters[0].photo : null;
		const imageAlt = recording.presenters[0] ?
			`${recording.presenters[0].name.first} ${recording.presenters[0].name.last} ${recording.presenters[0].name.suffix}` : null;
		const presenters = recording.presenters.map((presenter) =>
			`${presenter.name.first} ${presenter.name.last} ${presenter.name.suffix}`).join(", ");

		return molecule_mediaObject(recording.title, presenters, imageUrl, imageAlt);
	};

	blocks.registerBlockType( 'avorg/block-relatedsermons', {
		title: 'Example: Basic',
		icon: 'universal-access-alt',
		category: 'layout',
		edit: function() {
			console.log('hello back');

			const id = 'avorg-block-relatedSermons-' + Math.floor(Math.random() * Math.floor(1000));

			const url = 'http://localhost:8000/api/related/20047';
			fetch(url).then(response => {
				return response.json();
			}).then(response => {
				console.log(response);
				const el = document.querySelector('#'+id);
				console.log(el);
				el.textContent = 'Data loaded';
				const recordings = getRandomSubarray(Object.values( response ), 3);
				el.innerHTML = recordings.map( itemTemplate ).join( "" );
			});

			return el(
				'p',
				{
					style: blockStyle,
					id: id
				},
				'Loading...'
			);
		},
		save: function() {
			console.log('hello front');

			return el(
				'p',
				{ style: blockStyle },
				'Hello World, step 1 (from the frontend).'
			);
		},
	} );
}(
	(<any> window).wp.blocks,
	(<any> window).wp.element
) );