$(function() {
	var $root = $('html, body');
	$('a[href^="#"].smooth-scroll').click(function(){
		// if($(this).hasClass('smooth-scroll')){
			event.preventDefault();
			$root.animate({
				scrollTop: $($.attr(this, 'href')).offset().top
			},500);
			return false;
		// } else {
		// 	return true;
		// }
	});
});