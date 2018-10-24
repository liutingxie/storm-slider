/*
* @Author: liutingxie
* @Date:   2016-07-04 10:30:42
* @Last Modified by:   liutingxie
* @Last Modified time: 2017-10-09 10:55:29
*/

;(function($, window, document){
	var defaults = {
		slideWidth : 300,
		speed : 500
	};

	$.fn.stormtouch = function(options) {
		var settings = $.extend({}, defaults, options);
		var el = this;
		var slide = {};
		var isTouch = 'ontouchstart' in document,
		    isPointer = window.navigator.pointerEnabled,
			isMsPointer = !isPointer || window.navigator.msPointerEnabled;

			//Events
			// ev_start = (isPointer ? 'pointerdown ' : '') + (isMsPointer ? 'MSPointerDown ' : '') + (isTouch ? 'touchstart ' : '') + 'mousedown',
			// ev_move  = (isPointer ? 'pointermove ' : '') + (isMsPointer ? 'MSPointerMove ' : '') + (isTouch ? 'touchmove ' : '') + 'mousemove',
			// ev_end   = (isPointer ? 'pointerend ' : '') + (isMsPointer ? 'MSPointerEnd ' : '') + (isTouch ? 'touchend ' : '') + 'mouseup',
			// ev_cancel= (isPointer ? 'pointercancel ' : '') + (isMsPointer ? 'MSPointerCancel ' : '') + 'touchcancel';


			var init = function() {
					slide.touch = {
						start : { x : 0, y : 0 },
						end   : { x : 0, y : 0 }
					};
					el.bind('touchstart', touchstart);
			};

			var touchstart = function(ev) {
				var  orig = ev.originalEvent;
				slide.touch.originalPos = el.position();
				slide.touch.start.x = orig.changedTouches[0].pageX;
				slide.touch.start.y = orig.changedTouches[0].pageY;
				el.bind('touchmove', touchmove).bind('touchend', touchend).bind('touchcancel', touchcancel);
			};

			var touchmove = function(ev) {
				ev.preventDefault();
				var orig = ev.originalEvent;
				var xMovement = orig.changedTouches[0].pageX - slide.touch.start.x;
				var yMovement = Math.abs(orig.changedTouches[0].pageY - slide.touch.start.y);
				var before_trans = slide.touch.originalPos.left + xMovement;
				el.parent().css({transform: 'translateX('+before_trans+'px)', transition: 'transform 0ms'});
			};

			var touchend = function(ev) {
				var orig = ev.originalEvent;
				slide.touch.end.x = orig.changedTouches[0].pageX;
				slide.touch.end.y = orig.changedTouches[0].pageY;
				el.unbind('touchmove', touchmove).unbind('touchend', touchend).unbind('touchcancel', touchcancel);
			};

			var touchcancel = function(ev) {
				touchEnd(ev);
			};

			init();

			return this;
	};
})(jQuery, window, document);
