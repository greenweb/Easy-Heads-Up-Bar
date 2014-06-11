jQuery(document).ready(function($) {
	var $ehuBar = $('div#ehu-bar');
	var $ehuBarLinks = $('div#ehu-bar a');
	$ehuBar.remove();
	$linkColor = $ehuBar.attr('data-bar-link-color');
	$barLocatoin = $ehuBar.attr('data-bar-location');
	
	if ($barLocatoin=='top') {
		$ehuBar.prependTo('body');
	}else{
				$ehuBar.appendTo('body');
	};
	$ehuBarLinks.css({'color':$linkColor});

	// close button
	var $dhuCloseButton = $('#ehu-close-button');
	var $dhuCloseButtonPosition = $dhuCloseButton.position();
	
	// Open button
	var $dhuOpenButton = $('#ehu-open-button');
	if ($barLocatoin=='top') 
	{
		$dhuOpenButton.css({
			'top': $dhuCloseButtonPosition.top,	
		});
	}else{
		$dhuOpenButton.css({
			'bottom': $dhuCloseButtonPosition.bottom,	
		})
	};
	$dhuOpenButton.css({
		'right': $dhuCloseButtonPosition.right,	
	});

	// hide action
	$dhuCloseButton.click(function() {
	  $( this ).parent().slideUp( "fast", function() {
	    	$dhuOpenButton.css({
					'visibility': 'visible'	
				});
	  });
	});

	// hide action
	$dhuOpenButton.click(function() {
		$dhuOpenButton.css({'visibility': 'hidden'	});
	  $ehuBar.slideDown( "fast", function() {
			
			if ($barLocatoin=='top') 
			{
				window.scrollTo(0,0);
			}else{
				window.scrollTo(0,$dhuCloseButtonPosition.top);
			};
	  });
	});

});