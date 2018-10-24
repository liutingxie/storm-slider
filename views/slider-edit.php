<?php
/**
 * @Author: liutingxie
 * @Date:   2016-08-07 21:51:21
 * @Last Modified by:   liutingxie
 * @Last Modified time: 2018-01-06 18:05:49
 */
require_once(STORM_DIR. '/config/default.php');

$id = (int)$_GET['id'];

$slider = Storm_Sliders::_getByid($id);

$stormOption = Storm_Sliders::getOption('preset_effect');

$data = $slider['data'];

// if(!isset($slider['data']['layers'][0]['param'])) {
//   $slider['data']['layers'][0]['param'] = array();
// }

// if(!isset($slider['data']['layers'][0]['sublayer'])) {
//   $slider['data']['layers'][0]['sublayer'] = array();
// }

$newDefault = $Storm_Default;

if(!empty($newDefault) && is_array($newDefault)) {
  $default = $newDefault;
}

if(!empty($stormOption) && is_array($stormOption)) {
  $defaultOption = $stormOption['option_value'];
}

$current_param = inputValue($default, $data);
?>
<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
<script type="text/javascript">
  window.stormOptionData = <?php echo json_encode($defaultOption); ?>;
  window.stormDefaultData = <?php echo json_encode($default); ?>;
</script>

<script type="text/javascript">
  window.stormSliderData = <?php echo json_encode($slider['data']); ?>;
</script>

<script type="text/template" id="storm-transition-window-template">
  <div class="storm-transition-window">
    <div class="transition-type">
      <h2><?php _e('Select slider transition', 'StormLayer'); ?></h2>
      <div class="transition-type-select">
        <?php _e('Show :', 'StormLayer'); ?>
        <span class="active"><a href="#"><?php _e('3D', 'StormSlider'); ?></a></span>
        <span><a href="#"><?php _e('2D', 'StormSlider'); ?></a></span>
        <span><a href="#"><?php _e('Custom', 'StormLayer'); ?></a></span>
      </div>
      <div class="transition-button">
        <a href="#" class="transition-select-all"><?php _e('Select All', 'StormLayer'); ?></a>
        <a href="#">
          <i class="dashicons dashicons-no-alt"></i>
        </a>
      </div>
    </div>
    <div class="transition-container">
      <table>
      </table>
    </div>
  </div>
</script>

<script type="text/template" id="storm-slide-template">
  <li class="image-thumb">
    <div class="image-thumb-container-content">
      <div id="" class="image-thumb-img" style=""></div>
      <div class="image-thumb-img-bg">
        <span class="remove-slide" title="Remove slide"></span>
        <span class="duplicate-slide" title="Duplicate slide"></span>
        <span class="edit-slide" title="Edit slide"></span></div>
      <div class="image-order"></div>
    </div>
  </li>
</script>

<script type="text/template" id="storm-link-template">
   <div class="link-url-content">
      <input type="text" name="linkUrl" value="">
      <select name="linkTarget">
        <option value="_self"></option>
      </select>
  </div>
  <div class="link-attr-content">
      <input type="text" name="linkId" value="">
      <input type="text" name="linkClass" value="">
      <input type="text" name="linkTitle" value="">
      <input type="text" name="linkRel" value="">
  </div>
</script>

<script type="text/template" id="storm-misc-template">
  <div class="misc-attr">
    <input type="text" name="miscClass" value="">
    <input type="text" name="miscId" value="">
  </div>
  <div class="misc-background">
    <input type="text" name="miscBackgroundColor" class="storm-color-picker" value="">
    <input type="text" name="miscBackgroundAlt" value="">
    <input type="text" name="miscBackgroundTitle" value="">
  </div>
</script>

<script type="text/template" id="storm-slide-trans-template">
  <div class="trans-container">
    <div class="trans-header">
        <div class="presetTitleBar">
          <span>Transition Preset</span>
        </div>
        <div class="transTitleBar">
          <span class="transTitle">Transition Editor</span>
          <b class="dashicons dashicons-no"></b>
        </div>
        <div class="clear"></div>
    </div>
    <div class="trans-cont">
      <div class="trans-preset-list"></div>
      <div class="trans-content">
        <div class="trans-preview">
          <div class="trans-preview-cont">
            <div class="trans-preview-sample trans-preview-content">

            </div>
            <div class="trans-effect-control">
              <span class="dashicons dashicons-controls-play play"></span>
            </div>
          </div>
        </div>
        <div class="trans-tabs">
          <div class="trans-metabox-tabs">
            <ul class="metabox-tabs">
              <li><a href="#trans-general">General and Offset</a></li>
              <li><a href="#trans-rotate">Rotate</a></li>
              <li><a href="#trans-scale-skew">Scale and Skew</a></li>
              <li><a href="#trans-origin">Transition Origin</a></li>
            </ul>
          </div>
          <ul class="tabs-content">
            <li class="trans-general">
              <div>
                <label>Fade :</label>
                <input type="checkbox" name="fade" style="display: none;">
                <label>Ease function :</label>
                <select name="easing">
                  <option selected="selected" value="linear">linear</option>
                  <option value="swing">swing</option>
                  <option value="easeInQuad">easeInQuad</option>
                  <option value="easeOutQuad">easeOutQuad</option>
                  <option value="easeInOutQuad">easeInOutQuad</option>
                  <option value="easeInCubic">easeInCubic</option>
                  <option value="easeOutCubic">easeOutCubic</option>
                  <option value="easeInOutCubic">easeInOutCubic</option>
                  <option value="easeInQuart">easeInQuart</option>
                  <option value="easeOutQuart">easeOutQuart</option>
                  <option value="easeInOutQuart">easeInOutQuart</option>
                  <option value="easeInQuint">easeInQuint</option>
                  <option value="easeOutQuint">easeOutQuint</option>
                  <option value="easeInOutQuint">easeInOutQuint</option>
                  <option value="easeInExpo">easeInExpo</option>
                  <option value="easeOutExpo">easeOutExpo</option>
                  <option value="easeInOutExpo">easeInOutExpo</option>
                  <option value="easeInSine">easeInSine</option>
                  <option value="easeOutSine">easeOutSine</option>
                  <option value="easeInOutSine">easeInOutSine</option>
                  <option value="easeInCirc">easeInCirc</option>
                  <option value="easeOutCirc">easeOutCirc</option>
                  <option value="easeInOutCirc">easeInOutCirc</option>
                  <option value="easeInElastic">easeInElastic</option>
                  <option value="easeOutElastic">easeOutElastic</option>
                  <option value="easeInOutElastic">easeInOutElastic</option>
                  <option value="easeInBack">easeInBack</option>
                  <option value="easeOutBack">easeOutBack</option>
                  <option value="easeInOutBack">easeInOutBack</option>
                  <option value="easeInBounce">easeInBounce</option>
                  <option value="easeOutBounce">easeOutBounce</option>
                  <option value="easeInOutBounce">easeInOutBounce</option>
                </select>
              </div>
              <div>
                <label for="duration">Duration :</label>
                <input name="duration" class="insert-spinner" type="text" value="1" valuemin="0"> s
                <label for="delay">Delay :</label>
                <input class="insert-spinner" type="text" name="delay" value="0" valuemin="0"> s
                <label for="offsetX">Offset X :</label>
                <input type="text" name="offsetX" class="insert-spinner" value="0">
                <label for="offsetY">Offset Y :</label>
                <input type="text" name="offsetY" class="insert-spinner" value="0">
              </div>
            </li>
            <li class="trans-rotate">
              <div>
                <label for="rotate2D">2D Rotate :</label>
                <input type="checkbox" name="rotate2D" style="display: none;">

              </div>
              <div>
                <label for="rotateX">Rotate X :</label>
                <input type="text" name="rotateX" class="insert-spinner" value="0">
                <label for="rotateY">Rotate Y :</label>
                <input type="text" name="rotateY" class="insert-spinner" value="0">
                <label for="rotateZ">Rotate Z :</label>
                <input type="text" name="rotateZ" class="insert-spinner" value="0">
              </div>
            </li>
            <li class="trans-scale-skew">
              <div>
                <label for="scaleX">Scale X :</label>
                <input type="text" name="scaleX" class="insert-spinner" value="1" valuemin="0">
                <label for="scaleY">Scale Y :</label>
                <input type="text" name="scaleY" class="insert-spinner" value="1" valuemin="0">
              </div>
              <div>
                <label for="skewX">Skew X :</label>
                <input type="text" name="skewX" class="insert-spinner" value="0">
                <label for="skewY">Skew Y :</label>
                <input type="text" name="skewY" class="insert-spinner" value="0">
              </div>
            </li>
            <li class="trans-origin">
              <div>
                <label for="originX">Origin X:</label>
                <input type="text" name="originX" class="insert-spinner" value="50"> %
                <label for="originY">Origin Y:</label>
                <input type="text" name="originY" class="insert-spinner" value="50"> %
                <label for="originZ">Origin Z:</label>
                <input type="text" name="originZ" class="insert-spinner" value="0">
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="clear"></div>
    <div class="trans-button">
      <a href="#" class="trans-apply">
        <span class="btn"><?php _e('Apply Transition', 'StormSlider'); ?></span>
      </a>
      <a href="#" class="trans-preset">
        <span class="btn"><?php _e('Preset As Transition', 'StormSlider'); ?></span>
      </a>
    </div>
  </div>
  <div class="storm-overlay"></div>
