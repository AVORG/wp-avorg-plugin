import molecule_mediaObject from "../../molecule/mediaObject/index.js"

declare const wp: any;

console.log(wp, wp.element);

const blocks = wp.blocks;
const element = wp.element;
const el = element.createElement;
const blockStyle = {
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

const prepareId = function(id: string) {
	return 'avorg-' + id.replace(/-/g,'')
};

const itemTemplate = function(recording: Recording) {
	const imageUrl = recording.presenters[0] ? recording.presenters[0].photo : null;
	const imageAlt = recording.presenters[0] ?
		`${recording.presenters[0].name.first} ${recording.presenters[0].name.last} ${recording.presenters[0].name.suffix}` : null;
	const presenters = recording.presenters.map((presenter) =>
		`${presenter.name.first} ${presenter.name.last} ${presenter.name.suffix}`).join(", ");

	return molecule_mediaObject(recording.title, presenters, imageUrl, imageAlt);
};

const loadRecordings = function(id: string) {
	const url = 'http://localhost:8000/api/related/20047';
	fetch(url).then(response => {
		return response.json();
	}).then(response => {
		const el = document.querySelector('#'+id);
		const recordings = getRandomSubarray(Object.values( response ), 3);

		el.innerHTML = recordings.map( itemTemplate ).join( "" );
	});
};

blocks.registerBlockType( 'avorg/block-relatedsermons', {
	title: 'Example: Basic',
	icon: 'universal-access-alt',
	category: 'layout',
	edit: function(props: any) {
		console.log('hello back', props);

		const id = prepareId(props.clientId);

		loadRecordings(id);

		return el(
			'p',
			{ style: blockStyle, id: id },
			'Loading...'
		);
	},
	save: function(props: any) {
		console.log('hello front');

		return el(
			'p',
			{ style: blockStyle },
			// { style: blockStyle, id: prepareId(props.clientId) },
			'Loading...'
		);
	},
} );
