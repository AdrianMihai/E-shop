<style type="text/css">
  .message{
    border-left: 2px solid #48A14D;
  }
</style>

<div class="row">
	<div class="message col-xs-12 col-sm-offset-2 col-sm-8"> 
		<h3> Order successfully placed ! </h3>

	</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){
        var progressBars = $('.progress .progress-bar');
        var roundedIcon = $('.rounded-icon');

        //set the transition time to 0 seconds for the first two
        //progress bars so there is no animation happening
        progressBars.eq(0).css('transition-duration', '0s');
        progressBars.eq(1).css('transition-duration', '0s');

        progressBars.eq(0).css('width', '100%');
        roundedIcon.eq(1).addClass('active-icon');

        progressBars.eq(1).css('width', '100%');
        roundedIcon.eq(2).addClass('active-icon');

        progressBars.eq(2).animate({
            width: '100%'
        }, 450, function(){

            roundedIcon.eq(3).addClass('active-icon');
        });

	});
</script>