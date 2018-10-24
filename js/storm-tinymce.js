/*
* @Author: liutingxie
* @Date:   2016-08-30 15:20:29
* @Last Modified by:   liutingxie
* @Last Modified time: 2016-09-21 18:13:05
*/

jQuery(document).ready(function($) {
	// Create 'keyup_event' tinymce plugin
    tinymce.PluginManager.add('keyup_event', function(editor){

    	// Create keyup event
    	editor.on('keyup change paste', function(e){

    		// Get the editor content
    		val = tinymce.get('slideSubLayerTinymce').getContent();

    		// Set the stormSliderData.layer.param val
 			activeSlideLayerData.slideSubLayerTinymce = val;

			var div = '<div class="stageLayer" id="'+(activeSlideLayerIndex+1)+'"></div>';
			jQuery("#"+(activeSlideLayerIndex+1)).remove();
			jQuery(".preview-bg").append(div);
			jQuery("#"+(activeSlideLayerIndex+1)).append(val);
    	});
    });

});
