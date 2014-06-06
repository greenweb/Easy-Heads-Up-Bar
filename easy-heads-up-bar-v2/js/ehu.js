// // heads up bar
// jQuery(document).ready(function($) {
// 	$('div#ehu-bar').css({
// 		'position': 'fixed',
// 		'top': '0',
// 		'left': '0',
// 		'z-index' : 100001,
// 		'width' : '100%'
// 	});
// 	$('div#ehu-bar').click(function () {
// 		$(this).hide('slow', function() {
			
// 		});
// 	});
// });

jQuery(document).ready(function($) {
	var $ehuBar = $('div#ehu-bar');
	$ehuBar.remove();
	$linkColor = $ehuBar.attr('data-bar-link-color');
	$barLocatoin = $ehuBar.attr('data-bar-location');
	$('div#ehu-bar a').css({'color':$linkColor});
	if ($barLocatoin=='top') {
		$ehuBar.prependTo('html');
	}else{
				$ehuBar.appendTo('html');
	};
});