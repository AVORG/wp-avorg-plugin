window.addEventListener('load', function () {
	console.log(avorg_scripts);
	for (var i = 0; i < avorg_scripts.urls.length; i++) {
		System.import( avorg_scripts.urls[i] );
	}
}, false);