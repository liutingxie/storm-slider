/*
* @Author: 37156
* @Date:   2016-12-12 10:23:50
* @Last Modified by:   liutingxie
* @Last Modified time: 2017-11-06 15:43:50
*/
jQuery(function($){
	$('.action-delete').on('click', function(e){
		e.preventDefault();
		if(confirm('Are you sure you want to remove this slider?')) {
			document.location.href = $(this).attr('href');
		}
	});

	$('.storm-search-reset a.btn').on('click', function(){
		var search_title = jQuery('#search-title').val();
		window.location.href='admin.php?page=stormslider&search=' + search_title;
	});

	//Save screen option data
	var screenOption = {
		init: function() {
			$(document).on('submit', '#storm-screen-options-form', function(e) {
				e.preventDefault();
				screenOption.saveSetting(this, true);
			});
		},

		saveSetting: function(el, reload) {
			var option;
			$(el).find('input').each(function() {
				option = $(this).val();
			});

			//Use ajax save settings
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'text',
				data: {action: 'stormScreenSetting', data: option},
				success: function() {
					if(typeof reload != "undefined" && reload === true) {
						document.location.href = 'admin.php?page=stormslider';
					}
				}
			});

		}
	}

	//Screen options
	$('#storm-screen-options').children(':first-child').appendTo('#screen-meta');
	$('#storm-screen-options').children(':last-child').appendTo('#screen-meta-links');
	screenOption.init();

	//Show importexport pop-up
	$('.import_export_btn').on('click', function() {
		// Append template to body
		jQuery(jQuery('#storm-importexport-template').html()).prependTo('body');

		$('.storm-export-form-container').css('min-height', $('.storm-importexportContainer').height() - $('#storm-export-form').position().top - $('.storm-export-btn').height() - 85);
	});

	//Hide importexport pop-up
	$(document).on('click', '.storm-importexport-overlay', function(e) {
		var $target = $(e.target);
		if(!$target.parents('div').hasClass('storm-importexportContainer')) {
			$('.storm-importexportContainer').remove();
			$('.storm-importexport-overlay').remove();
		}
	});

	//Hide importexport pop-up trigger
	$(document).on('click', '.storm-remove-importexport-btn', function() {
		$('.storm-importexportContainer').remove();
		$('.storm-importexport-overlay').remove();
	});

	//Checked will select all preset transition
	$(document).on('click', '.storm-preset-check-all', function(e) {
		e.stopPropagation();

		var $this = $(this);
		var isChecked = $this.prop('checked');
		$('.storm-exportPresetTransitionContainer .storm-preset-check').prop('checked', isChecked);

	});

	//Checked will select all sliders
	$(document).on('click', '.storm-slider-check-all', function(e) {
		e.stopPropagation();

		var $this = $(this);
		var isChecked = $this.prop('checked');
		$('.storm-exportSliderContainer .storm-slider-check').prop('checked', isChecked);
	});
});
