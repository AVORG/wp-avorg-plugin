window.addEventListener('load', function () {
	console.log(avorg_sys);
	for (var i = 0; i < avorg_sys.urls.length; i++) {
		System.import( avorg_sys.urls[i] );
	}
}, false);