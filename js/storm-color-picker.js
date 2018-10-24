/*
* @Author: qwer2370722
* @Date:   2016-06-03 09:37:57
* @Last Modified by:   37156
* @Last Modified time: 2016-11-25 09:06:10
*/
;(function ($, undefined) {
	jQuery(document).ready(function (){
		var myOptions = {
		// you can declare a default color here,
		// or in the data-default-color attribute on the input
		defaultColor: true,
		// a callback to fire whenever the color changes to a valid color
		change: function(event, ui){},
		// a callback to fire when the input is emptied or an invalid color
		clear: function() {},
		// hide the color picker controls on load
		hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
		};
		$('.storm-color-picker').wpColorPicker();
	});
}(jQuery, undefined));
