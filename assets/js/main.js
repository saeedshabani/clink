
/*
Plugin name: Clink - Countdown then Redirect links
Plugin URI: http://aryanthemes.com
Version: 1.0
Author: Aryan Themes
Author URI: http://aryanthemes.com
License: GPLv2 or later
*/

$(function(){
	var redirect_func = function(target){
		$(location).attr('href', target);
	};	
	countdown_duration = countdown_duration;
	redirect_target_url = redirect_target_url ;
	$("#countdown").countdown360({
		radius      : 100,
		seconds     : countdown_duration,
		strokeWidth : 2,
		//fillStyle   : '#dfdfdf',
		fillStyle   : 'transparent',
		strokeStyle : '#17b4e9',
		strokeStyle : '#17b4e9',
		fontSize    : 40,
		fontColor   : '#555',
		fontFamily: "Helvetica",
		fontWeight: 'normal',  		
		autostart: false,
		label: false,
		onComplete  : function () {
			console.log('completed');
			redirect_func(redirect_target_url);	
		}
	}).start();
	
	var CTR_responsive = function(){
		var container_el = $('.container');
		var redirect_link_container_height =  $(container_el).height();
		$(container_el).css('marginTop', '-' + ( redirect_link_container_height / 2 ) + 'px' );	
	};
	
	CTR_responsive();
	
	$(window).resize(function(){
		CTR_responsive();
	});
});