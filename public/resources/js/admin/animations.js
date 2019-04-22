$(document).ready(function(){

	$('.swipe-helper').on('swiperight', function(){
		$('.sidebar').animate({
			'left': 0
		}, 300);
		$('.content-display').animate({
			'width': 'auto',
			'margin-left': 'initial'
		}, 300);
	});

	$('.sidebar').on('swipeleft', function(){
		$('.sidebar').animate({
			'left': -400
		}, 300);
		$('.content-display').animate({
			'width': '100%',
			'margin-left': 0
		}, 300);

	});

	$('.products-table th').dblclick(function(){
		var classname = this.className;
		$('.' + classname ).fadeOut(300);
		//alert(classname);
	});
});