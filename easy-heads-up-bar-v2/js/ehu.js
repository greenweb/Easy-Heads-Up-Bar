jQuery(document).ready(function($) {
	var $ehuBar = $('div#ehu-bar');
	var $ehuBarLinks = $('div#ehu-bar a');
	$ehuBar.remove();
	$linkColor = $ehuBar.attr('data-bar-link-color');
	$barLocatoin = $ehuBar.attr('data-bar-location');
	
	if ($barLocatoin=='top') {
		$ehuBar.prependTo('html');
	}else{
				$ehuBar.appendTo('html');
	};
	$ehuBarLinks.css({'color':$linkColor});
});