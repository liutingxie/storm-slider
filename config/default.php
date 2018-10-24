<?php
/**
 * @Author: liutingxie
 * @Date:   2016-08-10 06:58:20
 * @Last Modified by:   liutingxie
 * @Last Modified time: 2018-01-06 18:14:55
 */
function inputValue($default, $current) {
	$slider = $default;

	$slider_name = $default['slider'];
	$layers_param_name = $default['layers']['param'];
	$layers_sublayer_name = $default['layers']['sublayer'];
	$layers_param_link_name = $default['layers']['param']['slideLink'];
	$layers_param_misc_name = $default['layers']['param']['slideMisc'];

	foreach ($slider_name as $key => $value) {
		if( isset($current['slider'][$key]) && $current['slider'][$key] !== '' ) {
			$slider['slider'][$key]['value'] = $current['slider'][$key];
		}
	}

	foreach ($layers_param_name as $key => $value) {
		if( isset($current['layers'][0]['param'][$key]) && $current['layers'][0]['param'][$key] !== '' && !is_array($current['layers'][0]['param'][$key])) {
			$slider['layers']['param'][$key]['value'] = $current['layers'][0]['param'][$key];
		}
	}

	foreach ($layers_sublayer_name as $key => $value) {
		if( isset($current['layers'][0]['sublayer'][0][$key]) && $current['layers'][0]['sublayer'][0][$key] !== '' ) {
			$slider['layers']['sublayer'][$key]['value'] = $current['layers'][0]['sublayer'][0][$key];
		}
	}

	foreach ($layers_param_link_name as $key => $value) {
		if( isset($current['layers'][0]['param']['slideLink'][$key]) && $current['layers'][0]['param']['slideLink'][$key] !== '' ) {
			$slider['layers']['param']['slideLink'][$key]['value'] = $current['layers'][0]['param']['slideLink'][$key];
		}
	}

	foreach ($layers_param_misc_name as $key => $value) {
		if( isset($current['layers'][0]['param']['slideMisc'][$key]) && $current['layers'][0]['param']['slideMisc'][$key] !== '' ) {
			$slider['layers']['param']['slideMisc'][$key]['value'] = $current['layers'][0]['param']['slideMisc'][$key];
		}
	}

	return $slider;
}

