/*
* @Author: qwer2370722
* @Date:   2016-04-21 11:20:04
* @Last Modified by:   liutingxie
* @Last Modified time: 2018-01-06 18:07:10
*/
var currentId = 0;
var activeSlideIndex = 0;
var activeSlideLayerIndex = 0;
var activeSlideData = null;
// var activeSlideTransData = null;
var activeSlideLayerTransShowData = null;
var activeSlideLayerTransHideData = null;
var activeSlideParamData = null;
var activeSlideLayerData = null;
var presetTransData = null;
var slideCount = 0;
var sliderData = {};
var slideParamData = {};
var slideLayerData = {};
var slideTransData = {};
var slideParamLinkData = {};
var slideParamMiscData = {};
var action;
var transData = null;
var playInterval;
var oneSecWidth = 80;
var jscrollPaneInit;
var parentOffsetLeft;

(function($){
	$.fn.customCheckbox = function() {
		return this.each(function() {
			// Get element
			var $el = $(this).hide();

			// Set replace element
			var html = '<div class="storm-switchbox"><div class="storm-switch-cont"><span class="storm-switch-off">OFF</span><div class="storm-switch-handle"></div><span class="storm-switch-on">ON</span></div></div>';
			var $rel = $(html).insertAfter(this);

			if($el.prop('checked')) {
				$rel.addClass('switched');
			}
			else {
				$rel.removeClass('switched');
			}
		});
	};
})(jQuery);

(function($) {
	// Sort by index to insert
	$.fn.appendIndex = function(dom, index) {
		if(!dom instanceof jQuery) { dom = $(dom); }

		if(index === 0) {
			this.prependTo(dom);
		}
		else {
			this.insertAfter( dom.children(':eq('+(index-1)+')') );
		}

		return this;
	};

})(jQuery);

	var stormSliderRange = function() {
		this._parentOffsetLeft = jQuery('.image-layer-timeline-list').offset().left;
		this.ranges = [];
	},
	p = stormSliderRange.prototype;

	p.addRange = function(el, value, labelProxy) {
		var element = jQuery(el),
			tooltip = jQuery('<div></div>').addClass('storm-tooltip'),
			range = {
				element: element,
				tooltip: tooltip,
				labelProxy: labelProxy,
				value: value
			},
			that = this;

		element.data('range', range);

		element.mousedown(function(event) {
			var $this = jQuery(this);
			that._startX = event.pageX;
			that._startW = jQuery(this).width();
			that._draging = $this;
			that._start = !0;

			jQuery(document).bind('mousemove', {ref: that}, that._onMouseMove).bind('mouseup', {ref: that}, that._onMouseUp);

		}).mouseover(function() {
			that._start || (tooltip.stop(!0).fadeIn(150), tooltip.appendTo('body'), that.updateTooltip(range))
		}).mouseout(function() {
			that._start || tooltip.stop(!0).fadeIn(150, function() {
				tooltip.detach();
			})
		});

		this.ranges.push(range);
	};

	p.remove = function() {
		for (var i = 0; i < this.ranges.length; i++) {
			this.ranges[i].tooltip.remove();
			this.ranges[i] = null;
		}
	};

	p.updateTooltip = function(range) {
		var tooltip = range.tooltip,
			el_pos = range.element.offset();

		if(el_pos.left < this._parentOffsetLeft + 10) {
			el_pos.left = this._parentOffsetLeft + 10;
		}

		tooltip.css({
			top: el_pos.top,
			left: el_pos.left
		});

		tooltip.html(range.labelProxy.call(null, range));
	};

	p.setValue = function(range, value) {
		range.value = value;
		range.element.width(value);
	};

	p._onMouseMove = function(event) {
		var that = event.data.ref,
			range = that._draging.data('range');
		// Calculation move px
		var moveX = event.pageX - that._startX;

		// Calculation
		var x = (that._startW + moveX);

		that.setValue(range, x);
		that.updateTooltip(range);
		StormLayer.updateLayerTimeline();
		event.preventDefault();
	};

	p._onMouseUp = function(event) {
		var that = event.data.ref;
		that._start = !1;
		that._draging.trigger('mouseout');
		that._draging = null;
		jQuery(document).unbind('mousemove', that._onMouseMove).unbind('mouseup', that._onMouseUp);
	};

function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

