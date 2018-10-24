<?php
/**
 * @Author: 37156
 * @Date:   2016-12-18 10:59:49
 * @Last Modified by:   liutingxie
 * @Last Modified time: 2017-11-07 16:04:45
 */

	//Custom transition file
	$custom_dir = wp_upload_dir();
	$custom_file = $custom_dir['basedir'] . '/storm-transition.js';
	$default_file = STORM_DIR . 'config/transitionCustomData.js';

	//Get transition file
	if(file_exists($custom_file)) {
		$data = file_get_contents($custom_file);
	}
	else if(file_exists($default_file)) {
		$data = file_get_contents($default_file);
	}

	//Get JSON data
	if(!empty($data)) {
		$data = substr($data, 27);
		$data = substr($data, 0, -1);
		$data = json_decode($data, true);
	}

	function converProperty($value) {
		switch ($value) {
			case 'scale3d':
				return 'Scale3D';
				break;

			case 'rotateX':
				return 'RotateX';
				break;

			case 'rotateY':
				return 'RotateY';
				break;

			case 'delay':
				return 'Delay';
				break;

			default:
				return $value;
			break;
		}
	}
?>

<script type="text/template" id="template-3d">
	<div class="transition-item">
		<table>
			<thead>
				<tr>
					<td  colspan="4"><?php _e('Preview', 'StormSlider'); ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="4">
						<div class="storm-builder-preview storm-transition-preview ">
							<img src="<?php echo plugins_url('/image/slide1-preview.png', dirname(__FILE__)); ?>" alt="preview image">
						</div>
						<div class="storm-builder-preview-button">
							<a href="#" class="btn"><?php _e('Preview', 'StormSlider') ?></a>
						</div>
					</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<td  colspan="4"><?php _e('Basic propertier', 'StormSlider'); ?></td>
				</tr>
			</thead>
			<tbody class="basic">
				<tr>
					<td class="right">
						<?php _e('Transition name :', 'StormSlider'); ?>
					</td>
					<td colspan="3">
						<input type="text" name="name" value="Turn top" title="The name of you custom transition!">
					</td>
				</tr>
				<tr>
					<td class="right">
						<?php _e('Rows :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="rows" value="1" title="Number or min,max. If you specify a value greater than 1, that will cut your slide into tiles. You can specify here how many rows of your transition should have.">
					</td>
					<td class="right">
						<?php _e('Cols :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="cols" value="1" title="Number or min,max. If you specify a value greater than 1, that will cut your slide into tiles. You can specify here how many cols of your transition should have.">
					</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<td colspan="4"><?php _e('Tiles', 'StormSlider'); ?></td>
				</tr>
			</thead>
			<tbody class="tile">
				<tr>
					<td class="right">
						<?php _e('Delay ：', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="delay" value="75" title="You can apply a delay between the tiles. The value is in millisecs.">
					</td>
					<td class="right">
						<?php _e('Sequence :', 'StormSlider'); ?>
					</td>
					<td>
						<select name="sequence" title="You can select the transition order of the tiles here">
							<option value="forward"><?php _e('Forward', 'StormSlider'); ?></option>
							<option value="reverse"><?php _e('Reverse', 'StormSlider'); ?></option>
							<option value="col-forward"><?php _e('Col-forward', 'StormSlider'); ?></option>
							<option value="col-reverse"><?php _e('Col-reverse', 'StormSlider'); ?></option>
							<option value="spiral"><?php _e('Spiral', 'StormSlider'); ?></option>
							<option value="spiral-center"><?php _e('Spiral-center', 'StormSlider'); ?></option>
							<option value="spread"><?php _e('Spread', 'StormSlider'); ?></option>
							<option value="spread-center"><?php _e('Spread-center', 'StormSlider'); ?></option>
							<option value="random"><?php _e('Random', 'StormSlider'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td class="right">
						<?php _e('Depth :', 'StormSlider'); ?>
					</td>
					<td>
						<label for="depth" title="The script tries identify the optimal depth for you rotated objects(tiles).">
							<input type="checkbox" name="depth" value="large">
							<?php _e('Large depth', 'StormSlider'); ?>
						</label>
					</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<td colspan="4"><?php _e('Before animation', 'StormSlider'); ?>
						<p class="transition-checkbox">
							<label>
								<input type="checkbox" class="build-collaspe-toggle"><?php _e('Enabled', 'StormSlider'); ?>
							</label>
						</p>
					</td>
				</tr>
			</thead>
			<tbody class="before template-collapsed">
				<tr>
					<td class="right">
						<?php _e('Duration :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="duration" value="1" title="The duration of you animation. The value is in second.">
					</td>
					<td class="right">
						<a href="http://easings.net/" target="_blank"><?php _e('Easing', 'StormSlider'); ?></a>
					</td>
					<td>
						<select name="easing" title="The timing function of the animation. With this function you can manipulate the movement of the animated object. Please click on the link next to this select field to open easings.net for more information and real-time examples.">
							<option>linear</option>
							<option>swing</option>
							<option>easeInQuad</option>
							<option>easeOutQuad</option>
							<option>easeInOutQuad</option>
							<option>easeInCubic</option>
							<option>easeOutCubic</option>
							<option>easeInOutCubic</option>
							<option>easeInQuart</option>
							<option>easeOutQuart</option>
							<option>easeInOutQuart</option>
							<option>lineaseInQuintear</option>
							<option>easeOutQuint</option>
							<option selected="selected">easeInOutQuint</option>
							<option>easeInExpo</option>
							<option>easeOutExpo</option>
							<option>easeInOutExpo</option>
							<option>easeInSine</option>
							<option>easeOutSine</option>
							<option>easeInOutSine</option>
							<option>easeInCirc</option>
							<option>easeOutCirc</option>
							<option>easeInOutCirc</option>
							<option>easeInElastic</option>
							<option>easeOutElastic</option>
							<option>easeInOutElastic</option>
							<option>easeInBack</option>
							<option>easeOutBack</option>
							<option>easeInOutBack</option>
							<option>easeInBounce</option>
							<option>easeOutBounce</option>
							<option>easeInOutBounce</option>
						</select>
					</td>
				</tr>
				<tr class="transition">
					<td colspan="4">
						<ul class="transition-tag"></ul>
						<p class="transition-property">
							<a href="#" class="btn"><?php _e('Add new'); ?></a>
							<select>
								<option value="scale3d,0.8"><?php _e('Scale3D', 'StormSlider'); ?></option>
								<option value="rotateX,90"><?php _e('RotateX', 'StormSlider'); ?></option>
								<option value="rotateY,90"><?php _e('RotateY', 'StormSlider'); ?></option>
								<option value="delay,200"><?php _e('Delay', 'StormSlider'); ?></option>
							</select>
						</p>
					</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<td colspan="4"><?php _e('Animation', 'StormSlider'); ?></td>
				</tr>
			</thead>
			<tbody class="animation">
				<tr>
					<td class="right">
						<?php _e('Duration :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="duration" value="1" title="The duration of you animation. The value is in secode.">
					</td>
					<td class="right">
						<a href="http://easings.net/" target="_blank"><?php _e('Easing', 'StormSlider'); ?></a>
					</td>
					<td>
						<select name="easing" title="The timing function of the animation. With this function you can manipulate the movement of the animated object. Please click on the link next to this select field to open easings.net for more information and real-time examples.">
							<option>linear</option>
							<option>swing</option>
							<option>easeInQuad</option>
							<option>easeOutQuad</option>
							<option>easeInOutQuad</option>
							<option>easeInCubic</option>
							<option>easeOutCubic</option>
							<option>easeInOutCubic</option>
							<option>easeInQuart</option>
							<option>easeOutQuart</option>
							<option>easeInOutQuart</option>
							<option>lineaseInQuintear</option>
							<option>easeOutQuint</option>
							<option selected="selected">easeInOutQuint</option>
							<option>easeInExpo</option>
							<option>easeOutExpo</option>
							<option>easeInOutExpo</option>
							<option>easeInSine</option>
							<option>easeOutSine</option>
							<option>easeInOutSine</option>
							<option>easeInCirc</option>
							<option>easeOutCirc</option>
							<option>easeInOutCirc</option>
							<option>easeInElastic</option>
							<option>easeOutElastic</option>
							<option>easeInOutElastic</option>
							<option>easeInBack</option>
							<option>easeOutBack</option>
							<option>easeInOutBack</option>
							<option>easeInBounce</option>
							<option>easeOutBounce</option>
							<option>easeInOutBounce</option>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td class="right"><?php _e('Direction :', 'StormSlider'); ?></td>
					<td>
						<select name="direction" title="The direction of rotation">
							<option value="horizontal"><?php _e('Horizontal', 'StormSlider'); ?></option>
							<option value="vertical"><?php _e('Vertical', 'StormSlider'); ?></option>
						</select>
					</td>
				</tr>
				<tr class="transition">
					<td colspan="4">
						<ul class="transition-tag">
							<li>
								<span>RotateY</span>
								<input value="90" name="rotateY">
								<a href="#">
									<i class="dashicons dashicons-dismiss"></i>
								</a>
							</li>
						</ul>
						<p class="transition-property">
							<a href="#" class="btn"><?php _e('Add new', 'StormSlider'); ?></a>
							<select>
								<option value="scale3d,0.8"><?php _e('Scale3D', 'StormSlider'); ?></option>
								<option value="rotateX,90"><?php _e('RotateX', 'StormSlider'); ?></option>
								<option value="rotateY,90"><?php _e('RotateY', 'StormSlider'); ?></option>
								<option value="delay,200"><?php _e('Delay', 'StormSlider'); ?></option>
							</select>
						</p>
					</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<td colspan="4"><?php _e('After animation', 'StormSlider'); ?>
						<p class="transition-checkbox">
							<label>
								<input type="checkbox" class="build-collaspe-toggle"><?php _e('Enabled', 'StormSlider'); ?>
							</label>
						</p>
					</td>
				</tr>
			</thead>
			<tbody class="after template-collapsed">
				<tr>
					<td class="right">
						<?php _e('Duration :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="duration" value="1" title="The duration of you animation. The value is in second.">
					</td>
					<td class="right">
						<a href="http://easings.net/" target="_blank"><?php _e('Easing', 'StormSlider'); ?></a>
					</td>
					<td>
						<select name="easing" title="The timing function of the animation. With this function you can manipulate the movement of the animated object. Please click on the link next to this select field to open easings.net for more information and real-time examples.">
							<option>linear</option>
							<option>swing</option>
							<option>easeInQuad</option>
							<option>easeOutQuad</option>
							<option>easeInOutQuad</option>
							<option>easeInCubic</option>
							<option>easeOutCubic</option>
							<option>easeInOutCubic</option>
							<option>easeInQuart</option>
							<option>easeOutQuart</option>
							<option>easeInOutQuart</option>
							<option>lineaseInQuintear</option>
							<option>easeOutQuint</option>
							<option selected="selected">easeInOutQuint</option>
							<option>easeInExpo</option>
							<option>easeOutExpo</option>
							<option>easeInOutExpo</option>
							<option>easeInSine</option>
							<option>easeOutSine</option>
							<option>easeInOutSine</option>
							<option>easeInCirc</option>
							<option>easeOutCirc</option>
							<option>easeInOutCirc</option>
							<option>easeInElastic</option>
							<option>easeOutElastic</option>
							<option>easeInOutElastic</option>
							<option>easeInBack</option>
							<option>easeOutBack</option>
							<option>easeInOutBack</option>
							<option>easeInBounce</option>
							<option>easeOutBounce</option>
							<option>easeInOutBounce</option>
						</select>
					</td>
				</tr>
				<tr class="transition">
					<td colspan="4">
						<ul class="transition-tag"></ul>
						<p class="transition-property">
							<a href="#" class="btn"><?php _e('Add new', 'StormSlider'); ?></a>
							<select>
								<option value="scale3d,0.8"><?php _e('Scale3D', 'StormSlider'); ?></option>
								<option value="rotateX,90"><?php _e('RotateX', 'StormSlider'); ?></option>
								<option value="rotateY,90"><?php _e('RotateY', 'StormSlider'); ?></option>
								<option value="delay,200"><?php _e('Delay', 'StormSlider'); ?></option>
							</select>
						</p>
					</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<td colspan="4"><?php _e('Transition option', 'StormSlider'); ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="4">
						<a href="#" class="btn remove-option">Remove transition</a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</script>

<script type="text/template" id="template-2d">
	<div class="transition-item">
		<table>
			<thead>
				<tr>
					<td colspan="4"><?php _e('Preview', 'StormSlider'); ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="4">
						<div class="storm-builder-preview storm-transition-preview ">
							<img src="<?php echo plugins_url('/image/slide1-preview.png', dirname(__FILE__)); ?>" alt="preview image">
						</div>
						<div class="storm-builder-preview-button">
							<a href="#" class="btn"><?php _e('Preview', 'StormSlider') ?></a>
						</div>
					</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<td colspan="4"><?php _e('Basic properties', 'StormSlider'); ?></td>
				</tr>
			</thead>
			<tbody class="basic">
				<tr>
					<td class="right">
						<?php _e('Transition name :', 'StormSlider'); ?>
					</td>
					<td colspan="3">
						<input type="text" name="name" value="Trun top" title="The name of you custom transition!">
					</td>
				</tr>
				<tr>
					<td class="right">
						<?php _e('Rows :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="rows" value="1" title="Number or min,max. If you specify a value greater than 1, that will cut your slide into tiles. You can specify here how many rows of your transition should have.">
					</td>
					<td class="right">
						<?php _e('Cols ：', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="cols" value="1" title="Number or min,max. If you specify a value greater than 1, that will cut your slide into tiles. You can specify here how many cols of your transition should have.">
					</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<td colspan="4"><?php _e('Tiles', 'StormSlider'); ?></td>
				</tr>
			</thead>
			<tbody class="tile">
				<tr>
					<td class="right">
						<?php _e('Delay :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="delay" value="75" title="You can apply a delay between the tiles. The value is in millisecs">
					</td>
					<td class="right">
						<?php _e('Sequence :', 'StormSlider'); ?>
					</td>
					<td>
						<select name="sequence" title="You can control the animation order of the tiles here.">
							<option value="forward"><?php _e('Forward', 'StormSlider'); ?></option>
							<option value="reverse"><?php _e('Reverse', 'StormSlider'); ?></option>
							<option value="col-forward"><?php _e('Col-forward', 'StormSlider'); ?></option>
							<option value="col-reverse"><?php _e('Col-reverse', 'StormSlider'); ?></option>
							<option value="spiral"><?php _e('Spiral', 'StormSlider'); ?></option>
							<option value="spiral-center"><?php _e('Spiral-center', 'StormSlider'); ?></option>
							<option value="spread"><?php _e('Spread', 'StormSlider'); ?></option>
							<option value="spread-center"><?php _e('Spread-center', 'StormSlider'); ?></option>
							<option value="random"><?php _e('Random', 'StormSlider'); ?></option>
						</select>
					</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<td colspan="4"><?php _e('Transition', 'StormSlider'); ?></td>
				</tr>
			</thead>
			<tbody class="transition">
				<tr>
					<td class="right">
						<?php _e('Duration :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="duration" value="1" title="The duration of the animation. The value is in second.">
					</td>
					<td class="right">
						<a href="http://easings.net/" target="_blank"><?php _e('Easing', 'StormSlider'); ?></a>
					</td>
					<td>
						<select name="easing" title="The timing function of the animation. With this function you can manipulate the movement of the animated object. Please click on the link next to this select field to open easings.net for more information and real-time examples.">
							<option>linear</option>
							<option>swing</option>
							<option>easeInQuad</option>
							<option>easeOutQuad</option>
							<option>easeInOutQuad</option>
							<option>easeInCubic</option>
							<option>easeOutCubic</option>
							<option>easeInOutCubic</option>
							<option>easeInQuart</option>
							<option>easeOutQuart</option>
							<option>easeInOutQuart</option>
							<option>lineaseInQuintear</option>
							<option>easeOutQuint</option>
							<option selected="selected">easeInOutQuint</option>
							<option>easeInExpo</option>
							<option>easeOutExpo</option>
							<option>easeInOutExpo</option>
							<option>easeInSine</option>
							<option>easeOutSine</option>
							<option>easeInOutSine</option>
							<option>easeInCirc</option>
							<option>easeOutCirc</option>
							<option>easeInOutCirc</option>
							<option>easeInElastic</option>
							<option>easeOutElastic</option>
							<option>easeInOutElastic</option>
							<option>easeInBack</option>
							<option>easeOutBack</option>
							<option>easeInOutBack</option>
							<option>easeInBounce</option>
							<option>easeOutBounce</option>
							<option>easeInOutBounce</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="right">
						<?php _e('Type :', 'StormSlider'); ?>
					</td>
					<td>
						<select name="type" title="The type of the animation. either slide, fade or both(mixed).">
							<option value="slide"><?php _e('Slide', 'StormSlider'); ?></option>
							<option value="fade"><?php _e('Fade', 'StormSlider'); ?></option>
							<option value="mixed"><?php _e('Mixed', 'StormSlider'); ?></option>
						</select>
					</td>
					<td class="right">
						<?php _e('Direction :', 'StormSlider'); ?>
					</td>
					<td>
						<select name="direction" title="The direction of slide or mixed animation if you've chosen this type in the previous setting.">
							<option value="top"><?php _e('Top', 'StormSlider'); ?></option>
							<option value="right"><?php _e('Right', 'StormSlider'); ?></option>
							<option value="bottom"><?php _e('Bottom', 'StormSlider'); ?></option>
							<option value="left"><?php _e('Left', 'StormSlider'); ?></option>
							<option value="random"><?php _e('Random', 'StormSlider'); ?></option>
							<option value="topleft"><?php _e('Top left', 'StormSlider'); ?></option>
							<option value="topright"><?php _e('Top right', 'StormSlider'); ?></option>
							<option value="bottomleft"><?php _e('Bottom left', 'StormSlider'); ?></option>
							<option value="bottomright"><?php _e('Bottom right', 'StormSlider'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="right">
						<?php _e('RotateX :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="rotateX" value="0" title="The initial rotation of the individual tiles which will be animated to the default(0deg) value around the X axis. You can use negative values.">
					</td>
					<td class="right">
						<?php _e('RotateY :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="rotateY" value="0" title="The initial rotation of the individual tiles which will be animated to the default(0deg) value around the Y axis. You can use negative values.">
					</td>
				</tr>
				<tr>
					<td class="right">
						<?php _e('RotateZ :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="rotateZ" value="0" title="The initial rotation of the individual tiles which will be animated the default(0deg) value around the Z axis. You can use nagetive values.">
					</td>
					<td class="right">
						<?php _e('Scale :', 'StormSlider'); ?>
					</td>
					<td>
						<input type="text" name="scale" value="1" title="The initial scale of the individual tiles which will be animated the default(1.0) value.">
					</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<td colspan="4"><?php _e('Transition option', 'StormSlider'); ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="4">
						<a href="#" class="btn remove-option">Remove transition</a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</script>

<h2><?php _e('StormSlider Transition Builder', 'StormSlider'); ?></h2>
<form id="storm-slider-transition-template-form" method="post">
	<input type="hidden" name="storm-save-transition-template">
	<?php wp_nonce_field('save-transition-template'); ?>
	<div class="template-container">
		<h3>
			<div class="transition-build-left">
				<div class="header"><?php _e('3D Transition Template', 'StormSlider'); ?>
					<p><?php _e( 'Choose :', 'StormSlider'); ?>
						<select class="transition-template-select 3d">
							<?php if(!empty($data['3d']) && is_array($data['3d'])) : ?>
							<?php foreach ($data['3d'] as $key) : ?>
							<option><?php echo $key['name']; ?></option>
							<?php endforeach; ?>
							<?php else : ?>
							<option class="notification"><?php _e('Not 3D transition yet'); ?></option>
							<?php endif; ?>
						</select>
						<a href="#" class="add-slide-icon 3d">
							<i class="dashicons dashicons-plus"></i>
							<?php _e('Add Transition', 'StormSlider'); ?>
						</a>
					</p>
				</div>
			</div>
			<div class="transition-build-right">
				<div class="header"><?php _e('2D Transition Template', 'StormSlider'); ?>
					<p><?php _e( 'Choose :', 'StormSlider'); ?>
						<select class="transition-template-select 2d">
							<?php if(!empty($data['2d']) && is_array($data['2d'])) : ?>
							<?php foreach ($data['2d'] as $key => $value) : ?>
							<option><?php echo $value['name']; ?></option>
							<?php endforeach; ?>
							<?php else : ?>
							<option class="notification"><?php _e('Not 2D transition yet'); ?></option>
							<?php endif; ?>
						</select>
						<a href="#" class="add-slide-icon 2d">
							<i class="dashicons dashicons-plus"></i>
							<?php _e('Add Transition', 'StormSlider'); ?>
						</a>
					</p>
				</div>
			</div>
		</h3>
		<div class="transition-build-left transition-3d">
			<?php if(!empty($data['3d']) && is_array($data['3d'])) : ?>
			<?php foreach ($data['3d'] as $key => $value) : ?>
			<?php $activeClass = ($key == 0) ? 'active' : ''; ?>
			<div class="transition-item <?php echo $activeClass; ?>">
				<table>
					<thead>
						<tr>
							<td  colspan="4"><?php _e('Preview', 'StormSlider'); ?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="4">
								<div class="storm-builder-preview storm-transition-preview ">
									<img src="<?php echo plugins_url('/image/slide1-preview.png', dirname(__FILE__)); ?>" alt="preview image">
								</div>
								<div class="storm-builder-preview-button">
									<a href="#" class="btn"><?php _e('Preview', 'StormSlider') ?></a>
								</div>
							</td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<td  colspan="4"><?php _e('Basic propertier', 'StormSlider'); ?></td>
						</tr>
					</thead>
					<tbody class="basic">
						<tr>
							<td class="right">
								<?php _e('Transition name :', 'StormSlider'); ?>
							</td>
							<td colspan="3">
								<input type="text" name="name" value="<?php echo $value['name']; ?>" title="The name of your custom transition.">
							</td>
						</tr>
						<tr>
							<?php $rows = is_array($value['rows']) ? implode(',', $value['rows']) : $value['rows']; ?>
							<?php $cols = is_array($value['cols']) ? implode(',', $value['cols']) : $value['cols']; ?>
							<td class="right">
								<?php _e('Rows :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="rows" value="<?php echo $rows; ?>" title="Number or min,max. If you specify a value greatr than 1, that will cut your slide into tiles. You can specify here how many rows of your transition should have.">
							</td>
							<td class="right">
								<?php _e('Cols :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="cols" value="<?php echo $cols; ?>" title="Number or min,max. If you specify a value greater than 1, that will be cut your slide into tiles. You can specify here how many cols of your transition should have.">
							</td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<td colspan="4"><?php _e('Tiles', 'StormSlider'); ?></td>
						</tr>
					</thead>
					<tbody class="tile">
						<tr>
							<td class="right">
								<?php _e('Delay ：', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="delay" value="<?php echo $value['tile']['delay']; ?>" title="You can apply a delay between the tiles. The value is in millisecs">
							</td>
							<td class="right">
								<?php _e('Sequence :', 'StormSlider'); ?>
							</td>
							<td>
								<select name="sequence" title="You can control the animation order of the tiles here.">
									<option value="forward" <?php echo $value['tile']['sequence'] == 'forward' ? 'selected="selected"' : ''; ?>><?php _e('Forward', 'StormSlider'); ?></option>
									<option value="reverse" <?php echo $value['tile']['sequence'] == 'reverse' ? 'selected="selected"' : ''; ?>><?php _e('Reverse', 'StormSlider'); ?></option>
									<option value="col-forward" <?php echo $value['tile']['sequence'] == 'col-forward' ? 'selected="selected"' : ''; ?>><?php _e('Col-forward', 'StormSlider'); ?></option>
									<option value="col-reverse" <?php echo $value['tile']['sequence'] == 'col-reverse' ? 'selected="selected"' : ''; ?>><?php _e('Col-reverse', 'StormSlider'); ?></option>
									<option value="spiral" <?php echo $value['tile']['sequence'] == 'spiral' ? 'selected="selected"' : ''; ?>><?php _e('Spiral', 'StormSlider'); ?></option>
									<option value="spiral-center" <?php echo $value['tile']['sequence'] == 'spiral-center' ? 'selected="selected"' : ''; ?>><?php _e('Spiral-center', 'StormSlider'); ?></option>
									<option value="spread" <?php echo $value['tile']['sequence'] == 'spread' ? 'selected="selected"' : ''; ?>><?php _e('Spread', 'StormSlider'); ?></option>
									<option value="spread-center" <?php echo $value['tile']['sequence'] == 'spread-center' ? 'selected="selected"' : ''; ?>><?php _e('Spread-center', 'StormSlider'); ?></option>}
									option
									<option value="random" <?php echo $value['tile']['sequence'] == 'random' ? 'selected="selected"' : ''; ?>><?php _e('Random', 'StormSlider'); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td class="right">
								<?php _e('Depth :', 'StormSlider'); ?>
							</td>
							<td>
								<label for="depth" title="The script tries to identify the optimal depth for your rotated objects(tiles).">
									<input type="checkbox" name="depth" value="large" <?php echo isset($value['tile']['depth']) ? 'checked="checked"' : ''; ?>>
									<?php _e('Large depth', 'StormSlider'); ?>
								</label>
							</td>
						</tr>
					</tbody>
					<?php $beforeEnabledProp = isset($value['before']['enabled']) ? 'checked="checked"' : ''; ?>
					<?php $beforeCollapsedClass = isset($value['before']['enabled']) ? '' : 'template-collapsed'; ?>
					<thead>
						<tr>
							<td colspan="4"><?php _e('Before animation', 'StormSlider'); ?>
								<p class="transition-checkbox">
									<label>
										<input type="checkbox" class="build-collaspe-toggle" <?php echo $beforeEnabledProp; ?>><?php _e('Enabled', 'StormSlider'); ?>
									</label>
								</p>
							</td>
						</tr>
					</thead>
					<tbody class="before <?php echo $beforeCollapsedClass; ?>">
						<tr>
							<td class="right">
								<?php _e('Duration :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="duration" value="<?php echo isset($value['before']['duration']) ? $value['before']['duration'] : '1'; ?>" title="The duration of your animation. The value is in secode.">
							</td>
							<td class="right">
								<a href="http://easings.net/" target="_blank"><?php _e('Easing', 'StormSlider'); ?></a>
							</td>
							<td>
								<?php $value['before']['easing'] = isset($value['before']['easing']) ? $value['before']['easing'] : 'easeInOutBack'; ?>
								<select name="easing" title="The timing function of the animation. With this function you can manipulate the movement of the animated object. Please click on the link next to this select field to open easings.net for more information and real-time examples.">
									<option <?php echo ($value['before']['easing'] == 'linear') ? 'selected="selected"' : ''; ?>>linear</option>
									<option <?php echo ($value['before']['easing'] == 'swing') ? 'selected="selected"' : ''; ?>>swing</option>
									<option <?php echo ($value['before']['easing'] == 'easeInQuad') ? 'selected="selected"' : ''; ?>>easeInQuad</option>
									<option <?php echo ($value['before']['easing'] == 'easeOutQuad') ? 'selected="selected"' : ''; ?>>easeOutQuad</option>
									<option <?php echo ($value['before']['easing'] == 'easeInOutQuad') ? 'selected="selected"' : ''; ?>>easeInOutQuad</option>
									<option <?php echo ($value['before']['easing'] == 'easeInCubic') ? 'selected="selected"' : ''; ?>>easeInCubic</option>
									<option <?php echo ($value['before']['easing'] == 'easeOutCubic') ? 'selected="selected"' : ''; ?>>easeOutCubic</option>
									<option <?php echo ($value['before']['easing'] == 'easeInOutCubic') ? 'selected="selected"' : ''; ?>>easeInOutCubic</option>
									<option <?php echo ($value['before']['easing'] == 'easeInQuart') ? 'selected="selected"' : ''; ?>>easeInQuart</option>
									<option <?php echo ($value['before']['easing'] == 'easeOutQuart') ? 'selected="selected"' : ''; ?>>easeOutQuart</option>
									<option <?php echo ($value['before']['easing'] == 'easeInOutQuart') ? 'selected="selected"' : ''; ?>>easeInOutQuart</option>
									<option <?php echo ($value['before']['easing'] == 'lineaseInQuintear') ? 'selected="selected"' : ''; ?>>lineaseInQuintear</option>
									<option <?php echo ($value['before']['easing'] == 'easeOutQuint') ? 'selected="selected"' : ''; ?>>easeOutQuint</option>
									<option <?php echo ($value['before']['easing'] == 'easeInOutQuint') ? 'selected="selected"' : ''; ?>>easeInOutQuint</option>
									<option <?php echo ($value['before']['easing'] == 'easeInExpo') ? 'selected="selected"' : ''; ?>>easeInExpo</option>
									<option <?php echo ($value['before']['easing'] == 'easeOutExpo') ? 'selected="selected"' : ''; ?>>easeOutExpo</option>
									<option <?php echo ($value['before']['easing'] == 'easeInOutExpo') ? 'selected="selected"' : ''; ?>>easeInOutExpo</option>
									<option <?php echo ($value['before']['easing'] == 'easeInSine') ? 'selected="selected"' : ''; ?>>easeInSine</option>
									<option <?php echo ($value['before']['easing'] == 'easeOutSine') ? 'selected="selected"' : ''; ?>>easeOutSine</option>
									<option <?php echo ($value['before']['easing'] == 'easeInOutSine') ? 'selected="selected"' : ''; ?>>easeInOutSine</option>
									<option <?php echo ($value['before']['easing'] == 'easeInCirc') ? 'selected="selected"' : ''; ?>>easeInCirc</option>
									<option <?php echo ($value['before']['easing'] == 'easeOutCirc') ? 'selected="selected"' : ''; ?>>easeOutCirc</option>
									<option <?php echo ($value['before']['easing'] == 'easeInOutCirc') ? 'selected="selected"' : ''; ?>>easeInOutCirc</option>
									<option <?php echo ($value['before']['easing'] == 'easeInElastic') ? 'selected="selected"' : ''; ?>>easeInElastic</option>
									<option <?php echo ($value['before']['easing'] == 'easeOutElastic') ? 'selected="selected"' : ''; ?>>easeOutElastic</option>
									<option <?php echo ($value['before']['easing'] == 'easeInOutElastic') ? 'selected="selected"' : ''; ?>>easeInOutElastic</option>
									<option <?php echo ($value['before']['easing'] == 'easeInBack') ? 'selected="selected"' : ''; ?>>easeInBack</option>
									<option <?php echo ($value['before']['easing'] == 'easeOutBack') ? 'selected="selected"' : ''; ?>>easeOutBack</option>
									<option <?php echo ($value['before']['easing'] == 'easeInOutBack') ? 'selected="selected"' : ''; ?>>easeInOutBack</option>
									<option <?php echo ($value['before']['easing'] == 'easeInBounce') ? 'selected="selected"' : ''; ?>>easeInBounce</option>
									<option <?php echo ($value['before']['easing'] == 'easeOutBounce') ? 'selected="selected"' : ''; ?>>easeOutBounce</option>
									<option <?php echo ($value['before']['easing'] == 'easeInOutBounce') ? 'selected="selected"' : ''; ?>>easeInOutBounce</option>
								</select>
							</td>
						</tr>
						<tr class="transition">
							<td colspan="4">
								<ul class="transition-tag">
									<?php if(isset($value['before']['transition'])) : ?>
									<?php foreach($value['before']['transition'] as $name => $v) : ?>
									<li>
										<span><?php echo converProperty($name); ?></span><input value="<?php echo $v; ?>" name="<?php echo $name; ?>"><a href="#"><i class="dashicons dashicons-dismiss"></i>
										</a>
									</li>
									<?php endforeach; ?>
									<?php endif; ?>
								</ul>
								<p class="transition-property">
									<a href="#" class="btn"><?php _e('Add new'); ?></a>
									<select>
										<option value="scale3d,0.8"><?php _e('Scale3D', 'StormSlider'); ?></option>
										<option value="rotateX,90"><?php _e('RotateX', 'StormSlider'); ?></option>
										<option value="rotateY,90"><?php _e('RotateY', 'StormSlider'); ?></option>
										<option value="delay,200"><?php _e('Delay', 'StormSlider'); ?></option>
									</select>
								</p>
							</td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<td colspan="4"><?php _e('Animation', 'StormSlider'); ?></td>
						</tr>
					</thead>
					<tbody class="animation">
						<tr>
							<td class="right">
								<?php _e('Duration :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="duration" value="<?php echo $value['animation']['duration']; ?>" title="The duration of your animation. The value is in second.">
							</td>
							<td class="right">
								<a href="http://easings.net/" target="_blank"><?php _e('Easing', 'StormSlider'); ?></a>
							</td>
							<td>
								<select name="easing" title="The timing function of the animation. With this function you can manipulate the movement of the animated object. Please click on the link next to this select field to open easings.net for more information and real-time examples.">
									<option <?php echo ($value['animation']['easing'] == 'linear') ? 'selected="selected"' : ''; ?>>linear</option>
									<option <?php echo ($value['animation']['easing'] == 'swing') ? 'selected="selected"' : ''; ?>>swing</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInQuad') ? 'selected="selected"' : ''; ?>>easeInQuad</option>
									<option <?php echo ($value['animation']['easing'] == 'easeOutQuad') ? 'selected="selected"' : ''; ?>>easeOutQuad</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInOutQuad') ? 'selected="selected"' : ''; ?>>easeInOutQuad</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInCubic') ? 'selected="selected"' : ''; ?>>easeInCubic</option>
									<option <?php echo ($value['animation']['easing'] == 'easeOutCubic') ? 'selected="selected"' : ''; ?>>easeOutCubic</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInOutCubic') ? 'selected="selected"' : ''; ?>>easeInOutCubic</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInQuart') ? 'selected="selected"' : ''; ?>>easeInQuart</option>
									<option <?php echo ($value['animation']['easing'] == 'easeOutQuart') ? 'selected="selected"' : ''; ?>>easeOutQuart</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInOutQuart') ? 'selected="selected"' : ''; ?>>easeInOutQuart</option>
									<option <?php echo ($value['animation']['easing'] == 'lineaseInQuintear') ? 'selected="selected"' : ''; ?>>lineaseInQuintear</option>
									<option <?php echo ($value['animation']['easing'] == 'easeOutQuint') ? 'selected="selected"' : ''; ?>>easeOutQuint</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInOutQuint') ? 'selected="selected"' : ''; ?>>easeInOutQuint</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInExpo') ? 'selected="selected"' : ''; ?>>easeInExpo</option>
									<option <?php echo ($value['animation']['easing'] == 'easeOutExpo') ? 'selected="selected"' : ''; ?>>easeOutExpo</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInOutExpo') ? 'selected="selected"' : ''; ?>>easeInOutExpo</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInSine') ? 'selected="selected"' : ''; ?>>easeInSine</option>
									<option <?php echo ($value['animation']['easing'] == 'easeOutSine') ? 'selected="selected"' : ''; ?>>easeOutSine</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInOutSine') ? 'selected="selected"' : ''; ?>>easeInOutSine</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInCirc') ? 'selected="selected"' : ''; ?>>easeInCirc</option>
									<option <?php echo ($value['animation']['easing'] == 'easeOutCirc') ? 'selected="selected"' : ''; ?>>easeOutCirc</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInOutCirc') ? 'selected="selected"' : ''; ?>>easeInOutCirc</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInElastic') ? 'selected="selected"' : ''; ?>>easeInElastic</option>
									<option <?php echo ($value['animation']['easing'] == 'easeOutElastic') ? 'selected="selected"' : ''; ?>>easeOutElastic</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInOutElastic') ? 'selected="selected"' : ''; ?>>easeInOutElastic</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInBack') ? 'selected="selected"' : ''; ?>>easeInBack</option>
									<option <?php echo ($value['animation']['easing'] == 'easeOutBack') ? 'selected="selected"' : ''; ?>>easeOutBack</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInOutBack') ? 'selected="selected"' : ''; ?>>easeInOutBack</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInBounce') ? 'selected="selected"' : ''; ?>>easeInBounce</option>
									<option <?php echo ($value['animation']['easing'] == 'easeOutBounce') ? 'selected="selected"' : ''; ?>>easeOutBounce</option>
									<option <?php echo ($value['animation']['easing'] == 'easeInOutBounce') ? 'selected="selected"' : ''; ?>>easeInOutBounce</option>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td class="right"><?php _e('Direction :', 'StormSlider'); ?></td>
							<td>
								<select name="direction" title="The direction of rotation.">
									<option value="vertical" <?php echo $value['animation']['direction'] == 'vertical' ? 'selected="selected"' : ''; ?>><?php _e('Vertical', 'StormSlider'); ?></option>
									<option value="horizontal" <?php echo $value['animation']['direction'] == 'horizontal' ? 'selected="selected"' : ''; ?>><?php _e('Horizontal', 'StormSlider'); ?></option>
								</select>
							</td>
						</tr>
						<tr class="transition">
							<td colspan="4">
								<ul class="transition-tag">
									<?php if(isset($value['animation']['transition'])) : ?>
									<?php foreach($value['animation']['transition'] as $name => $v) : ?>
									<li>
										<span><?php echo converProperty($name); ?></span><input value="<?php echo $v; ?>" name="<?php echo $name; ?>"><a href="#"><i class="dashicons dashicons-dismiss"></i>
										</a>

									</li>
									<?php endforeach; ?>
									<?php endif; ?>
								</ul>
								<p class="transition-property">
									<a href="#" class="btn"><?php _e('Add new', 'StormSlider'); ?></a>
									<select>
										<option value="scale3d,0.8"><?php _e('Scale3D', 'StormSlider'); ?></option>
										<option value="rotateX,90"><?php _e('RotateX', 'StormSlider'); ?></option>
										<option value="rotateY,90"><?php _e('RotateY', 'StormSlider'); ?></option>
										<option value="delay,200"><?php _e('Delay', 'StormSlider'); ?></option>
									</select>
								</p>
							</td>
						</tr>
					</tbody>
					<?php $afterEnadbleDProp = isset($value['after']['enabled']) ? 'checked="checked"' : ''; ?>
					<?php $afterCollapsedClass = isset($value['after']['enabled']) ? '' : 'template-collapsed'; ?>
					<thead>
						<tr>
							<td colspan="4"><?php _e('After animation', 'StormSlider'); ?>
								<p class="transition-checkbox">
									<label>
										<input type="checkbox" class="build-collaspe-toggle" <?php echo $afterEnadbleDProp; ?>><?php _e('Enabled', 'StormSlider'); ?>
									</label>
								</p>
							</td>
						</tr>
					</thead>
					<tbody class="after <?php echo $afterCollapsedClass; ?>">
						<tr>
							<td class="right">
								<?php _e('Duration :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="duration" value="<?php echo isset($value['after']['duration']) ? $value['after']['duration'] : '1'; ?>" title="The duration of your animation. The value is in second.">
							</td>
							<td class="right">
								<a href="http://easings.net/" target="_blank"><?php _e('Easing', 'StormSlider'); ?></a>
							</td>
							<td>
								<?php $value['after']['easing'] = isset($value['after']['easing']) ? $value['after']['easing'] : 'easeInOutBack';?>
								<select name="easing" desc-html="The timing function of the animation. With this function you can manipulate the movement of the animated object. Please click on the link next to this select field to open easings.net for more information and real-time examples.">
									<option <?php echo ($value['after']['easing'] == 'linear') ? 'selected="selected"' : ''; ?>>linear</option>
									<option <?php echo ($value['after']['easing'] == 'swing') ? 'selected="selected"' : ''; ?>>swing</option>
									<option <?php echo ($value['after']['easing'] == 'easeInQuad') ? 'selected="selected"' : ''; ?>>easeInQuad</option>
									<option <?php echo ($value['after']['easing'] == 'easeOutQuad') ? 'selected="selected"' : ''; ?>>easeOutQuad</option>
									<option <?php echo ($value['after']['easing'] == 'easeInOutQuad') ? 'selected="selected"' : ''; ?>>easeInOutQuad</option>
									<option <?php echo ($value['after']['easing'] == 'easeInCubic') ? 'selected="selected"' : ''; ?>>easeInCubic</option>
									<option <?php echo ($value['after']['easing'] == 'easeOutCubic') ? 'selected="selected"' : ''; ?>>easeOutCubic</option>
									<option <?php echo ($value['after']['easing'] == 'easeInOutCubic') ? 'selected="selected"' : ''; ?>>easeInOutCubic</option>
									<option <?php echo ($value['after']['easing'] == 'easeInQuart') ? 'selected="selected"' : ''; ?>>easeInQuart</option>
									<option <?php echo ($value['after']['easing'] == 'easeOutQuart') ? 'selected="selected"' : ''; ?>>easeOutQuart</option>
									<option <?php echo ($value['after']['easing'] == 'easeInOutQuart') ? 'selected="selected"' : ''; ?>>easeInOutQuart</option>
									<option <?php echo ($value['after']['easing'] == 'lineaseInQuintear') ? 'selected="selected"' : ''; ?>>lineaseInQuintear</option>
									<option <?php echo ($value['after']['easing'] == 'easeOutQuint') ? 'selected="selected"' : ''; ?>>easeOutQuint</option>
									<option <?php echo ($value['after']['easing'] == 'easeInOutQuint') ? 'selected="selected"' : ''; ?>>easeInOutQuint</option>
									<option <?php echo ($value['after']['easing'] == 'easeInExpo') ? 'selected="selected"' : ''; ?>>easeInExpo</option>
									<option <?php echo ($value['after']['easing'] == 'easeOutExpo') ? 'selected="selected"' : ''; ?>>easeOutExpo</option>
									<option <?php echo ($value['after']['easing'] == 'easeInOutExpo') ? 'selected="selected"' : ''; ?>>easeInOutExpo</option>
									<option <?php echo ($value['after']['easing'] == 'easeInSine') ? 'selected="selected"' : ''; ?>>easeInSine</option>
									<option <?php echo ($value['after']['easing'] == 'easeOutSine') ? 'selected="selected"' : ''; ?>>easeOutSine</option>
									<option <?php echo ($value['after']['easing'] == 'easeInOutSine') ? 'selected="selected"' : ''; ?>>easeInOutSine</option>
									<option <?php echo ($value['after']['easing'] == 'easeInCirc') ? 'selected="selected"' : ''; ?>>easeInCirc</option>
									<option <?php echo ($value['after']['easing'] == 'easeOutCirc') ? 'selected="selected"' : ''; ?>>easeOutCirc</option>
									<option <?php echo ($value['after']['easing'] == 'easeInOutCirc') ? 'selected="selected"' : ''; ?>>easeInOutCirc</option>
									<option <?php echo ($value['after']['easing'] == 'easeInElastic') ? 'selected="selected"' : ''; ?>>easeInElastic</option>
									<option <?php echo ($value['after']['easing'] == 'easeOutElastic') ? 'selected="selected"' : ''; ?>>easeOutElastic</option>
									<option <?php echo ($value['after']['easing'] == 'easeInOutElastic') ? 'selected="selected"' : ''; ?>>easeInOutElastic</option>
									<option <?php echo ($value['after']['easing'] == 'easeInBack') ? 'selected="selected"' : ''; ?>>easeInBack</option>
									<option <?php echo ($value['after']['easing'] == 'easeOutBack') ? 'selected="selected"' : ''; ?>>easeOutBack</option>
									<option <?php echo ($value['after']['easing'] == 'easeInOutBack') ? 'selected="selected"' : ''; ?>>easeInOutBack</option>
									<option <?php echo ($value['after']['easing'] == 'easeInBounce') ? 'selected="selected"' : ''; ?>>easeInBounce</option>
									<option <?php echo ($value['after']['easing'] == 'easeOutBounce') ? 'selected="selected"' : ''; ?>>easeOutBounce</option>
									<option <?php echo ($value['after']['easing'] == 'easeInOutBounce') ? 'selected="selected"' : ''; ?>>easeInOutBounce</option>
								</select>
							</td>
						</tr>
						<tr class="transition">
							<td colspan="4">
								<ul class="transition-tag">
									<?php if(isset($value['after']['transition'])) : ?>
									<?php foreach($value['after']['transition'] as $name => $v) : ?>
									<li>
										<span><?php echo converProperty($name); ?></span><input value="<?php echo $v; ?>" name="<?php echo $name; ?>"><a href="#"><i class="dashicons dashicons-dismiss"></i>
										</a>
									</li>
									<?php endforeach; ?>
									<?php endif; ?>
								</ul>
								<p class="transition-property">
									<a href="#" class="btn"><?php _e('Add new', 'StormSlider'); ?></a>
									<select>
										<option value="scale3d,0.8"><?php _e('Scale3D', 'StormSlider'); ?></option>
										<option value="rotateX,90"><?php _e('RotateX', 'StormSlider'); ?></option>
										<option value="rotateY,90"><?php _e('RotateY', 'StormSlider'); ?></option>
										<option value="delay,200"><?php _e('Delay', 'StormSlider'); ?></option>
									</select>
								</p>
							</td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<td colspan="4"><?php _e('Transition option', 'StormSlider'); ?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="4">
								<a href="#" class="btn remove-option">Remove transition</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php endforeach; ?>
			<?php else : ?>
			<div class="storm-no-transition-notification">
				<h1><?php _e('You did\'t create any 3D transition yet'); ?></h1>
				<p>
					<?php _e('To create a new transition, click to the "Add new" button above.', 'StormSlider'); ?>
				</p>
			</div>
			<?php endif; ?>
		</div>
		<div class="transition-build-right transition-2d">
			<?php if(!empty($data['2d']) && is_array($data['2d'])) : ?>
			<?php foreach($data['2d'] as $key => $value) : ?>
				<?php $activeClass = ($key == '0') ? 'active' : ''; ?>
			<div class="transition-item <?php echo $activeClass; ?>">
				<table>
					<thead>
						<tr>
							<td colspan="4"><?php _e('Preview', 'StormSlider'); ?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="4">
								<div class="storm-builder-preview storm-transition-preview ">
									<img src="<?php echo plugins_url('/image/slide1-preview.png', dirname(__FILE__)); ?>" alt="preview image">
								</div>
								<div class="storm-builder-preview-button">
									<a  href="#" class="btn"><?php _e('Preview', 'StormSlider') ?></a>
								</div>
							</td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<td colspan="4"><?php _e('Basic properties', 'StormSlider'); ?></td>
						</tr>
					</thead>
					<tbody class="basic">
						<tr>
							<td class="right">
								<?php _e('Transition name :', 'StormSlider'); ?>
							</td>
							<td colspan="3">
								<input type="text" name="name" value="<?php echo $value['name']; ?>" title="The name of your custom transition.">
							</td>
						</tr>
						<tr>
							<?php $rows = is_array($value['rows']) ? implode(',', $value['rows']) : $value['rows']; ?>
							<?php $cols = is_array($value['cols']) ? implode(',', $value['cols']) : $value['cols']; ?>
							<td class="right">
								<?php _e('Rows :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="rows" value="<?php echo $rows; ?>" title="Number or min,max. If you specify a value greater than 1, that will be cut your slide into tiles. You can specify a value here how many rows of your transition should have.">
							</td>
							<td class="right">
								<?php _e('Cols ：', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="cols" value="<?php echo $cols; ?>" title="Number or min,max. If you specify a value greater than 1, that will be cut your slide into tiles. You can specify a value here how many cols of your transition should have.">
							</td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<td colspan="4"><?php _e('Tiles', 'StormSlider'); ?></td>
						</tr>
					</thead>
					<tbody class="tile">
						<tr>
							<td class="right">
								<?php _e('Delay :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="delay" value="<?php echo $value['tile']['delay']; ?>" title="You can apply a delay between the tiles. The value is in millisecs, so the value 1000 means 1 second.">
							</td>
							<td class="right">
								<?php _e('Sequence :', 'StormSlider'); ?>
							</td>
							<td>
								<select name="sequence" title="You can control the animation order ot the tiles here.">
									<option value="forward" <?php echo ($value['tile']['sequence'] == 'forward') ? 'selected="selected"' : ''; ?>><?php _e('Forward', 'StormSlider'); ?></option>
									<option value="reverse" <?php echo ($value['tile']['sequence'] == 'reverse') ? 'selected="selected"' : ''; ?>><?php _e('Reverse', 'StormSlider'); ?></option>
									<option value="col-forward" <?php echo ($value['tile']['sequence'] == 'col-forward') ? 'selected="selected"' : ''; ?>><?php _e('Col-forward', 'StormSlider'); ?></option>
									<option value="col-reverse" <?php echo ($value['tile']['sequence'] == 'col-reverse') ? 'selected="selected"' : ''; ?>><?php _e('Col-reverse', 'StormSlider'); ?></option>
									<option value="spiral" <?php echo ($value['tile']['sequence'] == 'spiral') ? 'selected="selected"' : ''; ?>><?php _e('Spiral', 'StormSlider'); ?></option>
									<option value="spiral-center" <?php echo ($value['tile']['sequence'] == 'spiral-center') ? 'selected="selected"' : ''; ?>><?php _e('Spiral-center', 'StormSlider'); ?></option>
									<option value="spread" <?php echo $value['tile']['sequence'] == 'spread' ? 'selected="selected"': ''; ?>><?php _e('Spread', 'StormSlider'); ?></option>
									<option value="spread-center" <?php echo $value['tile']['sequence'] == 'spread-center' ? 'selected="selected"': '';?>><?php _e('Spread-center', 'StormSlider'); ?></option>}
									option
									<option value="random" <?php echo ($value['tile']['sequence'] == 'random') ? 'selected="selected"' : ''; ?>><?php _e('Random', 'StormSlider'); ?></option>
								</select>
							</td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<td colspan="4"><?php _e('Transition', 'StormSlider'); ?></td>
						</tr>
					</thead>
					<tbody class="transition">
						<tr>
							<td class="right">
								<?php _e('Duration :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="duration" value="<?php echo $value['transition']['duration']; ?>" title="The duration of the animation. The value is in second.">
							</td>
							<td class="right">
								<a href="http://easings.net/" target="_blank"><?php _e('Easing', 'StormSlider'); ?></a>
							</td>
							<td>
								<select name="easing" desc-html="The timing function of the animation. With this function you can manipulate the movement of the animated object. Please click on the link next to this select field to open easings.net for more information and real-time examples.">
									<option <?php echo ($value['transition']['easing'] == 'linear') ? 'selected="selected"' : ''; ?>>linear</option>
									<option <?php echo ($value['transition']['easing'] == 'swing') ? 'selected="selected"' : ''; ?>>swing</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInQuad') ? 'selected="selected"' : ''; ?>>easeInQuad</option>
									<option <?php echo ($value['transition']['easing'] == 'easeOutQuad') ? 'selected="selected"' : ''; ?>>easeOutQuad</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInOutQuad') ? 'selected="selected"' : ''; ?>>easeInOutQuad</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInCubic') ? 'selected="selected"' : ''; ?>>easeInCubic</option>
									<option <?php echo ($value['transition']['easing'] == 'easeOutCubic') ? 'selected="selected"' : ''; ?>>easeOutCubic</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInOutCubic') ? 'selected="selected"' : ''; ?>>easeInOutCubic</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInQuart') ? 'selected="selected"' : ''; ?>>easeInQuart</option>
									<option <?php echo ($value['transition']['easing'] == 'easeOutQuart') ? 'selected="selected"' : ''; ?>>easeOutQuart</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInOutQuart') ? 'selected="selected"' : ''; ?>>easeInOutQuart</option>
									<option <?php echo ($value['transition']['easing'] == 'lineaseInQuintear') ? 'selected="selected"' : ''; ?>>lineaseInQuintear</option>
									<option <?php echo ($value['transition']['easing'] == 'easeOutQuint') ? 'selected="selected"' : ''; ?>>easeOutQuint</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInOutQuint') ? 'selected="selected"' : ''; ?>>easeInOutQuint</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInExpo') ? 'selected="selected"' : ''; ?>>easeInExpo</option>
									<option <?php echo ($value['transition']['easing'] == 'easeOutExpo') ? 'selected="selected"' : ''; ?>>easeOutExpo</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInOutExpo') ? 'selected="selected"' : ''; ?>>easeInOutExpo</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInSine') ? 'selected="selected"' : ''; ?>>easeInSine</option>
									<option <?php echo ($value['transition']['easing'] == 'easeOutSine') ? 'selected="selected"' : ''; ?>>easeOutSine</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInOutSine') ? 'selected="selected"' : ''; ?>>easeInOutSine</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInCirc') ? 'selected="selected"' : ''; ?>>easeInCirc</option>
									<option <?php echo ($value['transition']['easing'] == 'easeOutCirc') ? 'selected="selected"' : ''; ?>>easeOutCirc</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInOutCirc') ? 'selected="selected"' : ''; ?>>easeInOutCirc</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInElastic') ? 'selected="selected"' : ''; ?>>easeInElastic</option>
									<option <?php echo ($value['transition']['easing'] == 'easeOutElastic') ? 'selected="selected"' : ''; ?>>easeOutElastic</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInOutElastic') ? 'selected="selected"' : ''; ?>>easeInOutElastic</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInBack') ? 'selected="selected"' : ''; ?>>easeInBack</option>
									<option <?php echo ($value['transition']['easing'] == 'easeOutBack') ? 'selected="selected"' : ''; ?>>easeOutBack</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInOutBack') ? 'selected="selected"' : ''; ?>>easeInOutBack</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInBounce') ? 'selected="selected"' : ''; ?>>easeInBounce</option>
									<option <?php echo ($value['transition']['easing'] == 'easeOutBounce') ? 'selected="selected"' : ''; ?>>easeOutBounce</option>
									<option <?php echo ($value['transition']['easing'] == 'easeInOutBounce') ? 'selected="selected"' : ''; ?>>easeInOutBounce</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="right">
								<?php _e('Type :', 'StormSlider'); ?>
							</td>
							<td>
								<select name="type" title="The type of the animation, either slide, fade or both(mixed).">
									<option value="slide" <?php echo ($value['transition']['type'] == 'slide') ? 'selected="selected"' : ''; ?>><?php _e('Slide', 'StormSlider'); ?></option>
									<option value="fade" <?php echo ($value['transition']['type'] == 'fade') ? 'selected="selected"' : ''; ?>><?php _e('Fade', 'StormSlider'); ?></option>
									<option value="mixed" <?php echo ($value['transition']['type'] == 'mixed') ? 'selected="selected"' : ''; ?>><?php _e('Mixed', 'StormSlider'); ?></option>
								</select>
							</td>
							<td class="right">
								<?php _e('Direction :', 'StormSlider'); ?>
							</td>
							<td>
								<select name="direction" title="The direction of slide or mixed animation if you've chosen this type in previous settings.">
									<option value="top" <?php echo ($value['transition']['direction'] == 'top') ? 'selected="selected"' : ''; ?>><?php _e('Top', 'StormSlider'); ?></option>
									<option value="right" <?php echo ($value['transition']['direction'] == 'right') ? 'selected="selected"' : ''; ?>><?php _e('Right', 'StormSlider'); ?></option>
									<option value="bottom" <?php echo ($value['transition']['direction'] == 'bottom') ? 'selected="selected"' : ''; ?>><?php _e('Bottom', 'StormSlider'); ?></option>
									<option value="left" <?php echo ($value['transition']['direction'] == 'left') ? 'selected="selected"' : ''; ?>><?php _e('Left', 'StormSlider'); ?></option>
									<option value="random" <?php echo ($value['transition']['direction'] == 'random') ? 'selected="selected"' : ''; ?>><?php _e('Random', 'StormSlider'); ?></option>
									<option value="topleft" <?php echo ($value['transition']['direction'] == 'topleft') ? 'selected="selected"' : ''; ?>><?php _e('Top left', 'StormSlider'); ?></option>
									<option value="topright" <?php echo ($value['transition']['direction'] == 'topright') ? 'selected="selected"' : ''; ?>><?php _e('Top right', 'StormSlider'); ?></option>
									<option value="bottomleft" <?php echo ($value['transition']['direction'] == 'bottomleft') ? 'selected="selected"' : ''; ?>><?php _e('Bottom left', 'StormSlider'); ?></option>
									<option value="bottomright" <?php echo ($value['transition']['direction'] == 'bottomright') ? 'selected="selected"' : ''; ?>><?php _e('Bottom right', 'StormSlider'); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="right">
								<?php _e('RotateX :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="rotateX" value="<?php echo !empty($value['transition']['rotateX']) ? $value['transition']['rotateX'] : '0';?>" title="The initial rotation of the individual tiles which will be animated to the default(0deg) value around the X axis. You can use negative values.">
							</td>
							<td class="right">
								<?php _e('RotateY :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="rotateY" value="<?php echo !empty($value['transition']['rotateY']) ? $value['transition']['rotateY'] : '0';?>" title="The initial rotation of the individual tiles which will be animated to the default(0deg) value around the Y axis. You can use negative values.">
							</td>
						</tr>
						<tr>
							<td class="right">
								<?php _e('RotateZ :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="rotateZ" value="<?php echo !empty($value['transition']['rotateZ']) ? $value['transition']['rotateZ'] : '0';?>" title="The initial rotation of the individual tiles which will be animated to the default(0deg) value around the Z axis. You can use negative values.">
							</td>
							<td class="right">
								<?php _e('Scale :', 'StormSlider'); ?>
							</td>
							<td>
								<input type="text" name="scale" value="<?php echo !empty($value['transition']['scale']) ? $value['transition']['scale'] : '1';?>" title="The initial scale of the individual tiles which will be animated to the default(1.0) value.">
							</td>
						</tr>
					</tbody>
					<thead>
						<tr>
							<td colspan="4"><?php _e('Transition option', 'StormSlider'); ?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="4">
								<a href="#" class="btn remove-option">Remove transition</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php endforeach; ?>
			<?php else : ?>
				<div class="storm-no-transition-notification">
					<h1>
						<?php _e('You did\'t create any 3D transition yet', 'StormSlider'); ?>
					</h1>
					<p>
					<?php _e('To create a new transition, click to the "Add new" button above.', 'StormSlider'); ?>
					</p>
				</div>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="transition-save">
		<button class="btn"><?php _e('Save template', 'StormSlider'); ?></button>
	</div>
	<script>
		var imgPath = "<?php echo plugins_url('image/', dirname(__FILE__)); ?>";
	</script>
</form>