/*
 *Powered by:iisquare.com
 */

(function () {
	var bPause = false;

	$('#sec').bind('click', function () {
		bPause = !bPause;
	});

	(function lazyGoBack() {
		var sec = parseInt($('#sec').text());
		!bPause && $('#sec').text(--sec);
		if (sec > 0) {
			setTimeout(lazyGoBack, 1000);
		} else {
			window.history.back();
		}
	})();
})();

