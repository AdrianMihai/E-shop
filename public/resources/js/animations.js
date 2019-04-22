//variable used for keeping track of notification panel timeout id
var notifPanelTimeout;

//function that 
function prepareCartMessage(notifPanel, productName, productImage, productAction){
	notifPanel.find(".notification-image img").attr({
		src: productImage
	});

	notifPanel.find('.text p:first-child').text(productName);
	notifPanel.find('.text p:nth-child(2)').empty().append(productAction);

	notifPanel.addClass('visible');

	notifPanelTimeout = setTimeout(
		function() {
			notifPanel.removeClass('visible');
		}, 8000
	);
}

//function that displays a message whenever the user wants to add/remove a product to/from the cart
function showCartMessage(productName, productImage, productAction){
	/*$('.notification-panel')
		.stop()
		.animate({
			height: 0,
			top: 72,
			opacity: 0.4
		}, 300, function(){
			$('.notification-panel .notification-image img').attr({
				src: productImage
			});
			$('.notification-panel .text p:first-child').text(productName);
			$('.notification-panel .text p:nth-child(2)').empty().append(productAction);
			$('.notification-panel').animate({
				height: 50,
				top: 77,
				opacity: 1
			}, 300)
			.delay(8000)
			.animate({
				height: 0,
				top: 72,
				opacity: 0.4
			}, 300);
		});*/

	var notifPanel = $('.notification-panel');

	if(typeof notifPanelTimeout !== undefined){
		
		clearTimeout(notifPanelTimeout);	
	}

	notifPanel.queue(function(next){
		notifPanel.stop().removeClass('visible', 500, function(){
			prepareCartMessage(notifPanel, productName, productImage, productAction);
		});
		
		next();
	});
}

function show_cart(){
	$(".cart-contents").animate({
		maxHeight: 55,
		scrollTop: 0
	}, 400);
	$('.footer').animate({
		paddingBottom: 70
	},400);
	
}
function hide_cart(){
	$(".cart-contents").animate({
		maxHeight: 0
	}, 400);
	$('.footer').animate({
		paddingBottom: 25
	},400);
}

$(document).ready(function(){
	var cat_ok = false, cart_ok = false;
	var theme_color = '#ED9B42';

	//Toggle the categories
	$(".categories-switch").click(function(){

		$(".categories-container").animate({
			left: "0px"
		},
		300,
		function(){
			cat_ok = true;
		});
	});

	$(".close-categories").click(function(){
		$(".categories-container").animate({
				left: "-270px"
			},
			400,
			function(){
				cat_ok = false;
			});
	});

	//Showing/hiding categories and subcategories
	$('.categories-container .list-unstyled').children().click(function(){
		var _this = $(this);
		if( !_this.data('toggled') ){
			_this.data('toggled', true);

			_this.find('.list-header span:first-child').removeClass('glyphicon-triangle-right')
													   .addClass('glyphicon-triangle-bottom');
			_this.find('.list-header').addClass('list-active');
			
			var child_counter = _this.find('.nested-list a').length;

			_this.find('.nested-list .theme-line').stop().animate({
				top: 29 * (child_counter - 1),
				opacity: 1
			}, 700, function(){
				_this.find('.nested-list .theme-line').animate({
					height: 0
				}, 100);
			});

			_this.find('.nested-list').animate({
				maxHeight: child_counter * 30,
				marginTop: 5,
				opacity: 1

			}, 300, function(){
				
			});
		}
		else{
			_this.data('toggled', false); 	

			_this.find('.list-header span:first-child').removeClass('glyphicon-triangle-bottom')
													   .addClass('glyphicon-triangle-right');

			_this.find('.list-header').removeClass('list-active');									   
			
			_this.find('.nested-list .theme-line').stop().animate({
				top: 0,
				height: 29
			}, 150, function(){
				_this.find('.nested-list .theme-line').animate({
					opacity: 0.5
				}, 100);
			});
			_this.find('.nested-list').animate({
				maxHeight: 0,
				marginTop: 0,
				opacity: 0.25

			}, 400, function(){
				
			});
			
		}

	});

	//Toggle the shopping cart
	$(".toggle-cart").click(function(){
		if(!$('.cart-contents').data('opened')){

			$('.cart-contents').data('opened', true);

			$(".toggle-cart").removeClass("glyphicon-menu-up", 200, function(){
				$(".toggle-cart").addClass("glyphicon-menu-down", 200);
			});

			$(".cart-contents").animate({
				maxHeight: $('.cart-contents').data('maxheight')

			}, 400, function(){
				$(".cart-contents").css("overflow-y", "auto");
				$('#cartDragBar').css('cursor', 'row-resize');
			});
			
		}
		else{
			$('.cart-contents').data('opened', false);
			$(".toggle-cart").removeClass("glyphicon-menu-down", 200, function(){
				$(".toggle-cart").addClass("glyphicon-menu-up", 200);
			});
			$(".cart-contents").animate({
				maxHeight: "55px",
				scrollTop: 0

			}, 400, function(){
				$(".cart-contents").css("overflow-y", "hidden");
				$('#cartDragBar').css('cursor', 'default');
			});
		}
	});

	//Hover for product review
	$(document).on('mouseenter', '.product .image-container', function(){
		var over_layer = $(this).find(".over-layer");
		var review = $(this).find(".product-review");
		review.stop().fadeIn(300);
		over_layer.stop().animate({
			width: $(this).outerWidth() - 8,
			height: $(this).outerHeight() - 8,
			top: 4,
			left:4
		}, 300);
	});

	$(document).on('mouseleave', '.product .image-container', function(){
		var over_layer = $(this).find(".over-layer");
		var review = $(this).find(".product-review");
		review.stop().fadeOut(300);
		over_layer.stop().animate({
			width: 0,
			height: 0,
			top: "50%",
			left: "50%"
		}, 300);
	});

	/*$(".product .image-container").hover(function(){
		var over_layer = $(this).find(".over-layer");
		var review = $(this).find(".product-review");
		review.stop().fadeIn(300);
		over_layer.stop().animate({
			width: $(this).outerWidth() - 8,
			height: $(this).outerHeight() - 8,
			top: 4,
			left:4
		}, 300);

	}, function(){
		var over_layer = $(this).find(".over-layer");
		var review = $(this).find(".product-review");
		review.stop().fadeOut(300);
		over_layer.stop().animate({
			width: 0,
			height: 0,
			top: "50%",
			left: "50%"
		}, 300);
	});*/
	/*Scroll event for setting the cart's summary top position
				var initial_pos = $('.summary-container').offset();
			
				var scrolled_too_much = false; 

				$(document).scroll(function(){
					var el = $('.summary-container');
					if(el.css('position') != 'static'){
						var position = el.offset();
						
						if(position.top >= initial_pos.top + 62 && !scrolled_too_much ){
							el.animate({
								top: 79
							}, 200);
							scrolled_too_much = true;
						}

						if(position.top < initial_pos.top && scrolled_too_much){
							el.animate({
								top: initial_pos.top
							}, 200);
							scrolled_too_much = false; 
						}
					}
				});*/
});
	