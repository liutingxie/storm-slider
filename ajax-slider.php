<?php

add_action('wp_ajax_storm_slider', 'storm_slider_ajax');
add_action('wp_ajax_nopriv_storm_slider', 'storm_slider_ajax');

function storm_slider_ajax(){
	$id = $_POST['id'];
	$sliderData = $_POST['data'];


	// Parse slider
	$sliderData['slider'] = json_decode(stripslashes($sliderData['slider']), true);

	// Parse Slider layers
	if(!empty($sliderData['layers']) && is_array($sliderData['layers'])){
		foreach ($sliderData['layers'] as $key => $value) {
			$sliderData['layers'][$key] = json_decode(stripcslashes($value), ture);
		}
	}

	Storm_Sliders::update($id, $sliderData);

	die();
}

add_action('wp_ajax_stormSliderAddOption', 'storm_trans_ajax');
add_action('wp_ajax_nopriv_stormSliderAddOption', 'storm_trans_ajax');

function storm_trans_ajax() {
	$option_name = $_POST['option_name'];

	//Parse transition data
	$data = json_decode(stripslashes($_POST['data']), true);

	Storm_Sliders::addOption($option_name, $data);

	die();
}

add_action('wp_ajax_stormSliderUpdateOption', 'storm_trans_update_ajax');
add_action('wp_ajax_nopriv_stormSliderUpdateOption', 'storm_trans_update_ajax');

function storm_trans_update_ajax() {
	$option_name = $_POST['option_name'];

	//Parse transition data
	$data = json_decode(stripslashes($_POST['data']), true);

	Storm_Sliders::updateOption($option_name, $data);

	die();
}

add_action('wp_ajax_stormScreenSetting', 'storm_screen_setting_ajax');
add_action('wp_ajax_nopriv_stormScreenSetting', 'storm_screen_setting_ajax');

function storm_screen_setting_ajax() {
	$data = !empty($_POST['data']) ? $_POST['data'] : 20;

	update_option('storm-screen-setting', $data);

	die();
}