$Storm_Default = array(
	'slider' => array(
		'name' => array(
			'name' => __('Slider Name :', 'StormSlider'),
			'value' => 'New Slider',
			'key' => 'name',
			'desc' => __('Put the slider name on here.', 'StormSlider')
		),
		//The width of the new slider.
		'width' => array(
			'name'  => __('Slider Width', 'StormSlider'),
			'value' => 600,
			'key'   => 'width',
			'desc' => __('The width of the sliders in pixels.', 'StormSlider')
		),

		//The height of the new slider.
		'height' => array(
			'name'  => __('Slider Height', 'StormSlider'),
			'value' => 300,
			'key'   => 'height',
			'desc' => __('The height of the sliders in pixels.', 'StormSlider')
		),

		//The layout of the new slider.
		'layout' => array(
			'name'  => __('Slider layout', 'StormSlider'),
			'value' => 'responsive',
			'key'   => 'layout',
			'desc' => __('The layout of the sliders, choose you layout mode.', 'StormSlider')
		),

		//Set the slider will start specified slide.
		'firstSlide' => array(
			'name' => __('Start slide', 'StormSlider'),
			'value' => '1',
			'key' => 'firstSlide',
			'desc' => __('The slider will start with the specified slide or random.', 'StormSlider')
		),

		//Set the pause hover effect.
		'pauseHover' => array(
			'name'        => __('Pause Hover', 'StormSlider'),
			'value'       => false,
			'key'         => 'pauseHover',
			'desc' => __('If mouse in the slider, stop auto play.', 'StormSlider')
		),

		//Choose the slider background color, default transparent.
		'backgroundColor' => array(
			'name' => __('Background Color', 'StormSlider'),
			'value' => '',
			'key'   => 'backgroundColor',
			'desc' => __('Choose background color of the slider.', 'StormSlider')
		),

		//Select the slider background image, default is none.
		'backgroundImage' => array(
			'name' => __('Background Image', 'StormSlider'),
			'value' => '',
			'key'   => 'backgroundImage',
			'desc' => __('Select background image of the slider.', 'StormSlider')
		),

		//Select the slider background image repeat, default is no-repeat
		'backgroundRepeat' => array(
			'name' => __('Background Repeat', 'StormSlider'),
			'value' => 'no-repeat',
			'key' => 'backgroundRepeat',
			'desc' => __('Select background image repeat.', 'StormSlider'),
			'title' => array(
				'no-repeat' => __('No-repeat', 'StormSlider'),
				'repeat' => __('Repeat', 'StormSlider'),
				'repeat-x' => __('Repeat-x', 'StormSlider'),
				'repeat-y' => __('Repeat-y', 'StormSlider')
			)
		),

		//Select the slider background attachment, default is scroll
		'backgroundAttachment' => array(
			'name' => __('Background attachment behavior', 'StormSlider'),
			'value' => 'scroll',
			'key' => 'backgroundAttachment',
			'desc' => __('Choose between a scrollable or fixed background image.', 'StormSlider'),
			'title' => array(
				'scroll' => __('Scroll', 'StormSlider'),
				'fixed' => __('Fixed', 'StormSlider')
			)
		),

		//Select the slider background image position, default is center center
		'backgroundPosition' => array(
			'name' => __('Background Position', 'StormSlider'),
			'value' => 'center-center',
			'key' => 'backgroundPosition',
			'desc' => __('Select background image position.', 'StormSlider'),
			'title' => array(
				'left-top' => __('Left top', 'StormSlider'),
				'left-center' => __('Left center', 'StormSlider'),
				'left-bottom' => __('Left bottom', 'StormSlider'),
				'center-top' => __('Center top', 'StormSlider'),
				'center-center' => __('Center center', 'StormSlider'),
				'center-bottom' => __('Center bottom', 'StormSlider'),
				'right-top' => __('Right top', 'StormSlider'),
				'right-center' => __('Right center', 'StormSlider'),
				'right-bottom' => __('Right bottom', 'StormSlider')
			)
		),

		//Select the slider background image size, default is center
		'backgroundSize' => array(
			'name' => __('Background Size', 'StormSlider'),
			'value' => 'center',
			'key' => 'backgroundSize',
			'desc' => __('Select background image size.', 'StormSlider'),
			'title' => array(
				'center' => __('Auto', 'StormSlider'),
				'cover' => __('Cover', 'StormSlider'),
				'contain' => __('Contain', 'StormSlider'),
				'stretch' => __('Stretch', 'StormSlider')
			)
		),

		//Choose the navigation dots color, default is transparent.
		'navigationColor' => array(
			'name' => __('Navigation Dots Color', 'StormSlider'),
			'value' => '000000',
			'key'   => 'navigationColor',
			'desc' => __('Choose navigation dots color of the slider.', 'StormSlider')
		),

		//Choose the navigation dots active color, default is white.
		'navigationActiveColor' => array(
			'name' => __('Navigation Dots Active Color', 'StormSlider'),
			'value' => 'ffffff',
			'key'   => 'navigationActiveColor',
			'desc' => __('Choose navigation dots active color of the slider.', 'StormSlider')
		),


		//Set the navigation whether is show, default is show.
		'showNavigationButton' => array(
			'name' => __('Show Navigation', 'StormSlider'),
			'value' => true,
			'key'   => 'showNavigationButton',
			'desc' => __('Disabling this option will hide slide navigation buttons.', 'StormSlider')
		),

		//Set the start && stop button, default is show.
		'showStartStopButton' => array(
			'name' => __('Show Start And Stop', 'StormSlider'),
			'value' => true,
			'key'   => 'showStartStopButton',
			'desc' => __('Disabling this option will hide slide start and buttons.', 'StormSlider')
		),

		// Set the thumb button, default is show.
		'showThumbButton' => array(
			'name' => __('Show Thumbnails', 'StormSlider'),
			'value' => true,
			'key'   => 'showThumbButton',
			'desc' => __('Disabling this option will hide slide thumbnails.', 'StormSlider')
		),

		//Set the thumb img where show amount, default is 5.
		'showThumbImgAmount' => array(
			'name' => __('Show Thumbnails Image Amout', 'StormSlider'),
			'value' => 5,
			'key' => 'showThumbImgAmount',
			'desc' => __('This number decide how much slide thumbnails amount will show.', 'StormSlider')
		),

		//Set the circle timer show, default is circle show.
		'showCircleTimer' => array(
			'name' => __('Show Circle Timer', 'StormSlider'),
			'value' => true,
			'key'   => 'showCircleTimer',
			'desc' => __('Disabling this option will hide slide cirlce timer.', 'StormSlider')
		),

		//Set the bat timer show, default is bar timer hide.
		'showBarTimer' => array(
			'name' => __('Show Bar Timer', 'StormSlider'),
			'value' => false,
			'key' => 'showBarTimer',
			'desc' => __('Disabling this option will hide slide bar timer.', 'StormSlider')
		)
	),

	'layers' => array(
		'param' => array(

			//slide background thumb, default is transperent
			'backgroundThumb' => array(
				'name' => __('Slide Background Thumb', 'StormSlider'),
				'value' => '',
				'key' => 'backgroundThumb',
				'desc' => __('Slide background thumb.', 'StormSlider')
			),

			// // slider background, default is transperent
			// 'sliderBackground' => array(
			// 	'name' => __('Slider Background', 'StormSlider'),
			// 	'value' => '',
			// 	'key' => 'sliderBackground',
			// 	'desc' => __('Slider background image on here.', 'StormSlider')
			// ),

			// slide background image, default is transperent
			'background' => array(
				'name' => __('Slide Background Image', 'StormSlider'),
				'value' => '',
				'key' => 'background',
				'desc' => __('Slide background image on here.', 'StormSlider')
			),

			// fill mode, default is auto
			'fillMode' => array(
				'name' => __('Fill Mode', 'StormSlider'),
				'value' => 'stretch',
				'key' => 'fillMode',
				'desc' => __('You can select your slide fill mode.', 'StormSlider'),
				'src' => array(
						'center' =>  plugins_url('/image/center.png', dirname(__FILE__)),
						'stretch' =>  plugins_url('/image/stretch.png', dirname(__FILE__)),
						'cover' => plugins_url('/image/cover.png', dirname(__FILE__)),
						'contain' => plugins_url('/image/contain.png', dirname(__FILE__))
					),
				'title' => array(
					'center' => __('Auto', 'StormSlider'),
					'stretch' => __('Stretch', 'StormSlider'),
					'cover' => __('Cover', 'StormSlider'),
					'contain' => __('Contain', 'StormSlider')
				)
			),

			// position mode, default is center center
			'positionMode' => array(
				'name' => __('Position :', 'StormSlider'),
				'value' => 'center-center',
				'key' => 'positionMode',
				'desc' => __('You can select you slide position mode.', 'StormSlider'),
				'src' => array(
						'left-top' => plugins_url('/image/left-top.png', dirname(__FILE__)),
						'left-center' => plugins_url('/image/left-center.png', dirname(__FILE__)),
						'left-bottom' => plugins_url('/image/left-bottom.png', dirname(__FILE__)),
						'center-top' => plugins_url('/image/center-top.png', dirname(__FILE__)),
						'center-center' => plugins_url('/image/center-center.png', dirname(__FILE__)),
						'center-bottom' => plugins_url('/image/center-bottom.png', dirname(__FILE__)),
						'right-top' => plugins_url('/image/right-top.png', dirname(__FILE__)),
						'right-center' => plugins_url('/image/right-center.png', dirname(__FILE__)),
						'right-bottom' => plugins_url('/image/right-bottom.png', dirname(__FILE__)),

					),
				'title' => array(
					'left-top' => __('Left top', 'StormSlider'),
					'left-center' => __('Left center', 'StormSlider'),
					'left-bottom' => __('Left bottom', 'StormSlider'),
					'center-top' => __('Center top', 'StormSlider'),
					'center-center' => __('Center center', 'StormSlider'),
					'center-bottom' => __('Center bottom', 'StormSlider'),
					'right-top' => __('Right top', 'StormSlider'),
					'right-center' => __('Right center', 'StormSlider'),
					'right-bottom' => __('Right bottom', 'StormSlider')
				)
			),
			// slide change delay time, default is 1
			'slideDelay' => array(
				'name' => __('Slide timing :', 'StormSlider'),
				'value' => '1',
				'key' => 'slideDelay',
				'desc' => __('You can apply a dealy between the slide change.', 'StormSlider')
			),

			'checkKenBurns' => array(
				'value' => false,
				'key'   => 'checkKenBurns'
			),

			'kenBurnsZoom' => array(
				'name' => __('Zoom', 'StormSlider'),
				'value' => 'zoom-in',
				'key' => 'kenBurnsZoom',
				'desc' => __('Use ken burns effect for this slide.', 'StormSlider'),
				'title' => array(
					'zoom-in' => __('Zoom In', 'StormSlider'),
					'zoom-out' => __('Zoom Out', 'StormSlider')
				)
			),

			'kenBurnsScale' => array(
				'name' => __('Scale', 'StormSlider'),
				'value' => '1',
				'key' => 'kenBurnsScale',
				'desc' => __('Ken burns scale for this slide.', 'StormSlider')
			),

			'kenBurnsDirection' =>  array(
				'name' => __('Diection', 'StormSlider'),
				'value' => 'left-top',
				'key' => 'kenBurnsDirection',
				'desc' => __('Select ken burns direction for this slide.', 'StormSlider'),
				'title' => array(
					'left-top' => __('Left top', 'StormSlider'),
					'left-bottom' => __('Left bottom', 'StormSlider'),
					'right-top' => __('Right top', 'StormSlider'),
					'right-bottom' => __('Right bottom', 'StormSlider')
				)
			),

			'slideLink' => array(
				// slide link url, default is empty
				'linkUrl' => array(
					'name' => __('Link url :', 'StormSlider'),
					'value' => '',
					'key' => 'linkUrl',
					'desc' => __('If you want the link whole slide, enter the url of here.', 'StormSlider')
				),

				// slide link target, default is open on the new page
				'linkTarget' => array(
					'name' => __('Link target', 'StormSlider'),
					'value' => '_self',
					'key' => 'linkTarget',
					'desc' => __('Slide link target type.', 'StormSlider')
				),

				// slide link id, default is empty
				'linkId' => array(
					'name' => __('Link id :', 'StormSlider'),
					'value' => '',
					'key' => 'linkId',
					'desc' => __('You can an apply id attributes on the HTML element of this slide to work with id in your custom CSS or javascript code.', 'StormSlider')
				),

				// slide link class, default is empty
				'linkClass' => array(
					'name' => __('Link class :', 'StormSlider'),
					'value' => '',
					'key' => 'linkClass',
					'desc' => __('You can an apply class attributes on the HTML element of this slide to work with id in your custom CSS or javascript code.', 'StormSlider')
				),

				// slide link title, default is empty
				'linkTitle' => array(
					'name' => __('Link title :', 'StormSlider'),
					'value' => '',
					'key' => 'linkTitle',
					'desc' => __('You can an apply title attributes on the HTML element of this slide to work with id in your custom CSS or javascript code.', 'StormSlider')
				),

				// slide link rel, default is empty
				'linkRel' => array(
					'name' => __('Link rel :', 'StormSlider'),
					'value' => '',
					'key' => 'linkRel',
					'desc' => __('You can an apply rel attributes on the HTML element of this slide to work with it in your custom CSS or javascript code.', 'StormSlider')
				)
			),

			'slideMisc' => array(
				// slide misc class, default is empty
				'miscClass' => array(
					'name' => __('Class name :', 'StormSlider'),
					'value' => '',
					'key' => 'miscClass',
					'desc' => __('You can apply class attributes on the HTML element of this Slide to work with it in your CSS or javascript code.', 'StormSlider')
				),

				// slide misc id, default is empty
				'miscId' => array(
					'name' => __('Id name :', 'StormSlider'),
					'value' => '',
					'key' => 'miscId',
					'desc' => __('You can apply id attributes on the HTML element of this Slide to work with it in your CSS or javascript code.', 'StormSlider')
				),

				// slide misc background color, default is transparent
				'miscBackgroundColor' => array(
					'name' => __('Background color :', 'StormSlider'),
					'value' => '',
					'key' => 'miscBackgroundColor',
					'desc' => __('You can select background color on the slide.', 'StormSlider')
				),

				// slide misc background alt, default is empty
				'miscBackgroundAlt' => array(
					'name' => __('Background alt :', 'StormSlider'),
					'value' => '',
					'key' => 'miscBackgroundAlt',
					'desc' => __('You can apply alt attributes on the HTMl element of this slide.', 'StormSlider')
				),

				// slide misc background image title, default is empty
				'miscBackgroundTitle' => array(
					'name' => __('Background image title :', 'StormSlider'),
					'value' => '',
					'key' => 'miscBackgroundTitle',
					'desc' => __('You can apply title attributes on the HTMl element of this slide.', 'StormSlider')
				),
			),

			'3d' => array(
				'value' => '',
				'key' => '3d'
			),

			'2d' => array(
				'value' => '',
				'key' => '2d'
			),

			'custom3d' => array(
				'value' => '',
				'key' => 'custom3d'
			),

			'custom2d' => array(
				'value' => '',
				'key' => 'custom2d'
			),
		),

		'sublayer' => array(
			// slide sublayer Show, default is false
			'show' => array(
				'name' => __('Show', 'StormSlider'),
				'value' => false,
				'key' => 'show',
				'desc' => __('Show layer in the editor.', 'StormSlider')
			),

			// slide sublayer lock, default is false
			'lock' => array(
				'name' => __('Lock Layer', 'StormSlider'),
				'value' => false,
				'key' => 'lock',
				'desc' => __('Prevent layer from the dragging in the editor.', 'StormSlider')
			),

			// sublayer type, default is text
			'layerType' => array(
				'name' => __('Layer Type', 'StormSlider'),
				'value' => 'text',
				'key' => 'layerType'
			),

			// sublayer name
			'layerName' => array(
				'name' => __('Layer Name', 'StormSlider'),
				'value' => 'Layer',
				'key' => 'layerName'
			),

			// slide sublayer content.
			'slideSubLayerText' => array(
				'name' => __('The layer text.', 'StormSlider'),
				'value' => '',
				'key' => 'slideSubLayerText',
				'desc' => __('Add text to layer and render in slide after.', 'StormSlider')
			),

			// slide sublyaer image
			'slideSubLayerImage' => array(
				'name' => __('The layer image.', 'StormSlider'),
				'value' => plugins_url('/image/image-layer.png', dirname(__FILE__)),
				'key' => 'slideSubLayerImage',
				'desc' => __('Add image to layer and render in slide after.', 'StormSlider')
			),

			// slide sublayer transition parallax, default is 0
			'parallax' => array(
				'name' => __('Parallax Level :', 'StormSlider'),
				'value' => '',
				'key' => 'parallax',
				'desc' => __('Applies a parallax effect on layer.', 'StormSlider')
			),

			'checkLayerTransitionHide' => array(
				'value' => false,
				'key'   => 'checkLayerTransitionHide'
			),

			'transitionShow' => array(
				'value' => '',
				'key'   => 'transitionShow'
			),

			'transitionHide' => array(
				'value' => '',
				'key'   => 'transitionHide'
			),


			// the layer position from the top  of the slide, default is zero
			'positionTop' => array(
				'name' => __('Top :', 'StormSlider'),
				'value' => '',
				'key' => 'positionTop',
				'desc' => __('The layer position from the top  of the slide. you can use pixels and percentage. Example: 100px', 'StormSlider')
			),

			// the layer position from the left of the slide. default is 0
			'positionLeft' => array(
				'name' => __('Left :', 'StormSlider'),
				'value' => '',
				'key' => 'positionLeft',
				'desc' => __('The layer position from the left of the slide. you can use pixels and percentage. Example: 100px', 'StormSlider')
			),

			//Set the sublayer position way. default is fixed on the slider
			'positionWay' => array(
				'name' => __('Position way :', 'StormSlider'),
				'value' => '',
				'key' => 'positionWay',
				'desc' => __('You can set the position fixed on the slider, or fixed on the window.', 'StormSlider'),
				'title' => array(
					'fixedSlider' => __('Fixed on the slider.', 'StormSlider'),
					'fixedWindow' => __('Fixed on the window.', 'StormSlider')
				)
			),

			// set the sublayer width. default is auto
			'subWidth' => array(
				'name' => __('Width :', 'StormSlider'),
				'value' => '',
				'key' => 'subWidth',
				'desc' => __('You can set the width of the layer. you can use pixels, percentage, or the default value auto. Example: 100px', 'StormSlider')
			),

			// set the sublayer height. default is auto
			'subHeight' => array(
				'name' => __('Height :', 'StormSlider'),
				'value' => '',
				'key' => 'subHeight',
				'desc' => __('You can set the height of the layer. you can use pixels, percentage, or the default value auto. Example: 100px', 'StormSlider')
			),

			// set the sublayer border on the top. default is none
			'borderTop' => array(
				'name' => __('Top :', 'StormSlider'),
				'value' => '',
				'key' => 'borderTop',
				'desc' => __('You can set the border on the top of the layer. Example: 5px solid #000', 'StormSlider')
			),

			// set the sublayer border on the right. default is none
			'borderRight' => array(
				'name' => __('Right :', 'StormSlider'),
				'value' => '',
				'key' => 'borderRight',
				'desc' => __('You can set the border on the right of the layer. Example: 5px solid #000', 'StormSlider')
			),

			// set the sublayer border on the bottom. default is none
			'borderBottom' => array(
				'name' => __('Bottm :', 'StormSlider'),
				'value' => '',
				'key' => 'borderBottom',
				'desc' => __('You can set the border on the bottom of the layer. Example: 5px solid #000', 'StormSlider')
			),

			// set the sublayer border on the left. default is none
			'borderLeft' => array(
				'name' => __('Left :', 'StormSlider'),
				'value' => '',
				'key' => 'borderLeft',
				'desc' => __('You can set the border on the left of the layer. Example: 5px solid #000', 'StormSlider')
			),

			//Set the sublayer border style. default is solid
			'borderStyle' => array(
				'name' => __('Border Style :', 'StormSlider'),
				'value' => '',
				'key' => 'borderStyle',
				'desc' => __('Choose border style.', 'StormSlider')
			),

			//Set the sublayer border color. default is transparent
			'borderColor' => array(
				'name' => __('Border Color :', 'StormSlider'),
				'value' => '',
				'key' => 'borderColor',
				'desc' => __('The color of you border. you can use color names,  heaxdecimal, RGB or RGBA values. Example: #000', 'StormSlider')
			),


			// set the sublayer padding on the top. default is none
			'paddingTop' => array(
				'name' => __('Top :', 'StormSlider'),
				'value' => '',
				'key' => 'paddingTop',
				'desc' => __('You can set the padding on the top of the layer. Example: 5px', 'StormSlider')
			),


			// set the sublayer padding on the right. default is none
			'paddingRight' => array(
				'name' => __('Right :', 'StormSlider'),
				'value' => '',
				'key' => 'paddingRight',
				'desc' => __('You can set the padding on the right of the layer. Example: 5px', 'StormSlider')
			),


			// set the sublayer padding on the bottom. default is none
			'paddingBottom' => array(
				'name' => __('Bottom :', 'StormSlider'),
				'value' => '',
				'key' => 'paddingBottom',
				'desc' => __('You can set the padding on the bottom of the layer. Example: 5px', 'StormSlider')
			),


			//Set the sublayer padding on the left. default is none
			'paddingLeft' => array(
				'name' => __('Left :', 'StormSlider'),
				'value' => '',
				'key' => 'paddingLeft',
				'desc' => __('You can set the padding on the left of the layer. Example: 5px', 'StormSlider')
			),

			//Set the sublayer font weight.
			'fontWeight' => array(
				'name' => __('Weight :', 'StormSlider'),
				'value' => '400',
				'key' => 'fontWeight',
				'desc' => __('Select font weight for your layer.', 'StormSlider'),
				'title' => array(
					'100' => __('Thin', 'StormSlider'),
					'200' => __('Extra light', 'StormSlider'),
					'300' => __('Light', 'StormSlider'),
					'400' => __('Normal', 'StormSlider'),
					'500' => __('Medium', 'StormSlider'),
					'600' => __('Semi blod', 'StormSlider'),
					'700' => __('Blod', 'StormSlider'),
					'800' => __('Extra blod', 'StormSlider'),
					'900' => __('Black', 'StormSlider')
				)

			),

			//Set the sublayer font color. default is black
			'fontColor' => array(
				'name' => __('Color :', 'StormSlider'),
				'value' => '#ffffff',
				'key' => 'fontColor',
				'desc' => __('The color of you text. you can use color names,  heaxdecimal, RGB or RGBA values. Example: #000, Default is black.', 'StormSlider')
			),

			//Set the sublayer font size. default is inherit
			'fontSize' => array(
				'name' => __('Size :', 'StormSlider'),
				'value' => '',
				'key' => 'fontSize',
				'desc' => __('The font size in pixels. Example: 14px', 'StormSlider')
			),

			// set the sublayer font line-height. default is inherit
			'fontLineHeight' => array(
				'name' => __('Line-height :', 'StormSlider'),
				'value' => '',
				'key' => 'fontLineHeight',
				'desc' => __('The font Line-height in pixels. Example: 24px', 'StormSlider')
			),

			// set the sublayer word-wrap, default is false
			'wordWrap' => array(
				'name' => __('word-wrap :', 'StormSlider'),
				'value' => false,
				'key' => 'wordWrap',
				'desc' => __('If you use custom size layer, you haved to enable this setting to wrap your text.', 'StormSlider')
			),

			// set the sublayer background color. default is #000
			'backgroundColor' => array(
				'name' => __('Background :', 'StormSlider'),
				'value' => '',
				'key' => 'backgroundColor',
				'desc' => __('You can set the background color of your layer. You can use color names, heaxdecimal, RGB or RBGA values. Example: #000', 'StormSlider')
			),

			// set the sublayer background radius. default is zero
			'backgroundRadius' => array(
				'name' => __('Background Radius :', 'StormSlider'),
				'value' => '',
				'key' => 'backgroundRadius',
				'desc' => __('You can set the background radius of your layer. Example: 5px', 'StormSlider')
			),

			// set the sublayer misc class. default is empty
			'subClass' => array(
				'name' => __('Class :', 'StormSlider'),
				'value' => '',
				'key' => 'subClass',
				'desc' => __('You can apply class attributes of this HTML element to work with it in your custom CSS or javascript code.', 'StormSlider')
			),

			// set the sublayer misc id. default is empty
			'subId' => array(
				'name' => __('Id :', 'StormSlider'),
				'value' => '',
				'key' => 'subId',
				'desc' => __('You can apply id attributes of this HTML element to work with it in your custom CSS or javascript code.', 'StormSlider')
			),

			// set the sublayer misc title. default is empty
			'subTitle' => array(
				'name' => __('Title :', 'StormSlider'),
				'value' => '',
				'key' => 'subTitle',
				'desc' => __('You can add a title to this layer which will display as a tooltip if someone holds his mouse cursor over the layer.', 'StormSlider')
			),

			// set the sublayer misc alt. default is empty
			'subAlt' => array(
				'name' => __('Alt :', 'StormSlider'),
				'value' => '',
				'key' => 'subAlt',
				'desc' => __('You can add an alternative text to this layer which will display as a tooltip if someone holds mouse cursor over the layer.', 'StormSlider')
			),

			// set the sublayer misc rel. default is empty
			'subRel' => array(
				'name' => __('Rel :', 'StormSlider'),
				'value' => '',
				'key' => 'subRel',
				'desc' => __('Some pluin will use rel attributes. You can add specify it.', 'StormSlider')
			),
		),

		'transition' => array(
			// preset name
			'name' => array(
				'name' => __('Preset Name.', 'StormSlider'),
				'value' => 'Custom effect'
			),

			// transition ease function
			'transEase' => array(
				'name' => __('Ease function :', 'StormSlider'),
				'value' => 'liner',
				'key' => 'transEase',
				'desc' => __('Slide transition ease function.', 'StormSlider')
			),

			// transition fade
			'fade' => array(
				'name' => __('Fade :', 'StormSlider'),
				'value' => 'off',
				'key' => 'fade',
				'desc' => __('transition fade', 'StormSlider')
			),

			// transition effect duration, default is 4s
			'duration' => array(
				'name' => __('Duration :', 'StormSlider'),
				'value' => '1',
				'key'   => 'duration',
				'desc' => __('Slide transition effects duration time.', 'StormSlider')
			),

			// transition effect delay, default is 1s
			'delay' => array(
				'name' => __('Delay :', 'StormSlider'),
				'value' => '1',
				'key' => 'delay',
				'desc' => __('Slide transition effects delay time.', 'StormSlider')
			),

			// transition effect offset x
			'offsetX' => array(
				'name' => __('OffsetX :', 'StormSlider'),
				'value' => '',
				'key' => 'offsetX',
				'desc' => __('Slide transition effect offset x.', 'StormSlider')
			),

			// transition effect offset y
			'offsetY' => array(
				'name' => __('OffsetY :', 'StormSlider'),
				'value' => '',
				'key' => 'offsetT',
				'desc' => __('Slide transition effect offset y.', 'StormSlider')
			),

			// transition rotate 2d
			'rotate2D' => array(
				'name' => __('2D Rotate :', 'StormSlider'),
				'value' => '',
				'key' => 'rotate2D',
				'desc' => __('Transition 2d rotate.', 'StormSlider')
			),

			// transition rotate x
			'rotateX' => array(
				'name' => __('Rotate X :', 'StormSlider'),
				'value' => '',
				'key' => 'rotateX',
				'desc' => __('Transition rotate x.', 'StormSlider')
			),

			// transition rotate y
			'rotateY' => array(
				'name' => __('Rotate Y :', 'StormSlider'),
				'value' => '',
				'key' => 'rotateX',
				'desc' => __('Transition rotate y.', 'StormSlider')
			),

			// transition rotate z
			'rotateZ' => array(
				'name' => __('Rotate Z :', 'StormSlider'),
				'value' => '',
				'key' => 'rotateZ',
				'desc' => __('Transition rotate z.', 'StormSlider')
			),

			// transition scale x
			'scaleX' => array(
				'name' => __('Scale X :', 'StormSlider'),
				'value' => '',
				'key' => 'scaleX',
				'desc' => __('Transition scale x.', 'StormSlider')
			),

			// transition scale y
			'scaleY' => array(
				'name' => __('Scale Y :', 'StormSlider'),
				'value' => '',
				'key' => 'scaleY',
				'desc' => __('Transition scale y.', 'StormSlider')
			),

			// transition skew x
			'skewX' => array(
				'name' => __('Skew X :', 'StormSlider'),
				'value' => '',
				'key' => 'skewX',
				'desc' => __('Transition skew x.', 'StormSlider')
			),

			// transition skew x
			'skewY' => array(
				'name' => __('Skew Y :', 'StormSlider'),
				'value' => '',
				'key' => 'skewY',
				'desc' => __('Transition skew y.', 'StormSlider')
			),
		)
	),


	'effect' => array(
		'linear',
		'swing',
		'easeInQuad',
		'easeOutQuad',
		'easeInOutQuad',
		'easeInCubic',
		'easeOutCubic',
		'easeInOutCubic',
		'ease',
		'easeInQuart',
		'easeOutQuart',
		'easeInOutQuart',
		'easeInQuint',
		'easeOutQuint',
		'easeInOutQuint',
		'easeInExpo',
		'easeOutExpo',
		'easeInOutExpo',
		'easeInSine',
		'easeOutSine',
		'easeInOutSine',
		'easeInCirc',
		'easeOutCirc',
		'easeInOutCirc',
		'easeInElastic',
		'easeOutElastic',
		'easeInOutElastic',
		'easeInBack',
		'easeOutBack',
		'easeInOutBack',
		'easeInBounce',
		'easeOutBounce',
		'easeInOutBounce'
	)
);
