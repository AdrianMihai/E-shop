//
$(document).ready(function(){
	var finished_animation = true;

	$('*[data-list="nested"] li .list-header').click(function(){
		var _this = $(this).parent("li");

			//Get the height of each element in the nested list
			var height = _this.data('maxheight') ? _this.data('maxheight') : _this.find('.nested-list li').outerHeight();
			console.log(height);

			//Get the value of top margin in pixels that needs to be left for the elements in the nested list
			//From the data-margin attribute
			var margin = parseInt( $(this).parent("li").parent("ul").data("margin") );
			margin = margin ? margin : 0;
			//console.log(margin);
			
			if( !_this.data('toggled') ){
				if(finished_animation){
					_this.data('toggled', true);
					finished_animation = false;

					if( _this.data('icon') === true ){
						_this.find('.list-header span:first-child').removeClass('glyphicon-triangle-right')
														   .addClass('glyphicon-triangle-bottom');
					}
					
					_this.find('.list-header').addClass('list-active');
					
					var child_counter = _this.find('.nested-list li').length;

					_this.find('.nested-list .sliding-line').stop().animate({
						top: (height + margin) * (child_counter - 1) + margin,
						opacity: 1
					}, 700, function(){
						_this.find('.nested-list .sliding-line').animate({
							height: 0
						}, 100, function(){
							finished_animation = true;
						});
					});

					_this.find('.nested-list').animate({
						maxHeight: child_counter * (height + margin),
						marginTop: 5,
						opacity: 1

					}, 300);
				}
			}
			else{
				_this.data('toggled', false);
				finished_animation = false;

				_this.find('.list-header span:first-child').removeClass('glyphicon-triangle-bottom')
													   .addClass('glyphicon-triangle-right');

				_this.find('.list-header').removeClass('list-active');									   
				
				_this.find('.nested-list .sliding-line').stop().animate({
					top: 0,
					height: height
				}, 200, function(){
					_this.find('.nested-list .sliding-line').animate({
						opacity: 0.5
					}, 100);
				});
				_this.find('.nested-list').animate({
					maxHeight: 0,
					marginTop: 0,
					opacity: 0.25

				}, 300, function(){
					finished_animation = true;
				});
			}
		
	});

	jQuery.fn.extend({
		nestingListShow : function(indexes, callback){
			return this.each(function(){
				//Get the value of top margin in pixels that needs to be left for the elements in the nested list
				//From the data-margin attribute
				var margin = parseInt($(this).data("margin")) ? parseInt($(this).data("margin")) : 0;

				//Get the children of the list (not the nested one)
				var _this = $(this).children("li");

				if(Array.isArray(indexes)){
					for (var i = indexes.length - 1; i >= 0; i--) {
						//Get the height of each element in the nested list
						var height = $(_this[indexes[i]]).find('.nested-list li').outerHeight();
						
						$(_this[indexes[i]]).data('toggled', true);

						//console.log(height + " " +  margin);

						if( $(_this[indexes[i]]).data('icon')){
							$(_this[indexes[i]]).find('.list-header span:first-child').removeClass('glyphicon-triangle-right')
									.addClass('glyphicon-triangle-bottom');
						}
						
			            
						$(_this[indexes[i]]).find('.list-header').addClass('list-active');
						
						//Count how many elements each nested list has
						var child_counter = $(_this[indexes[i]]).find('.nested-list li').length;

						//Select the sliding line then animate it
						var sliding_line = $(_this[indexes[i]]).find('.nested-list .sliding-line');
						
						sliding_line.stop().animate({
							top: (height + margin) * (child_counter - 1) + margin,
							opacity: 1
						}, 700, function(){
							sliding_line.animate({
								height: 0
							}, 100, function(){
								if(typeof callback === "function")
									callback();
							});
						});

						$(_this[indexes[i]]).find('.nested-list').animate({
							maxHeight: child_counter * (height + margin),
							marginTop: 5,
							opacity: 1

						}, 300);
					};
				}
				else if(typeof indexes != "undefined"){
					console.log(_this);
				}
				
			});
		},
		nestingListHide : function(indexes, callback){
			return this.each(function(){
				//Get the value of top margin in pixels that needs to be left for the elements in the nested list
				//From the data-margin attribute
				var margin = parseInt($(this).data("margin")) ? parseInt($(this).data("margin")) : 0;

				//Get the children of the list (not the nested one)
				var _this = $(this).children("li");
				if(Array.isArray(indexes)){
					for (var i = indexes.length - 1; i >= 0; i--) {
						var listElement = $(_this[indexes[i]]);

						//Get the height of each element in the nested list
						var height = listElement.find('.nested-list li').outerHeight();

						listElement.data('toggled', false);

						listElement.find('.list-header span:first-child').removeClass('glyphicon-triangle-bottom')
															   .addClass('glyphicon-triangle-right');

						listElement.find('.list-header').removeClass('list-active');									   
						
						listElement.find('.nested-list .sliding-line').stop().animate({
							top: 0,
							height: height
						}, 200, function(){
							listElement.find('.nested-list .sliding-line').animate({
								opacity: 0.5
							}, 100);
						});
						listElement.find('.nested-list').animate({
							maxHeight: 0,
							marginTop: 0,
							opacity: 0.25

						}, 300, function(){
							if(typeof callback === 'function')
								callback();
						});
					};
				}
				
			});
		}
	});

});