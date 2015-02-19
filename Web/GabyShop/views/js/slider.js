// JavaScript Document
$(document).ready(function(){
		
	var container = $('#collection');
	var list = $('#collection ul');
	var itemWidth = $('#collection ul li').outerWidth(true);
	var items = list.children().size();
	
	var fullWidth =  items * itemWidth + 'px';
	$('#collection ul').css({width: fullWidth});
	
	var point = 0;
	var speed = 0;
	var topSpeed = 8000;
	
	addHandler();
	
	function addHandler() {
		//sliding
		if (list.width() > container.width()) {
			$('#collection').mousemove(function(e) {
				var cWidth = container.width();
				var middle = cWidth / 2;
				
				if (e.pageX > middle) {
					speed = Math.abs(e.pageX - cWidth) / middle;
					point = cWidth - list.width();
				} else {
					speed = e.pageX / middle;
					point = 0;
				}
				
				if (e.pageX > middle + 200 || e.pageX < middle - 200) {
					list.stop().delay(100).animate(
						{ left: point },
						speed * topSpeed,
						'linear'
					);
				} else {
					list.stop(true, false);
				}
				
			});
			$('#collection').mouseleave(function() {
				list.stop(true, false);
			});
		}
		
		// re-calculate window height for model photos
	//	setHeight();
	}

   
});