</script>

<script type="text/template" id="storm-layerlist-template">
  <li class="layer-ul-li">
    <div class="image-layer-cont">
      <div class="image-layer-controls-list">
        <div class="show-lock">
          <span title="Show/Hide" class="layer-show black-graypoint"></span>
          <span title="Lock/Unlock" class="layer-lock black-graypoint"></span>
        </div>
        <div class="image-layer-type">
          <img class="image-layer">
          <span class="layerName" name="layerName">Layer</span>
        </div>
        <div class="image-layer-button">
          <span title="Duplicate" class="black-duplicate"></span>
          <span title="Remove" class="black-remove"></span>
        </div>
      </div>
    </div>
  </li>
</script>

<script type="text/template" id="storm-layer-timeline-list-template">
  <li>
    <div class="timeline-view" style="width: 2560px;">
      <div class="layer-delayShow-timeline" style="width: 0px;"></div>
      <div class="layer-durationShow-timeline" style="width: 80px;"></div>
      <div class="layer-delayHide-timeline" style="width: 0px;"></div>
      <div class="layer-durationHide-timeline" style="width: 0px;"></div>
    </div>
  </li>
</script>

<script type="text/template" id="storm-layer-tinymce-template">
  <div class="layer-tinymce">
    <h4>Insert your text content here, for a better SEO result you can use heading tags for important contents.</h4>
    <div class="layer-tinymce-container"></div>
  </div>
</script>

<script type="text/template" id="storm-layer-image-template">
  <div class="layer-image">
    <h4>Layer image</h4>
    <div class="add-slide storm-layer-newuploader" title="Add Image/Edit Image">
      <span class="add-slide-icon"></span>
    </div>
    <div>
      <h4>Link layer</h4>
      <div>
        <label for="slideSubLayerImageUrl">Url :</label>
        <input name="slideSubLayerImageUrl" type="text" />
        <select name="slideSubLayerImageLinkTarget">
          <option value="_self">Open In Same Page</option>
          <option value="_blank">Open In New Page</option>
          <option value="_parent">Open In Parent Page</option>
          <option value="_top">Open In Main Page</option>
        </select>
        </div>
    </div>
    <input type="hidden" name="slideSubLayerImage" />
  </div>
</script>

<script type="text/template" id="storm-layer-button-template">
  <div class="layer-button">
    <h4>Layer button</h4>
    <div>
      <label for="slideSubLayerText">Label :</label>
      <input name="slideSubLayerText" type="text" />
    </div>
    <div>
      <h4>Layer button link</h4>
      <div>
        <label for="slideSubLayerButtonUrl">Url :</label>
        <input name="slideSubLayerButtonUrl" type="text" />
        <select name="slideSubLayerButtonLinkTarget">
          <option value="_self">Open In Same Page</option>
          <option value="_blank">Open In New Page</option>
          <option value="_parent">Open In Parent Page</option>
          <option value="_top">Open In Main Page</option>
        </select>
      </div>
    </div>
  </div>

</script>

<script type="text/template" id="storm-layer-video-template">
  <div class="layer-video">
    <div>
      <h4>Youtube or Vimeo video embed source</h4>
      <label for="slideSubLayerText">Video Url :</label>
      <input name="slideSubLayerText" type="text" />
    </div>
  </div>
</script>

