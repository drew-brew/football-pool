function resizeNavText() {
	var $body = $('body');

	var setBodyScale = function() {
		var scaleSource = $body.width(), scaleFactor = 0.09, maxScale = 200, minScale = 25;
		//alert($body.width());
		var fontSize = scaleSource * scaleFactor;

		if (fontSize > maxScale)
			fontSize = maxScale;
		if (fontSize < minScale)
			fontSize = minScale;

		$('#topNav').css('font-size', fontSize + '%');
	}

	$(window).resize(function() {
		setBodyScale();
	});

	setBodyScale();
};