<?php
/**
 * @Author: qwer2370722
 * @Date:   2016-05-13 11:09:55
 * @Last Modified by:   liutingxie
 * @Last Modified time: 2018-01-06 18:21:03
 */

function stormslider($id) {
  echo storm_shortcodes::display($id);
}

class storm_shortcodes{

	public static function registerShortcode() {

		if(!shortcode_exists('stormslider')) {
		  add_shortcode('stormslider', array(__CLASS__, 'stormslider_shortcode'));
		}

	}

	public static function stormslider_shortcode($atts){

	  $atts = shortcode_atts(array('id' => 1), $atts);
	  ob_start();
	  self::display($atts['id']);
	  return ob_get_clean();

	}

	public static function display($id){

		$sliderData = Storm_Sliders::_getByid($id);
		if(empty($sliderData['data']['layers'][0]['param']['background'])) {
			echo '<div class="noti">youwenti</div>';
			return;
		}

		$uid = uniqid('STORM');

		$data = $sliderData['data'];

		$styleAttr[] = 'width: '.$data['slider']['width'].'px;';
		$styleAttr[] = 'height: '.$data['slider']['height'].'px;';

		$styleAttr[] = 'margin: 0px auto;';

		//Set slider data
		$data['slider']['layout'] ? $arraySlider = "layout: '" . $data['slider']['layout'] . "'," : '';
		$data['slider']['firstSlide'] ? $arraySlider .= "firstSlide: " . $data['slider']['firstSlide'] . "," : '';
		$data['slider']['backgroundColor'] ? $arraySlider .= "backgroundColor: '" . $data['slider']['backgroundColor'] . "'," : '';
		$data['slider']['backgroundImage'] ? $arraySlider .= "backgroundImage: '" . $data['slider']['backgroundImage'] . "'," : '';
		$data['slider']['backgroundAttachment'] ? $arraySlider .= "backgroundAttachment: '" . $data['slider']['backgroundAttachment'] . "'," : '';
		$data['slider']['backgroundRepeat'] ? $arraySlider .= "backgroundRepeat: '" . $data['slider']['backgroundRepeat'] . "'," : '';
		$data['slider']['backgroundPosition'] ? $arraySlider .= "backgroundPosition: '" . str_replace('-', ' ', $data['slider']['backgroundPosition']) . "'," : '';
		$data['slider']['backgroundSize'] ? $arraySlider .= "backgroundSize: '" . $data['slider']['backgroundSize'] . "'," : '';
		$data['slider']['showNavigationButton'] ? $arraySlider .= "showNavigationButton: " . $data['slider']['showNavigationButton'] . "," : '';
		$data['slider']['showStartStopButton'] ? $arraySlider .= "showStartStopButton: " . $data['slider']['showStartStopButton'] . "," : '';
		$data['slider']['showThumbButton'] ? $arraySlider .= "showThumbButton: " . $data['slider']['showThumbButton'] . "," : '';
		$data['slider']['showThumbImgAmount'] ? $arraySlider .= "showThumbImgAmount: " . $data['slider']['showThumbImgAmount'] . "," : '';
		$data['slider']['showCircleTimer'] ? $arraySlider .= "showCircleTimer: " . $data['slider']['showCircleTimer'] . "," : '';
		$data['slider']['showBarTimer'] ? $arraySlider .= "showBarTimer: " . $data['slider']['showBarTimer'] . "," : '';


		//Set slideLink data
 		foreach ($data['layers'] as $key => $value) {
			if($value['param']['slideLink']) {
				$linkData = array();
				foreach ($value['param']['slideLink'] as $subkey => $subvalue) {
					if(!empty($subvalue)) {
						$linkData[] = $subkey . ': ' . $subvalue . ';';
					}
				}
				$linkArr['slideLink'] = $linkData;

				// Get slide data link
				if(!empty($linkData['slideLink']['linkUrl'])) {
					$linkString[$key] = 'data-link="'.implode(' ', $linkArr['slideLink']).'"';
				}
			}
		}


		foreach ($data['layers'] as $key => $value) {
			if($value['param']['slideMisc']) {
				$miscData = array();
				foreach ($value['param']['slideMisc'] as $subkey => $subvalue) {
					if(!empty($subvalue)) {
						$miscData[] = $subkey . ': ' . $subvalue . ';';
					}
				}
				$miscArr['slideMisc'] = $miscData;

				// Get slide data misc
				if(!empty($miscData)) {
					$miscString[$key] = 'data-misc="'.implode(' ', $miscArr['slideMisc']).'"';
				}else {
					$miscString[$key] = '';
				}
			}
		}

		$sublayeKeyData = array();
		foreach ($data['layers'] as $key => $value) {
			if(isset($value['sublayer'])) {

				foreach ($value['sublayer'] as $subkey => $subvalue) {

					$sublayeData = array();

					$subvalue['positionTop'] ? $sublayeData[] = 'top: ' . $subvalue['positionTop'] . 'px;' : '';
					$subvalue['positionLeft'] ? $sublayeData[] = 'left: ' . $subvalue['positionLeft'] . 'px;' : '';
					$subvalue['subWidth'] ? $sublayeData[] = 'width: ' . $subvalue['subWidth'] . 'px;' : $sublayeData[] = 'width: 50px;';
					$subvalue['subHeight'] ? $sublayeData[] = 'height: ' . $subvalue['subHeight'] . 'px;' : $sublayeData[] = 'height: 50px;';
					$subvalue['borderTop'] ? $sublayeData[] = 'border-top: ' . $subvalue['borderTop'] . 'px;' : '';
					$subvalue['borderRight'] ? $sublayeData[] = 'border-right: ' . $subvalue['borderRight'] . 'px;' : '';
					$subvalue['borderBottom'] ? $sublayeData[] = 'border-bottom: ' . $subvalue['borderBottom'] . 'px;' : '';
					$subvalue['borderLeft'] ? $sublayeData[] = 'border-left: ' . $subvalue['borderLeft'] . 'px;' : '';
					$subvalue['borderStyle'] ? $sublayeData[] = 'border-style: ' . $subvalue['borderStyle'] . ';' : 'none;';
					$subvalue['borderColor'] ? $sublayeData[] = 'border-color: ' . $subvalue['borderColor'] . ';' : 'transparent;';
					$subvalue['paddingTop'] ? $sublayeData[] = 'padding-top: ' . $subvalue['paddingTop'] . 'px;' : '';
					$subvalue['paddingRight'] ? $sublayeData[] = 'padding-right: ' . $subvalue['paddingRight'] . 'px;' : '';
					$subvalue['paddingBottom'] ? $sublayeData[] = 'padding-bottom: ' . $subvalue['paddingBottom'] . 'px;' : '';
					$subvalue['paddingLeft'] ? $sublayeData[] = 'padding-left: ' . $subvalue['paddingLeft'] . 'px;' : '';
					$subvalue['wordWrap'] ? $sublayeData[] = 'white-space: ' . $subvalue['wordWrap'] . ';' : '';
					$subvalue['fontWeight'] ? $sublayeData[] = 'font-weight: ' . $subvalue['fontWeight'] . ';' : '';
					$subvalue['fontColor'] ? $sublayeData[] = 'color: ' . $subvalue['fontColor'] . ';' : '';
					$subvalue['fontSize'] ? $sublayeData[] = 'font-size: ' . $subvalue['fontSize'] . 'px;' : '';
					$subvalue['fontLineHeight'] ? $sublayeData[] = 'line-height: ' . $subvalue['fontLineHeight'] . 'px;' : '';
					$subvalue['backgroundColor'] ? $sublayeData[] = 'background-color: ' . $subvalue['backgroundColor'] . ';' : '';
					$subvalue['backgroundRadius'] ? $sublayeData[] = 'border-radius: ' . $subvalue['backgroundRadius'] . 'px;' : '';

					$sublayeDataArr[$subkey]['transitionShow'] = $sublayeData;


					// Get slide sublayer style data
					$sublayeKeyData[$key][$subkey] = 'style="'.implode(' ', $sublayeDataArr[$subkey]['transitionShow']).'"';
				}
			}
		}

		$transitionShowString = array();
		foreach ($data['layers'] as $key => $value) {
			if(isset($value['sublayer'])) {
				foreach ($value['sublayer'] as $subkey => $subvalue) {
					$transtionShowData = array();

					foreach ($subvalue['transitionShow'] as $showkey => $showvalue) {
						if(!empty($showvalue)) {
							$transtionShowData[] = $showkey . ':' . $showvalue . ';';
						}
					}

					$transitionShowArr[$subkey]['transitionShow'] = $transtionShowData;

					// Get slide transitionShow data
					$transitionShowString[$key][$subkey] = 'data-storm-transitionshow="'.implode('', $transitionShowArr[$subkey]['transitionShow']).'"';
				}


			}
		}

		$transitionHideString = array();
		foreach ($data['layers'] as $key => $value) {
			if(isset($value['sublayer'])) {
				foreach ($value['sublayer'] as $subkey => $subvalue) {

					if($subvalue['checkLayerTransitionHide']) {

						$transtionHideData = array();

						foreach ($subvalue['transitionHide'] as $hidekey => $hidevalue) {
							if(!empty($hidevalue)) {
								$transtionHideData[] = $hidekey . ':' . $hidevalue . ';';
							}
						}

						$transitionHideArr[$subkey]['transitionHide'] = $transtionHideData;

						// Get slide transitionHide data
						$transitionHideString[$key][$subkey] = 'data-storm-transitionhide="'.implode('', $transitionHideArr[$subkey]['transitionHide']).'"';
					}
				}
			}
		}

		$paramString = array();
		foreach ($data['layers'] as $key => $value) {
			$paramData = array();
			foreach ($value['param'] as $subkey => $subvalue) {
				if(!empty($subvalue) && !is_array($subvalue)) {
					$paramData[] = $subkey . ': ' . $subvalue . ';';
				}
			}

			$paramArr['option'] = $paramData;

			$strings = implode($paramArr['option']);
			if(strpos($strings, '3d') == false && strpos($strings, '2d') == false) {
				$paramArr['option'][] = '2d: 1;';
			}

			// Get slide option data
			$paramString[$key] = 'data-option="'.implode(' ', $paramArr['option']).'"';
		}


		?>
		<script type="text/javascript"> var stormjQuery = jQuery; </script>
		<script type="text/javascript">
			stormjQuery(document).ready(function() {
				if(typeof stormjQuery.fn.stormSlider == 'undefined') {
				}
				else {
					stormjQuery('#stormslider_<?php echo $id; ?>[data-stormuid="<?php echo $uid; ?>"]').stormSlider({ <?php echo $arraySlider; ?> });
				}
			})
		</script>
		<div id="stormslider_<?php echo $id; ?>" data-stormuid="<?php echo $uid; ?>">
			<div class="storm-inner-parent" style="<?php echo implode(' ', $styleAttr); ?>">
				<?php foreach ($data['layers'] as $key => $value) : ?>
				<div class="storm-layer" <?php if(isset($value['param']['slideLink'])) {if(!empty($linkString[$key])) { echo $linkString[$key]; }} if(isset($value['param']['slideMisc'])) {echo $miscString[$key];} ?> <?php echo $paramString[$key]; ?> <?php echo 'data-slidedelay="'.$value['param']['slideDelay'].'"'; ?> >
					<img src="<?php echo $value['param']['background']; ?>" class="storm-bg" >

					<?php if(isset($value['sublayer'])) : ?>
						<?php foreach ($value['sublayer'] as $subkey => $subvalue): ?>
							<?php if(!$subvalue['show'] && $subvalue['layerType'] == 'text') : ?>
								<div class="storm-sublayer" <?php if(isset($subvalue['transitionShow'])) {echo $transitionShowString[$key][$subkey];} if($subvalue['checkLayerTransitionHide']) {echo $transitionHideString[$key][$subkey];} echo $sublayeKeyData[$key][$subkey]; ?>><?php echo $subvalue['slideSubLayerText']; ?></div>
							<?php endif; ?>
							<?php if(!$subvalue['show'] && $subvalue['layerType'] == 'image') : ?>
								<img class="storm-sublayer" src="<?php echo $subvalue['slideSubLayerImage'] ?>" <?php if(isset($subvalue['transitionShow'])) {echo $transitionShowString[$key][$subkey];} if($subvalue['checkLayerTransitionHide']) {echo $transitionHideString[$key][$subkey];} echo $sublayeKeyData[$key][$subkey]; ?>>
							<?php endif; ?>
							<?php if(!$subvalue['show'] && $subvalue['layerType'] == 'button') : ?>
								<button class="storm-sublayer" <?php if(isset($subvalue['transitionShow'])) {echo $transitionShowString[$key][$subkey];} if($subvalue['checkLayerTransitionHide']) {echo $transitionHideString[$key][$subkey];} echo $sublayeKeyData[$key][$subkey]; ?>><?php echo $subvalue['slideSubLayerText']; ?></button>
							<?php endif; ?>
						<?php endforeach ?>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	// public function add_shortcode_jquery()
	// {
	// 	$jquery[] = '<script type="text/javascript"> var stormjQuery = jQuery </script>';

	// 	$jquery[] .= '<script type="text/javascript">' . '\r\n';
	// 		$jquery[] .= 'stormjQuery(document).ready(function() {' . '\r\n';
	// 			$jquery[] .= '' . '\r\n';
	// 		$jquery[] .= '})' . '\r\n';
	// 	$jquery[] .= '</script>';

	// 	return $jquery;
	// }
}