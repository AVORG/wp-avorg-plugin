( function( blocks, element ) {
	var el = element.createElement;

	var blockStyle = {
		backgroundColor: '#900',
		color: '#fff',
		padding: '20px',
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
			});

			return el(
				'p',
				{
					style: blockStyle,
					id: id
				},
				'Hello World, step 1 (from the editor).'
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
	window.wp.blocks,
	window.wp.element
) );