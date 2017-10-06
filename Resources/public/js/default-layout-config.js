$(document).ready(function(){
	$('input').iCheck({
		checkboxClass: 'icheckbox icheckbox_minimal-blue',
		radioClass: 'iradio iradio_minimal',
		increaseArea: '20%' // optional
	});
});
$.fn.select2.defaults.set( "theme", "bootstrap" );
layout = {};	// Container for custom things