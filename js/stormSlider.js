(function($) {
	$.fn.stormSlider = function(option) {
		if ((typeof option).match('object|undefined')) {
			return this.each(function() {
				new slider(this, option);
			});
		}
		else {
			if ('defaultInitData' !== option) {
				return this.each(function() {
					var sliderObject = $(this).data('stormSlider');
					if (sliderObject) {
						if ( option == 'forceStop') {
							sliderObject.forcestop();
						}

						if(!sliderObject.global.isAnimating && !sliderObject.global.isLoading) {
							if(typeof option == 'number' && option > 0 && option < sliderObject.global.sliderNum + 1 && option != sliderObject.global.curSliderIndex) {
								sliderObject.change(option);
							}
							else {
								switch (option) {
									case 'prev':
										sliderObject.prev('clicked');
										break;
									case 'next':
										sliderObject.next('clicked');
										break;
									case 'start':
										sliderObject.start();
										break;
									case 'stop':
										sliderObject.stop();
										break;
								}
							}
						}
					}


				});
			}
		}
	};

	var slider = function(el, option) {
		var $this = this;

		$this.$el = $(el).addClass('storm-container');

		$this.$el.data('stormSlider', $this);

		$this.load = function() {
			$this.default = slider.options;
			$this.option = $.extend({}, $this.default, option);
			$this.transition = $.extend({}, slider.transition);
			$this.global = $.extend({}, slider.global);
			$this.slideTransitions = $.extend({}, slider.slideTransitions);
			$this.layerTransitions = $.extend({}, slider.layerTransitions);

			$this.global.ie78 && ($this.option.lazyLoad = !1);

			//Get default transtion data
			if ('undefined' !== typeof transitionDefaultData) {
				$this.transitionDefaultData = $.extend({}, transitionDefaultData);
			}

			//Get custom transition data
			if ('undefined' !== typeof transitionCustomData) {
				$this.transitionCustomData = $.extend({}, transitionCustomData);
			}

			$this.init();
		},

		$this.init = function() {
			$this.global.innerParent = $(el).find('.storm-inner-parent');

			$this.global.innerParent.find('.storm-layer').removeClass('storm-layer').addClass('storm-slide');
			$this.global.innerParent.find('.storm-slide').wrapAll('<div class="storm-inner"></div>');
			$this.global.initWidth = $this.global.innerParent.width();

			$this.sliderResponse();

			//Set background style
			$this.global.inner = $this.global.innerParent.find('.storm-inner');

			$this.option.backgroundImage && $this.global.inner.css({
				background: "url("+ $this.option.backgroundImage + ")",
				backgroundRepeat: $this.option.backgroundRepeat,
				backgroundAttachment: $this.option.backgroundAttachment,
				backgroundPosition: $this.option.backgroundPosition,
				backgroundSize: $this.option.backgroundSize,
			});
			$this.global.inner.css({
				// height: $this.global.height,
				backgroundColor: $this.option.backgroundColor
			});
			$this.option.backgroundColor == 'transparent' && $this.option.backgroundImage === !1 && $this.global.inner.css('background', 'none transparent');

			$this.global.sliderNum = $this.global.inner.find('.storm-slide').length;
			$this.option.randomSliderShow && $this.global.sliderNum > 2 ? ('random' == $this.option.firstSlide) : $this.option.randomSliderShow = !1, 'random' == $this.option.firstSlide && ($this.option.firstSlide = Math.floor(Math.random() * $this.global.sliderNum + 1));
			$this.option.firstSlide = $this.option.firstSlide < $this.global.sliderNum + 1 ? $this.option.firstSlide : 1;
			$this.option.firstSlide =  $this.option.firstSlide < 1 ? 1 : $this.option.firstSlide;
			$this.option.animateFirstSlide && ($this.option.firstSlide = $this.option.firstSlide - 1 == 0 ? $this.global.sliderNum : $this.option.firstSlide - 1);
			$this.global.curSliderIndex = $this.option.firstSlide;
			$this.global.curSlider = $this.global.inner.find(".storm-slide:eq(" + ($this.global.curSliderIndex - 1) + ")");

			//Add navigation button
			$this.option.showNavigationButton && ($this.global.navPrevButton = $('<div>').addClass('storm-nav-prev storm-nav-hide').appendTo($this.global.innerParent).on('click', function(e) {
				e.preventDefault();
				$(el).stormSlider('prev');
			}), $this.global.navNextButton = $('<div>').addClass('storm-nav-next storm-nav-hide').appendTo($this.global.innerParent).on('click', function(e) {
				e.preventDefault();
				$(el).stormSlider('next');
			}));

			if($this.option.showStartStopButton || $this.option.showThumbButton) {
				$this.global.navWrapper = $('<div>').addClass('storm-nav-wrapper storm-nav-hide').appendTo($this.global.innerParent);
			}

			//Add start and stop button
			$this.option.showStartStopButton && ($this.global.startButton = $('<a>').addClass('storm-start').appendTo($this.global.navWrapper).on('click', function(e) {
				e.preventDefault();
				$(el).stormSlider('start');
			}), $this.global.stopButton = $('<a>').addClass('storm-stop').appendTo($this.global.navWrapper).on('click', function(e) {
				e.stopPropagation();
				console.log(1);
				$(el).stormSlider('stop');
			}));

			//Add thumb button
			$this.option.showThumbButton && $this.option.showStartStopButton ? ($this.global.thumbButton = $('<span>').addClass('storm-thumb-wrapper').insertBefore($this.global.stopButton)) : ($this.global.thumbButton = $('<span>').addClass('storm-thumb-wrapper').appendTo($this.global.navWrapper));

			if($this.global.thumbButton) {
				$this.global.inner.find('.storm-slide').each(function(index, el) {

					var $thumb = $('<a>').addClass('storm-thumb-img').appendTo($this.global.thumbButton).on('click', function(e){
							e.preventDefault();
							$this.$el.stormSlider(index + 1);
						});
				});

				$this.global.thumbButton.find('.storm-thumb-img:eq('+ $this.option.firstSlide +')').addClass('storm-thumb-active');
			}

			//Add barTimer
			$this.option.showBarTimer && ($this.global.barTimer = $('<div>').addClass('storm-bar-timer').appendTo($this.global.innerParent));

			//Add circleTimer
			$this.option.showCircleTimer && !$this.global.ie78 && ($this.global.circleTimer = $('<div>').addClass('storm-circletimer-timer').appendTo($this.global.innerParent), $this.global.circleTimer.append($('<div class="storm-circletimer-left"><div class="storm-circletimer-rotate"><div class="storm-circletimer-hider"><div class="storm-circletimer-half"></div></div></div></div><div class="storm-circletimer-right"><div class="storm-circletimer-rotate"><div class="storm-circletimer-hider"><div class="storm-circletimer-half"></div></div></div></div><div class="storm-circletimer-center"></div>')));

			//Listen $this.global.innerParent hover enent
			$this.global.innerParent.on('mouseenter', function() {
				$this.global.innerParent.find('.storm-nav-hide').fadeIn('300');
			}).on('mouseleave', function() {
				$this.global.innerParent.find('.storm-nav-hide').fadeOut('300');
			});

			//Add thumb img
			if($this.option.showThumbImg) {
				$this.global.thumbList = $('<div>').addClass('storm-thumb-list').appendTo($(el)).css('width', $this.global.width);
				$this.global.thumbContainer = $('<div>').addClass('storm-thumbimg-container').appendTo($this.global.thumbList);

				var thumbImgWidth = ($this.global.width - ($this.option.showThumbImgAmount - 1) * 5)  / $this.option.showThumbImgAmount;

				var widthImg = thumbImgWidth * $this.global.sliderNum + $this.global.sliderNum * 5;

				$this.global.thumbContainer.css({
					width: widthImg
				});

				$this.global.inner.find('.storm-slide').each(function(index, el) {
					var $thumbImg = $('<div>').addClass('storm-thumbimg-frame').appendTo($this.global.thumbContainer).css({
						width: thumbImgWidth,
						height: 90,
						marginRight: 5
					});;

					$('<img>').addClass('storm-thumbimg').appendTo($thumbImg).attr('src', $(el).find('img').attr('src')).on('click', function(e) {
						e.preventDefault();
						$this.$el.stormSlider(index + 1);
					}).on('dragstart', function(event) { event.preventDefault(); });
				});

				$this.global.showThumbImgAmount !== 'all' && $this.global.thumbList.mousedown(function(event) {
					var that = this;
					that._startX = event.pageX;
					that._positionX = $this.global.thumbContainer.position().left;

					$(document).bind('mousemove', {ref: that}, $this.mouseMove).bind('mouseup', $this.mouseUp);
				});

				// $thumbList.stormtouch();

				$this.global.thumbContainer.find('.storm-thumbimg-frame:eq('+ $this.option.firstSlide +')').addClass('storm-thumbimg-active');
			}


			$this.global.inner.find('img').each(function(index, el) {
				$(this).on('dragstart', function(event) {
					event.preventDefault();
				});
			});
			//Add loading img
			// $this.global.loadingContainer = $('<div>').css({
			// 	zIndex: -1,
			// 	display: 'none'
			// }).addClass('storm-loading-container').appendTo($(el));

			// $('<div>').addClass('storm-loading-indicator').appendTo($this.global.loadingContainer);

			$this.global.inner.find('.storm-slide').each(function() {
				if($(this).data('misc')) {
					var data = $(this).data('misc').split(';');

					for (var i = 0; i < data.length; i++) {
						var param = data[i].split(':');
						$(this).data($.trim(param[0]), $.trim(param[1]));
					}
				}
				if($(this).data('link')) {
					var data = $(this).data('link').split(';');

					for (var i = 0; i < data.length; i++) {
						var param = data[i].match(/linkUrl:+/g) ? data[i].split('rl:'): data[i].split(':');
						$(this).data($.trim(param[0]), $.trim(param[1]));
					}

					if($(this).data('linkU')) {
						var $link = $('<a class="storm-link">').appendTo($(this));
						$link.attr('href', $(this).data('linkU'));
						$link.attr('target', $(this).data('linkTarget'));
						$link.attr('id', $(this).data('linkId'));
						$link.addClass($(this).data('linkClass'));
						$link.attr('title', $(this).data('linkTitle'));
						$link.attr('rel', $(this).data('linkRel'));
					}
				}

				if($(this).data('kenburnsoption')) {
					var data = $(this).data('kenburnsoption').toLowerCase().split(';');

					for (var i = 0; i < data.length; i++) {
						var param = data[i].split(':');
						$(this).data($.trim(param[0]), $.trim(param[1]));
					}
				}
			});

			$this.global.inner.find('.storm-slide, *[class*="storm-sublayer"]').each(function() {
				//Add data
				if ($(this).data('option')) {
					var data = $(this).data('option').toLowerCase().split(';');

					for (var i = 0; i < data.length; i++) {
						var param = data[i].split(':');
						$(this).data($.trim(param[0]), $.trim(param[1]));
					}
				}

				$this.option.startInViewport == !0 && $this.option.autoStart == !0 && ($this.option.autoStart = !1, $this.global.originalAutoStart = !0);

				var element = $(this);

				element.data('left', element[0].style.left);
				element.data('top', element[0].style.top);

				// if ($(this).is('a') && $(this).children().length > 0) {
				// 	element = $(this).children();
				// }

				var elWidth = element.width(),
					elHeight = element.height();

				if (element[0].style.width) {
					elWidth = element[0].style.width;
				}

				if (element[0].style.height) {
					elHeight = element[0].style.height;
				}

				element.data('width', elWidth);
				element.data('height', elHeight);
				element.data('paddingTop', element.css('padding-top'));
				element.data('paddingLeft', element.css('padding-left'));
				element.data('paddingBottom', element.css('padding-bottom'));
				element.data('paddingRight', element.css('padding-right'));
				element.data('fontSize', element.css('font-size'));

			});

			$this.global.showSlider = !0;
			$this.option.animateFirstSlide ? ($this.option.autoStart ? ($this.global.autoSliderShow = !0, $(el).find('.storm-nav-start').addClass('storm-nav-start-active')) : $(el).find('.storm-nav-stop').addClass('storm-nav-stop-active'), $this.next()) : 'undefined' != typeof $this.global.curSlider[0] && $this.isPreload($this.global.curSlider, function(){
				$this.global.curSlider.fadeIn($this.option.sliderFadeInDuration, function() {
					$this.global.isLoading = !1;
					$(this).addClass('storm-active');
				});

				$this.option.autoStart ? ($this.global.isLoading = !1, $this.start()) : $(el).find('.storm-nav-stop').addClass('storm-nav-stop-active');
			});

			$(window).resize(function() {
				$this.resize();
			});
		},

		$this.mouseMove = function(event) {

			var initailX = event.data.ref._positionX;

			var moveFrom = {
				left: initailX
			}

			var moveTo = {
				left: event.pageX - event.data.ref._startX + initailX
			}

			TweenLite.fromTo($this.global.thumbContainer, 0, moveFrom, moveTo);

		},

		$this.mouseUp = function(event) {

			if($this.global.thumbContainer.find('.storm-thumbimg:eq(0)').offset().left > $this.global.thumbList.offset().left) {

				var moveFrom = {
					left: $this.global.thumbContainer.position().left
				}
				var moveTo = {
					left: 0
				}

				TweenLite.fromTo($this.global.thumbContainer, 1, moveFrom, moveTo);

			}
			else if($this.global.thumbList.width() - $this.global.thumbContainer.find('.storm-thumbimg').last().offset().left < $this.global.thumbList.width() && $this.global.thumbContainer.position().left !== 0 && Math.abs($this.global.thumbContainer.position().left) > $this.global.thumbContainer.find('.storm-thumbimg').width()) {

				var moveFrom = {
					left: $this.global.thumbContainer.position().left
				}

				var moveTo = {
					left: -($this.global.thumbContainer.find('.storm-thumbimg').width() + 5)
				}

				TweenLite.fromTo($this.global.thumbContainer, 1, moveFrom, moveTo);

			}

			$(document).unbind('mousemove', $this.mouseMove).unbind('mouseup', $this.mouseUp);
		},

		$this.resize = function() {
			$this.sliderResponse();
			$this.thumbResponse();
			$this.global.resize = !0;
			$this.global.isAnimating || ($this.response($this.global.curSlider, function() {
				$this.global.innerContainer && $this.global.innerContainer.empty();
				$this.global.resize = !1;
			}));
		},

		$this.start = function() {
			$this.global.autoSliderShow ? 'prev' == $this.global.prevNext ? $this.prev() : $this.next() : ($this.global.autoSliderShow = !0, $this.global.isAnimating || $this.global.isLoading || $this.timer());
			$this.option.showStartStopButton && $this.global.startButton.addClass('storm-startStop-active');
			$this.option.showStartStopButton && $this.global.stopButton.removeClass('storm-startStop-active');
		},

		$this.timer = function() {
			var defaultSlideDelay;

			if ($this.global.inner.find('.storm-active').data('option')) {
				defaultSlideDelay = $this.slideTransitions.slideDelay;
			}
			else {
				defaultSlideDelay = $this.option.slideDelay;
			}


			//Set slide delay
			var slideDelay;
			if($this.global.inner.find('.storm-active').data('slidedelay')) {
				slideDelay = parseFloat($this.global.inner.find('.storm-active').data('slidedelay')) * 1e3;
			}
			else if($this.global.inner.find('.storm-active').data('slidedelay') == 0) {
				slideDelay = 0;
			}
			else {
				slideDelay = defaultSlideDelay;
			}
			// var slideDelay = $this.global.inner.find('.storm-active').data('slidedelay') ? parseFloat($this.global.inner.find('.storm-active').data('slidedelay')) * 1e3 :

			if (!$this.option.animateFirstSlide && !$this.global.inner.find('.storm-active').data('slidedelay')) {
				var delay = parseFloat($this.global.inner.find('.storm-slide:eq('+ ($this.option.firstSlide - 1) +')').data('slidedelay'));
				slideDelay = delay ? delay * 1e3 : defaultSlideDelay;
			}

			clearTimeout($this.global.slideTimer);

			$this.global.pausedSlideTime ? ($this.global.startSlideTime || ($this.global.startSlideTime = (new Date).getTime()), $this.global.startSlideTime > $this.global.pausedSlideTime && ($this.global.pausedSlideTime = (new Date).getTime()), $this.global.curSlideTime || ($this.global.curSlideTime = slideDelay), $this.global.curSlideTime -= $this.global.pausedSlideTime - $this.global.startSlideTime, $this.global.pausedSlideTime = !1, $this.global.startSlideTime = (new Date).getTime()) : ($this.global.curSlideTime = slideDelay, $this.global.startSlideTime = (new Date).getTime());

			$this.global.curSlideTime = parseInt($this.global.curSlideTime);
			$this.global.slideTimer = setTimeout(function(){
					$this.global.startSlideTime = $this.global.pausedSlideTime = $this.global.curSlideTime = !1;
					$this.start();
			}, $this.global.curSlideTime);

			$this.global.barTimer && $this.barTimer();

			$this.global.circleTimer && $this.circleTimer();

			$this.global.nextSlider.data('kenburnsoption') && $this.kenBurns($this.global.nextSlider);
		},

		$this.barTimer = function() {
			$this.barTimerTl = new TimelineLite({onComplete: barComplete});

			$this.barTimerTl.to($this.global.barTimer, $this.global.curSlideTime / 1e3, {
				width: $this.global.width,
				ease: Linear.easeNone
			});

			function barComplete() {
				$this.barTimerTl.to($this.global.barTimer, 0, {
					width: 0
				});
			}
		},

		$this.circleTimer = function () {
			var rightCircle = $this.global.circleTimer.find('.storm-circletimer-right .storm-circletimer-rotate'),
				leftCircle = $this.global.circleTimer.find('.storm-circletimer-left .storm-circletimer-rotate');

			'none' == $this.global.circleTimer.css('display') && (rightCircle.css('rotate', '0'), leftCircle.css('rotate', '0'), $this.global.circleTimer.fadeIn(350));

			$this.circleTimerTl = new TimelineLite();

			$this.circleTimerTl.fromTo($this.global.circleTimer, 0, {
				opacity: 0
			},{
				opacity: 0.65
			}).fromTo(rightCircle, $this.global.curSlideTime / 2 / 1e3, {
				rotation: 0
			}, {
				ease: Linear.easeNone,
				rotation: 180
			}).fromTo(leftCircle, $this.global.curSlideTime / 2 / 1e3, {
				rotation: 0
			}, {
				ease: Linear.easeNone,
				rotation: 180
			});
		},

		$this.stop = function() {
			$this.global.autoSliderShow = !1;
			$this.global.pausedSlideTime = (new Date).getTime();
			clearTimeout($this.global.slideTimer);
			$this.option.showStartStopButton && $this.global.startButton.removeClass('storm-startStop-active');
			$this.option.showStartStopButton && $this.global.stopButton.addClass('storm-startStop-active');
		},

		$this.forcestop = function() {
			$this.global.autoSliderShow = !1;
			clearTimeout($this.global.setClass);
			$this.global.inner.find('.storm-slide >').each(function(){
				$(this).data('tr') && $(this).data('tr').kill();
			});

			$this.option.showTimeLine && $this.timeLineStop();
		},

		$this.timeLineStop = function() {
			$this.option.showTimeLine = !1;

			$this.global.timeline && $this.global.timeline.remove();
		},

		//Control Slide order
		$this.prev =  function(option) {
			if(option) {
				clearTimeout($this.global.slideTimer);
				var index = $this.global.curSliderIndex < 2 ? $this.global.sliderNum : $this.global.curSliderIndex - 1;
				$this.change(index);
			}
			else {
				var index = $this.global.curSliderIndex < 2 ? $this.global.sliderNum : $this.global.curSliderIndex - 1;
				$this.global.prevNext = 'prev';
				$this.change(index);
			}
		},

		//Control Slide order
		$this.next = function(option) {
			if(option) {
				clearTimeout($this.global.slideTimer);
				var index = $this.global.curSliderIndex < $this.global.sliderNum ? $this.global.curSliderIndex + 1 : 1;
				$this.change(index);
			}
			else {
				var index = $this.global.curSliderIndex < $this.global.sliderNum ? $this.global.curSliderIndex + 1 : 1;
				$this.global.prevNext = 'next';
				$this.change(index);
			}
		},

		//Slide order init
		$this.change = function(index) {

			$this.global.startSlideTime = $this.global.pausedSlideTime = $this.global.curSlideTime = !1;
			$this.option.showBarTimer && $this.barTimerTl && $this.barTimerTl.reverse().duration(0.35);
			$this.option.showCircleTimer && $this.circleTimerTl && $this.circleTimerTl.reverse().duration(0.35);

			$this.global.nextSliderIndex = index;
			$this.global.nextSlider = $this.global.inner.find('.storm-slide:eq('+ ($this.global.nextSliderIndex - 1) +')');

			'undefined' !== typeof $this.global.nextSlider[0] && $this.isPreload($this.global.nextSlider, function(){
				$this.animate();
			});
		},

		$this.isPreload = function(slider, func) {

			if ($this.global.isLoading = !0, $this.global.showSlider && $this.global.innerParent.css({
				visibility: 'visible'
			}), $this.option.isPreload) {
				var arr = [],
					num = 0;

				if ('none' != slider.css('background-image') && -1 != slider.css('background-image').indexOf('url') && !slider.hasClass('storm-preloaded') && !slider.hasClass('storm-not-preloaded')) {
					var url = slider.css('background-image');
					url = url.match(/url\((.*)\)/)[1].replace(/'/gi, '');
					arr[arr.length] = [url, slider];
				}

				if (slider.find('img:not(.storm-preloaded, .storm-not-preloaded)').each(function() {
					$this.option.lazyLoad == !0 && $(this).attr('src', $(this).data('src'));
					arr[arr.length] = [$(this).attr('src'), $(this)];
				}), slider.find('*').each(function() {
					if ("none" != $(this).css("background-image") && -1 != $(this).css("background-image").indexOf("url") && !$(this).hasClass("storm-preloaded") && !$(this).hasClass("storm-not-preloaded")) {
							var url = $(this).css("background-image");
							url = url.match(/url\((.*)\)/)[1].replace(/'/gi, '');
							arr[arr.length] = [url, $(this)];
					}
				}), arr.length == 0) {
					$this.response(slider, func);
				}
				else {
					//check ie78
					var n = function() {
						-1 == navigator.userAgent.indexOf('Trident/7') || $this.global.ie78 ? setTimeout(function() {
							$this.response(slider, func);
						}, 50) : $this.response(slider, func);
					};
					for (var i = 0; i < arr.length; i++) {
						$('<img>').data('el', arr[i]).load(function() {
							$(this).data('el')[1].addClass('storm-preloaded');
							++num == arr.length && n();
						}).error(function() {
							var error = $(this).data('el')[0].substring($(this).data('el')[0].lastIndexOf('/') + 1, $(this).data('el')[0].length);
							window.console ? console.log(error) : alert(error);
						}).attr('src', arr[i][0]);
					}
				}
			}
			else {
				$this.response(slider, func);
			}
		},

		//Make slider width and height
		$this.sliderResponse = function() {

			//Set slider width and height
			switch ($this.option.layout) {
				case 'fixedSize':
					$this.global.width = parseInt($this.global.innerParent.width());
					$this.global.height = parseInt($this.global.innerParent.height());
					break;
				case 'fullSize':
					$this.global.width = parseInt($(window).width());
					$this.global.height = parseInt($this.global.innerParent.height());
					var marginLeft = -$(el).offset().left;
					break;
				case 'fullWidth':
					$this.global.width = parseInt($(window).width());
					$this.global.height = parseInt($this.global.innerParent.height());
					var marginLeft = -$(el).offset().left;
					break;
				default:
					$this.global.width = parseInt($(el).parent().width());
					$this.global.height = parseInt($this.global.width / 2);
					var marginLeft = 'auto';
					break;
			}

			$this.global.innerParent.css({
				width: $this.global.width,
				height: $this.global.height,
				marginBottom: 0,
				marginLeft: marginLeft
			});
		},

		//Make thumbnail list response
		$this.thumbResponse = function() {
			if($this.option.showThumbImg) {
				switch($this.option.layout) {
					case 'fullSize':
						var thumbLeft = -$(el).offset().left;
						break;
					case 'fullWidth':
						var thumbLeft = -$(el).offset().left;
						break;
					default:
						var thumbLeft = 'auto';
						break;
				}

				$this.global.thumbList.css({
					width: $this.global.width,
					marginLeft: thumbLeft
				});

				var $thumbContainer = $this.global.thumbList.find('.storm-thumbimg-container');

				var thumbFrameWidth = ($this.global.width - ($this.option.showThumbImgAmount - 1) * 5)  / $this.option.showThumbImgAmount;

				var thumbFrameHeight = 90;

				var thumbFrameRatio = thumbFrameWidth / thumbFrameHeight;

				var widthImg = thumbFrameWidth * $this.global.sliderNum + $this.global.sliderNum * 5;

				$thumbContainer.css({
					width: widthImg
				});

				$this.global.thumbContainer.find('.storm-thumbimg-frame').each(function(index, el) {
					$(this).css({
						width: thumbFrameWidth,
						height: thumbFrameHeight,
						marginRight: 5
					});;

					this.ratio = $(this).find('img')[0].naturalWidth / $(this).find('img')[0].naturalHeight;
					var thumbImgHeight = thumbFrameRatio > this.ratio ? thumbFrameWidth / this.ratio : thumbFrameWidth * this.ratio;

					$(this).find('img').css({
						width: thumbFrameWidth,
						height: thumbImgHeight,
						marginTop: -(thumbImgHeight - thumbFrameHeight) / 2,
						marginLeft: 0
					});
				});
			}
		},

		//Make slide response
		$this.response = function(slider, func) {

			slider.css({
				visibility: 'hidden',
				display: 'block'
			});

			$this.resizeSlider();

			var fillMode = slider.data('fillmode') ? slider.data('fillmode') : 'stretch';
			var positionMode = slider.data('positionmode') ? slider.data('positionmode').split('-') : new Array("center","center");

			var elChildren = slider.children();

			elChildren.each(function() {

				var el = $(this),
					left = el.data('left') ? el.data('left') : 0,
					top = el.data('top') ? el.data('top') : 0;

				var width = 'auto',
					height = 'auto';

				el.data('width') && (width = el.data('width')),
				el.data('height') && (height = el.data('height'));


				if(el.hasClass('storm-sublayer')) {

					width == 'auto' && (width = el.width() * $this.global.ratio);
					height == 'auto' && (height = el.height() * $this.global.ratio);

					var paddingTop = el.data('paddingTop') ? el.data('paddingTop') : 0,
						paddingLeft = el.data('paddingLeft') ? el.data('paddingLeft') : 0,
						paddingBottom = el.data('paddingBottom') ? el.data('paddingBottom') : 0,
						paddingRight =  el.data('paddingRight') ? el.data('paddingRight') : 0,
						fontSize = el.data('fontSize') ? el.data('fontSize') : '14px';

					var layerTop = top.replace(/[^0-9\d.]/ig, '') * $this.global.ratio,
						layerLeft = left.replace(/[^0-9\d.]/ig, '') * $this.global.ratio,
						layerWidth = width.replace(/[^0-9\d.]/ig, '') * $this.global.ratio,
						layerHeight = height.replace(/[^0-9\d.]/ig, '') * $this.global.ratio,
						paddingTop = paddingTop.replace(/[^0-9\d.]/ig, '') * $this.global.ratio,
						paddingRight = paddingRight.replace(/[^0-9\d.]/ig, '') * $this.global.ratio,
						paddingBottom = paddingBottom.replace(/[^0-9\d.]/ig, '') * $this.global.ratio,
						paddingLeft = paddingLeft.replace(/[^0-9\d.]/ig, '') * $this.global.ratio,
						fontSize = fontSize.replace(/[^0-9\d.]/ig, '') * $this.global.ratio;

					el.css({
						top: layerTop,
						left: layerLeft,
						width: layerWidth,
						height: layerHeight,
						paddingTop: paddingTop,
						paddingRight: paddingRight,
						paddingBottom: paddingBottom,
						paddingLeft: paddingLeft,
						fontSize: fontSize
					});
				}

				if (el.is('img') && el.hasClass('storm-bg')) {
					el.css({
						width: width,
						height: height
					});

					// Set img style
					var imgRatio = this.naturalWidth / this.naturalHeight;

					var elWidth, elHeight, elMarginTop, elMarginLeft;
					switch (fillMode) {
						case 'center':
							elWidth = this.naturalWidth, elHeight = this.naturalHeight;
							break;
						case 'cover':
							imgRatio < $this.global.ratio ? (elWidth = $this.global.width, elHeight = elWidth / imgRatio) : (elHeight = $this.global.height, elWidth = elHeight * imgRatio);
							break;
						case 'contain':
							$this.global.ratio < imgRatio ? (elWidth = $this.global.height, elHeight = imgRatio * $this.global.width) : (elWidth = imgRatio * $this.global.height, elHeight = $this.global.height);
							break;
						case 'stretch':
							elWidth =  $this.global.width;
							elHeight = $this.global.height;
							break;
					}

					switch(positionMode[0]) {
						case 'left':
							elMarginLeft = 0;
							break;
						case 'center':
							elMarginLeft = ($this.global.width - elWidth) / 2;
							break;
						case 'right':
							elMarginLeft = $this.global.width - elWidth;
							break;
					}

					switch (positionMode[1]) {
						case 'top':
							elMarginTop = 0;
							break;
						case 'center':
							elMarginTop = ($this.global.height - elHeight) / 2;
							break;
						case 'bottom':
							elMarginTop = $this.global.height - elHeight;
							break;
					}

					el.css({
						width: elWidth,
						height: elHeight,
						marginTop: elMarginTop,
						marginLeft: elMarginLeft
					});
				}
			});

			slider.css({
				display: 'none',
				visibility: 'visible'
			});

			func();

		},

 		//
		$this.resizeSlider = function() {

			//Set el ratio
			if($this.option.layout == 'responsive' || $this.option.layout == 'fullSize' || $this.option.layout == 'fullWidth') {
				$this.global.ratio = $this.global.inner.width() / $this.global.initWidth;
			}
			else {
				$this.global.ratio = 1;
			}

			// console.log($this.global.ratio);
			$this.global.curSlider && $this.global.nextSlider ? ($this.global.curSlider.css({
					width: $this.global.width,
					height: $this.global.height
				}),
				$this.global.nextSlider.css({
					width: $this.global.width,
					height: $this.global.height
				})
			) : $this.global.inner.find('.storm-slide').css({
					width: $this.global.width,
					height: $this.global.height
			});
		},

		//Create layer timeline
		$this.timeLine = function() {

			var $layerUl = $('.layer-ul');
			var $timelineUl = $('.image-layer-timeline-ul');

			$layerUl.empty();
			$timelineUl.empty();

			$('.image-layer-timeline-cont').find('.timeline-dragger').remove();


			//Get transition duration and delay
			var transitionTimeLine = function(el) {
				if ($this.global.nextSlider.find(' > *[class*="storm-sublayer"]').length) {
					var arrTime = [], timeLineHeight = 0;

					$this.global.nextSlider.find(' > *[class*="storm-sublayer"]').each(function(){

						var hideTime = 0, showTime = 0,
							transitionHideData = $(this).data('stormTransitionhide'),
							transitionShowData = $(this).data('stormTransitionshow');
						if (transitionHideData) {
							var data = {};
							var dataObject = transitionHideData.split(';');

							$.each(dataObject, function(index, val) {
								var prop = val.split(':');
								data[prop[0]] = prop[1];
							});

							hideTime = parseInt(data.duration) + parseInt(data.delay);
						}

						if (transitionShowData) {
							var data = {};
							var dataObject = transitionShowData.split(';');

							$.each(dataObject, function(index, val) {
								var prop = val.split(':');
								data[prop[0]] = prop[1];
							});

							showTime = parseInt(data.duration) + parseInt(data.delay);
						}

						arrTime.push(hideTime + showTime);
					});

					var duration = Math.max.apply({}, arrTime);
					var x = duration * 80;

					$this.global.timeline.data('timeTransition', TweenLite.to(el, duration, {x: x}));
				}
			};

			//Create timeline ul and layer ul
			var create = function() {
				var timeLineHeight = 0, $parentContainer = $('.image-layer-timeline-cont');

				$this.global.nextSlider.find(' > *[class*="storm-sublayer"]').each(function() {
					timeLineHeight++;

					var $timelist = $($('#storm-layer-timeline-list-template').text()),
						showData = $(this).data('stormTransitionshow'),
						hideData = $(this).data('stormTransitionhide'),
						oneSecWidth = 80;

					$timelineUl.append($timelist);
					$timelist.find('.timeline-view div').width(0);

					if (showData) {
						var data = {};
						var dataObject = showData.split(';');

						$.each(dataObject, function(index, val) {
							var prop = val.split(':');
							data[prop[0]] = prop[1];
						});

						$timelist.find('.layer-delayShow-timeline').width(parseInt(data.delay) * oneSecWidth);
						$timelist.find('.layer-durationShow-timeline').width(parseInt(data.duration) * oneSecWidth);
					}

					if (hideData) {
						var data = {};
						var dataObject = hideData.split(';');

						$.each(dataObject, function(index, val) {
							var prop = val.split(':');
							data[prop[0]] = prop[1];
						});

						$timelist.find('.layer-delayHide-timeline').width(parseInt(data.delay) * oneSecWidth);
						$timelist.find('.layer-durationHide-timeline').width(parseInt(data.duration) * oneSecWidth);
					}

					var $template = jQuery(jQuery('#storm-layerlist-template').text()),
						layerTimeline = $(this).data('layerTimeline'),
						layerData = {}, imageUrl;

					var layerData = layerTimeline.split(';');

					$.each(layerData, function(index, val) {
						var prop = val.split(':');
						layerData[prop[0]] = prop[1];
					});

					switch (layerData.type) {
						case 'text':
							imageUrl = '/image/layertypes/text.png';
							break;
						case 'image':
							imageUrl = '/image/layertypes/image.png';
							break;
						case 'button':
							imageUrl = '/image/layertypes/button.png';
							break;
						case 'video':
							imageUrl = '/image/layertypes/video.png';
							break;
					}

					$template.find('.image-layer').attr({'src': pluginUrl.pluginUrl + imageUrl, 'alt': layerData.type});
					$template.find('span[name=layerName]').html(layerData.name);
					$template.appendTo($layerUl);

				});

				if (timeLineHeight !== 0) {
					$this.global.timeline = $('<div class="timeline-dragger">').appendTo($parentContainer).css({
						height: timeLineHeight * 40 + 20
					});

					transitionTimeLine($this.global.timeline);
				}
			};

			create();
		},

		$this.thumbActive = function (index) {
			var innerParentLeft = $this.global.innerParent.offset().left;

			var thumbLeft = $this.global.thumbList.find('.storm-thumbimg-frame:eq('+(index - 1)+')').offset().left;
			if(thumbLeft > innerParentLeft && thumbLeft < $this.global.width) {

			}
			else if(thumbLeft <= innerParentLeft) {
				var moveFrom = {
					left: $this.global.thumbContainer.position().left
				}

				var moveTo = {
					left: 0
				}

				TweenLite.fromTo($this.global.thumbContainer, 1, moveFrom, moveTo);
			}
			else {

				var moveFrom = {
					left: $this.global.thumbContainer.position().left
				}

				var moveTo = {
					left: ($this.option.showThumbImgAmount - index) * $this.global.thumbContainer.find('.storm-thumbimg-frame').width()
				}

				TweenLite.fromTo($this.global.thumbContainer, 1, moveFrom, moveTo);
			}

		},

		//Ken burns effect
		$this.kenBurns = function(slider) {

			$this.global.innerParent.css('overflow', 'hidden');

			var effectTo = {}, effectFrom = {};
			switch (slider.data('kenburnszoom')) {
				case 'zoom-in':
					effectTo = {
						scale: slider.data('kenburnsscale')
					}
					effectFrom = {
						scale: 1
					}
					break;
				case 'zoom-out':
				 	effectTo = {
				 		scale: 1
				 	}
					effectFrom = {
						scale: slider.data('kenburnsscale')
					}
					break;
			}

			effectTo.onComplete = function() {
				$this.global.innerParent.css('overflow', 'none');
			}

			var transformOrigin = slider.data('kenburnsdirection').replace('-', ' ');

			TweenLite.set(slider, {
				transformOrigin: transformOrigin
			});

			TweenLite.fromTo(slider, slider.data('slidedelay'), effectFrom, effectTo);

		},

		$this.animate = function() {
			$this.global.inner.find('.storm-slide').length > 0 && ($this.global.isAnimating = !0);
			$this.global.isLoading = !1;
			clearTimeout($this.global.slideTimer);
			clearTimeout($this.global.changeTimer);
			$this.global.stopSlider = $this.global.curSlider;
			$this.global.nextSlider.addClass('storm-animating');

			if($this.option.animateFirstSlide && !$this.global.firstSlideAnimated && $this.global.nextSlider.data('kenburnsoption')) {
				$this.kenBurns($this.global.nextSlider);
			}

			var curSliderLeft = curSliderRight = curSliderTop = curSliderBottom = nextSliderLeft = nextSliderRight = nextSliderTop = nextSliderBottom = sliderMarginLeft = sliderMarginRight = sliderMarginTop = sliderMarginBottom = "auto",
				nextSliderWidth = $this.global.width,
				nextSliderHeight = $this.global.height,
				slider = "prev" == $this.global.prevNext ? $this.global.curSlider : $this.global.nextSlider,
				sliderDirection = slider.data("slidedirection") ? slider.data("slidedirection") : $this.option.slideDirection,
				direction = $this.global.slideDirections[$this.global.prevNext][sliderDirection];

			if (direction == 'left' || direction == 'right') {
				nextSliderWidth = curSliderTop = nextSliderTop = sliderMarginTop = 0;
			}

			if (direction == 'top' || direction == 'bottom') {
				nextSliderHeight = nextSliderLeft = sliderMarginLeft = 0;
			}

			switch (direction) {
				case 'left':
					nextSliderLeft = curSliderRight = 0;
					break;
				case 'right':
					nextSliderRight = curSliderLeft = 0;

					break;
				case 'top':
					nextSliderTop = curSliderBottom = 0;
					break;
				case 'bottom':
					nextSliderBottom = curSliderTop = 0;
					break;
			}
			$this.global.curSlider.css({
				left: curSliderLeft,
				bottom: curSliderBottom,
				right: curSliderRight,
				top: curSliderTop
			});

			$this.global.nextSlider.css({
				width: nextSliderWidth,
				height: nextSliderHeight,
				left: nextSliderLeft,
				bottom: nextSliderBottom,
				right: nextSliderRight,
				top: nextSliderTop
			});

			//Get curSlider delay and duration time
			var delayShow = $this.global.curSlider.data('delayShow') ? parseInt($this.global.curSlider.data('delayShow')) : $this.option.delayShow,
				durationShow = $this.global.curSlider.data('durationShow') ? parseInt($this.global.curSlider.data('durationShow')) : $this.option.durationShow,
				easeInOut = $this.global.curSlider.data('easeInOut') ? parseInt($this.global.curSlider.data('easeInOut')) : $this.option.easeInOut;

			//Get nextSlider delay and duration time
			var	delayHide = $this.global.nextSlider.data('delayHide') ? parseInt($this.global.nextSlider.data('delayHide')) : $this.option.delayHide,
				durationHide = $this.global.nextSlider.data('durationHide') ? parseInt($this.global.nextSlider.data('durationHide')) : $this.option.durationHide,
				easeIn = $this.global.nextSlider.data('easeIn') ? parseInt($this.global.nextSlider.data('easeIn')) : $this.option.easeIn;

			//Add .storm-active class
			var setClass = function() {
				if ($this.global.stopSlider.find(' > *[class*="storm-sublayer"]').each(function() {
					$(this).data('tr') && $(this).data('tr').kill();
					$(this).css('filter', 'none');
				}), $this.global.curSlider = $this.global.nextSlider, $this.global.prevSliderIndex = $this.global.curSliderIndex, $this.global.curSliderIndex = $this.global.nextSliderIndex, $this.option.isPreload && $this.option.lazyLoad) {

					var sliderIndex = $this.global.curSliderIndex == $this.global.sliderNum ? 1 : $this.global.curSliderIndex + 1;
					$this.global.inner.find('.storm-slide').eq(sliderIndex - 1).find('img:not(.storm-preloaded)').each(function(){
						$(this).load(function() {
							$(this).addClass('storm-preloaded');
						}).error(function() {
							var e = $(this).data('src').substring($(this).data('src').lastIndexOf('/') + 1, $(this).data('src').length);
							window.console ? console(e) : alert(e);
							$(this).addClass('storm-not-preloaded');
						}).attr('src', $(this).data('src'));
					});
				}

				$this.global.inner.find('.storm-slide').removeClass('storm-active');
				$this.global.inner.find('.storm-slide:eq('+ ($this.global.curSliderIndex - 1) +')').addClass('storm-active').removeClass('storm-animating');
				$this.global.innerParent.find('.storm-nav-wrapper .storm-thumb-img').removeClass('storm-thumb-active');
				$this.global.innerParent.find('.storm-nav-wrapper .storm-thumb-img:eq('+ ($this.global.curSliderIndex - 1) +')').addClass('storm-thumb-active');
				$this.option.showThumbImg && $this.global.thumbContainer.find('.storm-thumbimg-frame').removeClass('storm-thumbimg-active');
				$this.option.showThumbImg && $this.global.thumbContainer.find('.storm-thumbimg-frame:eq('+ ($this.global.curSliderIndex - 1) +')').addClass('storm-thumbimg-active');

				$this.option.showThumbImg && $this.thumbActive($this.global.curSliderIndex);
				$this.global.autoSliderShow && $this.timer();
				$this.global.isAnimating = !1;
				$this.global.resize == 1 && ($this.response($this.global.curSlider, function(){
					$this.global.resize = !1;
				}));

			};

			// var curSliderAnimate = function() {
			// 	$this.global.curSlider.delay(delayShow + durationShow).animate({
			// 		width: nextSliderWidth,
			// 		height: nextSliderHeight
			// 	},  durationShow, easeInOut, function() {
			// 			setClass();
			// 	});
			// };

			// var nextSliderAnimate = function() {
			// 	$this.global.nextSlider.delay(delayHide + durationHide).animate({
			// 		width: $this.global.width,
			// 		height: $this.global.height
			// 	},  durationHide, easeIn);
			// };


			//SubLayer TweenLite to
			var subLayerTweenTo = function() {
				var slide = $this.global.curSlider.find(' > *[class*="storm-sublayer"]');

				slide.each(function() {
					var layerX, layerY, layerDirection, dataObject = data = {};

					if ( $(this).data('stormTransitionhide')) {
						dataObject = $(this).data('stormTransitionhide').split(';');

						$.each(dataObject, function(index, val) {
							var prop = val.split(':');
							data[prop[0]] = prop[1];
						});

						var transitionData = $.extend({}, $this.layerTransitions, data);

						// $(this).data('transitionType') || $this.getTransitionType($(this));

						// if ($(this).data('transitionType') == 'new') {
						// 	layerDirection =  'new';
						// }
						// else {
							layerDirection = $(this).data('sliderDirection') ? $(this).data('sliderDirection') : direction;
						// }

						switch (layerDirection) {
							case 'top':
								layerX = 0; layerY = $this.global.height;
								break;
							case 'left':
								layerX = $this.global.width; layerY = 0;
								break;
							case 'bottom':
								layerX = 0; layerY = -$this.global.height;
								break;
							case 'right':
								layerX = -$this.global.width; layerY = 0;
								break;
							case 'new':
								layerX = transitionData.offsetX; layerY = transitionData.offsetY;
								break;
						}

						var delay = transitionData.delay;

						var rotateZ = transitionData.rotate2D !== 'false' ? 0 : transitionData.rotateZ;

						var transformOrigin = transitionData.originX + '% ';
						transformOrigin += transitionData.originY + '% ';
						transformOrigin += transitionData.originZ;


						var completeCss = {
							visibility: 'hidden',
						},
						transition = {
							delay: delay,
							rotation: rotateZ,
							rotationX: transitionData.rotateX,
							rotationY: transitionData.rotateY,
							scaleY: transitionData.scaleY,
							scaleX: transitionData.scaleX,
							skewX: transitionData.skewX,
							skewY: transitionData.skewY,
							ease: getEaseEffect(transitionData.easing),
							x: layerX,
							y: layerY,
							onComplete: function() {
								$(this).css(completeCss);
							}
						};

						if (transitionData.fade !== 'false') {
							completeCss.opacity = 0;
							transition.opacity = 1;
						}
						$(this).data('tr') && $(this).data('tr').kill();

						TweenLite.set($(this), {
							transformPerspective: transitionData.transformPerspective,
							transformOrigin: transformOrigin
						});

						$(this).data('tr', TweenLite.to($(this), transitionData.duration, transition));
					}
				});
			};

			//SubLayer TweenLite fromTo
			var subLayerTweenFormTo = function() {
				var slide = $this.global.nextSlider.find(' > *[class*="storm-sublayer"]');

				slide.each(function() {
					var layerX, layerY, layerDirection, dataObject = {}, data = {};

					if ($(this).data('stormTransitionshow')) {

	 					dataObject = $(this).data('stormTransitionshow').split(';');

						$.each(dataObject, function(index, val) {
							var prop = val.split(':');
							data[prop[0]] = prop[1];
						});

						var transitionData = $.extend({}, $this.layerTransitions, data);

						// $(this).data('transitionType') || $this.getTransitionType($(this));

						// if ($(this).data('transitionType') == 'new') {
						// 	layerDirection = 'new';
						// }
						// else {
							layerDirection = $(this).data('sliderDirection') ? $(this).data('sliderDirection') : direction;
						// }

						switch (layerDirection) {
							case 'top':
								layerX = 0; layerY = $this.global.height;
								break;
							case 'left':
								layerX = $this.global.width; layerY = 0;
								break;
							case 'bottom':
								layerX = 0; layerY = -$this.global.height;
								break;
							case 'right':
								layerX = -$this.global.width; layerY = 0;
								break;
							case 'new':
								layerX = transitionData.offsetX; layerY = transitionData.offsetY;
								break;
						}
						var rotateZ = transitionData.rotate2D !== 'false' ? 0 : transitionData.rotateZ;

						var transformOrigin = transitionData.originX + '% ';
						transformOrigin += transitionData.originY + '% ';
						transformOrigin += transitionData.originZ;

						var transitionFrom = {
							rotation: rotateZ,
							rotationX: transitionData.rotateX,
							rotationY: transitionData.rotateY,
							scaleY: transitionData.scaleY,
							scaleX: transitionData.scaleX,
							skewX: transitionData.skewX,
							skewY: transitionData.skewY,
							visibility: "visible",
							x: layerX,
							y: layerY
						};
						var transitionTo = {
							delay: transitionData.delay,
							rotation: 0,
							rotationX: 0,
							rotationY: 0,
							scaleY: 1,
							scaleX: 1,
							skewX: 0,
							skewY: 0,
							ease: getEaseEffect(transitionData.easing),
							x: 0,
							y: 0
						};

						if (transitionData.fade !== 'false') {
							transitionFrom.opacity = 0;
							transitionTo.opacity = 1;
						}

						$(this).data('tr') && $(this).data('tr').kill();

						TweenLite.set($(this), {
							transformPerspective: transitionData.transformPerspective,
							transformOrigin: transformOrigin
						});

						TweenLite.fromTo($(this), transitionData.duration, transitionFrom, transitionTo);

						$(this).data('tr', TweenLite.to($(this), transitionData.duration));
					}
				});
			};

			//Set transition type
			var setTransitionType = function(type, contexts) {
				var content, length, data = -1 == type.indexOf('custom') ? $this.transitionDefaultData : $this.transitionCustomData,
					option = '3d';

				if (-1 !== type.indexOf('2d') && (option = '2d'), -1 !== contexts.indexOf('last')) {
					length = data[option].length - 1;
					content = 'last';
				}
				else if(-1 !== contexts.indexOf('all') ){
					length = Math.round(data[option].length * Math.random());
					content = 'random from all';
				}
				else {
					var arr = contexts.split(',');
					var	arrLength = arr.length;
					length = parseInt(arr[Math.floor(Math.random() * arrLength)]) - 1;
					content = 'random from specifile';
				}

				play(option, data[option][length]);
			};

			//Check slider transition 3d or 2d
			var checkTransitionType = function() {
				if ($this.global.nextSlider.data('3d') || $this.global.nextSlider.data('custom3d')) {
					if ($this.global.nextSlider.data('3d') && $this.global.nextSlider.data('custom3d')) {
						var index = Math.floor(Math.random() * 2),
							array = [
								['3d', $this.global.nextSlider.data('3d')],
								['custom3d', $this.global.nextSlider.data('custom3d')]
							];

						setTransitionType(array[index][0], array[index][1]);
					}
					else {
						$this.global.nextSlider.data('3d') ? setTransitionType('3d', $this.global.nextSlider.data('3d')) : setTransitionType('custom3d', $this.global.nextSlider.data('custom3d'));
					}
				}
				else if($this.global.nextSlider.data('2d') && $this.global.nextSlider.data('custom2d')) {
					var index = Math.floor(Math.random() * 2),
						array = [
							['2d', $this.global.nextSlider.data('2d')],
							['custom2d', $this.global.nextSlider.data('custom2d')]
						];

					setTransitionType(array[index][0], array[index][1]);
				}
				else {
					$this.global.nextSlider.data('2d') ? setTransitionType('2d', $this.global.nextSlider.data('2d')) : $this.global.nextSlider.data('custom2d') ? setTransitionType('custom2d', $this.global.nextSlider.data('custom2d')) : setTransitionType('2d', '1');
				}
			};

			//Check custom transition type
			// var checkCustomTransitionType = function() {
			// 	-1 !== $this.transitionCustomData.indexOf('3d') ? setTransitionType('3d', $this.transitionCustomData.split(':')[1]) : -1 !== $this.transitionCustomData.indexOf('2d') ? setTransitionType('2d', 'all') : setTransitionType('2d', $this.transitionCustomData.split(':')[1]);
			// };


			var play = function(type, options) {
				//Get slide row and col
				var tiles = [], arrDelay = [], row, col, direction;

				var inner = $this.global.inner;
				var option = options;
				var num = $this.global.curSlider.find('*[class*="storm-sublayer"]').length > 0 ? 1: 0;

				switch (typeof option.rows) {
					case 'number':
						row = option.rows;
						break;
					case 'string':
						row = Math.floor(Math.random() * (parseInt(option.rows.split(',')[1]) - parseInt(option.rows.split(',')[0]) + 1)) + parseInt(option.rows.split(',')[0]);
						break;
					default:
						row = Math.floor(Math.random() * (option.rows[1] - option.rows[0] + 1)) + option.rows[0];
						break;
				}

				switch (typeof option.cols) {
					case 'number':
						col = option.cols;
						break;
					case 'string':
						col = Math.floor(Math.random() * (parseInt(option.cols.split(',')[1]) - parseInt(option.cols.split(',')[0]) + 1)) + parseInt(option.cols.split(',')[0]);
						break;
					default:
						col = Math.floor(Math.random() * (option.cols[1] - option.cols[0] + 1)) + option.cols[0];
						break;
				}

				//Set array delay
				for (var i = 0; i < col * row; i++) {
					arrDelay.push(i);
				}

				//Get sequence effect
				switch (options.tile.sequence) {
					case 'reverse':
						arrDelay.reverse();
						break;
					case 'col-forward':
						arrDelay = conversion(col, row, 'col-forward');
						break;
					case 'col-reverse':
						arrDelay = conversion(col, row, 'col-reverse');
						break;
					case 'random':
						arrDelay = conversion(col, row, 'random');
						break;
					case 'spiral':
						arrDelay = spiralMatrixSeq(col, row, 'spiral');
						break;
					case 'spiral-center':
						arrDelay = spiralMatrixSeq(col, row, 'spiral-center');
						break;
					case 'spread':
						arrDelay = spread(col, row, 'spread');
						break;
					case 'spread-center':
						arrDelay = spread(col, row, 'spread-center');
						break;
				}

				$this.global.innerContainer ? $this.global.innerContainer.stop(!0, !0).empty().css({
					display: 'block',
					width: inner.width(),
					height: inner.height()
				}) : $this.global.innerContainer = $('<div>').addClass('storm-inner-container').addClass('storm-overflow-hidden').css({
					width: inner.width(),
					height: inner.height()
				}).prependTo(inner);

				// Set tiles width and height
				var tilesWidth = inner.width() / col,
					tilesHeight = inner.height() / row;

				//If width and height don't full the img, get be short of width and height
				var shortWidth = inner.width() - Math.floor(tilesWidth) * col,
					shortHeight = inner.height() - Math.floor(tilesHeight) * row;

				var curBg = $this.global.curSlider.find('.storm-bg'),
					nextBg = $this.global.nextSlider.find('.storm-bg');

				if (curBg.length == 0 && nextBg.length == 0 && (type = '2d'), type == '3d') {
					$this.global.totalDuration = ((col * row - 1) * option.tile.delay) / 1e3;
					var duration = 0;
					option.after && option.after.duration && (duration += option.after.duration), option.animation.duration && (duration += option.animation.duration), option.before && option.before.duration && (duration += option.before.duration), $this.global.totalDuration += duration;

					$this.global.totalDuration *= 1e3;

					//3d delay
					var delay = 0;
					option.after && option.after.transition && option.after.transition.delay && (delay += option.after.transition.delay), option.animation.transition.delay && (delay += option.animation.transition.delay), option.before && option.before.transition && option.before.transition.delay && (delay += option.before.transition.delay), $this.global.totalDuration += delay;
				}
				else {
					$this.global.totalDuration = option.tile.sequence.indexOf('spread') == -1 ? (col * row - 1) * option.tile.delay + option.transition.duration * 1e3 : Math.max.apply(Math, arrDelay) * option.tile.delay + option.transition.duration * 1e3;

					$this.global.curTiles = $('<div>').addClass('storm-inner-curtile').appendTo($this.global.innerContainer);
					$this.global.nextTiles = $('<div>').addClass('storm-inner-nexttile').appendTo($this.global.innerContainer);
				}

				//Set sublayer setTimeout time;
				$this.global.sublayerTime = $this.global.totalDuration;

				// $this.global.curSlider.data('index', $this.global.curSliderIndex);
				// $this.global.curSlider.data('totalDuration', $this.global.totalDuration);

				if ($this.global.curSlider.find(' > *[class*="storm-sublayer"]').data('complete')) {
					var arrTime = [], dataObject = data = {};
					$this.global.curSlider.find(' > *[class*="storm-sublayer"]').each(function(){
						var time;

						dataObject = $(this).data('stormTransitionhide').split(';');

						$.each(dataObject, function(index, val) {
							var prop = val.split(':');
							data[prop[0]] = prop[1];
						});

						var transitionData = $.extend({}, $this.layerTransitions, data);

						if (transitionData.duration || transitionData.delay) {
							time = parseInt(transitionData.duration) + parseInt(transitionData.delay);
							arrTime.push(time * 1e3);
						}
					});

					$this.global.totalDuration += Math.max.apply({}, arrTime);
				}

				for (var i = 0; i < col * row; i++) {
					var prev = $this.global.prevNext, activeCurTiles, activeNextTiles;
					var fillWidth = i % col == 0 ? shortWidth : 0,
						fillHeight = i > (row - 1) * col - 1 ? shortHeight : 0,
						$tiles = $('<div>').addClass('storm-inner-container-tiles').css({
							width: Math.floor(tilesWidth) + fillWidth,
							height: Math.floor(tilesHeight) + fillHeight
						}).appendTo($this.global.innerContainer);
					if(type == '3d') {
						$tiles.addClass('storm-3d-container');
						var width = Math.floor(tilesWidth) + fillWidth,
							height = Math.floor(tilesHeight) + fillHeight,
							fill = 'vertical' == option.animation.direction ? height : width;

						var halfWidth = width / 2,
							halfHeight = height / 2,
							halfFill = fill / 2;

						var container = function(className, selecter, width, height, translateZ, translateX, translateY, rotateX, rotateY) {
							$('<div>').addClass(className).css({
								width: width,
								height: height,
								transform: "translate3d("+ translateZ +"px, "+ translateX +"px, "+ translateY +"px) rotateX("+ rotateX +"deg) rotateY("+ rotateY +"deg) rotateZ(0deg) scale3d(1, 1, 1)"
							}).appendTo(selecter);
						};
						container('storm-3d-box', $tiles, 0, 0, 0, 0, -halfFill, 0, 0);
						if ('vertical' == option.animation.direction) {
							container('storm-3d-back', $tiles.find('.storm-3d-box'), width, height, -halfWidth, -halfHeight, -halfFill, 180, 0);
						}
						else {
							container('storm-3d-back', $tiles.find('.storm-3d-box'), width, height, -halfWidth, -halfHeight, -halfFill, 0, 180);
						}
						container('storm-3d-front', $tiles.find('.storm-3d-box'), width, height, -halfWidth, -halfHeight, fill - halfFill, 0, 0);
						container('storm-3d-top', $tiles.find('.storm-3d-box'), width, fill, -halfWidth, -halfHeight - halfFill, 0, 90, 0);
						container('storm-3d-left', $tiles.find('.storm-3d-box'), fill, height, -halfFill - halfWidth, -halfHeight, 0, 0, -90);
						container('storm-3d-bottom', $tiles.find('.storm-3d-box'), width, fill, -halfWidth, halfHeight - halfFill, 0, -90, 0);
						container('storm-3d-right', $tiles.find('.storm-3d-box'), fill, height, halfWidth - halfFill, -halfHeight, 0, 0, -90);
						activeCurTiles = $tiles.find('.storm-3d-front');
						activeNextTiles = 'vertical' == option.animation.direction ? $tiles.find('.storm-3d-top, .storm-3d-bottom') : $tiles.find('.storm-3d-left, .storm-3d-right');
						var delay = arrDelay[i] * option.tile.delay,
							animationTile = $this.global.inner.find('.storm-3d-container:eq('+ i +') .storm-3d-box'),
							F = new TimelineLite;

						option.before && option.before.transition && (option.before.transition.delay = option.before.transition.delay ? (option.before.transition.delay + delay) / 1e3 : delay / 1e3, F.to(animationTile[0], option.before.duration, object3d(option.before.transition, option.before.easing)));
						option.animation.transition.delay = option.animation.transition.delay ? (option.animation.transition.delay + delay) / 1e3 : delay / 1e3;
						F.to(animationTile[0], option.animation.duration, object3d(option.animation.transition, option.animation.easing));
						option.after && option.after.transition && (option.after.transition.delay = option.after.transition.delay ? (option.after.transition.delay + delay) / 1e3 : delay / 1e3, F.to(animationTile[0], option.after.duration, object3d(option.after.transition, option.after.easing)));
						$(animationTile).data('sliderTransition', F);
 					}
					else {

						var top = left = nextTop = nextLeft = 'auto',
							opacity = nextOpacity = 1;

						//Get slide easing affect
						var easing = option.transition.easing;

						//Get slide type
						var sliderType = option.transition.type;

						//Get slide direction
						if (option.transition.direction == 'random') {
							var directionArr = ['left', 'right', 'top', 'bottom'];
							direction = directionArr[Math.floor(Math.random() * directionArr.length)];
						}
						else {
							direction = option.transition.direction;
						}

						switch (direction) {
							case 'top':
								top = -$tiles.height();
								left = 0;
								nextTop = $tiles.height();
								nextLeft = 0;
								break;

							case 'right':
								top = 0;
								left = $tiles.width();
								nextTop = 0;
								nextLeft = -$tiles.width();
								break;

							case 'bottom':
								top = $tiles.height();
								left = 0;
								nextTop = -$tiles.height();
								nextLeft = 0;
								break;

							case 'left':
								top = 0;
								left = -$tiles.width();
								nextTop = 0;
								nextLeft = $tiles.width();
								break;

							case 'topleft':
								top = 0;
								left = 0;
								nextTop = $tiles.height();
								nextLeft = $tiles.width();
								break;

							case 'topright':
								top = 0;
								left = 0;
								nextTop = $tiles.height();
								nextLeft = -$tiles.width();
								break;

							case 'bottomleft':
								top = 0;
								left = 0;
								nextTop = -$tiles.height();
								nextLeft = $tiles.width();
								break;

							case 'bottomright':
								top = 0;
								left = 0;
								nextTop = -$tiles.height();
								nextLeft = -$tiles.width();
								break;
						}

						//Switch slider type
						switch (sliderType) {
							case 'fade':
								top = left = nextTop = nextLeft = opacity = 0;
								nextOpacity = 1;
								break;
							case 'mixed':
								opacity = 0;
								nextOpacity = 1;
								option.transition.scale == 1 && (top = left = 0);
								break;
						}
						if ($tiles.css((option.transition.rotateX || option.transition.rotateY || option.transition.rotateZ || 1 != option.transition.scale) && !$this.global.ie78 && 'slide' != sliderType ? {
							overflow: 'visible'
						} : {
							overflow: 'hidden'
						}), $this.global.curTiles.css({
							overflow: 'hidden'
						}), sliderType == 'slide') {
							var curTiles = $tiles.appendTo($this.global.curTiles),
								nextTiles = $tiles.clone().appendTo($this.global.nextTiles);
							activeCurTiles = $('<div>').addClass('storm-curtile').appendTo(curTiles);
						}
						else {
							var nextTiles = $tiles.appendTo($this.global.nextTiles);
						}

						activeNextTiles = $('<div>').addClass('storm-nexttile').appendTo(nextTiles).css({
							top: nextTop,
							left: nextLeft,
							opacity: opacity,
							display: 'block'
						});

						//Get slide rotationX rotationY rotationZ
						var rotationX = option.transition.rotateX ? option.transition.rotateX : 0;
						var rotationY = option.transition.rotateY ? option.transition.rotateY : 0;
						var rotationZ = option.transition.rotateZ ? option.transition.rotateZ : 0;

						//Get slide scale
						var scale = option.transition.scale;

						//Get slide delay
						var delay = arrDelay[i] * option.tile.delay;

						if ('prev' == prev && (rotationX = -rotationX, rotationY = -rotationY, rotationZ = -rotationZ), $(activeNextTiles).data('sliderTransition', TweenLite.fromTo(activeNextTiles[0], option.transition.duration, {
								rotation: rotationZ,
								rotationX: rotationX,
								rotationY: rotationY,
								scale: scale
							}, {
								delay: delay / 1e3,
								top: 0,
								left: 0,
								opacity: nextOpacity,
								rotation: 0,
								rotationX: 0,
								rotationY: 0,
								scale: 1,
								ease: getEaseEffect(easing)
							})), 'slide' == sliderType
						) {
							var nextRotation = 0;
							0 != rotationZ && (nextRotation = -rotationZ);

							//create animate Use GASP
							$(activeCurTiles).data('sliderTransition', TweenLite.to(activeCurTiles[0], option.transition.duration, {
								delay: delay / 1e3,
								top: top,
								left: left,
								scale: scale,
								opacity: opacity,
								rotation: nextRotation,
								ease: getEaseEffect(easing)
							}));
						}
					}
					curBg.length && (type == '3d' || type == '2d' && (option.transition.type == 'slide') ? activeCurTiles.append($('<img>').attr('src', curBg.attr('src')).css({
							width: curBg[0].style.width,
							height: curBg[0].style.height,
							marginTop: parseInt(curBg.css('margin-top')) - parseInt($tiles.position().top),
							marginLeft: parseInt(curBg.css('margin-left')) - parseInt($tiles.position().left)
						})) : 0 == $this.global.curTiles.children().length && $this.global.curTiles.append($('<img>').attr('src', curBg.attr('src')).css({
							width: curBg[0].style.width,
							height: curBg[0].style.height,
							marginTop: parseFloat(curBg.css('margin-top')),
							marginLeft: parseFloat(curBg.css('margin-left'))
						})));

					if (nextBg.length) {
						activeNextTiles.append($('<img>').attr('src', nextBg.attr('src')).css({
							width: nextBg[0].style.width,
							height: nextBg[0].style.height,
							marginTop: parseFloat(nextBg.css('margin-top')) - parseFloat($tiles.position().top),
							marginLeft: parseFloat(nextBg.css('margin-left')) - parseFloat($tiles.position().left)
						}));
					}

					if($this.global.curSlider.data('linkU')) {
						var $link = $('<a class="storm-link">').appendTo(activeCurTiles);
						$link.attr('href', $this.global.curSlider.data('linkU'));
						$link.attr('target', $this.global.curSlider.data('linkTarget'));
						$link.attr('id', $this.global.curSlider.data('linkId'));
						$link.addClass($this.global.curSlider.data('linkClass'));
						$link.attr('title', $this.global.curSlider.data('linkTitle'));
						$link.attr('rel', $this.global.curSlider.data('linkRel'));
					}

					if($this.global.nextSlider.data('linkU')) {
						var $link = $('<a class="storm-link">').appendTo(activeNextTiles);
						$link.attr('href', $this.global.nextSlider.data('linkU'));
						$link.attr('target', $this.global.nextSlider.data('linkTarget'));
						$link.attr('id', $this.global.nextSlider.data('linkId'));
						$link.addClass($this.global.nextSlider.data('linkClass'));
						$link.attr('title', $this.global.nextSlider.data('linkTitle'));
						$link.attr('rel', $this.global.nextSlider.data('linkRel'));
					}

					if($this.global.curSlider.data('misc')) {
						activeCurTiles.addClass($this.global.curSlider.data('miscClass'));
						activeCurTiles.attr('id', $this.global.curSlider.data('miscId'));
						activeCurTiles.css('backgroundColor', $this.global.curSlider.data('miscBackgroundColor'));
						activeCurTiles.find('img').attr('alt', $this.global.curSlider.data('miscBackgroundAlt'));
						activeCurTiles.find('img').attr('title', $this.global.curSlider.data('miscBackgroundTitle'));
					}

					if($this.global.nextSlider.data('misc')) {
						activeNextTiles.addClass($this.global.nextSlider.data('miscClass'));
						activeNextTiles.attr('id', $this.global.nextSlider.data('miscId'));
						activeNextTiles.css('backgroundColor', $this.global.nextSlider.data('miscBackgroundColor'));
						activeNextTiles.find('img').attr('alt', $this.global.nextSlider.data('miscBackgroundAlt'));
						activeNextTiles.find('img').attr('title', $this.global.nextSlider.data('miscBackgroundTitle'));
					}
				}

				setTimeout(function(){
					$this.global.curSlider.find('.storm-bg').css({
						visibility: 'hidden'
					});
				}, 50);

				setTimeout(function(){
					$this.global.nextSlider.find('.storm-bg').css({
						visibility: 'hidden'
					});
				});

				$this.global.innerContainer.removeClass('storm-overflow-hidden');

				subLayerTweenTo();

				// 0 === num && (num = 10);

				// setTimeout(function() {
				// 	$this.global.curSlider.css({
				// 		width: 0
				// 	});
				// }, num * 1e3);


				var timeShift = parseInt($this.global.nextSlider.data('timeShift')) ? parseInt($this.global.nextSlider.data('timeShift')) : 0;
				var timeShiftDuration = $this.global.totalDuration + timeShift > 0 ? $this.global.totalDuration + timeShift : 0;

				//Show the slider on animating is done
				setTimeout(function(){
					1 == $this.global.resize && ($this.global.innerContainer.empty(), $this.response($this.global.nextSlider, function(){
						$this.global.resize = !1;
					}));

					$this.global.nextSlider.find('.storm-bg').length > 0 && $this.global.innerContainer.delay(350).fadeOut(300, function() {
						$(this).empty().show();
					});

					subLayerTweenFormTo();

					$this.global.nextSlider.css({
						width: $this.global.width,
						height: $this.global.height
					});

					$this.option.showTimeLine && $this.timeLine();

				}, timeShiftDuration);


				$this.global.totalDuration < 300 && ($this.global.totalDuration = 1e3);

				//Trigger next slide image
				setTimeout(function(){
					$this.global.innerContainer.addClass('storm-overflow-hidden');
					$this.global.nextSlider.find('.storm-bg').length ? ($this.global.nextSlider.find('.storm-bg').css({
						display: 'none',
						visibility: 'visible'
					}), $this.global.ie78 ? ($this.global.nextSlider.find('.storm-bg').css('display', 'block'), setTimeout(function() {
						setClass();
					}, 500)) : $this.global.nextSlider.find('.storm-bg').fadeIn(500, function() {
						setClass();
					})) : setClass();

				}, $this.global.totalDuration);
			};

			//Trigger play()
			var initPlay = function() {

				$this.global.nextSlider.find(' > *[class*="storm-sublayer"]' ).each(function() {
					$(this).css('visibility', 'hidden');
				});

				$this.global.sliderTop = $(el).offset().top;

				$(window).load(function() {
					setTimeout(function(){
						$this.global.sliderTop = $(el).offset().top;
					}, 20);
				});

				var i = function() {
					$(window).scrollTop() + $(window).height() - $this.global.height / 2 > $this.global.sliderTop && ($this.global.firstSlideAnimated = !0, $this.global.originalAutoStart == !0 && ($this.option.autoStart = !0, $this.start()), subLayerTweenFormTo(), $this.option.showTimeLine && $this.timeLine());
				};

				$(window).scroll(function() {
					$this.global.firstSlideAnimated || i();
				});

				i();
			};

			//Set transitionType
			var transitionType = 'normal';

			if (
				// $this.global.nextSlider.data('transitionType') || $this.getTransitionType($this.global.nextSlider), 'new' == $this.global.nextSlider.data('transitionType') && (transitionType = 'normal'),
				$this.option.slideTransition && (transitionType = 'forced'), $this.option.animateFirstSlide && !$this.global.firstSlideAnimated) {

				if ($this.global.sliderNum == 1) {

				}
				else {
					var duration = $this.global.curSlider.data('slidedelay');

					$this.global.setClass = setTimeout(function(){
						setClass();
					}, duration);

				}

				if ($this.option.startInViewport) {
					initPlay();
				}
				else {
					$this.global.firstSlideAnimated = !0;
					subLayerTweenFormTo();
				}

				$this.global.nextSlider.css({
					width: $this.global.width,
					height: $this.global.height
				});

				$this.global.ie78 || $this.global.nextSlider.find('.storm-bg').css({
					display: 'none'
				}).fadeIn($this.option.sliderFadeInDuration);

				$this.global.isLoading = !1;
			}
			else {
				switch (transitionType) {
					// case 'old':
					//  	$this.global.innerContainer && $this.global.innerContainer.empty(), curSliderAnimate(), subLayerTweenTo(), nextSliderAnimate(), subLayerTweenFormTo();

					// 	break;
					case 'normal':
						// if ('undefined' !== typeof $this.transitionCustomData) {
						// 	checkCustomTransitionType();
						// }
						// else {
							checkTransitionType();
						// }
						break;
					case 'forced':
						play($this.option.slideTransition.type, $this.option.slideTransition.obj);
						break;
				}
			}
		},

		// $this.getTransitionType = function(el) {
		// 	var data = !el.data('option') && (el.data('option') || el.data('slidedelay') || el.data('slideDirection') || el.data('slideOutDirection') || el.data('delayShow') || el.data('delayHide') || el.data('durationShow') || el.data('durationHide') || el.data('easeIn') || el.data('easeInOut')) ? 'old' : 'new';

		// 	el.data('transitionType', data);
		// },

		$this.load()
	},

	conversion = function(col, row, type) {
		var arr = [];

		switch (type) {
			case 'col-forward':
				for (var c = 0; c < col; c++) {
					for (var r = 0; r < row; r++) {
						arr.push(c + r * col);
					}
				}
				break;
			case 'col-reverse':
				for (var c = col - 1; c > -1; c--) {
					for (var r = row - 1; r > -1; r--) {
						arr.push(c + r * col);
					}
				}
				break;
			case 'random':
				var randomNum, length = col * row;
				for (var i = 0; i < length; i++) {
					randomNum = Math.floor(Math.random() * length);
					arr.push(randomNum);
				}
				break;
		}

		return arr;

	},

	spiralMatrixSeq = function(col, row, type) {

		var seq = function(col, row, type) {
			var arr = [], finalArr = [];
			var num = 0, oldIndex;

			for (var i = 0; i < row * col; i++) {
				finalArr.push(i + 1);
			}

			for (var r = 0; r < row; r++) {
				arr[r] = [];
				for (var c = 0; c < col; c++) {
					num += 1;
					arr[r][c] = num;
				}
			}

			var oldArr = spiral(col, row, arr);

			if (type == 'spiral-center') {
				oldArr.reverse();
			}

			finalArr.forEach( function(element, index) {
				oldIndex = oldArr.indexOf(index + 1);
				finalArr[index] = oldIndex + 1;
			});

			return finalArr;
		}

		var spiral = function(col, row, arr) {
			var k = l = 0, newArr = [];
			while (k < row && l < col) {
				//first row
				for (var i = l; i < col; i++) {
					newArr.push(arr[k][i]);
				}
				k++;

				//last col
				for (var i = k; i < row; i++) {
					newArr.push(arr[i][col - 1]);
				}
				col--;

				//last row
				if (k < row) {
					for (var i = col - 1; i >= l; i--) {
						newArr.push(arr[row - 1][i]);
					}
					row--;
				}

				//first col
				if (l < col) {
					for (var i = row - 1; i >= k ; i--) {
						newArr.push(arr[i][l]);
					}
					l++;
				}
			}

			return newArr;
		}

		return seq(col, row, type);
	},

	spread = function(col, row, type) {

		var spreadSeq = function(col, row, type) {
			var arr = [], finalArr = [], dataArray = [];
			var oldIndex, num = 0;

			for (var i = 0; i < col * row; i++) {
				finalArr.push(i + 1);
				dataArray.push(i + 1);
			}

			for (var r = 0; r < row; r++) {
				arr[r] = [];
				for (var c = 0; c < col; c++) {
					num += 1;
					arr[r][c] = num;
				}
			}

			var oldArr = spreadMatrix(col, row, arr);

			oldArr.forEach( function(oldElement, oldIndex) {

					for (var i = 0; i < finalArr.length; i++) {
						if (finalArr[i] == oldElement[0]) {
							dataArray[i] = oldElement[1];
						}
					}
			});

			if (type == 'spread-center') {
				var max = Math.max.apply({}, dataArray);
				for (var i = 0; i < dataArray.length; i++) {
					dataArray[i] = max - dataArray[i];
				}
			}


			return dataArray;

		};

		var spreadMatrix = function(col, row, arr) {
			var k = l = n = 0, indexArr = Arr = [];

			while (k < row && l < col) {

				var newArr = [];

				//first row
				for (var i = l; i < col; i++) {
					newArr.push(arr[k][i]);
				}
				k++;

				//last col
				for (var i = k; i < row; i++) {
					newArr.push(arr[i][col - 1]);
				}
				col--;

				//last row
				if (k < row) {
					for (var i = col - 1; i >= l; i--) {
						newArr.push(arr[row - 1][i]);
					}
					row--;
				}

				//first col
				if (l < col) {
					for (var i = row - 1; i >= k ; i--) {
						newArr.push(arr[i][l]);
					}
					l++;
				}

				for (var i = 0; i < newArr.length; i++) {
					var data = newArr[i];
					var dataArr = [];
					dataArr.push(data);
					dataArr.push(n);
					Arr.push(dataArr);
				}

				n++;
				newArr = [];
			}

			return Arr;
		}

		return spreadSeq(col, row, type);

	},

	checkBrowser = function() {
		function match(info) {
			info = info.toLowerCase();
			var	chrome = /(chrome)[ \/]([\w.]+)/,
				webkit = /(webkit)[ \/]([\w.]+)/,
   				opera = /(opera)(?:.*version)?[ \/]([\w.]+)/,
    			msie = /(msie) ([\w.]+)/,
    			mozilla = /(mozilla)(?:.*? rv:([\w.]+))?/;

    		var match = chrome.exec(info) || webkit.exec(info) || opera.exec(info) || msie.exec(info) || info.indexOf('compatible') < 0 && mozilla.exec(info) || [];

    		return {
    			browser: match[1] || '',
    			version: match[2] || '0'
     		};
		}
		return match(navigator.userAgent);
	},

	getEaseEffect = function(easing) {
		var e, easeName;
		if(easing.toLowerCase().indexOf('linear') !== -1 || easing.toLowerCase().indexOf('swing') !== -1) {
			e = Linear.easeNone;
		}
		else if(easing.toLowerCase().indexOf('easeout') !== -1) {
			easeName = easing.toLowerCase().split('easeout')[1];
			e = window[easeName.charAt(0).toUpperCase() + easeName.slice(1)].easeOut;
		}
		else if(easing.toLowerCase().indexOf('easeinout') !== -1){
			easeName = easing.toLowerCase().split('easeinout')[1];
			e = window[easeName.charAt(0).toUpperCase() + easeName.slice(1)].easeInOut;
		}
		else if(easing.toLowerCase().indexOf('easein') !== -1) {
			easeName = easing.toLowerCase().split('easein')[1];
			e = window[easeName.charAt(0).toUpperCase() + easeName.slice(1)].easeIn;
		}

		return e;
	},

	object3d = function(options, easing, type, strings) {
		var option = {};
		return 	options.rotateX !== strings && (option.rotationX = options.rotateX), options.rotateY !== strings && (option.rotationY = options.rotateY), options.delay !== strings && (option.delay = options.delay), options.scale3d !== strings && (option.scaleX = option.scaleY = option.scale = options.scale3d), option.ease = getEaseEffect(easing), option;
	};

	slider.global = {
		ie78: checkBrowser().msie && checkBrowser().version < 9 ? !0 : !1,
		originalAutoStart: !1,
		prevNext: 'next',
		autoSliderShow: !1,
		isAnimating: !1,
		isLoading: !1,
		width: null,
		height: null,
		slideTimer: null,
		slideDirections: {
			prev: {
				left: "right",
				right: "left",
				top: "bottom",
				bottom: "top"
			},
			next: {
				left: "left",
				right: "right",
				top: "top",
				bottom: "bottom"
			}
		},
		pausedTimeLine: !0
	};

	slider.layerTransitions = {
		fade: !1,
		easing: 'linear',
		duration: 1,
		delay: 0,
		offsetX: 0,
		offsetY: 0,
		rotate2D: !0,
		rotateX: 0,
		rotateY: 0,
		rotateZ: 0,
		scaleX: 0,
		scaleY: 0,
		skewX: 0,
		skewY: 0,
		originX: '50%',
		originY: '50%',
		originZ: 0,
		transformPerspective: 500
	};

	slider.slideTransitions = {
		slideDelay: 1e3
	};

	slider.options = {
		// fullScreen: !0,
		layout: 'response',
		response: !0,
		pauseHover: !1,
		backgroundColor: 'transparent',
		backgroundImage: !1,
		backgroundRepeat: 'no-repeat',
		backgroundAttachment: 'scroll',
		backgroundPosition: 'center center',
		backgroundSize: 'auto',
		navigationShow: !0,
		navigationColor: '000000',
		navigationActiveColor: 'ffffff',
		randomSliderShow: !1,
		sliderFadeInDuration: 350,
		firstSlide: 1,
		animateFirstSlide: !0,
		slideDirection: 'right',
		slideDelay: 1e3,
		delayShow: 0,
		delayHide: 0,
		durationShow: 1e3,
		durationHide: 1e3,
		easeIn: 'easeInOutQuart',
		easeInOut: 'easeInOutQuart',
		startInViewport: !0,
		autoStart: !0,
		isPreload: !0,
		showNavigationButton: !0,
		showCircleTimer: !0,
		showBarTimer: !0,
		showStartStopButton: !0,
		showThumbButton: !0,
		showThumbImg: !1,
		showThumbImgAmount: 5,
		lazyLoad: !0,
		showTimeLine: !1,
	};
})(jQuery);