<div class="edit-slider">
  <form action="admin.php?page=stormslider" method="post" name="adminform" id="adminform">
    <div class="storm-slider">
      <div id="storm-slider-body"  class="storm-slider-body active">
        <div class="tab_container">
          <div class="tab_buttons_slider" onclick="StormLayer.storm_change_tab(this, 'storm-slider-body');">
            <a href="#" class="tab_buttons btn">
              <?php _e('Sliders', 'StormSlider'); ?>
            </a>
          </div>
          <div class="tab_buttons_settings" onclick="StormLayer.storm_change_tab(this, 'storm-slider-settings');">
            <a href="#" class="tab_buttons btn">
              <?php _e('Settings', 'StormSlider'); ?>
            </a>
          </div>
      </div>
        <div class="slider-body-centent">
        <!-- Content -->
            <div class="slider-body-heading">
                <div class="slider_title">
                  <label for="name"><?php echo $current_param['slider']['name']['name']; ?></label>
                  <input type="text" name="name" id="name" class="name" value="<?php echo $current_param['slider']['name']['value']; ?>" title="<?php echo $current_param['slider']['name']['desc']; ?>">
                </div>
            </div>
            <div class="slider-body">

                <div class="image-thumb-container">
                  <ul class="image-thumb-list">
                    <?php if (isset($data['layers'])): ?>
                      <?php foreach ($data['layers'] as $key => $slide) : ?>
                        <li class="image-thumb">
                          <div class="image-thumb-container-content <?php if($key == 0 ) { echo 'active'; } ?>">
                            <div id="image-thumb-img-<?php echo $key + 1; ?>" class="image-thumb-img" style="background-image: url('<?php echo $slide['param']['backgroundThumb']; ?>');"></div>
                            <div class="image-thumb-img-bg">
                              <span class="edit-slide" title="Edit slide"></span>
                              <span class="duplicate-slide" title="Duplicate slide"></span>
                              <span class="remove-slide" title="Remove slide"></span>
                            </div>
                            <div class="image-order">#<?php echo $key+1; ?></div>
                          </div>
                        </li>
                      <?php endforeach; ?>
                    <?php endif ?>

                    <li class="image-thumb-add">
                      <div class="add-slide storm-newuploader">
                        <span class="add-slide-icon"></span>
                        <span class="add-slide-label">Add Slide</span>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="slide-settings">
                  <div class="slide-box">
                    <ul class="tabs">
                      <li class="active"><a href="#slide-style"><?php _e('Slide Style', 'StormSlider'); ?></a></li>
                      <li><a href="#slide-link"><?php _e('Video and Link', 'StormSlider'); ?></a></li>
                      <li><a href="#slide-misc"><?php _e('Slide Misc', 'StormSlider'); ?></a></li>
                    </ul>
                  </div>
                  <ul class="slide-box-content">
                    <li class="slide-style active">
                      <h4>Slide Style</h4>
                      <table>
                        <tbody>
                          <tr>
                            <td>
                              <label><?php _e('Fill Mode', 'StormSlider'); ?> :</label>
                              <div class="mode-select select-option" title="<?php echo $default['layers']['param']['fillMode']['desc']; ?>">
                                <a href="#" class="current-select">
                                  <img class="select-mode-image" src="<?php echo plugins_url('/image/'.$current_param['layers']['param']['fillMode']['value'].'.png', dirname(__FILE__)); ?>" alt="<?php echo $current_param['layers']['param']['fillMode']['value']; ?>">
                                  <span class="select-mode-title"><?php echo $default['layers']['param']['fillMode']['title'][$current_param['layers']['param']['fillMode']['value']]; ?></span>
                                </a>
                                <span class="select-pointer select-pointer-down"></span>
                                <input type="text" name="fillMode"  style="display: none;">
                                <ul class="fill-options">
                                  <li>
                                    <a href="#" class="option-mode">
                                      <img class="option-mode-image" src="<?php echo plugins_url('/image/center.png', dirname(__FILE__)); ?>" alt="center">
                                      <span><?php _e('Auto', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="option-mode">
                                      <img class="option-mode-image" src="<?php echo plugins_url('/image/stretch.png', dirname(__FILE__)); ?>" alt="stretch">
                                      <span><?php _e('Stretch', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="option-mode">
                                      <img class="option-mode-image" src="<?php echo plugins_url('/image/cover.png', dirname(__FILE__)); ?>" alt="cover">
                                      <span><?php _e('Cover', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="option-mode">
                                      <img class="option-mode-image" src="<?php echo plugins_url('/image/contain.png', dirname(__FILE__)); ?>" alt="contain">
                                      <span><?php _e('Contain', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </td>
                            <td>
                              <label><?php _e('Position', 'StormSlider'); ?> :</label>
                              <div class="position-select select-option" title="<?php echo $default['layers']['param']['positionMode']['desc']; ?>">
                                <a href="#" class="current-position-select">
                                  <img class="current-position-image" src="<?php echo $default['layers']['param']['positionMode']['src'][$current_param['layers']['param']['positionMode']['value']]; ?>" alt="<?php echo $current_param['layers']['param']['positionMode']['value']; ?>">
                                  <span class="current-position-title"><?php echo $default['layers']['param']['positionMode']['title'][$current_param['layers']['param']['positionMode']['value']]; ?></span>
                                </a>
                                <span class="select-pointer select-pointer-down"></span>
                                <input type="text" name="positionMode"  style="display: none;">
                                <ul class="position-options">
                                  <li>
                                    <a href="#" class="position-select-mode">
                                      <img class="position-select-mode-image" src="<?php echo plugins_url('/image/left-top.png', dirname(__FILE__)); ?>" alt="<?php _e('left-top', 'StormSlider'); ?>">
                                      <span><?php _e('Left top', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="position-select-mode">
                                      <img class="position-select-mode-image" src="<?php echo plugins_url('/image/left-center.png', dirname(__FILE__)); ?>" alt="<?php _e('left-center', 'StormSlider'); ?>">
                                      <span><?php _e('Left center', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="position-select-mode">
                                      <img class="position-select-mode-image" src="<?php echo plugins_url('/image/left-bottom.png', dirname(__FILE__)); ?>" alt="<?php _e('left-bottom', 'StormSlider'); ?>">
                                      <span><?php _e('Left bottom', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="position-select-mode">
                                      <img class="position-select-mode-image" src="<?php echo plugins_url('/image/center-top.png', dirname(__FILE__)); ?>" alt="<?php _e('center-top', 'StormSlider'); ?>">
                                      <span><?php _e('Center top', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="position-select-mode">
                                      <img class="position-select-mode-image" src="<?php echo plugins_url('/image/center-center.png', dirname(__FILE__)); ?>" alt="<?php _e('center-center', 'StormSlider'); ?>">
                                      <span><?php _e('Center center', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="position-select-mode">
                                      <img class="position-select-mode-image" src="<?php echo plugins_url('/image/center-bottom.png', dirname(__FILE__)); ?>" alt="<?php _e('center-bottom', 'StormSlider'); ?>">
                                      <span><?php _e('Center bottom', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="position-select-mode">
                                      <img class="position-select-mode-image" src="<?php echo plugins_url('/image/right-top.png', dirname(__FILE__)); ?>" alt="<?php _e('right-top', 'StormSlider'); ?>">
                                      <span><?php _e('Right top', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="position-select-mode">
                                      <img class="position-select-mode-image" src="<?php echo plugins_url('/image/right-center.png', dirname(__FILE__)); ?>" alt="<?php _e('right-center', 'StormSlider'); ?>">
                                      <span><?php _e('Right center', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" class="position-select-mode">
                                      <img class="position-select-mode-image" src="<?php echo plugins_url('/image/right-bottom.png', dirname(__FILE__)); ?>" alt="<?php _e('right-bottom', 'StormSlider'); ?>">
                                      <span><?php _e('Right bottom', 'StormSlider'); ?></span>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </td>
                            <td>
                              <label for=slideDelay""><?php echo $default['layers']['param']['slideDelay']['name']; ?></label>
                              <input name="slideDelay" class="insert-spinner" type="text" title="<?php echo $current_param['layers']['param']['slideDelay']['desc'] ?>" value="<?php echo $current_param['layers']['param']['slideDelay']['value']; ?>">
                            </td>
                            <td>
                              <div class="slideTrans-container">
                                <label>Select Slide Transtion Effects :</label>
                                <a href="#" class="btn slide-transition">
                                  <?php _e('Select transition', 'StormSlider'); ?>
                                </a>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                        <h4><?php _e('Ken burns effect', 'StormSlider'); ?></h4>
                        <div class="ken-burns-disable-container">
                            <span><?php _e('Disable', 'StormSlider'); ?></span>
                            <input type="checkbox" name="checkKenBurns" <?php if($current_param['layers']['param']['checkKenBurns']['value'] == 1) {echo "checked"; }?>>

                        </div>
                        <div class="storm-ken-burns-container">
                                <label for="kenBurnsZoom"><?php echo $current_param['layers']['param']['kenBurnsZoom']['name']; ?></label>
                                <select name="kenBurnsZoom" value="zoom-in">
                                    <option value="zoom-in" <?php if($current_param['layers']['param']['kenBurnsZoom']['value'] == "zoom-in") { echo 'selected'; } ?>>
                                        <?php _e('Zoom In', 'StormSlider'); ?>
                                    </option>
                                    <option value="zoom-out" <?php if($current_param['layers']['param']['kenBurnsZoom']['value'] == "zoom-out") { echo 'selected'; } ?>>
                                        <?php _e('Zoom Out', 'StormSlider'); ?>
                                    </option>
                                </select>

                                <label for="kenBurnsDirection"><?php echo $current_param['layers']['param']['kenBurnsDirection']['name']; ?></label>
                                <select name="kenBurnsDirection" value="zoom-in">
                                    <option value="left-top" <?php if($current_param['layers']['param']['kenBurnsDirection']['value'] == "left-top") { echo 'selected'; } ?>>
                                        <?php _e('Left top', 'StormSlider'); ?>
                                    </option>
                                    <option value="left-bottom" <?php if($current_param['layers']['param']['kenBurnsDirection']['value'] == "left-bottom") { echo 'selected'; } ?>>
                                        <?php _e('Left Bottom', 'StormSlider'); ?>
                                    </option>
                                    <option value="right-top" <?php if($current_param['layers']['param']['kenBurnsDirection']['value'] == "right-top") { echo 'selected'; } ?>>
                                        <?php _e('Right top', 'StormSlider'); ?>
                                    </option>
                                    <option value="right-bottom" <?php if($current_param['layers']['param']['kenBurnsDirection']['value'] == "right-bottom") { echo 'selected'; } ?>>
                                        <?php _e('Right bottom', 'StormSlider'); ?>
                                    </option>
                                </select>

                                <label for="kenBurnsScale"><?php echo $current_param['layers']['param']['kenBurnsScale']['name']; ?></label>
                                <input type="text" name="kenBurnsScale" value="<?php echo $current_param['layers']['param']['kenBurnsScale']['value']; ?>" title="<?php echo $current_param['layers']['param']['kenBurnsScale']['desc']; ?>">
                            </div>
                    </li>
                    <li class="slide-link">
                      <div class="slide-link-container">
                          <h4>Link to this slide</h4>
                          <div class="link-url-content">
                              <label for="linkUrl"><?php echo $current_param['layers']['param']['slideLink']['linkUrl']['name']; ?></label>
                              <input type="text" name="linkUrl" value="<?php echo $current_param['layers']['param']['slideLink']['linkUrl']['value']; ?>" title="<?php echo $current_param['layers']['param']['slideLink']['linkUrl']['desc']; ?>">

                              <select value="_self" name="linkTarget">
                                <option <?php if($current_param['layers']['param']['slideLink']['linkTarget']['value'] == "_self") { echo 'selected="selected"'; } ?> value="_self"><?php _e('Open In Same Page', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['param']['slideLink']['linkTarget']['value'] == "_blank") { echo 'selected="selected"'; } ?> value="_blank"><?php _e('Open In New Page', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['param']['slideLink']['linkTarget']['value'] == "_parent") { echo 'selected="selected"'; } ?> value="_parent"><?php _e('Open In Parent Page', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['param']['slideLink']['linkTarget']['value'] == "_top") { echo 'selected="selected"'; } ?> value="_top"><?php _e('Open In Main Page', 'StormSlider'); ?></option>
                              </select>
                          </div>
                          <div class="link-attr-content">
                              <label for="linkId"><?php echo $current_param['layers']['param']['slideLink']['linkId']['name']; ?></label>
                              <input type="text" name="linkId" value="<?php echo $current_param['layers']['param']['slideLink']['linkId']['value']; ?>" title="<?php echo $current_param['layers']['param']['slideLink']['linkId']['desc']; ?>">
                              <label for="linkClass"><?php echo $current_param['layers']['param']['slideLink']['linkClass']['name']; ?></label>
                              <input type="text" name="linkClass" value="<?php echo $current_param['layers']['param']['slideLink']['linkClass']['value']; ?>" title="<?php echo $current_param['layers']['param']['slideLink']['linkClass']['desc']; ?>">
                              <label for="linkTitle"><?php echo $current_param['layers']['param']['slideLink']['linkTitle']['name']; ?></label>
                              <input type="text" name="linkTitle" value="<?php echo $current_param['layers']['param']['slideLink']['linkTitle']['value']; ?>" title="<?php echo $current_param['layers']['param']['slideLink']['linkTitle']['desc']; ?>">
                              <label for="linkRel"><?php echo $current_param['layers']['param']['slideLink']['linkRel']['name']; ?></label>
                              <input type="text" name="linkRel" value="<?php echo $current_param['layers']['param']['slideLink']['linkRel']['value']; ?>" title="<?php echo $current_param['layers']['param']['slideLink']['linkRel']['desc']; ?>">
                          </div>
                      </div>
                    </li>
                    <li class="slide-misc">
                      <h4>Custom class name and ID for slide element</h4>
                      <div class="misc-attr">
                        <label for="miscClass"><?php echo $current_param['layers']['param']['slideMisc']['miscClass']['name']; ?></label>
                        <input type="text" name="miscClass" value="<?php echo $current_param['layers']['param']['slideMisc']['miscClass']['value']; ?>" title="<?php echo $current_param['layers']['param']['slideMisc']['miscClass']['desc']; ?>">
                        <label for="miscId"><?php echo $current_param['layers']['param']['slideMisc']['miscId']['name']; ?></label>
                        <input type="text" name="miscId" value="<?php echo $current_param['layers']['param']['slideMisc']['miscId']['value']; ?>" title="<?php echo $current_param['layers']['param']['slideMisc']['miscId']['desc']; ?>">
                      </div>
                      <h4>Background color and slide background alt text</h4>
                      <div class="misc-background">
                        <label for="miscBackgroundColor"><?php echo $current_param['layers']['param']['slideMisc']['miscBackgroundColor']['name']; ?></label>
                        <input type="text" name="miscBackgroundColor" class="storm-color-picker" value="<?php echo $current_param['layers']['param']['slideMisc']['miscBackgroundColor']['value']; ?>" title="<?php echo $current_param['layers']['param']['slideMisc']['miscBackgroundColor']['desc']; ?>">
                        <label for="miscBackgroundAlt"><?php echo $current_param['layers']['param']['slideMisc']['miscBackgroundAlt']['name']; ?></label>
                        <input type="text" name="miscBackgroundAlt" value="<?php echo $current_param['layers']['param']['slideMisc']['miscBackgroundAlt']['value']; ?>" title="<?php echo $current_param['layers']['param']['slideMisc']['miscBackgroundAlt']['desc']; ?>">
                        <label for="miscBackgroundTitle"><?php echo $current_param['layers']['param']['slideMisc']['miscBackgroundTitle']['name']; ?></label>
                        <input type="text" name="miscBackgroundTitle" value="<?php echo $current_param['layers']['param']['slideMisc']['miscBackgroundTitle']['value']; ?>" title="<?php echo $current_param['layers']['param']['slideMisc']['miscBackgroundTitle']['desc']; ?>">
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="slide-container-data">
                    <div class="image-container">
                      <div class="image-container-content">
                        <div id="preview-bg" class="preview-bg" style="width: <?php echo $current_param['slider']['width']['value']; ?>px; background-image: url('<?php echo $current_param['slider']['backgroundImage']['value']; ?>');">
                          <div id="previewLayer" class="previewLayer" style="width: <?php echo $current_param['slider']['width']['value']; ?>px; height: <?php echo $current_param['slider']['height']['value']; ?>px; background-image: url('<?php echo $current_param['layers']['param']['background']['value']; ?>');">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="image-list-layer">
                      <div class="add-layer">
                        <label>New Layer :</label>
                        <a href="#" id="add-layer-button" class="btn">
                          <?php _e('Add Layer', 'StormSlider'); ?>
                        </a>
                        <ul class="add-layer-container">
                          <li>
                            <a href="#" class="add-layer-option">
                              <input type="hidden" class="add-layery-value" value="text">
                              <img class="add-layer-text-img" src="<?php echo plugins_url('/image/layertypes/text.png', dirname(__FILE__)) ?>">
                              <label class="add-layer-text-label">Text Layer</label>
                            </a>
                          </li>
                          <li>
                            <a href="#" class="add-layer-option">
                              <input type="hidden" class="add-layery-value" value="image">
                              <img class="add-layer-image-img" src="<?php echo plugins_url('/image/layertypes/image.png', dirname(__FILE__)) ?>">
                              <label class="add-layer-image-label">Image Layer</label>
                            </a>
                          </li>
                          <li>
                            <a href="#" class="add-layer-option">
                              <input type="hidden" class="add-layery-value" value="button">
                              <img class="add-layer-button-img" src="<?php echo plugins_url('/image/layertypes/button.png', dirname(__FILE__)) ?>">
                              <label class="add-layer-button-label">Button Layer</label>
                            </a>
                          </li>
                          <li>
                            <a href="#" class="add-layer-option">
                              <input type="hidden" class="add-layery-value" value="video">
                              <img class="add-layer-video-img" src="<?php echo plugins_url('/image/layertypes/video.png', dirname(__FILE__)) ?>">
                              <label class="add-layer-video-label">Video Layer</label>
                            </a>
                          </li>
                        </ul>
                      </div>
                      <div class="image-preview">
                        <a href="#" class="btn slide-preview">
                          <?php _e('Preview', 'StormSlider'); ?>
                        </a>
                      </div>
                  </div>
                  <div class="image-layer-view">
                    <div class="image-layer-container">
                      <div class="image-layer-headbar">
                        <div class="image-layer-controls">
                          <div>
                            <span title="Show/Hide All" class="black-hide-all"></span>
                            <span title="Lock/Unlock All" class="black-lock-all"></span>
                          </div>
                        </div>
                        <div class="timeline">
                          <div class="timeline-ruler-container">
                            <div class="timeline-label">0s</div>
                            <div class="timeline-label">1s</div>
                            <div class="timeline-label">2s</div>
                            <div class="timeline-label">3s</div>
                            <div class="timeline-label">4s</div>
                            <div class="timeline-label">5s</div>
                            <div class="timeline-label">6s</div>
                            <div class="timeline-label">7s</div>
                            <div class="timeline-label">8s</div>
                            <div class="timeline-label">9s</div>
                            <div class="timeline-label">10s</div>
                            <div class="timeline-label">11s</div>
                            <div class="timeline-label">12s</div>
                            <div class="timeline-label">13s</div>
                            <div class="timeline-label">14s</div>
                            <div class="timeline-label">15s</div>
                            <div class="timeline-label">16s</div>
                            <div class="timeline-label">17s</div>
                            <div class="timeline-label">18s</div>
                            <div class="timeline-label">19s</div>
                            <div class="timeline-label">20s</div>
                            <div class="timeline-label">21s</div>
                            <div class="timeline-label">22s</div>
                            <div class="timeline-label">23s</div>
                            <div class="timeline-label">24s</div>
                            <div class="timeline-label">25s</div>
                            <div class="timeline-label">26s</div>
                            <div class="timeline-label">27s</div>
                            <div class="timeline-label">28s</div>
                            <div class="timeline-label">29s</div>
                            <div class="timeline-label">30s</div>
                          </div>
                        </div>
                      </div>
                      <div class="image-layer-timeline-cont">
                        <div class="image-layer-list">
                          <ul class="layer-ul">
                            <?php if (isset($data['layers'][0]['sublayer'])): ?>
                              <?php foreach ($data['layers'][0]['sublayer'] as $key => $value): ?>
                                <li class="layer-ul-li">
                                  <div class="image-layer-cont">
                                    <div class="image-layer-controls-list">
                                      <div class="show-lock">
                                        <span title="Show/Hide" class="layer-show <?php echo $value['show'] ? 'black-hide' : 'black-graypoint'; ?>"></span>
                                        <span title="Lock/Unlock" class="layer-lock <?php echo $value['lock'] ? 'black-lock' : 'black-graypoint'; ?>"></span>
                                      </div>
                                      <div class="image-layer-type">
                                        <img src="<?php echo plugins_url('/image/layertypes/'.$value['layerType'].'.png', dirname(__FILE__)); ?>" alt="<?php echo $value['layerType']; ?>" class="image-layer">
                                        <span class="layerName" name="layerName"><?php echo $value['layerName']; ?></span>
                                      </div>
                                      <div class="image-layer-button">
                                          <span title="Duplicate" class="black-duplicate"></span>
                                          <span title="Remove" class="black-remove">
                                          </span>
                                      </div>
                                    </div>
                                  </div>
                                </li>
                              <?php endforeach ?>
                            <?php endif ?>
                          </ul>
                        </div>
                        <div class="image-layer-timeline-list">
                          <ul class="image-layer-timeline-ul">
                            <?php if (isset($data['layers'][0]['sublayer'])): ?>
                            <?php foreach ($data['layers'][0]['sublayer'] as $key => $value ): ?>
                            <?php
                              $delayShow = $value['transitionShow']['delay'];
                              $durationShow = $value['transitionShow']['duration'];
                              $delayHide = $value['checkLayerTransitionHide'] ? $value['transitionHide']['delay'] : 0;
                              $durationHide = $value['checkLayerTransitionHide'] ? $value['transitionHide']['duration'] : 0;
                            ?>
                            <li>
                              <div class="timeline-view">
                                <div class="layer-delayShow-timeline" style="width:<?php echo $delayShow * 80; ?>px;"></div>
                                <div class="layer-durationShow-timeline" style="width:<?php echo $durationShow * 80; ?>px;"></div>
                                <div class="layer-delayHide-timeline" style="width:<?php echo $delayHide  * 80; ?>px;"></div>
                                <div class="layer-durationHide-timeline" style="width:<?php echo $durationHide * 80; ?>px;"></div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                          <?php endif; ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="show-layer" style="display: none;">
                  <div class="layer-handle">
                    <ul class="tabs">
                      <li class="active"><a href="#layer-content">Content</a></li>
                      <li><a href="#layer-transition">Transition</a></li>
                      <li><a href="#layer-style">Style</a></li>
                      <li><a href="#layer-attr">Misc</a></li>
                    </ul>
                  </div>
                  <ul class="layer-container-content">
                    <li class="layer-content active">

                    </li>
                    <li class="layer-transition">
                      <div class="layer-transition-container">
                        <div class="layer-transition-parallax">
                          <h4>Parallax</h4>
                          <div>
                            <label for="parallax"><?php echo $current_param['layers']['sublayer']['parallax']['name']; ?></label>
                            <input type="text" name="parallax" class="insert-spinner" value="<?php echo $current_param['layers']['sublayer']['parallax']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['parallax']['desc']; ?>"/>
                          </div>
                        </div>
                        <div class="layer-transition-show">
                          <h4>Transition Effect Show</h4>
                          <div class="left layerTransShow-container">
                            <a href="#" class="btn layerTransShow">
                              <?php _e('Select Transition', 'StormSlider'); ?>
                            </a>
                            <label>Duration :</label>
                            <input type="text" name="duration" class="insert-spinner transition" title="The transition duration in seconds when the layer enters into the slide, A second equals to 1 seconds."/>
                            <label>Delay :</label>
                            <input type="text" name="delay" class="insert-spinner" title="Delay the transition duration in seconds when the layer enters into the slide, A second equals to 1 seconds."/>
                          </div>

                        </div>
                        <div class="clear"></div>
                        <div class="layer-transition-hide">
                          <h4>Transition Effect Hide</h4>
                          <div class="checkLayerTransitionHide">
                            <span>Enable transition hide</span>
                            <input type="checkbox" name="checkLayerTransitionHide" <?php empty($value['checkLayerTransitionHide']) ?  '' : 'checked="checked"';?> >
                          </div>
                          <div class="left layerTransHide-container">
                            <a href="#" class="btn layerTransHide">
                              <?php _e('Select Transition', 'StormSlider'); ?>
                            </a>
                            <label for="duration">Duration :</label>
                            <input type="text" name="duration" class="insert-spinner" title="The transition duration in seconds when the layer leaves the slide, A second equals to 1 seconds."/>
                            <label for="delay">Delay :</label>
                            <input type="text" name="delay" class="insert-spinner" title="Delay the transition duration in seconds when the layer leaves the slide, A second equals to 1 seconds."/>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li class="layer-style">
                      <h4>Layer Style</h4>
                      <table>
                        <tbody>
                          <tr class="layout-position layer-style-design">
                            <td>Layout & Position</td>
                            <td><label for="subWidth"><?php echo $current_param['layers']['sublayer']['subWidth']['name']; ?></label></td>
                            <td><input placeholder="auto" type="text" name="subWidth" class="insert-spinner style" value="<?php echo $current_param['layers']['sublayer']['subWidth']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['subWidth']['desc']; ?>" valuemin="0"/></td>
                            <td><label for="subHeight"><?php echo $current_param['layers']['sublayer']['subHeight']['name']; ?></label></td>
                            <td><input placeholder="auto" type="text" name="subHeight" class="insert-spinner style" value="<?php echo $current_param['layers']['sublayer']['subHeight']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['subHeight']['desc']; ?>"/></td>
                            <td>
                              <label for="positionTop"><?php echo $current_param['layers']['sublayer']['positionTop']['name']; ?></label>
                            </td>
                            <td><input type="text" name="positionTop" class="insert-spinner" value="<?php echo $current_param['layers']['sublayer']['positionTop']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['positionTop']['desc']; ?>"/></td>
                            <td><label for="positionLeft"><?php echo $current_param['layers']['sublayer']['positionLeft']['name']; ?></label></td>
                            <td><input type="text" name="positionLeft" class="insert-spinner" value="<?php echo $current_param['layers']['sublayer']['positionLeft']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['positionLeft']['desc']; ?>"/></td>
                            <td><label for="positionWay"><?php echo $current_param['layers']['sublayer']['positionWay']['name']; ?></label></td>
                            <td>
                              <select class="positionWay" name="<?php echo $current_param['layers']['sublayer']['positionWay']['key']; ?>" title="<?php echo $current_param['layers']['sublayer']['positionWay']['desc']; ?>">
                                <option <?php if($current_param['layers']['sublayer']['positionWay']['value'] == 'fixedSlider') {
                                    echo 'Selected';
                                  } ?> value="fixedSlider"><?php _e('Fixed on slider', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['borderStyle']['value'] == 'fixedWindow') {
                                    echo 'Selected';
                                  } ?> value="fixedWindow"><?php _e('Fixed on window', 'StormSlider'); ?></option>
                              </select>
                            </td>
                          </tr>
                          <tr class="layer-style-border layer-style-design">
                            <td>Border</td>
                            <td><label for="borderTop"><?php echo $current_param['layers']['sublayer']['borderTop']['name']; ?></label></td>
                            <td><input type="text" name="borderTop" value="<?php echo $current_param['layers']['sublayer']['borderTop']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['borderTop']['desc']; ?>"/></td>
                            <td><label for="borderRight"><?php echo $current_param['layers']['sublayer']['borderRight']['name']; ?></label></td>
                            <td><input type="text" name="borderRight" value="<?php echo $current_param['layers']['sublayer']['borderRight']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['borderRight']['desc']; ?>"/></td>
                            <td><label for="borderBottom"><?php echo $current_param['layers']['sublayer']['borderBottom']['name']; ?></label></td>
                            <td><input type="text" name="borderBottom" value="<?php echo $current_param['layers']['sublayer']['borderBottom']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['borderBottom']['desc']; ?>"/></td>
                            <td><label for="borderLeft"><?php echo $current_param['layers']['sublayer']['borderLeft']['name']; ?></label></td>
                            <td><input type="text" name="borderLeft" value="<?php echo $current_param['layers']['sublayer']['borderBottom']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['borderLeft']['desc']; ?>"/></td>

                            <td><label for="borderColor"><?php echo $current_param['layers']['sublayer']['borderColor']['name']; ?></label></td>
                            <td><input type="text" name="borderColor" class="storm-color-picker" value="<?php echo $current_param['layers']['sublayer']['borderColor']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['borderColor']['desc']; ?>"/></td>
                            <td><label for="borderStyle"><?php echo $current_param['layers']['sublayer']['borderStyle']['name']; ?></label></td>
                            <td>
                              <select class="borderStyle" name="<?php echo $current_param['layers']['sublayer']['borderStyle']['key']; ?>" title="<?php echo $current_param['layers']['sublayer']['borderStyle']['desc']; ?>">
                                <option <?php if($current_param['layers']['sublayer']['borderStyle']['value'] == 'none') {
                                    echo 'Selected';
                                  } ?> value="none"><?php _e('None', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['borderStyle']['value'] == 'solid') {
                                    echo 'Selected';
                                  } ?> value="solid"><?php _e('Solid', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['borderStyle']['value'] == 'dashed') {
                                    echo 'Selected';
                                  } ?> value="dashed"><?php _e('Dashed', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['borderStyle']['value'] == 'double') {
                                    echo 'Selected';
                                  } ?> value="double"><?php _e('Double', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['borderStyle']['value'] == 'groove') {
                                    echo 'Selected';
                                  } ?> value="groove"><?php _e('Groove', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['borderStyle']['value'] == 'dotted') {
                                    echo 'Selected';
                                  } ?> value="dotted"><?php _e('Dotted', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['borderStyle']['value'] == 'ridge') {
                                    echo 'Selected';
                                  } ?> value="ridge"><?php _e('Ridge', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['borderStyle']['value'] == 'inset') {
                                    echo 'Selected';
                                  } ?> value="inset"><?php _e('inset', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['borderStyle']['value'] == 'outset') {
                                    echo 'Selected';
                                  } ?> value="outset"><?php _e('Outset', 'StormSlider'); ?></option>
                              </select>
                            </td>
                          </tr>
                          <tr class="layer-style-padding layer-style-design">
                            <td>Padding</td>
                            <td><label for="paddingTop"><?php echo $current_param['layers']['sublayer']['paddingTop']['name']; ?></label></td>
                            <td><input type="text" name="paddingTop" class="insert-spinner" value="<?php echo $current_param['layers']['sublayer']['paddingTop']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['paddingTop']['desc']; ?>"/></td>
                            <td><label for="paddingRight"><?php echo $current_param['layers']['sublayer']['paddingRight']['name']; ?></label></td>
                            <td><input type="text" name="paddingRight"  class="insert-spinner" value="<?php echo $current_param['layers']['sublayer']['paddingRight']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['paddingRight']['desc']; ?>"/></td>
                            <td><label for="paddingBottom"><?php echo $current_param['layers']['sublayer']['paddingBottom']['name']; ?></label></td>
                            <td><input type="text" name="paddingBottom" class="insert-spinner" value="<?php echo $current_param['layers']['sublayer']['paddingBottom']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['paddingBottom']['desc']; ?>"/></td>
                            <td><label for="paddingLeft"><?php echo $current_param['layers']['sublayer']['paddingLeft']['name']; ?></label></td>
                            <td><input type="text" name="paddingLeft" class="insert-spinner" value="<?php echo $current_param['layers']['sublayer']['paddingLeft']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['paddingLeft']['desc']; ?>"/></td>
                          </tr>
                          <tr class="layer-style-font layer-style-design">
                            <td>Font</td>
                            <td><label for="fontWeight"><?php echo $current_param['layers']['sublayer']['fontWeight']['name']; ?></label></td>
                            <td>
                              <select class="fontWeight" name="<?php echo $current_param['layers']['sublayer']['fontWeight']['key']; ?>" title="<?php echo $current_param['layers']['sublayer']['fontWeight']['desc']; ?>">
                                <option <?php if($current_param['layers']['sublayer']['fontWeight']['value'] == '100') {
                                    echo 'Selected';
                                  } ?> value="100"><?php _e('Thin', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['fontWeight']['value'] == '200') {
                                    echo 'Selected';
                                  } ?> value="200"><?php _e('Extra light', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['fontWeight']['value'] == '300') {
                                    echo 'Selected';
                                  } ?> value="300"><?php _e('Light', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['fontWeight']['value'] == '400') {
                                    echo 'Selected';
                                  } ?> value="400"><?php _e('Normal', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['fontWeight']['value'] == 'medium') {
                                    echo 'Selected';
                                  } ?> value="500"><?php _e('Medium', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['fontWeight']['value'] == '600') {
                                    echo 'Selected';
                                  } ?> value="600"><?php _e('Semi blod', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['fontWeight']['value'] == '700') {
                                    echo 'Selected';
                                  } ?> value="700"><?php _e('Blod', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['fontWeight']['value'] == '800') {
                                    echo 'Selected';
                                  } ?> value="800"><?php _e('Extra blod', 'StormSlider'); ?></option>
                                <option <?php if($current_param['layers']['sublayer']['fontWeight']['value'] == '900') {
                                    echo 'Selected';
                                  } ?> value="900"><?php _e('Black (Heavy)', 'StormSlider'); ?></option>
                              </select>
                            </td>
                            <td><label for="fontColor"><?php echo $current_param['layers']['sublayer']['fontColor']['name']; ?></label></td>
                            <td><input type="text" name="fontColor" class="storm-color-picker" value="<?php echo $current_param['layers']['sublayer']['fontColor']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['fontColor']['desc']; ?>"/></td>
                            <td><label for="fontSize"><?php echo $current_param['layers']['sublayer']['fontSize']['name']; ?></label></td>
                            <td><input type="text" name="fontSize" class="insert-spinner" value="<?php echo $current_param['layers']['sublayer']['fontSize']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['fontSize']['desc']; ?>"/></td>
                            <td><label for="fontLineHeight"><?php echo $current_param['layers']['sublayer']['fontLineHeight']['name']; ?></label></td>
                            <td><input type="text" name="fontLineHeight" class="insert-spinner" value="<?php echo $current_param['layers']['sublayer']['fontLineHeight']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['fontLineHeight']['desc']; ?>"/></td>
                          </tr>
                          <tr class="layer-style-misc layer-style-design">
                            <td>Misc</td>
                            <td><label for="wordWrap"><?php echo $current_param['layers']['sublayer']['wordWrap']['name']; ?></label></td>
                            <td><input type="checkbox" name="wordWrap"></td>
                            <td><label for="backgroundColor"><?php echo $current_param['layers']['sublayer']['backgroundColor']['name']; ?></label></td>
                            <td><input type="text" name="backgroundColor" class="storm-color-picker" value="<?php echo $current_param['layers']['sublayer']['backgroundColor']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['backgroundColor']['desc']; ?>"/></td>
                            <td><label for="backgroundRadius"><?php echo $current_param['layers']['sublayer']['backgroundRadius']['name']; ?></label></td>
                            <td><input type="text" name="backgroundRadius" class="insert-spinner" value="<?php echo $current_param['layers']['sublayer']['backgroundRadius']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['backgroundRadius']['desc']; ?>"/></td>
                          </tr>
                        </tbody>
                      </table>
                    </li>
                    <li class="layer-attr">
                      <h4>Custom Attributes</h4>
                      <div class="layer-attr-container">
                        <div class="layer-attr-style">
                          <label for="subClass"><?php echo $current_param['layers']['sublayer']['subClass']['name']; ?></label>
                          <input type="text" name="subClass" value="<?php echo $current_param['layers']['sublayer']['subClass']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['subClass']['desc']; ?>"/>
                        </div>
                        <div class="layer-attr-style">
                          <label for="subId"><?php echo $current_param['layers']['sublayer']['subId']['name']; ?></label>
                          <input type="text" name="subId" value="<?php echo $current_param['layers']['sublayer']['subId']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['subId']['desc']; ?>"/>
                        </div>
                        <div class="layer-attr-style">
                          <label for="subAlt"><?php echo $current_param['layers']['sublayer']['subAlt']['name']; ?></label>
                          <input type="text" name="subAlt" value="<?php echo $current_param['layers']['sublayer']['subAlt']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['subAlt']['desc']; ?>"/>
                        </div>
                        <div class="layer-attr-style">
                          <label for="subTitle"><?php echo $current_param['layers']['sublayer']['subTitle']['name']; ?></label>
                          <input type="text" name="subTitle" value="<?php echo $current_param['layers']['sublayer']['subTitle']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['subTitle']['desc']; ?>"/>
                        </div>
                        <div class="layer-attr-style">
                          <label for="subRel"><?php echo $current_param['layers']['sublayer']['subRel']['name']; ?></label>
                          <input type="text" name="subRel" value="<?php echo $current_param['layers']['sublayer']['subRel']['value']; ?>" title="<?php echo $current_param['layers']['sublayer']['subRel']['desc']; ?>"/>
                        </div>
                      </div>
                        <div class="clear"></div>

                    </li>
                  </ul>
                </div>
            </div>
        </div>
      </div>

      <div id="storm-slider-settings" class="storm-slider-settings">
          <div class="tab_container">
            <div class="tab_buttons_slider" onclick="StormLayer.storm_change_tab(this, 'storm-slider-body');">
              <a href="#" class="tab_buttons btn">
                <?php _e('Sliders', 'StormSlider'); ?>
              </a>
            </div>
            <div class="tab_buttons_settings" onclick="StormLayer.storm_change_tab(this, 'storm-slider-settings');">
              <a href="#" class="tab_buttons btn">
                <?php _e('Settings', 'StormSlider'); ?>
              </a>
            </div>
            <div class="setting-reset" onclick="saveSetting('reset');">
              <a href="#" class="btn">
                <?php _e('Reset', 'StormSlider'); ?>
              </a>
            </div>
            <div class="setting-save" onclick="saveSetting('save');">
              <a href="#" class="btn">
              <?php _e('Save', 'StormSlider'); ?>
              </a>
            </div>
          </div>
        <div class="options-nav-tab">
          <div class="options-nav-menu"></div>
          <ul class="options-nav-ul tabs">
            <li class="active">
              <a href="#options-slide"> Slide Options</a>
            </li>
            <li>
              <a href="#options-slider"> Slider Style</a>
            </li>
            <li>
              <a href="#options-navigation">Navigation Style</a>
            </li>
          </ul>
        </div>
        <div class="options-container">
          <div class="options-slide option-block active">
            <div class="image-slider-option">
              <h3>Options</h3>
              <table>
                <tbody>
                  <tr>
                    <td>
                      <label for="width"><?php echo $default['slider']['width']['name']; ?></label>
                    </td>
                    <td>
                      <input class="insert-spinner" type="text" name="width" id="width" value="<?php echo $current_param['slider']['width']['value']; ?>" title="<?php echo $default['slider']['width']['desc']; ?>"> px
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <label for="height"><?php echo $default['slider']['height']['name']; ?></label>
                    </td>
                    <td>
                      <input class="insert-spinner" type="text" name="height" id="height" value="<?php echo $current_param['slider']['height']['value']; ?>" title="<?php echo $default['slider']['height']['desc']; ?>"> px
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <label for="layout"><?php echo $default['slider']['layout']['name']; ?></label>
                    </td>
                    <td>
                      <select name="layout" id="layout" title="<?php echo $default['slider']['layout']['desc']; ?>">
                        <option <?php if($current_param['slider']['layout']['value'] == "fixedSize") { echo "selected"; } ?> value="fixedSize">Fixed size</option>
                        <option <?php if($current_param['slider']['layout']['value'] == "responsive") { echo "selected"; } ?> value="responsive">Responsive</option>
                        <option <?php if( $current_param['slider']['layout']['value'] == "fullWidth") { echo "selected"; } ?> value="fullWidth">Full width</option>
                        <option <?php if($current_param['slider']['layout']['value'] == "fullSize") { echo "selected"; } ?> value="fullSize">Full size</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td><label for="firstSlide"><?php echo $default['slider']['firstSlide']['name']; ?></label></td>
                    <td><input type="text" name="firstSlide" id="firstSlide" value="<?php echo $current_param['slider']['firstSlide']['value']; ?>" title="<?php echo $default['slider']['firstSlide']['desc']; ?>"></td>
                  </tr>
                  <tr>
                    <td>
                      <label for="pauseHover"><?php echo $current_param['slider']['pauseHover']['name']; ?></label>
                    </td>
                    <td><input type="checkbox" name="pauseHover" <?php if($current_param['slider']['pauseHover']['value'] == 1) {echo "checked"; }?>></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="options-slider option-block">
            <h3>Slider Style</h3>
            <table>
              <tbody>
                <tr>
                  <td>
                    <label for="backgroundColor"><?php echo $current_param['slider']['backgroundColor']['name']; ?></label>
                  </td>
                  <td>
                    <input type="text" id="backgroundColor" name="backgroundColor" value="<?php echo $current_param['slider']['backgroundColor']['value']; ?>" class="storm-color-picker" title="<?php echo $current_param['slider']['backgroundColor']['desc']; ?>">
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="backgroundImage"><?php echo $current_param['slider'][
                    'backgroundImage']['name']; ?></label>
                  </td>
                  <td>
                    <div class="add-slide-container">
                      <input type="hidden" name="backgroundImage" class="backgroundImage" value="<?php echo $current_param['slider']['backgroundImage']['value']; ?>">
                      <div class="add-slide storm-bg-newuploader" style="<?php if($current_param['slider']['backgroundImage']['value'] !== '') echo 'background-image: url(\'' . $current_param['slider']['backgroundImage']['value'] . '\');'; ?>" title=" <?php echo $current_param['slider']['backgroundImage']['desc']; ?>">
                      </div>
                      <div class="add-bg-slide">
                        <span class="edit-slide" title="Add or edit background iamge"></span>
                        <span class="remove-slide" title="Remove background image"></span>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><label for="backgroundRepeat"><?php echo $current_param['slider']['backgroundRepeat']['name']; ?></label></td>
                  <td>
                    <select name="backgroundRepeat" id="backgroundRepeat" title="<?php echo $current_param['slider']['backgroundRepeat']['desc']; ?>">
                      <option <?php if( $current_param['slider']['backgroundRepeat']['value'] == "no-repeat") { echo "selected"; } ?> value="no-repeat"><?php echo $default['slider']['backgroundRepeat']['title']['no-repeat']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundRepeat']['value'] == "repeat") { echo "selected"; } ?> value="repeat"><?php echo $default['slider']['backgroundRepeat']['title']['repeat']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundRepeat']['value'] == "repeat-x") { echo "selected"; } ?> value="repeat-x"><?php echo $default['slider']['backgroundRepeat']['title']['repeat-x']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundRepeat']['value'] == "repeat-y") { echo "selected"; } ?> value="repeat-y"><?php echo $default['slider']['backgroundRepeat']['title']['repeat-y']; ?></option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><label for="backgroundAttachment"><?php echo $current_param['slider']['backgroundAttachment']['name']; ?></label></td>
                  <td>
                    <select name="backgroundAttachment" id=backgroundAttachment"" title="<?php echo $current_param['slider']['backgroundAttachment']['desc']; ?>">
                      <option <?php if( $current_param['slider']['backgroundAttachment']['value'] == 'scroll' ) { echo "selected" ; } ?> value="scroll"><?php echo $default['slider']['backgroundAttachment']['title']['scroll']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundAttachment']['value'] == 'fixed' ) { echo "selected" ; } ?> value="fixed"><?php echo $default['slider']['backgroundAttachment']['title']['fixed']; ?></option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><label for="backgroundPosition"><?php echo $current_param['slider']['backgroundPosition']['name']; ?></label></td>
                  <td>
                    <select name="backgroundPosition" id="backgroundPosition" title="<?php echo $current_param['slider']['backgroundPosition']['desc']; ?>">
                      <option <?php if( $current_param['slider']['backgroundPosition']['value'] == "left-top") { echo "selected"; } ?> value="left-top"><?php echo $default['slider']['backgroundPosition']['title']['left-top']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundPosition']['value'] == "left-center") { echo "selected"; } ?> value="left-center"><?php echo $default['slider']['backgroundPosition']['title']['left-center']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundPosition']['value'] == "left-bottom") { echo "selected"; } ?> value="left-bottom"><?php echo $default['slider']['backgroundPosition']['title']['left-bottom']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundPosition']['value'] == "center-top") { echo "selected"; } ?> value="center-top"><?php echo $default['slider']['backgroundPosition']['title']['center-top']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundPosition']['value'] == "center-center") { echo "selected"; } ?> value="center-center"><?php echo $default['slider']['backgroundPosition']['title']['center-center']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundPosition']['value'] == "center-bottom") { echo "selected"; } ?> value="center-bottom"><?php echo $default['slider']['backgroundPosition']['title']['center-bottom']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundPosition']['value'] == "right-top") { echo "selected"; } ?> value="right-top"><?php echo $default['slider']['backgroundPosition']['title']['right-top']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundPosition']['value'] == "right-center") { echo "selected"; } ?> value="right-center"><?php echo $default['slider']['backgroundPosition']['title']['right-center']; ?></option>
                      <option <?php if( $current_param['slider']['backgroundPosition']['value'] == "right-bottom") { echo "selected"; } ?> value="right-bottom"><?php echo $default['slider']['backgroundPosition']['title']['right-bottom']; ?></option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><label for="backgroundSize"><?php echo $current_param['slider']['backgroundSize']['name']; ?></label></td>
                  <td>
                    <select name="backgroundSize" id="backgroundSize" title="<?php echo $current_param['slider']['backgroundSize']['desc']; ?>">
                      <option <?php if($current_param['slider']['backgroundSize']['value'] == 'center') { echo "selected"; } ?> value="center"><?php echo $default['slider']['backgroundSize']['title']['center']; ?></option>
                      <option <?php if($current_param['slider']['backgroundSize']['value'] == 'cover') { echo "selected"; } ?> value="cover"><?php echo $default['slider']['backgroundSize']['title']['cover']; ?></option>
                      <option <?php if($current_param['slider']['backgroundSize']['value'] == 'contain') { echo "selected"; } ?> value="contain"><?php echo $default['slider']['backgroundSize']['title']['contain']; ?></option>
                      <option <?php if($current_param['slider']['backgroundSize']['value'] == 'stretch') { echo "selected"; } ?> value="stretch"><?php echo $default['slider']['backgroundSize']['title']['stretch']; ?></option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="options-navigation option-block">
            <h3>Navigation Dot Style And Option</h3>
            <table>
              <tbody>
                <tr>
                  <td>
                    <label for="navigationColor"><?php echo $current_param['slider']['navigationColor']['name']; ?></label>
                  </td>
                  <td>
                    <input type="text" name="navigationColor" id="navigationColor" value="<?php echo $current_param['slider']['navigationColor']['value']; ?>" class="storm-color-picker" title="<?php echo $current_param['slider']['navigationColor']['desc']; ?>">
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="navigationActiveColor"><?php echo $current_param['slider']['navigationActiveColor']['name']; ?></label>
                  </td>
                  <td>
                    <input type="text" name="navigationActiveColor" id="navigationActiveColor" value="<?php echo $current_param['slider']['navigationActiveColor']['value']; ?>" class="storm-color-picker" title="<?php echo $current_param['slider']['navigationActiveColor']['desc']; ?>">
                  </td>
                </tr>
                <tr>
                  <td><label for="showNavigationButton"><?php echo $current_param['slider']['showNavigationButton']['name']; ?></label></td>
                  <td><input type="checkbox" name="showNavigationButton" <?php if($current_param['slider']['showNavigationButton']['value'] == 1) {echo "checked"; } ?>></td>
                </tr>
                <tr>
                  <td><label for="showStartStopButton"><?php echo $current_param['slider']['showStartStopButton']['name']; ?></label></td>
                  <td><input type="checkbox" name="showStartStopButton" <?php if($current_param['slider']['showStartStopButton']['value'] == 1) {echo "checked"; } ?>></td>
                </tr>
                <tr>
                  <td><label for="showThumbButton"><?php echo $current_param['slider']['showThumbButton']['name']; ?></label></td>
                  <td><input type="checkbox" name="showThumbButton" <?php if($current_param['slider']['showThumbButton']['value'] == 1) {echo "checked"; } ?>></td>
                </tr>
                <tr>
                  <td><label for="showThumbImgAmount"><?php echo $default['slider']['showThumbImgAmount']['name']; ?></label></td>
                  <td><input type="text" name="showThumbImgAmount" id="showThumbImgAmount" value="<?php echo $current_param['slider']['showThumbImgAmount']['value']; ?>" title="<?php echo $default['slider']['showThumbImgAmount']['desc']; ?>"></td>
                </tr>
                <tr>
                  <td><label for="showCircleTimer"><?php echo $current_param['slider']['showCircleTimer']['name']; ?></label></td>
                  <td><input type="checkbox" name="showCircleTimer" <?php if($current_param['slider']['showCircleTimer']['value'] == 1) {echo "checked"; } ?>></td>
                </tr>
                <tr>
                  <td><label for="showBarTimer"><?php echo $current_param['slider']['showBarTimer']['name']; ?></label></td>
                  <td><input type="checkbox" name="showBarTimer" <?php if($current_param['slider']['showBarTimer']['value'] == 1) {echo "checked"; } ?>></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="msp-save-bar-placeholder" id="saveBarPlaceHolder" style="display: none;"></div>
    <div class="save">
        <div>
          <input class="btn button-large" type="button" name="saveImage" id="saveImage" value="Save slider">
        </div>
        <div>
          <label><?php _e('Shortcode:', 'StormSlider'); ?></label>
          <input type="text" readonly="readonly" value='[stormslider id="<?php echo $id; ?>"]'>
        </div>
        <div>
          <label><?php _e('PHP function:', 'StormSlider'); ?></label>
          <input type="text" readonly="readonly" value="&lt?php stormslider(<?php echo $id ?>); ?&gt">
        </div>
    </div>
    <div class="task_cont">
        <input type="hidden" id="currentId" name="currentIid" value="<?php echo $id; ?>">
    </div>
  </form>
  <div id="stormHiddenEditor" style="display: none;">
    <?php wp_editor('', 'storm-hidden-editor', array('textarea_rows' => '8')); ?>
  </div>
</div>
<script>
  var imgPath = "<?php echo plugins_url('image/', dirname(__FILE__)); ?>";
</script>