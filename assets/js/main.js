jQuery(function(){
	var redirect_func = function(target){
		jQuery(location).attr('href', target);
	};	
	countdown_duration = countdown_duration;
	redirect_target_url = redirect_target_url ;
	jQuery("#countdown").countdown360({
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
		var container_el = jQuery('.container');
		var redirect_link_container_height =  jQuery(container_el).height();
		jQuery(container_el).css('marginTop', '-' + ( redirect_link_container_height / 2 ) + 'px' );	
	};
	
	CTR_responsive();
	
	jQuery(window).resize(function(){
		CTR_responsive();
	});
});