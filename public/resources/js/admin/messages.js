//functions that display different messages

//show image warnings
function imageWarnings(){
	$('.warnings').stop().animate({
		maxHeight: 100,
	   	opacity: 1
	}, 300)
	.delay(7000)
	.animate({
	    maxHeight: 0,
	    opacity: 0
	}, 300);
}

//Show message when a product is added/updated
function infoMessage(text, classToBeAdded ){
	$('.infoMessages .text').text(text);

	if(classToBeAdded === 'alert-success'){
		$('.infoMessages').removeClass('alert-danger').addClass('alert-success');
		$('.infoMessages .icon span').removeClass('glyphicon glyphicon-exclamation-sign')
										.addClass('glyphicon glyphicon-ok');

	}
	else if(classToBeAdded === 'alert-danger'){
		$('.infoMessages').removeClass('alert-success').addClass('alert-danger');
		$('.infoMessages .icon span').removeClass('glyphicon glyphicon-ok')
										.addClass('glyphicon glyphicon-exclamation-sign');
	}

	//actual animation
	$('.infoMessages').stop()
		.animate({
			top: 10,
			right: 15,
			opacity: 1,
			maxHeight: 250
		}, 400)
		.delay(7000)
		.animate({
			top: 5,
			right: 5,
			opacity: 0.25,
			maxHeight: 0
		}, 200);
}