var StormLayer = {
	dragIndex : 0,
	spin: function(el, min) {
		jQuery(el).spinner({
				min: min,
				step: 1,
				numberFormat: 'N',
				// change: function() {
				// 	jQuery(document).trigger(
				// 		jQuery.Event('change', {target: this})
				// 	);
				// },
				spin: function(event, ui) {
					jQuery(this).val(ui.value);
					jQuery(document).trigger(
						jQuery.Event('change', {target: this})
					);
				}
		});
	},

	addColorPicker: function(el) {
		jQuery(el).minicolors({
			opacity: true,
			changeDelay: 100,
			position: 'bottom left'
		});
	},

	selectSlide: function(el) {
		if( jQuery(el).hasClass('active') ) {
			return false;
		}

		jQuery(el).siblings().find('.image-thumb-container-content').removeClass('active');
		jQuery(el).find('.image-thumb-container-content').addClass('active');
		jQuery('.show-layer').hide();
		jQuery('.tabs li').removeClass('active');
		jQuery('.tabs').find('li:first').addClass('active');
		jQuery('.slide-box-content li').removeClass('active');
		jQuery('.slide-box-content').find('li:first').addClass('active');

		activeSlideIndex = jQuery(el).index();
		activeSlideData = window.stormSliderData.layers[activeSlideIndex];
		activeSlideParamData = activeSlideData.param;

		activeSlideLayerIndex = 0;
		activeSlideLayerData = activeSlideData.sublayer ? activeSlideData.sublayer[activeSlideLayerIndex] : null;

		StormLayer.setSlideData();
		StormLayer.buildLayerlist();

		StormLayer.bindAllRange();
	},

	setSlideData: function() {

		var $sliderBody = jQuery('.slide-settings');
		var $links = jQuery('.slide-settings .slide-link').find('input, select');
		var $miscs = jQuery('.slide-settings .slide-misc').find('input, select');
		var $items = jQuery('.slide-settings .slide-style').find('input, select');

		$sliderBody.find('.storm-switchbox').remove();
		$sliderBody.find('input:checkbox').prop('checked', false);
		$sliderBody.find('.storm-color-picker').minicolors('destroy');
		StormLayer.setItems($items, activeSlideParamData);
		StormLayer.setItems($links, activeSlideParamData.slideLink);
		StormLayer.setItems($miscs, activeSlideParamData.slideMisc)

		$sliderBody.find('input:checkbox').customCheckbox();

		StormLayer.addColorPicker($sliderBody.find('.storm-color-picker'));

		// Get fillMode data and set data to the html
		var fillModeType = $sliderBody.find('input[name=fillMode]').val();
		var fillModeSrc = window.stormDefaultData.layers.param.fillMode.src[fillModeType];
		var fillModeTitle = window.stormDefaultData.layers.param.fillMode.title[fillModeType];

		jQuery('.select-mode-image').attr({'src': fillModeSrc, 'alt': fillModeType});
		jQuery('.select-mode-title').html(fillModeTitle);

		// Get positionMode data and set data to the html
		var positionModeType = $sliderBody.find('input[name=positionMode]').val();
		var positionModeSrc = window.stormDefaultData.layers.param.positionMode.src[positionModeType];
		var positionModeTitle = window.stormDefaultData.layers.param.positionMode.title[positionModeType];

		jQuery('.current-position-image').attr({'src': positionModeSrc, 'alt': positionModeTitle});
		jQuery('.current-position-title').html(positionModeTitle);

		StormLayer.showKenBurnsOption();

		StormLayer.updatePreview();
	},

	showKenBurnsOption: function() {
		// Show transition hide
		if (activeSlideParamData.checkKenBurns) {
			jQuery('.storm-ken-burns-container').show();
		}
		else {
			jQuery('.storm-ken-burns-container').hide();
		}
	},

	selectLayer: function(el) {
		if( jQuery(el).hasClass('active') ) {
			return false;
		}

		// Add class for li
		var index = jQuery(el).index();
		jQuery(el).siblings().removeClass('active').end().addClass('active');
		jQuery('.timeline-view').removeClass('active');
		jQuery('.image-layer-timeline-ul').find('li').eq(index).children('div').addClass('active');
		jQuery('.tabs li').removeClass('active');
		jQuery('.tabs').find('li:first').addClass('active');
		jQuery('.layer-container-content li').removeClass('active');
		jQuery('.layer-container-content').find('li:first').addClass('active');

		activeSlideLayerIndex = index;
		activeSlideLayerData = activeSlideData.sublayer[activeSlideLayerIndex];
		activeSlideLayerTransShowData = activeSlideLayerData.transitionShow;
		activeSlideLayerTransHideData = activeSlideLayerData.transitionHide;

		StormLayer.setSlideLayerData();
		StormLayer.appendLayerType(activeSlideLayerData.layerType);
		StormLayer.updateLayerTimeline();

		// Show transition hide
		if (activeSlideLayerData.checkLayerTransitionHide) {
			jQuery('.layerTransHide-container').show();
		}
		else {
			jQuery('.layerTransHide-container').hide();
		}
	},

	setSlideLayerData: function() {
		var $sliderBody = jQuery('.show-layer').show();
		var $items = $sliderBody.find('input, select');
		$sliderBody.find('.storm-switchbox').remove();
		$sliderBody.find('input:checkbox').prop('checked', false);
		$sliderBody.find('.storm-color-picker').minicolors('destroy');
		StormLayer.setItems($items, activeSlideLayerData);

		$sliderBody.find('input:checkbox').customCheckbox();

		StormLayer.addColorPicker($sliderBody.find('.storm-color-picker'));

	},

	setItems: function($items, values) {
		if( typeof $items == 'undefined' || typeof values == 'undefined' ) {
			return false;
		}

		for( var itemIndex = 0; itemIndex < $items.length; itemIndex++) {
			var $item = jQuery($items[itemIndex]);
			var value = values[$item.attr('name')];

			if( typeof value == 'undefined' || !value && !$item.is('textarea')) {
				$item.val('');
				continue;
			}

			if($item.is(':checkbox')) {
				$item.prop('checked', Boolean(value));
			}
			else if($item.is('input')) {
				$item.val(value);
			}
			else if($item.is('select')) {
				$item.children().prop('selected', false);
				$item.children('[value="'+value+'"]').prop('selected', true);
			}
		}
	},

	addSlide: function() {
    	StormLayer.openMediaLibrary('add');
	},

	addAndEditBgImage: function() {
		StormLayer.openMediaLibrary('addAndEditBgImage');
	},

	removeBgImage: function() {
		jQuery('.storm-bg-newuploader').css('backgroundImage', 'none');

		window.stormSliderData.slider.backgroundImage = '';
		StormLayer.updatePreview();
	},

	addLayerImage: function(){
		StormLayer.openMediaLibrary('layerImage');
	},

	editSlide: function(el){
		StormLayer.openMediaLibrary('edit', el);
	},

	duplicateSlide: function(el){
		var $duplicateEl = jQuery(el).parents('li');
		var $cloneEl = $duplicateEl.clone();
		if( $cloneEl.find('.image-thumb-container-content').hasClass('active') ) {
			$cloneEl.find('.image-thumb-container-content').removeClass('active');
		}

		// Remove attr aria-describedby and change title
		$cloneEl.find('.duplicate-slide').removeAttr('aria-describedby').attr('title', 'Duplicate slide');

		// Add $clineEl after $duplicateEl
		$duplicateEl.after($cloneEl);

		// Add data to stormSliderData
		var index = $duplicateEl.index();
		var duplicateSlideData = window.stormSliderData.layers[index];
		window.stormSliderData.layers.splice( index+1, 0,
			jQuery.extend(true, {}, duplicateSlideData)
		);
		StormLayer.resetIndexSlide();
	},

	removeSlide: function(el){
		if(confirm('Are you sure your want to remove this slide?')) {
			var $removeEl = jQuery(el).parents('li');
			var index = $removeEl.index();
			var $activeEl = $removeEl.find('.image-thumb-container-content').hasClass('active') ? $removeEl.next() : $removeEl.siblings().find('.active');

			window.stormSliderData.layers.splice(index, 1);
			$removeEl.remove();
			$activeEl.click();
			StormLayer.resetIndexSlide();

			// If slide is all remove
			var length = jQuery('.image-thumb').length;

			if(!length){
				jQuery('.previewLayer').css({backgroundImage: 'none'});
				jQuery('.layer-ul').empty();
				jQuery('.show-layer').hide();
			}
		}
	},

	save: function() {

		var sliderData = jQuery.extend(true, {}, window.stormSliderData);
		if(!sliderData.slider && !sliderData.layers) return false;
		// Serialize
		sliderData.slider = JSON.stringify(sliderData.slider);

		jQuery.each(sliderData.layers, function(layerIndex, layerData){
			jQuery.each(sliderData.layers.param, function(paramIndex, paramData){
				sliderData.layers.param[paramIndex] = JSON.stringify(paramData);
			});
			if(!sliderData.layers.sublayer) {
				jQuery.each(sliderData.layers.sublayer, function(sublayerIndex, sublayerData){
					sliderData.layers.sublayer[sublayerIndex] = JSON.stringify(sublayerData);
				});
			}
			sliderData.layers[layerIndex] = JSON.stringify(layerData);
		});

		jQuery("#saveImage").addClass('saving').val('Saving...').attr('disabled', true);

		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'text',
			data: {action: action, option_name: 'preset_effect', data: JSON.stringify(presetTransData)}
		});

		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'text',
			data: {action: 'storm_slider', id: currentId, data: sliderData},
			error: function(errorThrown) {
				alert('It seems there is a server issue that prevented LayerSlider from saving your work. Please, try to temporary disable themes/plugins, or contact with your hosting provider. Your HTTP server thrown the following error: \n\n' + errorThrown);
			},
			complete: function() {
				jQuery('#saveImage').removeClass('saving').addClass('saved').val('Saved');

				setTimeout(function(){
					jQuery('#saveImage').removeClass('saved').val('Save slider').attr('disabled', false);
				}, 2000);
			}
		});
	},

	openMediaLibrary: function(action_type, el) {
		  var frame;
		  if( frame ){
		    frame.open();
		    return;
		  }
		  var type = jQuery(this).hasClass('storm-insert-media') ? 'video,audio' : 'image';
		  var slideCount = jQuery(".image-thumb").length;
		  frame = wp.media({
		  	title : 'Choose',
		  	library: { type: type },
		  	button: { text: 'Insert' }
		  });

		  frame.on('select', function(){
		  	var attachment = frame.state().get('selection').first().toJSON();
		    var image_url = attachment.url;
		    var thumb_url = (attachment.sizes && attachment.sizes.thumbnail) ? attachment.sizes.thumbnail.url : image_url;
		  	switch (action_type) {
		  		case 'add':
						StormLayer.slider_layer(++slideCount, thumb_url, image_url);

						var newSlideParamData = jQuery.extend(true, {}, StormLayer.getSlideParamData());
						var newSlideLayerData = jQuery.extend(true, {}, StormLayer.getSlideLayerData());

						newSlideParamData.background = image_url;
						newSlideParamData.backgroundThumb = thumb_url;
						if(slideCount == 1) {
							window.stormSliderData.layers = [{
								param: newSlideParamData
							}];
						}
						else {
							window.stormSliderData.layers.push({
							  	param: newSlideParamData
							});
						}

						StormLayer.selectSlide(jQuery('.image-thumb').last());
		  			break;
		  		case 'edit':
		  		   var elIndex = jQuery(el).parents('li').index();
				   window.stormSliderData.layers[elIndex].param.backgroundThumb = thumb_url;
				   window.stormSliderData.layers[elIndex].param.background = image_url;
				   jQuery('#image-thumb-img-'+(elIndex+1)).css({'backgroundImage': 'url('+thumb_url+')'});
				   jQuery('.previewLayer').css({'backgroundImage': 'url('+image_url+')'});
		  			break;
		  		case 'addAndEditBgImage':
		  			jQuery('.storm-bg-newuploader').css({'backgroundImage': 'url('+thumb_url+')'});

		  			window.stormSliderData.slider.backgroundImage = thumb_url;
		  			break;
		  		case 'layerImage':
		  			jQuery('.storm-layer-newuploader').css({'backgroundImage': 'url('+thumb_url+')'}).addClass('active');
		  			jQuery('.slideSubLayerImage').val(image_url);
		  			activeSlideLayerData.slideSubLayerImage = image_url;
		  			break;
		  	}
		  	StormLayer.updatePreview();
		  }).open();
	},

	resetIndexSlide: function() {
		// Reset slide order name
		jQuery('.image-thumb').each(function(index){
			jQuery(this).find('.image-order').html('#' + (index + 1));
		});
	},

	// resetIndexLayer: function() {
	// 	// Reset layer title
	// 	var $items = jQuery('.layer-ul-li');
	// 	jQuery.each($items, function(){
	// 		var $item = jQuery(this);
	// 		var index = $item.index();
	// 		var title = 'Layer #' + (index + 1);
	// 		$item.find('input[name=layerName]').val(title);

	// 		activeSlideData.sublayer[index].layerName = title;
	// 	});
	// },

    slider_layer: function(order_id, thumb_url, image_url){

		var $template = jQuery(jQuery('#storm-slide-template').text());
		$template.find('.image-order').html('#' + order_id);
		$template.find('.image-thumb-img').css('backgroundImage', 'url('+thumb_url+')').attr('id', 'image-thumb-img-'+ order_id);
		jQuery(".image-thumb-add").before($template);

		jQuery("#previewLayer").css("background-image", "url('"+image_url+"')");
		jQuery("#imageurl").css("background-image", "url('"+image_url+"')");
		jQuery("#thumburl").css("background-image", "url('"+thumb_url+"')");
	},

	storm_change_tab: function(el, className) {
		jQuery(el).siblings().removeClass('active').end().addClass('active');
		jQuery('.'+className).siblings().removeClass('active').end().addClass('active');
	},

	addLayer: function(type, image_url) {
		var layerIndex = jQuery('.layer-ul-li').length;
		var layerName = 'Layer';
		var layer = StormLayer.layerView(type, layerName, image_url);

		jQuery('.layer-ul').append(layer);

		var $timelineLayer = jQuery(jQuery('#storm-layer-timeline-list-template').text());
		jQuery('.image-layer-timeline-ul').append($timelineLayer);

		var LayerData = jQuery.extend(true, {}, StormLayer.getSlideLayerData());
		LayerData.layerType = type;
		// LayerData.layerName = layerName;

		if(layerIndex === 0) {
			activeSlideData.sublayer = [LayerData];
		}
		else {
			activeSlideData.sublayer.push(LayerData);
		}

		// Set the layer transition data
		activeSlideData.sublayer[layerIndex].transitionShow = jQuery.extend(true, {}, StormLayer.getSlideTransitionData());
		activeSlideData.sublayer[layerIndex].transitionHide = jQuery.extend(true, {}, StormLayer.getSlideTransitionData());

		// Set ul timeline width
		jQuery('.layer-ul-li').last().find('.layer-durationShow-timeline').css('width', '80px').attr('title', 'Show duration: 1s');

		StormLayer.updatePreview();
		jscrollPaneInit.reinitialise();

		StormLayer.bindAllRange();
	},

	duplicateLayer: function(el) {
		var $duplicateLayer = jQuery(el).parents('li');
		var duplicateIndex = $duplicateLayer.index();
		var $cloneLayer = $duplicateLayer.clone();
		$cloneLayer.removeClass('active');
		$duplicateLayer.after($cloneLayer);

		var $timelineLayer = jQuery('.image-layer-timeline-ul li').eq(duplicateIndex);
		var $timelineClone = $timelineLayer.clone();
		$timelineClone.find('div').removeClass('active');
		$timelineLayer.after($timelineClone);

		// Duplicate layer data
		activeSlideData.sublayer.splice(
			duplicateIndex + 1, 0, jQuery.extend(true, {}, activeSlideData.sublayer[duplicateIndex]));

		// Reset layer title
		// StormLayer.resetIndexLayer();

		StormLayer.updatePreview();
	},

	removeLayer: function(el) {
		if(confirm('Are you sure your want to remove this layer?')) {

			var $removeLayer = jQuery(el).parents('li');
			var removeIndex = $removeLayer.index();
			var $nextActiveLayer = $removeLayer.next('li').length ? $removeLayer.next('li') : $removeLayer.prev('li');
			$removeLayer.remove();

			jQuery('.image-layer-timeline-ul li').eq(removeIndex).remove();
			// Remove layer data
			activeSlideData.sublayer.splice(removeIndex, 1);

			StormLayer.updatePreview();

			if($nextActiveLayer.length) {
				// Open it. The .click() event will maintain the new layer index and data
				$nextActiveLayer.click();

				// Reset layer title
				// StormLayer.resetIndexLayer();
			}
			else {
				// Hide the layer div
				jQuery('.show-layer').hide();
				jscrollPaneInit.reinitialise();
			}
		}
	},

	updatePreview: function() {
		var sliderProps = window.stormSliderData.slider;
		var slideIndex = activeSlideIndex;
		var slideData = activeSlideData;
		var layers = slideData.sublayer;
		var sliderParamProps = activeSlideParamData;

		// Get the main elements
		var previewBg = jQuery('.preview-bg');
		var preview = previewBg.find('.previewLayer');
		var settings = jQuery('.options-container');

		// Get slide image
		var image = sliderParamProps.background;

		// Set slide image
		preview.css('backgroundImage', 'url('+image+')');

		// Get sizes
		var width = sliderProps.width;
		var height = sliderProps.height;

		// Set sizes
		previewBg.css({width: width, height: height});
		preview.css({width: width, height: height});

		// Get backgrounds
		var bgColor = sliderProps.backgroundColor;
			bgColor = bgColor !== '' ? bgColor : 'transparent';

		var bgImage = sliderProps.backgroundImage;
			bgImage = bgImage !== '' ? 'url('+bgImage+')' : 'none';

		var bgAttachment = sliderProps.backgroundAttachment;
		var bgRepeat = sliderProps.backgroundRepeat;
		var bgPosition = sliderProps.backgroundPosition.replace('-', ' ');

		switch(sliderProps.backgroundSize) {
			case 'center':
				previewBg.css({'background-size': 'auto'});
				break;
			case 'stretch':
				previewBg.css({'background-size': '100% 100%'});
				break;
			case 'cover':
				previewBg.css({'background-size': 'cover'});
				break;
			case 'contain':
				previewBg.css({'background-size': 'contain'});
				break;
		}

		// Set background
		previewBg.css({
			'backgroundImage': bgImage,
			'backgroundColor': bgColor,
			'backgroundAttachment': bgAttachment,
			'backgroundRepeat': bgRepeat,
			'backgroundPosition': bgPosition
		});

		switch(sliderParamProps.fillMode) {
			case 'center':
				preview.css({'background-size': 'auto'});
				break;
			case 'stretch':
				preview.css({'background-size': '100% 100%'});
				break;
			case 'cover':
				preview.css({'background-size': 'cover'});
				break;
			case 'contain':
				preview.css({'background-size': 'contain'});
				break;
		}

		// Set background-position prop
		var positionVal = sliderParamProps.positionMode !== 'undefined' ? sliderParamProps.positionMode.replace('-', ' ') : 'center center';
		preview.css('background-position', positionVal);

		preview.css({'background-repeat': 'no-repeat'});

		// empty preview
		preview.empty();

		//Iterate over layers
		jQuery.each(layers, function(layerIndex, layerData){
			StormLayer.updateLayerPreview(layerIndex, layerData);
		});
 	},

	updateLayerPreview: function(layerIndex, layerData) {
		var $previewLayer = jQuery('#previewLayer');
		var layerItem = $previewLayer.children(':eq('+layerIndex+')');

		// Remove existing item when updating specific layer
		if(layerItem.length) { layerItem.remove(); }

		// Hidden layer
		if(layerData.show) {
			jQuery('<div>').appendIndex($previewLayer, layerIndex).hide();

			return true;
		}

		// Append layer to preview
		switch(layerData.layerType){
			case 'text':
				content = layerData.slideSubLayerText;
				break;
			case 'image':
				content = '<img src="'+layerData.slideSubLayerImage+'"/>';
				break;
			case 'button':
				content = layerData.slideSubLayerText;
				break;
			case 'video':
				content = layerData.slideSubLayerText;
				break;
		}

		var layerContent = '<div class="stageLayer draggable" >'+content+'</div>';
		var $item = jQuery(layerContent).appendIndex($previewLayer, layerIndex);

		// Locked layer
		if(layerData.lock) {
			$item.addClass('disable');
		}

		// var $item = jQuery('#'+(layerIndex+1));
		// Get layer position
		var position_top = layerData.positionTop + 'px';
		var position_left = layerData.positionLeft + 'px';

		// Set layer position css
		$item.css({'top': position_top, 'left': position_left});

		// Get layer width and height
		var width = layerData.subWidth ? layerData.subWidth + 'px' : 'auto';
		var height = layerData.subHeight ? layerData.subHeight + 'px' : 'auto';

		// Set layer width and height css
		$item.css({'width': width, 'height': height, 'lineHeight': height});

		// Get layer border size and color
		var borderTop = isNumber(layerData.borderTop) ? layerData.borderTop + 'px' : layerData.borderTop;
		var borderRight = isNumber(layerData.borderRight) ? layerData.borderRight + 'px' : layerData.borderRight;
		var borderBottom = isNumber(layerData.borderBottom) ? layerData.borderBottom + 'px' : layerData.borderBottom;
		var borderLeft = isNumber(layerData.borderLeft) ? layerData.borderLeft + 'px' : layerData.borderLeft;
		var borderStyle = layerData.borderStyle ? layerData.borderStyle : 'none';
		var borderColor = layerData.borderColor;

		// Set layer border css
		$item.css({'borderTop': borderTop, 'borderRight': borderRight, 'borderBottom': borderBottom, 'borderLeft': borderLeft, 'borderStyle': borderStyle, 'borderColor': borderColor});

		// Get layer padding size
		var paddingTop = layerData.paddingTop + 'px';
		var paddingRight = layerData.paddingRight + 'px';
		var paddingBottom = layerData.paddingBottom + 'px';
		var paddingLeft = layerData.paddingLeft + 'px';

		// Set layer padding css
		$item.css({'paddingTop': paddingTop, 'paddingRight': paddingRight, 'paddingBottom': paddingBottom, 'paddingLeft': paddingLeft});

		// Get layer font style
		var fontWeight = layerData.fontWeight;
		var fontColor = layerData.fontColor ? layerData.fontColor : '#ffffff';
		var fontSize = layerData.fontSize + 'px';
		var fontLineHeight = layerData.fontLineHeight + 'px';

		// Set layer font css
		$item.css({'font-weight': fontWeight, 'color': fontColor, 'fontSize': fontSize, 'lineHeight': fontLineHeight});

		// Get layer misc style
		var wordWrap = layerData.wordWrap ? 'nowrap' : 'normal';
		var backgroundColor = layerData.backgroundColor;
		var borderRadius = layerData.backgroundRadius + 'px';

		// Set layer misc css
		$item.css({'white-space': wordWrap, 'backgroundColor': backgroundColor, 'borderRadius': borderRadius});

		$item.css({zIndex: 10 + (layerIndex + 1)});

		StormLayer.draggAble();
	},

	updateSlideTransPreview: function(data) {
		if(jQuery('.trans-preview-clone').length === 0) {
			var template = jQuery('.trans-preview-sample').clone().removeClass('trans-preview-sample').addClass('trans-preview-clone');
			jQuery('.trans-preview-sample').after(template);
		}

		var preview = jQuery('.trans-preview-clone');

		preview.css({transform: StormLayer.previewTransFormData(data)});
		preview.css('transform-origin', StormLayer.previewTransOriginData(data));
	},

	getSlideParamData: function() {
		// Return previously store data whenever it's possible
		if(!jQuery.isEmptyObject(slideParamData)) {
			return slideParamData;
		}
		// slideParamData.transition = StormLayer.getSlideTransitionData();
		slideParamData.slideLink = StormLayer.getParamLinkData();
		slideParamData.slideMisc = StormLayer.getParamMiscData();

		var paramData = window.stormDefaultData.layers.param;

		// Iterate param data from stormDefaultData.layers.param and add their values to slideParamData
		jQuery.each(paramData, function (key, value) {
			 var prop = key;
			 var val = value.value;

			 switch (prop) {
			 	case 'slideLink':
			 		jQuery.each(val, function(index, el) {
				 		slideParamData['slideLink'][index] = el.value;
				 	});
			 		break;
			 	case 'slideMisc':
			 		jQuery.each(val, function(index, el) {
			 			slideParamData['slideMisc'][index] = el.value;
			 		});
			 		break;
			 	default:
			 		slideParamData[prop] = val;
			 		break;
			 }
		});

		return slideParamData;
	},

	getSlideLayerData: function() {
		// Return previously store data whenever it's possible
		if(!jQuery.isEmptyObject(slideLayerData)) {
			return slideLayerData;
		}

		var layerData = window.stormDefaultData.layers.sublayer;

		// Iterate layer data from stormDefaultData.layers.sublayer and add their values to slideLayerData
		jQuery.each(layerData, function(key, value){
			var prop = key;
			var val = value.value;

			slideLayerData[prop] = val;
		});

		return slideLayerData;
	},

	getSliderData: function() {
		// Return previously store data whenever it's possible
		if(!jQuery.isEmptyObject(sliderData)) {
			return sliderData;
		}

		var sliderData =  window.stormDefaultData.slider;

		// Iterate layer data from stormDefaultData.slider and add their values to sliderData
		jQuery.each(sliderData, function(key, value){
			var prop = key;
			var val = value.value;

			sliderData[prop] = val;
		});

		return sliderData;
	},

	getSlideTransitionData: function() {
		// Return previously store data whenever it's possible
		if(!jQuery.isEmptyObject(slideTransData)) {
			return slideTransData;
		}

		var $template = jQuery(jQuery('#storm-slide-trans-template').text());

		// Iterate transition data from #storm-slide-trans-template and add their values to slideTransData

		jQuery('input, select', $template).each(function(){
			var $item = jQuery(this);
			var prop = $item.attr('name');
			var val = $item.is(':checkbox') ? $item.prop('checked') : $item.val();

			slideTransData[prop] = val;
		});

		return slideTransData;
	},

	moveitem: function(array, from, to) {
		if(from == to) return;
		var target = array[from];
		var increment = to < from ? -1 : 1;
		for (var i = from; i != to ; i += increment) {
			array[i] = array[i + increment];
		}
		array[to] = target;
	},

	appendLayerType: function(type) {
		var $appendName = jQuery('.layer-content');
		var items;
		$appendName.empty();

		switch (type) {
			case 'text':
				tinymce.remove();

				var id = 'slideSubLayerText';

				var tinymceEditor = jQuery('#stormHiddenEditor')[0].outerHTML;
				var tinymceHtml  = jQuery(tinymceEditor).html().replace(/storm-hidden-editor/g, id);
				$tmpl = jQuery(jQuery('#storm-layer-tinymce-template').text());
				$tmpl.find('.layer-tinymce-container').html(tinymceHtml);

				$appendName.append($tmpl);
				StormLayer.wpEditor(id);

				break;
			case 'image':
				tmpl = jQuery('#storm-layer-image-template').text();
				$appendName.append(tmpl);
				items = jQuery('.layer-content').find('input, select');
				StormLayer.setItems(items, activeSlideLayerData);
				if(activeSlideLayerData.slideSubLayerImage != stormDefaultData.layers.sublayer.slideSubLayerImage.value) {
					jQuery('.storm-layer-newuploader').css('backgroundImage', 'url('+activeSlideLayerData.slideSubLayerImage+')').addClass('active');
				}
				break;
			case 'button':
				tmpl = jQuery('#storm-layer-button-template').text();
				$appendName.append(tmpl);
				items = jQuery('.layer-content').find('input, select');
				StormLayer.setItems(items, activeSlideLayerData);
				break;
			case 'video':
				tmpl = jQuery('#storm-layer-video-template').text();
				$appendName.append(tmpl);
				items = jQuery('.layer-content').find('input, select');
				StormLayer.setItems(items, activeSlideLayerData);

				break;
		}

	},

	layerView: function(layerType, layerName, image_url) {
		var $template = jQuery(jQuery('#storm-layerlist-template').text());
		$template.find('.image-layer').attr({'src': image_url, 'alt': layerType});
		$template.find('span[name=layerName]').html(layerName);
		return $template;
	},

	buildLayerlist: function() {
		var layerLength = !activeSlideData.sublayer ? 0 : activeSlideData.sublayer.length;
		var $layer = jQuery('.layer-ul');
		jQuery('.layer-ul-li').remove();
		jQuery('.image-layer-timeline-ul li').remove();

		for (var i = 0; i < layerLength; i++) {
			var layerData = activeSlideData.sublayer[i];
			var layerType = layerData.layerType;
			var layerName = layerData.layerName;
			var delayShow = layerData.transitionShow.delay * oneSecWidth;
			var durationShow = layerData.transitionShow.duration * oneSecWidth;
			var delayHide = layerData.checkLayerTransitionHide ? layerData.transitionHide.delay * oneSecWidth : 0;
			var durationHide = layerData.checkLayerTransitionHide ? layerData.transitionHide.duration * oneSecWidth : 0;

			switch (layerType) {
				case 'text':
					image_url = jQuery('.add-layer-text-img').attr('src');
					break;
				case 'image':
					image_url = jQuery('.add-layer-image-img').attr('src');
					break;
				case 'button':
					image_url = jQuery('.add-layer-button-img').attr('src');
					break;
				case 'video':
					image_url = jQuery('.add-layer-video-img').attr('src');
					break;
				default :
					image_url = jQuery('.add-layer-text-img').attr('src');
					break;
			}
			var layer = StormLayer.layerView(layerType, layerName, image_url);

			jQuery('.layer-ul').append(layer);

			// Show layer
			if(layerData.show) {
				$layer.children(':eq('+i+')').find('.layer-show').addClass('black-hide').removeClass('black-graypoint');
			}

			// Lock layer
			if(layerData.lock) {
				$layer.children(':eq('+i+')').find('.layer-lock').addClass('black-lock').removeClass('black-graypoint');
			}
			var $timelineLayer = jQuery(jQuery('#storm-layer-timeline-list-template').text());
			$timelineLayer.find('.layer-delayShow-timeline').width(delayShow);
			$timelineLayer.find('.layer-durationShow-timeline').width(durationShow);
			$timelineLayer.find('.layer-delayHide-timeline').width(delayHide);
			$timelineLayer.find('.layer-durationHide-timeline').width(durationHide);
			jQuery('.image-layer-timeline-ul').append($timelineLayer);
		}

		$layer.removeAttr('style');
		jscrollPaneInit.reinitialise();
	},

	wpEditor: function(id) {

		// Init tinymce
		var settings = jQuery.extend({}, window.tinyMCEPreInit.mceInit['storm-hidden-editor']);
		settings.force_br_newlines = !0;
		settings.force_p_newlines = !1;
		settings.wpautop = !1;
		settings.body_class = 'content post-type-post post-status-auto-draft post-format-standard';
		settings.selector = '#' + id;
		settings.setup = function(editor) {
			editor.on('init', function() {
				var text = activeSlideLayerData !== null ? activeSlideLayerData.slideSubLayerText : '';
				editor.setContent(text);
			});
			editor.on('keyup', function(){
				activeSlideLayerData.slideSubLayerText = editor.getContent();
				StormLayer.updateLayerPreview(activeSlideLayerIndex, activeSlideLayerData);
			});
		};
		tinymce.init(settings);

		// Init qtag settings
		var qtagSettings = jQuery.extend({}, window.tinyMCEPreInit.qtInit['storm-hidden-editor']);
		qtagSettings.id = id;
		quicktags(qtagSettings);
		QTags.buttonsInitDone = !1;
		QTags._buttonsInit();

		jQuery('.wp-editor-wrap').removeClass('html-active').addClass('tmce-active');
		// tinymce.get('slideSubLayerText').setContent(activeSlideLayerData.slideSubLayerText);
	},

	draggAble: function() {
		jQuery('.stageLayer').draggable({
			cancel: '.disable',
			start: function() { StormLayer.dragging(); },
			drag: function() { StormLayer.dragging(); },
			stop: function() { StormLayer.dragging(); }
		});
	},

	dragging: function() {
		// Get the position
		var top = parseInt(jQuery('.ui-draggable-dragging').position().top);

		var left = parseInt(jQuery('.ui-draggable-dragging').position().left);

		var index = jQuery('.ui-draggable-dragging').index();

		// Maintain change in the data source
		activeSlideData.sublayer[index].positionTop = top;
		activeSlideData.sublayer[index].positionLeft = left;

		// Update the input fields
		if(index == activeSlideLayerIndex) {
			jQuery('input[name=positionTop]').val(top);
			jQuery('input[name=positionLeft]').val(left);
		}
	},

	openTrans: function(el, data) {
		var $trans = jQuery(el).parent('div');
		var tmpl = jQuery('#storm-slide-trans-template').text();

		$trans.append(tmpl);

		jQuery('.trans-preview-sample').css('backgroundImage', 'url('+activeSlideParamData.backgroundThumb+')');
		jQuery('.metabox-tabs').find('li:first').addClass('active');
		jQuery('.tabs-content').find('li:first').addClass('active');

		var $items = jQuery('.tabs-content').find('input, select');
		jQuery('.tabs-content :checkbox').prop('checked', false);
		StormLayer.setItems($items, data);
		jQuery('.tabs-content :checkbox').customCheckbox();
		StormLayer.updateSlideTransPreview(data);
		StormLayer.openPresetTrans();
	},

	openPresetTrans: function() {
		var length = !presetTransData ? 0 : presetTransData.transition.length;
		for (var i = 0; i < length; i++) {
			var data = presetTransData.transition[i];
			var html = '<div class="preset-effect"><span>'+data.name+'</span><b class="dashicons dashicons-trash"></b></div>';
			jQuery(html).appendTo('.trans-preset-list');
		}
	},

	closeTrans: function() {
		jQuery('.trans-container').remove();
		jQuery('.storm-overlay').remove();
	},

	applyTrans: function(el) {

		if (jQuery(el).parents().hasClass('layerTransShow-container')) {
			activeSlideLayerData.transitionShow = activeSlideLayerTransShowData;

		}
		else if (jQuery(el).parents().hasClass('layerTransHide-container')) {
			activeSlideLayerData.transitionHide = activeSlideLayerTransHideData;
		}

		StormLayer.updateLayerTimeline();
		StormLayer.closeTrans();
	},

	presetTrans: function(el) {
		var presetName = prompt('Please enter name for new preset effect.', 'Custom effect');

		if ("" === presetName) presetName = 'Custom effect';
		else if (null === presetName) return;
		var data;

		if(jQuery(el).parents().hasClass('layerTransShow-container')) {
			data = activeSlideLayerTransShowData ? activeSlideLayerTransShowData : StormLayer.getSlideTransitionData();
		}

		else if(jQuery(el).parents().hasClass('layerTransHide-container')) {
			data = activeSlideLayerTransHideData ? activeSlideLayerTransHideData : StormLayer.getSlideTransitionData();
		}

		data.name = presetName;


		if(!presetTransData) {
			presetTransData = {transition: [data]};
			action = 'stormSliderAddOption';
		}
		else {
			presetTransData.transition.push(data);
			action = 'stormSliderUpdateOption';
		}

		var html = '<div class="preset-effect"><span>'+presetName+'</span><b class="dashicons dashicons-trash"></b></div>';
		jQuery(html).appendTo('.trans-preset-list');
	},

	selectPresetTrans: function(el) {
		jQuery('.storm-switchbox').remove();
		jQuery(el).siblings().removeClass('active').end().addClass('active');
		var selectIndex = jQuery(el).index();
		var data = presetTransData.transition[selectIndex];
		var $items = jQuery('.tabs-content').find('input, select');

		jQuery('.tabs-content :checkbox').prop('checked', false);
		StormLayer.setItems($items, data);
		jQuery('.tabs-content :checkbox').customCheckbox();
		StormLayer.updateSlideTransPreview(data);
	},

	removePresetTrans: function(el) {
		var $removelEl = jQuery(el).parent('.preset-effect');
		var removeIndex = $removelEl.index();
		var presetName = presetTransData.transition[removeIndex].name;
		if(confirm('Are you sure want to delete "'+presetName+'"?')) {
			presetTransData.transition.splice(removeIndex, 1);
			$removelEl.remove();
		}
	},

	previewTransFormData: function(data) {
		// Transition translate effect
		var transX = data.offsetX ? 'translateX('+ data.offsetX + 'px)' : '';
		var transY = data.offsetY ? 'translateY('+ data.offsetY + 'px)' : '';

		// Transition rotate effect
		// var rotate = data.rotate2D ? 'rotate('+ data.rotate2D + 'deg)' : '';
		var rotateX = data.rotateX ? 'rotateX('+ data.rotateX + 'deg)' : '';
		var rotateY = data.rotateY ? 'rotateY('+ data.rotateY + 'deg)' : '';
		var rotateZ = data.rotateZ ? 'rotateZ('+ data.rotateZ + 'deg)' : '';

		// Transition rotate effect
		var scaleX = data.scaleX ? 'scaleX('+ data.scaleX +')' : '';
		var scaleY = data.scaleY ? 'scaleY('+ data.scaleY +')' : '';

		// Transition skew effect
		var skewX = data.skewX ? 'skewX('+ data.skewX + 'deg)' : '';
		var skewY = data.skewY ? 'skewY('+ data.skewY + 'deg)' : '';


		return transX + transY + rotateX + rotateY + rotateZ + scaleX + scaleY + skewX + skewY;
	},

	previewTransOriginData: function(data) {
		// Transition origin
		var originX = data.originX ? data.originX + '%' : '50%';
		var originY = data.originY ? data.originY + '%' : '50%';
		var originZ = data.originZ ? data.originZ + 'px' : '0px';

		return originX + originY + originZ;
	},

	play: function(el) {

		jQuery('#preview-bg').hide();

		var sliderProps = window.stormSliderData.slider,
			sliderLayer = window.stormSliderData.layers,
			width = sliderProps.width,
			height = sliderProps.height;

		jQuery(el).css({
			width: width,
			height: height
		});

		jQuery(el).find('.storm-inner-parent').css({
			width: width,
			height: height
		});

		var $timelinePointerEvents  = jQuery('.image-layer-timeline-cont > div');
		$timelinePointerEvents.css('pointer-events', 'none');

		jQuery.each(sliderLayer, function(index, val) {
			var $previewImg, $previewSublayer;
			var $slides = jQuery('.storm-inner-parent').find('.storm-layer');

			var $previewSlide = jQuery('<div>').addClass('storm-layer').appendTo('.storm-inner-parent');


			var transitionType = '';
			jQuery.each(val.param, function(index, val) {
				if (index == '2d' && val !== '') {
					transitionType += '2d:' + val + ';';
				}

				if (index == '3d' && val !== '') {
					transitionType += '3d:' + val + ';';
				}

				if (index == 'custom2d' && val !== '') {
					transitionType += 'custom2d:' + val + ';';
				}

				if (index == 'custom3d' && val !== '') {
					transitionType += 'custom3d:' + val + ';';
				}
			});

			// Set slide link and misc
			var linkData = '', miscData = '';
			jQuery.each(val.param.slideLink, function(index, val) {
				if(val !== '') {
					linkData += index + ':' + val + ';';
				}
			});

			jQuery.each(val.param.slideMisc, function(index, val) {
				if(val !== '') {
					miscData += index + ':' + val + ';';
				}
			});

			$previewSlide.data('link', linkData);
			$previewSlide.data('misc', miscData);

			// Set data
			$previewSlide.data('option', transitionType);

			$previewSlide.data('slidedelay', val.param.slideDelay);

			// AppendTo $previewSlide
			$previewImg = jQuery('<img src="'+ val.param.background +'" class="storm-bg" />').appendTo($previewSlide);

			// Set data val.param.fillMode to the $previewSlide
			$previewSlide.data('fillmode', val.param.fillMode);

			$previewSlide.data('positionmode', val.param.positionMode);

			if(val.param.checkKenBurns) {
				var kenBurnString = 'kenburnszoom: ' + val.param.kenBurnsZoom + ';' + 'kenburnsdirection: ' + val.param.kenBurnsDirection + ';' + 'kenburnsscale: ' + val.param.kenBurnsScale + ';';
				$previewSlide.data('kenburnsoption', kenBurnString);
			}

			jQuery.each(val.sublayer, function(subIndex, subVal) {
				var styles = {};
				styles.top = subVal.positionTop;
				styles.left = subVal.positionLeft;
				styles.backgroundColor = subVal.backgroundColor;
				styles.borderRadius = subVal.backgroundRadius;
				styles.borderTop= subVal.borderTop;
				styles.borderLeft = subVal.borderLeft;
				styles.borderBottom = subVal.borderBottom;
				styles.borderRight = subVal.borderRight;
				styles.borderStyle = subVal.borderStyle;
				styles.borderColor = subVal.borderColor;
				styles.fontColor = subVal.fontColor;
				styles.lineHeight = subVal.fontLineHeight;
				styles.fontSize = subVal.fontSize;
				styles.paddingTop = subVal.paddingTop;
				styles.paddingLeft = subVal.paddingLeft;
				styles.paddingBottom = subVal.paddingBottom;
				styles.paddingRight = subVal.paddingRight;
				styles.parallax = subVal.parallax;
				styles.width = subVal.subWidth;
				styles.height = subVal.subHeight;
				styles.backgroundSize = subVal.sub;

				switch(subVal.layerType) {
					case 'text':
						$previewSublayer = jQuery('<div class="storm-sublayer">'+ subVal.slideSubLayerText +'</div>').appendTo($previewSlide);
						break;
					case 'image':
						$previewSublayer = jQuery('<img class="storm-sublayer" src="'+ subVal.slideSubLayerImage +'"/>').appendTo($previewSlide);
						break;
					case 'button':
						$previewSublayer = jQuery('<button class="storm-sublayer">'+ subVal.slideSubLayerText +'</button>').appendTo($previewSlide);
						break;
					case 'video':
						$previewSublayer = jQuery('<div class="storm-sublayer"><video src="'+ subVal.slideSubLayerText +'"></video></div>').appendTo($previewSlide);
				}

				jQuery.each(styles, function(index, val) {
					styles[index] = isNumber(val) ? val + 'px' : val;
				});

				// Set the sublayer css style
				$previewSublayer.css(styles);
				$previewSublayer.css('white-space', subVal.wordWrap ? 'nowrap' : 'normal');

				var transitionHide = '', transitionShow = '';
				if (subVal.checkLayerTransitionHide) {
					jQuery.each(subVal.transitionHide, function(hideIndex, hideVal) {
						transitionHide += hideIndex + ':' + hideVal + ';';
					});
				}

				jQuery.each(subVal.transitionShow, function(showIndex, showVal) {
					transitionShow += showIndex + ':' + showVal + ';';
				});

				// Set the sublayer transition data
				if (transitionHide) {
					$previewSublayer.attr({
						'data-storm-transitionHide': transitionHide
					});
				}

				if (transitionShow) {
					$previewSublayer.attr({
						'data-storm-transitionShow': transitionShow
					});
				}

				var timeLineData = '';
				timeLineData += 'type:' + subVal.layerType + ';';
				timeLineData += 'name:' + subVal.layerName + ';';

				// Set the layer timeline data
				$previewSublayer.attr({
					'data-layer-timeline': timeLineData
				})

			});
		});

		jQuery(el).stormSlider({
			showTimeLine: !0,
			layout: 'fixedSize',
			showStartStopButton: !1,
			showThumbButton: !1,
			showThumbImg: !1,
			showBarTimer: !1,
			backgroundColor: sliderProps.backgroundColor,
			backgroundImage: sliderProps.backgroundImage,
			backgroundRepeat: sliderProps.backgroundRepeat,
			backgroundAttachment: sliderProps.backgroundAttachment,
			backgroundPosition: sliderProps.backgroundPosition.replace('-', ' '),
			backgroundSize: sliderProps.backgroundSize
		});
	},

	stop: function(el) {
		jQuery('#preview-bg').show();
		var $timelinePointerEvents  = jQuery('.image-layer-timeline-cont > div');
		$timelinePointerEvents.css('pointer-events', '');
		jQuery(el).stormSlider('forceStop').remove();
		// var $timeline = jQuery(".image-layer-timeline-cont").find(".timeline-dragger");
		// $timeline.remove();

		//Show activeSlideData
		StormLayer.buildLayerlist();
	},

	openTransition: function() {

		// Create overlay
		jQuery(jQuery('<div>', {'class': 'storm-overlay'})).prependTo('body');

		// Append template to body
		jQuery(jQuery('#storm-transition-window-template').html()).prependTo('body');

		// Set template position
		var left = (jQuery(window).width() - jQuery('.storm-transition-window').width() ) / 2;
		jQuery('.storm-transition-window')[0].style.left = left + 'px';

		StormLayer.appendTransition('', '3d', transitionDefaultData['3d']);
		StormLayer.appendTransition('', '2d', transitionDefaultData['2d']);

		if(typeof transitionCustomData !== 'undefined') {
			if(transitionCustomData['3d'].length) {
				StormLayer.appendTransition('Custom 3d transition', 'custom3d', transitionCustomData['3d']);
			}
			if(transitionCustomData['2d'].length) {
				StormLayer.appendTransition('Custom 2d transition', 'custom2d', transitionCustomData['2d']);
			}
		}

		// Select proper table
		jQuery('.transition-type-select span.active').click();

		// Close event
		jQuery(document).one('click', '.storm-overlay', function() {
			StormLayer.closeOverlay();
		});

	},

	appendTransition: function(title, tbodyClass, data) {

		// Append new tbody
		var tbody = jQuery('<tbody>').data('tr-type', tbodyClass).appendTo('.transition-container table');

		// Get checked transition
		var checked = activeSlideParamData[tbodyClass];
		checked = (checked !== '') ? checked.split(',') : [];

		if(title !== '') {
			jQuery('<tr>').appendTo(tbody).append('<th colspan="4">'+title+'</th>');
		}

		for (var i = 0; i < data.length; i+=2) {
			var tr = jQuery('<tr>').appendTo(tbody)
				.append(jQuery('<td>', {'class':'tag'}))
				.append(jQuery('<td>'))
				.append(jQuery('<td>', {'class':'tag'}))
				.append(jQuery('<td>'));

			tr.children().eq(0).append(jQuery('<i>'+(i+1)+'</i><i class="dashicons dashicons-yes"></i>'));
			tr.children().eq(1).append(jQuery('<a>', {'html':data[i]['name'], 'data-key': (i+1), 'href':'#'}));

			if (data.length > (i+1)) {
				tr.children().eq(2).append(jQuery('<i>'+(i+2)+'</i><i class="dashicons dashicons-yes"></i>'));
				tr.children().eq(3).append(jQuery('<a>', {'html':data[(i+1)]['name'], 'data-key': (i+2), 'href':'#'}));
			}

			if (checked.indexOf(''+(i+1)+'') != -1 || checked == 'all') {
				tr.children().eq(0).addClass('select');
				tr.children().eq(1).addClass('select');
			}

			if (checked.indexOf(''+(i+2)+'') != -1 || checked == 'all') {
				tr.children().eq(2).addClass('select');
				tr.children().eq(3).addClass('select');
			}
		}
	},

	selectAllTransition: function(index, check) {

		// Get the transition type
		var transitionType = jQuery('.transition-container tbody').eq(index).data('tr-type');
		var el = jQuery('.transition-button .transition-select-all');

		if (typeof transitionType !== 'undefined' && check === true) {
			jQuery('.transition-container tbody').eq(index).find('td').addClass('select');
			el.removeClass('off').addClass('on').text('Deselect All');
			activeSlideParamData[transitionType] = 'all';
		}
		else {
			jQuery('.transition-container tbody').eq(index).find('td').removeClass('select');
			el.removeClass('on').addClass('off').text('Select All');
			activeSlideParamData[transitionType] = '';
		}
	},

	toggleTransition: function(el) {

		// Toggle add class
		if (jQuery(el).parent().hasClass('select')) {
			jQuery(el).parent().removeClass('select').prev('td').removeClass('select');

		}
		else {
			jQuery(el).parent().addClass('select').prev('td').addClass('select');
		}

		// Get transition
		var tds = jQuery(el).closest('tbody.active').find('td');

		// All selected
		if (tds.filter('.tag').length == tds.filter('.tag.select').length) {
			StormLayer.selectAllTransition(jQuery(el).closest('tbody').index(), true);
			return;
		}
		// Uncheck all select
		else {
			// Check the checkbox
			jQuery('.transition-button .transition-select-all').removeClass('off').addClass('on').text('Select All');
		}

		// Get category
		var transitionType = jQuery(el).closest('tbody').data('tr-type');

		var checked = [];

		// Gather select transition
		tds.filter('.select').find('a').each(function() {
			checked.push( jQuery(this).attr('data-key') );
		});

		// Set data
		activeSlideParamData[transitionType] = checked.join(',');
	},

	closeOverlay: function() {
		jQuery('.storm-transition-window').remove();
		jQuery('.storm-overlay').remove();
	},

	showTransition: function(el) {
		var type, trObject;

		// Get index
		var index = jQuery(el).attr('data-key') - 1;

		// Create popup
		jQuery('body').prepend(jQuery('<div>', { 'class' : 'storm-popup' }));

		// Get popup
		var popup = jQuery('.storm-popup');

		// Get transition type
		var transitionType = jQuery(el).closest('tbody').data('tr-type');

		// Built-in 3D
		if(transitionType == '3d') {
			type = '3d';
			trObject = transitionDefaultData[type][index];
		}
		// Built-in 2D
		else if(transitionType == '2d') {
			type = '2d';
			trObject = transitionDefaultData[type][index];
		}
		// Built-in custom 3d
		else if(transitionType == 'custom3d') {
			type = '3d';
			trObject = transitionCustomData[type][index];
		}
		// Built-in custom 2d
		else if(transitionType == 'custom2d') {
			type = '2d';
			trObject =  transitionCustomData[type][index];
		}

		// Get viewport dimensions
		var v_w = jQuery(window).width();

		// Get window scrollTop
		var w_s = jQuery(window).scrollTop();

		// Get element dimensions
		var e_w = jQuery(el).width();

		// Get element position
		var e_l = jQuery(el).offset().left;
		var e_t = jQuery(el).offset().top;

		// Get toolip dimensions
		var p_w = popup.outerWidth();
		var p_h = popup.outerHeight();

		// Set toolip position
		popup.css({ top : e_t - p_h - 14, left : e_l - (p_w - e_w) / 2 });

		// Fix top
		if(popup.offset().top - w_s < 20 ) {
			popup.css('top', e_t + 36);
		}

		// Fix left
		if(popup.offset().left < 20 ) {
			popup.css('left', 20);
		}

		// Init position
		popup.stormTransition({
			transitionType: type,
			transitionObject: trObject,
			imgPath: imgPath,
			delay: 1
		});

	},

	hideTransition: function() {

		// Stop transition
		jQuery('.storm-popup').stormTransition('forceStop').remove();

	},

	showLayerTransition: function(el) {

		var $previewLayer = jQuery('.trans-preview-content'),
			$cloneLayer = jQuery('.trans-preview-sample').clone();

		$previewLayer.hide();

		var $realContainer = jQuery('<div>').addClass('real-trans-preview-container').appendTo(jQuery('.trans-preview-cont'));

		var $previewImg = jQuery('<div>').addClass('storm-layer').appendTo($realContainer);

		var imgBg = jQuery('.trans-preview-sample').css('background-image').replace(/^url\(['"](.+)['"]\)/, '$1');

		var $img = $previewImg.append('<img class="storm-sublayer" src="'+  +'"/>');

		var $previewSublayer = jQuery($cloneLayer).appendTo($previewImg).addClass('storm-sublayer');
		if (jQuery(el).parents().hasClass('layerTransShow-container')) {
			StormLayer.layerFromTo($realContainer, activeSlideLayerTransShowData);
		}
		else {
			StormLayer.layerTo($realContainer, activeSlideLayerTransHideData);
		}

	},

	layerFromTo: function(el, option) {
		var $slide = jQuery(el).find('.storm-sublayer');

		var rotateZ = option.rotate2D == true ? option.rotateZ : 0;

		var transformOrigin = option.originX + '% ';
		transformOrigin += option.originY + '% ';
		transformOrigin += option.originZ;

		var transitionFrom = {
			rotation: rotateZ,
			rotationX: option.rotateX,
			rotationY: option.rotateY,
			scaleY: option.scaleY,
			scaleX: option.scaleX,
			skewX: option.skewX,
			skewY: option.skewY,
			x: option.offsetX,
			y: option.offsetY
		};
		var transitionTo = {
			delay: option.delay,
			rotation: 0,
			rotationX: 0,
			rotationY: 0,
			scaleY: 1,
			scaleX: 1,
			skewX: 0,
			skewY: 0,
			ease: StormLayer.getEaseEffect(option.easing),
			x: 0,
			y: 0
		};

		if (option.fade == true) {
			transitionFrom.opacity = 0;
			transitionTo.opacity = 1;
		}

		$slide.data('tr') && $slide.data('tr').kill();

		var tl = new TimelineMax({repeat:-1});

		tl.set($slide, {
			transformPerspective: option.transformPerspective,
			transformOrigin: transformOrigin
		});

		$slide.data('tr', tl.fromTo($slide, option.duration, transitionFrom, transitionTo));
	},

	layerTo: function(el, option) {
		var $slide = jQuery(el).find('.storm-sublayer');

		var rotateZ = option.rotate2D == true ? option.rotateZ : 0;

		var transformOrigin = option.originX + '% ';
		transformOrigin += option.originY + '% ';
		transformOrigin += option.originZ;

		var transitionTo = {
			delay: option.delay,
			rotation: rotateZ,
			rotationX: option.rotateX,
			rotationY: option.rotateY,
			scaleY: option.scaleY,
			scaleX: option.scaleX,
			skewX: option.skewX,
			skewY: option.skewY,
			ease: StormLayer.getEaseEffect(option.easing),
			x: option.offsetX,
			y: option.offsetY
		};

		if (option.fade == true) {
			transitionTo.opacity = 0;
		}

		$slide.data('tr') && $slide.data('tr').kill();

		var tl = new TimelineMax({repeat:-1});

		tl.set($slide, {
			transformPerspective: option.transformPerspective,
			transformOrigin: transformOrigin
		});

		$slide.data('tr', tl.to($slide, option.duration, transitionTo));
	},

	getEaseEffect: function(easing) {
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

	pauseLayerTransition: function() {
		var $el = jQuery('.real-trans-preview-container').find('.storm-sublayer');

		$el.data('tr') && $el.data('tr').pause();
	},

	replayLayerTransition: function() {
		var $el = jQuery('.real-trans-preview-container').find('.storm-sublayer');

		$el.data('tr') && $el.data('tr').play();
	},

	responseLayerTransition: function() {
		var $el = jQuery('.trans-preview-cont').find('.real-trans-preview-container');

		if ($el) {
			$el.remove();
			jQuery('.trans-preview-content').show();
			jQuery('.trans-preview-cont').find('.trans-effect-control').children().removeClass().addClass('dashicons dashicons-controls-play play');
		}
	},

	bindRange: function(index, el) {
		var title, type, type2, value, activeValue, className, sliderRange;

		if (jQuery(el).hasClass('layer-delayShow-timeline')) {
			title = 'Show dealy: ';
			value = activeSlideData.sublayer[index].transitionShow.delay * oneSecWidth;
			activeValue = activeSlideData.sublayer[index].transitionShow.delay;
			type = 'transitionShow';
			type2 = 'delay';

		}
		else if (jQuery(el).hasClass('layer-durationShow-timeline')) {
			title = 'Show duration: ';
			value = activeSlideData.sublayer[index].transitionShow.duration * oneSecWidth;
			activeValue = activeSlideData.sublayer[index].transitionShow.duration;
			type = 'transitionShow';
			type2 = 'duration';

		}
		else if (jQuery(el).hasClass('layer-delayHide-timeline')) {
			title = 'Hide delay: ';
			value = activeSlideData.sublayer[index].transitionHide.delay * oneSecWidth;
			activeValue = activeSlideData.sublayer[index].transitionHide.delay;
			type = 'transitionHide';
			type2 = 'delay';

		}
		else if (jQuery(el).hasClass('layer-durationHide-timeline')) {
			title = 'Hide duration: ';
			value = activeSlideData.sublayer[index].transitionHide.duration * oneSecWidth;
			activeValue = activeSlideData.sublayer[index].transitionHide.duration;
			type = 'transitionHide';
			type2 = 'duration';
		}

		sliderRangeInstance = new stormSliderRange();
		sliderRangeInstance.remove();
		sliderRangeInstance.addRange(el, value, function(range) {
			activeValue = activeSlideData.sublayer[index][type][type2] = Math.floor(range.value / oneSecWidth * 100) / 100;
			return title + Math.floor(range.value / oneSecWidth * 100) / 100 + 's';
		});
	},

	bindAllRange: function() {

		jQuery('.image-layer-timeline-ul').find('li').each(function(index, el) {
			jQuery(el).find('.timeline-view div').each(function(divIndex, el) {
				StormLayer.bindRange(index, el);
			});
		});
	},

	updateTimelineView: function(type, prop, val, layerIndex) {
		var className;

		if (type == 'show') {

			switch (prop) {
				case 'delay':
					className = '.layer-delayShow-timeline';
					break;
				default:
					className = '.layer-durationShow-timeline';
					break;
			}
		}
		else if ( type == 'hide') {
			switch (prop) {
				case 'delay':
					className = '.layer-delayHide-timeline';
					break;
				default:
					className = '.layer-durationHide-timeline';
					break;
			}
		}

		jQuery(className).each(function(index, el) {
			if(index == layerIndex) {
				jQuery(el).width(val * oneSecWidth);
			}
		});
	},

	changeTimelineView: function() {
		jQuery('.image-layer-timeline-ul li').each(function(index, el) {
			jQuery(el).find('.timeline-view div').width(0);
			jQuery(el).find('.layer-delayShow-timeline').width(activeSlideData.sublayer[index].transitionShow.delay * oneSecWidth);
			jQuery(el).find('.layer-durationShow-timeline').width(activeSlideData.sublayer[index].transitionShow.duration * oneSecWidth);
			if (activeSlideData.sublayer[index].checkLayerTransitionHide) {
				jQuery(el).find('.layer-delayHide-timeline').width(activeSlideData.sublayer[index].transitionHide.delay * oneSecWidth);
				jQuery(el).find('.layer-durationHide-timeline').width(activeSlideData.sublayer[index].transitionHide.duration * oneSecWidth);
			}
		});
	},

	updateLayerTimeline: function() {
		jQuery('.layerTransShow-container').find('input[name="duration"]').val(activeSlideData.sublayer[activeSlideLayerIndex].transitionShow.duration);
		jQuery('.layerTransShow-container').find('input[name="delay"]').val(activeSlideData.sublayer[activeSlideLayerIndex].transitionShow.delay);
		jQuery('.layerTransHide-container').find('input[name="duration"]').val(activeSlideData.sublayer[activeSlideLayerIndex].transitionHide.duration);
		jQuery('.layerTransHide-container').find('input[name="delay"]').val(activeSlideData.sublayer[activeSlideLayerIndex].transitionHide.delay);
	},

	changeLayerTag: function(el, type) {
		var val, content, parent = jQuery(el).parent('.image-layer-type');

		if (type == 'span') {
			val = jQuery(el).text();
			content = '<input type="text" class="layerName" name="layerName" value="'+val+'"/>';
		}
		else {
			val = jQuery(el).val();
			content = '<span class="layerName" name="layerName">'+val+'</span>';
		}

		parent.append(content);
		parent.find('.layerName').focus();
		jQuery(el).remove();
	},

	getParamLinkData: function() {
		// Return previously store data whenever it's possible
		if(!jQuery.isEmptyObject(slideParamLinkData)) {
			return slideParamLinkData;
		}

		var $template = jQuery('#storm-link-template').text();

		// Iterate transition data from #storm-link-template and add their values to slideTransData

		jQuery('input, select', $template).each(function(){
			var $item = jQuery(this);
			var prop = $item.attr('name');
			var val = $item.is(':checkbox') ? $item.prop('checked') : $item.val();

			slideParamLinkData[prop] = val;
		});

		return slideParamLinkData;
	},

	getParamMiscData: function() {
		// Return previously store data whenever it's possible
		if(!jQuery.isEmptyObject(slideParamMiscData)) {
			return slideParamMiscData;
		}

		var $template = jQuery('#storm-misc-template').text();

		// Iterate transition data from #storm-misc-template and add their values to slideTransData

		jQuery('input, select', $template).each(function(){
			var $item = jQuery(this);
			var prop = $item.attr('name');
			var val = $item.is(':checkbox') ? $item.prop('checked') : $item.val();

			slideParamMiscData[prop] = val;
		});

		return slideParamMiscData;
	}
};

jQuery(document).ready(function($) {

	// Pointer-events polyfill
	PointerEventsPolyfill.initialize({});

	currentId = $('#currentId').val();

	if(!window.stormSliderData.layers || window.stormSliderData.slider.new) {
		window.stormSliderData.slider = $.extend(true, {}, StormLayer.getSliderData());
		window.stormSliderData.layers = [{
			param: $.extend(true, {}, StormLayer.getSlideParamData())
			// sublayer: [$.extend(true, {}, StormLayer.getSlideLayerData())]
		}];
	}

	// if(window.stormSliderData.slider.new) {
	// 	window.stormSliderData.slider = $.extend(true, {}, StormLayer.getSliderData());
	// 	window.stormSliderData.layers = [{
	// 		param: $.extend(true, {}, StormLayer.getSlideParamData()),
	// 		// sublayer: [$.extend(true, {}, StormLayer.getSlideLayerData())]
	// 	}];
	// }

	activeSlideData = window.stormSliderData.layers[activeSlideIndex];
	activeSlideParamData = activeSlideData.param;

	// activeSlideTransData = activeSlideData.param.transition;

	presetTransData = window.stormOptionData;
	if(!presetTransData) {
		action = 'stormSliderAddOption';
	}
	else {
		action = 'stormSliderUpdateOption';
	}
	// activeSlideLayerData = activeSlideData.sublayer[activeSlideLayerIndex];
	StormLayer.updatePreview();
	StormLayer.showKenBurnsOption();

	// StormLayer.buildLayerlist();

	StormLayer.spin( $('.insert-spinner'), 0 );

	StormLayer.addColorPicker( $('#adminform input.storm-color-picker') );

	$('.options-container :checkbox, .slide-box-content :checkbox, .layer-container-content :checkbox').customCheckbox();

	$(document).on('click', '.storm-switchbox', function(e){
		e.preventDefault();

		var el = $(this).prev()[0];

		if( $(el).is(':checked') ) {
			$(el).prop('checked', false);
			$(this).removeClass('switched');
		}
		else {
			$(el).prop('checked', true);
			$(this).addClass('switched');
		}

		$('.slide-settings').trigger( $.Event('click', {target : el}) );
		$(document).trigger( $.Event('click', {target : el}) );

	});

	$(document).on('click', '.image-thumb', function(e){
		e.preventDefault();
		StormLayer.selectSlide(this);
	});

	$(document).delegate('.image-thumb-container-content .edit-slide', 'click', function(e){
		e.stopPropagation();
		StormLayer.editSlide(this);
	});

	$(document).delegate('.image-thumb-container-content .duplicate-slide', 'click', function(e){
		e.stopPropagation();
		StormLayer.duplicateSlide(this);
	});

	$(document).delegate('.image-thumb-container-content .remove-slide', 'click', function(e){
		e.stopPropagation();
		StormLayer.removeSlide(this);
	});

	$('.storm-newuploader').on('click', function(e){
		StormLayer.addSlide();
	});

	$('.storm-bg-newuploader').on('click', function(){
		StormLayer.addBgImage();
	});

	$(document).delegate('.add-slide-container .edit-slide', 'click', function(e){
		e.stopPropagation();
		StormLayer.addAndEditBgImage(this);
	});

	$(document).delegate('.add-slide-container .remove-slide', 'click', function(e){
		e.stopPropagation();
		StormLayer.removeBgImage(this);
	});

	$(document).on('click', '.storm-layer-newuploader', function(event){
		event.stopPropagation();
		StormLayer.addLayerImage();
	});

	$('#image-navigation-type li').on('click', function(){
		$(this).siblings().removeClass('active').end().addClass('active');
		$(this).find('input').prop('checked', 'checked');
	});

	$('.options-nav-menu').on('click', function(event) {
		$('.options-nav-ul').slideToggle('400');
	});

	$('.image-thumb-list').sortable({
		items: 'li:not(.image-thumb-add)',
		delay: 150,

		start: function() {
			StormLayer.dragIndex = $('.ui-sortable-placeholder').index() - 1;
		},

		change: function(){
			$('.ui-sortable-helper').addClass('moving');
		},

		stop: function () {
			var oldIndex = StormLayer.dragIndex;
			var index = $('.moving').removeClass('moving').index();

			StormLayer.moveitem(window.stormSliderData.layers, oldIndex, index);

			$(".image-thumb-list li").each(function(index) {
               $(this).find(".image-order").html('#'+(index + 1));
			});
		}
	});

	$(".layer-ul").sortable({
		containment: 'parent',
		tolerance: 'pointer',
		delay: 150,
		axis : 'y',

		start: function(event, ui) {
			$(ui.item).addClass('active');
			StormLayer.dragIndex = $('.ui-sortable-placeholder').index() - 1;
		},

		change: function(){
			$('.ui-sortable-helper').addClass('moving');
		},

		stop: function (event, ui) {
			$(ui.item).removeClass('active');
			var oldIndex = StormLayer.dragIndex;
			var index = $('.moving').removeClass('moving').index();

			StormLayer.moveitem(activeSlideData.sublayer, oldIndex, index);
			StormLayer.selectLayer(ui.item);
			StormLayer.changeTimelineView();

			StormLayer.bindAllRange();
		}
	});

	$("#saveImage").on('click', function(){
		StormLayer.save();
	});

	$("#add-layer-button").on('click', function(e) {
		e.preventDefault();
		$(".add-layer-container").slideDown('400');
	});

	// control fill mode select
	$(document).on('click', function(e) {
 		if(!$(e.target).is('.mode-select')) {
			$('.fill-options').slideUp('fast');
			$('.select-pointer').removeClass('select-pointer-up').addClass('select-pointer-down');
		}
	});

	$('.mode-select').on('click', function(e) {
		e.stopPropagation();
		e.preventDefault();
		var wasOpen = $('.fill-options').is(':hidden');
		if(wasOpen){
			$(this).find('.select-pointer').addClass('select-pointer-up').removeClass('select-pointer-down');

			$('.fill-options').slideDown('fast');
		}
		else {
			$(this).find('.select-pointer').removeClass('select-pointer-up').addClass('select-pointer-down');

			$('.fill-options').slideUp('fast');
		}
	});

	$('.fill-options li').on('click', function () {
		var src = $(this).find('.option-mode-image').attr('src');
		var val = $(this).find('.option-mode-image').attr('alt');
		var title = $(this).find('span').html();

		$('.select-mode-image').attr({'src': src, 'alt': val});
		$('.select-mode-title').html(title);
		$('input[name=fillMode]').val(val);

		activeSlideParamData.fillMode = val;

		StormLayer.updatePreview();
	});

	// control position mode select
	$(document).on('click', function(e) {
		if(!$(e.target).is('.position-select')) {
			$('.position-options').slideUp('fast');
			$('.select-pointer').removeClass('select-pointer-up').addClass('select-pointer-down');
		}
	});

	$('.position-select').on('click', function(e) {
		e.stopPropagation();
		e.preventDefault();
		var check = $('.position-options').is(':hidden');

		if(check) {
			$(this).find('.select-pointer').addClass('select-pointer-up').removeClass('select-pointer-down');

			$('.position-options').slideDown('fast');
		}
		else {
			$(this).find('.select-pointer').removeClass('select-pointer-up').addClass('select-pointer-down');

			$('.position-options').slideUp('fast');
		}
	});

	$('.position-options li').on('click', function(e) {
		var src = $(this).find('.position-select-mode-image').attr('src');
		var alt = $(this).find('.position-select-mode-image').attr('alt');
		var title = $(this).find('span').html();

		$('.current-position-image').attr({'src': src, 'alt': alt});
		$('.current-position-title').html(title);
		$('input[name="positionMode"]').val(alt);

		activeSlideParamData.positionMode = alt;

		StormLayer.updatePreview();
	});

	$(document).on('click', '.add-layer', function(e) {
		e.preventDefault();
		if( !$(e.target).is('#add-layer-button, .options-nav-menu') ) {
			$('.add-layer-container').slideUp('400');
		}
	});

	$('.edit-slider').on('click', '.tabs li', function(e) {
		e.stopPropagation();
		var className = $(this).find('a').attr('href');
		StormLayer.storm_change_tab(this, className.replace("#", ""));
	});

	$(document).on('click', '.metabox-tabs li', function(e) {
		e.stopPropagation();
		var className = $(this).find('a').attr('href');
		StormLayer.storm_change_tab(this, className.replace("#", ""));
	});

	$('.add-layer-container').on('click', 'li', function(){
		var type = $(this).find('.add-layery-value').val();
		var image_url = $(this).find('img').attr('src');
		StormLayer.addLayer(type, image_url);
	});

	//Select layer
	$('.layer-ul').on('click', 'li', function(){
		StormLayer.selectLayer(this);
	});

	$('#previewLayer').on('click', '.stageLayer', function(e){
		e.preventDefault();

		var index = $(this).index();
		var el = $('.layer-ul').children(':eq('+index+')');

		StormLayer.selectLayer(el);
	});

	// Show all layer or hide
	$('.black-hide-all').on('click', function(){
		if($(this).hasClass('show-all')) {
			$(this).removeClass('show-all');
			$('.layer-show').removeClass('black-hide').addClass('black-graypoint');
			for (i = 0; i < $('.layer-show').length; i++) {
				activeSlideData.sublayer[i].show = false;
			}
		}
		else {
			$(this).addClass('show-all');
			$('.layer-show').addClass('black-hide').removeClass('black-graypoint');
			for (i = 0; i < $('.layer-show').length; i++) {
				activeSlideData.sublayer[i].show = true;
			}
		}
		StormLayer.updatePreview();
	});

	// Lock all layer or unlock
	$('.black-lock-all').on('click', function(){

		if($(this).hasClass('lock-all')) {
			$(this).removeClass('lock-all');
			$('.layer-lock').removeClass('black-lock').addClass('black-graypoint');
			for (i = 0; i < $('.layer-lock').length; i++) {
				activeSlideData.sublayer[i].lock = false;
				jQuery('#previewLayer').children(':eq('+(i-1)+')').removeClass('disable');
			}
		}
		else {
			$(this).addClass('lock-all');
			$('.layer-lock').addClass('black-lock').removeClass('black-graypoint');
			for (i = 0; i < $('.layer-lock').length; i++) {
				activeSlideData.sublayer[i].lock = true;
				jQuery('#previewLayer').children(':eq('+(i-1)+')').addClass('disable');
			}
		}
	});

	// Show layer or hide
	$('.show-lock').on('click', 'span.layer-show', function(e){
		e.stopPropagation();
		var layerIndex = jQuery(this).closest('li').index();
		activeSlideLayerData = activeSlideData.sublayer[layerIndex];

		if($(this).hasClass('black-hide')) {
			$(this).removeClass('black-hide').addClass('black-graypoint');

			// Set the show value
			activeSlideLayerData.show = false;
		}
		else {
			$(this).addClass('black-hide').removeClass('black-graypoint');

			// Set the show value
			activeSlideLayerData.show = true;
		}

		StormLayer.updateLayerPreview(layerIndex, activeSlideLayerData);
	});

	// Lock layer or unlock
	$('.show-lock').on('click', 'span.layer-lock', function(e){
		e.stopPropagation();
		var layerIndex = jQuery(this).closest('li').index();
		var lockLayer = jQuery('#previewLayer').children(':eq('+layerIndex+')');
		activeSlideLayerData = activeSlideData.sublayer[layerIndex];

		if($(this).hasClass('black-lock')) {
			$(this).removeClass('black-lock').addClass('black-graypoint');
			lockLayer.removeClass('disable');

			// Set the lock value
			activeSlideLayerData.lock = false;
		}
		else {
			$(this).addClass('black-lock').removeClass('black-graypoint');
			lockLayer.addClass('disable');

			// Set the lock value
			activeSlideLayerData.lock = true;
		}
	});

	// Remove layer
	$('.layer-ul').on('click', 'span.black-remove', function(e){
		e.stopPropagation();
		StormLayer.removeLayer(this);
	});

	// Duplicate layer
	$('.layer-ul').on('click', 'span.black-duplicate', function(e){
		e.stopPropagation();
		StormLayer.duplicateLayer(this);
	});


	// jQuery ui tooltip
	 $('.storm-slider').tooltip({
      position: {
        my: "center bottom-20",
        at: "center top",
        using: function( position, feedback ) {
          $( this ).css( position );
          $( "<div>" )
            .addClass( "arrow" )
            .addClass( feedback.vertical )
            .addClass( feedback.horizontal )
            .appendTo( this );
        	}
    	}
	});

	// Select slide transition
	// $('.slide-transition').on('click', function(event) {
	// 	event.stopPropagation();
	// 	StormLayer.openTrans(this, activeSlideTransData);
	// 	StormLayer.spin( $('.insert-spinner') );
	// });
	$(document).on('click', '.slide-preview', function(e) {
		e.preventDefault();
		if ($(this).hasClass('playing')) {
			$(this).removeClass('playing').text('preview');
			StormLayer.stop('.realPreview');
		}
		else {
			$(this).addClass('playing').text('Exits Preview');

			var $realPreview = $('<div>').addClass('realPreview').appendTo('.image-container-content');
			$('<div>').addClass('storm-inner-parent').appendTo($realPreview);

			StormLayer.play($realPreview);
		}
	});

	$(document).on('click', '.transTitleBar b', function() {
		StormLayer.closeTrans();
	});

	$(document).on('click', '.trans-apply', function() {
		StormLayer.applyTrans(this);
	});

	$(document).on('click', '.trans-preset', function() {
		StormLayer.presetTrans(this);
	});

	$(document).on('click', '.preset-effect', function() {
		StormLayer.selectPresetTrans(this);
	});

	$(document).on('click', '.preset-effect b', function(e){
		e.stopPropagation();
		StormLayer.removePresetTrans(this);
	});

	$(document).on('click', '.layerTransShow', function() {
		StormLayer.openTrans(this, activeSlideLayerTransShowData);
		$('.layer-container-content .insert-spinner').each(function() {
			var min = $(this).attr('valuemin') ? $(this).attr('valuemin') : '';
			StormLayer.spin( this, min );
		});
	});

	$(document).on('click', '.layerTransHide', function() {
		StormLayer.openTrans(this, activeSlideLayerTransHideData);
		$('.layer-container-content .insert-spinner').each(function() {
			var min = $(this).attr('valuemin') ? $(this).attr('valuemin') : '';
			StormLayer.spin( this, min );
		});
	});

	$(document).on('click', '.trans-effect-control .play', function() {
		jQuery(this).removeClass('dashicons-controls-play play').addClass('dashicons-controls-pause pause');
		StormLayer.showLayerTransition(this);
	});

	$(document).on('click', '.trans-effect-control .pause', function() {
		jQuery(this).removeClass('dashicons-controls-pause pause').addClass('dashicons-controls-play playing');
		StormLayer.pauseLayerTransition();
	});

	$(document).on('click', '.trans-effect-control .playing', function() {
		jQuery(this).removeClass('playing').addClass('dashicons-controls-pause pause');
		StormLayer.replayLayerTransition();
	});

	$('.slide-transition').on('click', function(e) {
		e.preventDefault();
		StormLayer.openTransition();
	});

	//Select transition gallery
	$(document).on('click', '.transition-type-select span', function() {

		//Update navigation
		jQuery(this).siblings().removeClass('active');
		jQuery(this).addClass('active');

		jQuery('.transition-container tbody').removeClass('active');
		jQuery('.transition-container tbody').eq( jQuery(this).index() ).addClass('active');

		if (jQuery(this).index() == 2) {
			jQuery('.transition-container tbody').eq(3).addClass('active');
		}

		var tds = jQuery('.transition-container tbody.active').find('td');
		var element = jQuery('.transition-button .transition-select-all');

		if (tds.filter('.tag').length == tds.filter('.tag.select').length) {
			element.removeClass('off').addClass('on').text('Deselect All');
		}
		else {
			element.removeClass('on').addClass('off').text('Select All');
		}
	});

	//Close transition gallery
	$(document).on('click', '.transition-button i', function() {
		StormLayer.closeOverlay();
	});

	$(document).on('click', '.transition-container tbody.active a', function(e) {
		e.preventDefault();
		StormLayer.toggleTransition(this);
	});

	$(document).on('click', '.transition-button .transition-select-all', function() {
		var check = jQuery(this).hasClass('off') ? true : false;
		jQuery('.transition-container tbody.active').each(function() {
			StormLayer.selectAllTransition(jQuery(this).index(), check);
		});
	});

	//Show/Hide transition
	$(document).on('mouseenter', '.transition-container table a', function(){
		StormLayer.showTransition(this);
	}).on('mouseleave', '.transition-container table a', function() {
		StormLayer.hideTransition(this);
	});

	// Set the save bar
	$(window).scroll(function() {
	 	$(window).scrollTop() + $(window).height() >= $(document).height() - 45 ? ($('.save').removeClass('scroll-bottom'), $('#saveBarPlaceHolder').css('display', 'none')) : ($('.save').addClass('scroll-bottom'), $('#saveBarPlaceHolder').css('display', ''));
	}).trigger('scroll');

	//Set ruler
	var rulerWidth = 2560 + 'px';
	$('.timeline-ruler-container').css('width', rulerWidth);
	$('.timeline-view').css('width', rulerWidth);

	//Add jscrollPane
	var pane = $('.image-layer-timeline-list').bind('jsp-scroll-x', function(event, scrollPositionX) {
			parentOffsetLeft = scrollPositionX;
			$('.timeline-ruler-container')[0].style.right = scrollPositionX + 'px';
		}).bind('jsp-scroll-y', function(event, scrollPositionY) {
			$('.layer-ul')[0].style.bottom = scrollPositionY + 'px';
		}).jScrollPane({
		hideFocus: !0,
		forceReinit: !0,
		horizontalDragMinWidth: 40,
		horizontalDragMaxWidth: 40
	});

	jscrollPaneInit = pane.data('jsp');

	//Dragger timeline
	StormLayer.bindAllRange();

	//prevent mouseout or dblclick for layer title
	$(document).on('dblclick', '.layerName', function(event) {
		if ($(event.target).is('span')) {
			StormLayer.changeLayerTag(this, 'span');
		}
	}).on('mouseout', '.layerName', function(event) {
		if ($(event.target).is('input')) {
			StormLayer.changeLayerTag(this, 'input');
		}
	});

 	$(document).on('keyup change click', '.options-container input, .options-container select, .slider_title input', function(e){
 		if (e.type === 'click') {
	 		if (!$(this).is(':checkbox')) {
	 			return false;
	 		}
	 	}

	 	// Get the option data
	 	var $item = $(this);
	 	var prop = $item.attr('name');
	 	var val = $item.is(':checkbox') ? $item.prop('checked') : $item.val();

	 	// Set the new setting
	 	window.stormSliderData.slider[prop] = val;

	 	// Update the slide preview
	 	StormLayer.updatePreview();
	});

	$(document).on('keyup change click', '.slide-settings input, .slide-settings select', function(e){
	 	 if(e.type === 'click') {
	 		if(!$(this).is(':checkbox')) {
	 			return false;
	 		}
	 	}

	 	// Get the setting data
	 	var $item = $(this);
	 	var prop = $item.attr('name');
	 	var val = $item.is(':checkbox') ? $item.prop('checked') : $item.val();

	 	// Set the slide
	 	if($(this).parents().hasClass('slide-link')) {
	 		activeSlideParamData.slideLink[prop] = val;
	 	}
	 	else if($(this).parents().hasClass('slide-misc')) {
	 		activeSlideParamData.slideMisc[prop] = val;
	 	}
	 	else {
	 		activeSlideParamData[prop] = val;
	 	}

	 	// Update the slide preview
	 	StormLayer.updatePreview();
	});

	//Display transiiton hide
	$('.slider-body').on('keyup change click', 'input[name=checkLayerTransitionHide]', function(e) {
		if(e.type === 'click') {
			if(!$(this).is(':checkbox')) {
				return false;
			}
		}

		var prop = $(this).prop('checked');
		if(prop) {
			//Show the transition view
			$('.layerTransHide-container').show();
			//Set the layer transition data
			activeSlideLayerData.checkLayerTransitionHide = true;

			//Update timeline view
			StormLayer.updateTimelineView('hide',  'duration', activeSlideLayerTransHideData.duration, activeSlideLayerIndex);
			StormLayer.updateTimelineView('hide',  'delay', activeSlideLayerTransHideData.delay, activeSlideLayerIndex);
		}
		else {
			//Hide the transition view
			$('.layerTransHide-container').hide();
			//Set the layer transition data
			activeSlideLayerData.checkLayerTransitionHide = false;

			//Update timeline view
			StormLayer.updateTimelineView('hide',  'duration', '0', activeSlideLayerIndex);
			StormLayer.updateTimelineView('hide',  'delay', '0', activeSlideLayerIndex);

		}
	});

	//Display ken burns effect
	$('.slider-body').on('keyup change click', 'input[name=checkKenBurns]', function(e) {
		if(e.type === 'click') {
			if(!$(this).is(':checkbox')) {
				return false;
			}
		}

		var prop = $(this).prop('checked');
		if(prop) {
			//Show the ken burns option
			$('.storm-ken-burns-container').show();

			//Set the ken burns value is true
			activeSlideParamData.checkKenBurns = true;
		}
		else {
			//Hide the ken burns option
			$('.storm-ken-burns-container').hide();

			//Set the ken burns value is false
			activeSlideParamData.checkKenBurns = false;
		}

	});

	$(document).on('keyup change click', '.layer-container-content input, .layer-container-content select, .layerName', function (e) {
	 	if(e.type === 'click') {
	 		if(!$(this).is(':checkbox')) {
	 			return false;
	 		}
	 	}

	 	// Get the layer data
	 	var $item = $(this);
	 	var prop = $item.attr('name');

	 	//Don't add duration and delay
	 	if(prop === 'duration' || prop === 'delay' || $item.parents().hasClass('layerTransShow-container') || $item.parents().hasClass('layerTransHide-container') || $item.parents().hasClass('checkLayerTransitionHide')) {
	 		return false;
	 	}

	 	var val = $item.is(':checkbox') ? $item.prop('checked') : $item.val();

	 	// Set the layer
	 	activeSlideLayerData[prop] = val;

	 	// Update the layer preview
	 	StormLayer.updateLayerPreview(activeSlideLayerIndex, activeSlideLayerData);
	});

	$(document).on('keyup change click', '.slideTrans-container .trans-container input, .slideTrans-container .trans-container select, .layerTransShow-container input, .layerTransShow-container select, .layerTransHide-container input, .layerTransHide-container select', function(e){
		if(e.type === 'click') {
			if(!$(this).is(':checkbox')) {
				return false;
			}
		}

		// Get the slide trans data
		var $item = $(this);
		var prop = $item.attr('name');
		var val = $item.is(':checkbox') ? $item.prop('checked') : $item.val();

		if($(this).parents().hasClass('layerTransShow-container')) {
			// Set the layer trans show data
			activeSlideLayerTransShowData[prop] = val;
			transData = activeSlideLayerTransShowData;
			StormLayer.responseLayerTransition();

			//Update timeline view
			if (prop == 'duration' || prop == 'delay') {
				StormLayer.updateTimelineView('show', prop, val, activeSlideLayerIndex);
			}
		}

		else if($(this).parents().hasClass('layerTransHide-container')) {
			// Set the layer trans hide data
			activeSlideLayerTransHideData[prop] = val;
			transData = activeSlideLayerTransHideData;
			StormLayer.responseLayerTransition();

			//Update timeline view
			if(prop == 'duration' || prop == 'delay') {
				StormLayer.updateTimelineView('hide', prop, val, activeSlideLayerIndex);
			}
		}

		// Update the trans preview
		StormLayer.updateSlideTransPreview(transData);
	});
});