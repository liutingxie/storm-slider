(function($) {
	$.fn.stormTransition = function(options) {

		 return this.each(function() {

		 	if(typeof options === 'string') {

		 		var tpData = $(this).data('transitionPreview');

			 	switch(options) {
			 		case 'forceStop' :
			 		tpData.stop();
			 		break;
			 	}
		 	}
		 	else {
		 		new transitionPreview(this, options);
		 	}

		 });
	};

	var transitionPreview = function(el, options) {
		var tp = this;
		$(el).data('transitionPreview', tp);

		tp.init = function() {

			var settings = $.extend({
				width: 300,
				height: 150,
				transitionType: '3d',
				transitionObject: null,
				imgPath: '../image/',
				delay: 1
			}, options);

			//Add slider HTML markup
			// $(el).append( $('<div>', { 'class' : 'transitionpreview', 'style' : 'width: '+settings.width+'px; height: '+settings.height+'px;'})
			// 	.append( $('<div>', { 'class' : 'storm-layer', 'data-option' : 'slidedelay: '+settings.delay+';'})
			// 		.append( $('<img>', { 'src' : ''+settings.imgPath+'slide1-preview.png', 'class' : 'storm-bg'})))
			// 	.append( $('<div>', { 'class' : 'storm-layer', 'data-option' : 'slidedelay: '+settings.delay+';'})
			// 		.append( $('<img>', { 'src' : ''+settings.imgPath+'slide2-preview.png', 'class' : 'storm-bg'})))
			// );

			var $elContainer = $(el).append($('<div>', {'class' : 'transitionpreview'}));
			$('<div>', {'class' : 'storm-inner-parent', 'style' : 'width: '+settings.width+'px; height: '+settings.height+'px;'}).append( $('<div>', { 'class' : 'storm-layer', 'data-option' : 'slidedelay: '+settings.delay+';'})
					.append( $('<img>', { 'src' : ''+settings.imgPath+'slide1-preview.png', 'class' : 'storm-bg'})))
				.append( $('<div>', { 'class' : 'storm-layer', 'data-option' : 'slidedelay: '+settings.delay+';'})
					.append( $('<img>', { 'src' : ''+settings.imgPath+'slide2-preview.png', 'class' : 'storm-bg'}))).appendTo($(el).find('.transitionpreview'));

			//Initialize the slider
			$(el).find('.transitionpreview').stormSlider({
				showNavigationButton: !1,
				showCircleTimer: !1,
				showStartStopButton: !1,
				showThumbButton: !1,
				showThumbImg: !1,
				showBarTimer: !1,
				pauseHover: !1,
				slideTransition: {
					type: settings.transitionType,
					obj: settings.transitionObject
				}
			});
		};

		tp.stop = function() {
			$(el).find('.transitionpreview').stormSlider('forceStop').remove();
		};

		tp.init();
	};
})(jQuery);