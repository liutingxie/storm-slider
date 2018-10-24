(function($) {

	var radioCheck = /radio|checkbox/i,
		keyBreaker = /[^\[\]]+/g,
		numberMatcher = /^[\-+]?[0-9]*\.?[0-9]+([eE][\-+]?[0-9]+)?$/;

	var isNumber = function( value ) {
		if ( typeof value == 'number' ) {
			return true;
		}

		if ( typeof value != 'string' ) {
			return false;
		}

		return value.match(numberMatcher);
	};

	$.fn.extend({

		formParams: function() {
			return jQuery('input[name], select[name]', this[0]).getParams();
		},

		getParams: function() {
			var data = {},
				current;

			this.each(function() {

				var el = this,
					type = el.type && el.type.toLowerCase();
				var key = el.name,
					value = $.fn.val.call([el]),
					isRadioCheck = radioCheck.test(el.type),
					parts = key.match(keyBreaker),
					write = !isRadioCheck || !! el.checked,
					//make an array of values
					lastPart;

				if ( isNumber(value) ) {
					value = parseFloat(value);
				} else if ( value === 'true' || value === 'false' ) {
					value = Boolean(value);
				}

				// go through and create nested objects
				current = data;

				for ( var i = 0; i < parts.length - 1; i++ ) {

					if (!current[parts[i]] ) {
						current[parts[i]] = {};
					}

					current = current[parts[i]];

				}

				lastPart = parts[parts.length - 1];

				//now we are on the last part, set the value
				if ( lastPart in current && type === "checkbox" ) {

					if (!$.isArray(current[lastPart]) ) {
						current[lastPart] = current[lastPart] === undefined ? [] : [current[lastPart]];
					}

					if ( write ) {
						current[lastPart].push(value);
					}
				} else if ( write || !current[lastPart] ) {

					current[lastPart] = write ? value : undefined;
				}

			});
			return data;
		}
	});

})(jQuery);

var transitionData = null;
var transitionData3dData = null;
var transitionData2dData = null;
var transitionData3DIndex = 0;
var transitionData2DIndex = 0;
var activeTransitionData3D = null;
var activeTransitionData2D = null;

var transitionBuild = {
	selectTransition: function(el) {

		//Get select
		var select = jQuery(el);

		//Get parent
		var parent = jQuery(el).next().hasClass('3d') ? jQuery('.transition-3d') : jQuery('.transition-2d');

		//Get index
		var index = jQuery(el).find('option:selected').index();

		//Show new transition
		parent.children().removeClass('active');
		parent.children().eq(index).addClass('active');
	},

	addTransition: function(el) {

		//Get select
		var select = jQuery(el).prev();

		//Get parent
		var parent = jQuery(el).hasClass('3d') ? jQuery('.transition-3d') : jQuery('.transition-2d');

		//Get template
		var template = jQuery(el).hasClass('3d') ? jQuery('#template-3d').text() : jQuery('#template-2d').text();

		//Remove notification if any
		if(parent.find('.storm-no-transition-notification').length) {

			//Remove transition notification
			parent.children('.storm-no-transition-notification').remove();

			//Remove option from select
			select.children('.notification').remove();
		}

		//Append template
		var item = jQuery(template).appendTo(parent);

		//Find item name
		var name = item.find('input[name="name"]').val();

		//Append item to list and select it
		select.children().prop('selected', false);
		jQuery(jQuery('<option>', {text : name})).appendTo(select).prop('selected', true);

		//Active item to Show new transition
		parent.children().removeClass('active');
		item.addClass('active');

	},

	removeTransition: function(el) {

		if(!confirm('Are you sure you want to remove this transition?')) {
			return;
		}

		var select;

		//3d
		if(jQuery(el).closest('.transition-3d').length > 0) {

			//Get select
			select = jQuery('.transition-template-select.3d');

			//Select notification
			selectNotification = 'Not 3D transition yet';

			//Text notification
			textNotification = 'You did\'t create any 3D transition yet';
		}
		//2d
		else {

			//Get select
			select = jQuery('.transition-template-select.2d');

			//select notification
			selectNotification = 'Not 2D transition yet';

			//Text notification
			textNotification = 'You did\'t create any 2D transition yet';
		}

		//Get transition
		var item = jQuery(el).closest('.transition-item');

		//Get parent
		var parent = item.parent();

		//Get index
		var index = item.index();

		if(select.children().length > 1) {

			//Get new index
			var newIndex = item.prev().length > 0 ? index - 1 : index + 1;

			//Show new transition
			item.parent().children().removeClass('active');
			item.parent().children().eq(newIndex).addClass('active');

			//Select new item in list
			select.children().prop('selected', false);
			select.children().eq(newIndex).prop('selected', true);
		}

		item.remove();

		//Remove from select
		select.children().eq(index).remove();

		if(select.children().length === 0) {

			//Add select placeholder
			select.append(jQuery('<option>', {class: 'notification', text: selectNotification}));

			//Add notification
			parent.append(jQuery('<div>', {class: 'storm-no-transition-notification'})
				.append(jQuery('<h1>', {text: textNotification}))
				.append(jQuery('<p>', {text: 'To create a new transition, click to the \"Add new\" button above.'}))
			);
		}
	},

	addTransitionProperty: function(el) {
		var $item = jQuery(el);
		var property = $item.next().val();
		var text = $item.parent('p').find('select option:selected').text();
		var val = property.split(',');

		var li = '<li><span>'+text+'</span><input value="'+val[1]+'" name="'+val[0]+'"/><a href="#"><i class="dashicons dashicons-dismiss"></i></a></li>';

		var $ul = jQuery(el).parent('p').prev();
		$ul.append(li);
	},

	removeTransitionProperty: function(el) {
		jQuery(el).parent('li').remove();
	},

	toggleTableGroup: function(el) {
		//Get element
		var tbody = jQuery(el).closest('thead').next();

		if(tbody.hasClass('template-collapsed')) {
			tbody.removeClass('template-collapsed');
		}
		else {
			tbody.addClass('template-collapsed');
		}
	},

	save: function (el) {

		//Store name attrs
		transitionBuild.storeNameAttrs();

		jQuery('.transition-save').find('.btn').addClass('saving').text('Save...').attr('disabled', true);

		transitionBuild.serializeTransition();

		jQuery.post(window.location.href, jQuery(el).serialize(), function() {
			jQuery('.transition-save').find('.btn').removeClass('saving').addClass('saved').text('Saved');

			setTimeout(function() {
				jQuery('.transition-save').find('.btn').removeClass('saved').text('Save template').attr('disabled', false);
			}, 2000);

			transitionBuild.restoreNameAttrs();
		});
	},

	serializeTransition: function() {

		jQuery('.transition-3d table').each(function(index) {

			//Get basic value
			jQuery(this).find('tbody.basic input').each(function() {
				jQuery(this).attr('name', '3d['+index+']['+jQuery(this).attr('name')+']');
			});

			//Iterate over the other tbody
			jQuery(this).find('tbody:gt(1)').each(function() {

				if(typeof jQuery(this).attr('class') !== 'undefined') {

					//Get othre tbody class
					var tbodyClass = jQuery(this).attr('class').split(' ')[0];

					jQuery(this).find('input,select').each(function() {

						if(jQuery(this).closest('tr.transition').length === 0) {

							jQuery(this).attr('name', '3d['+index+']['+tbodyClass+']['+jQuery(this).attr('name')+']');
						}
						else if(jQuery(this).is('input')) {
							jQuery(this).attr('name', '3d['+index+']['+tbodyClass+'][transition]['+jQuery(this).attr('name')+']');
						}
					});

					jQuery(this).prev().find('input').each(function() {
						jQuery(this).attr('name', '3d['+index+']['+tbodyClass+'][enabled]');
					});
				}
			});
		});


		jQuery('.transition-2d table').each(function(index) {

			//Get basic value
			jQuery(this).find('tbody.basic input').each(function() {
				jQuery(this).attr('name', '2d['+index+']['+jQuery(this).attr('name')+']');
			});

			//Iterate over the other tbody
			jQuery(this).find('tbody:gt(1)').each(function() {

				if(typeof jQuery(this).attr('class') !== 'undefined') {

					var tbodyClass = jQuery(this).attr('class');

					jQuery(this).find('input,select').each(function(){

						jQuery(this).attr('name', '2d['+index+']['+tbodyClass+']['+jQuery(this).attr('name')+']');
					});
				}
			});
		});
	},

	storeNameAttrs: function() {
		jQuery('#storm-slider-transition-template-form').find('input,select').each(function() {
			jQuery(this).data('originName', jQuery(this).attr('name'));
		});
	},

	restoreNameAttrs: function() {
		jQuery('#storm-slider-transition-template-form').find('input,select').each(function() {
			jQuery(this).attr('name', jQuery(this).data('originName'));
		});
	},

	playPreview: function(el) {

		//Check transition status
		if(jQuery(el).hasClass('playing')) {
			transitionBuild.stopPreview(el);
			return;
		}

		jQuery(el).text('Exit Preview').addClass('playing');

		//Get transition item
		var item = jQuery(el).closest('.transition-item');

		//Get index
		var index = jQuery(item).index();

		//Get type
		var type = jQuery(item).closest('.transition-3d').length > 0 ? '3d' : '2d';

		//Store name attrs
		transitionBuild.storeNameAttrs();

		//Serialize
		transitionBuild.serializeTransition();

		//Get transiiton
		var trObject = jQuery(item).formParams();
			trObject = trObject[type][index];

		if(type == '3d') {
			if(typeof trObject['before']['enabled'] == 'undefined') {
				trObject['before'] = {};
			}

			if(typeof trObject['after']['enabled'] == 'undefined') {
				trObject['after'] = {};
			}
		}

		//Restore name attrs
		transitionBuild.restoreNameAttrs();

		//Try preview
		try {
			jQuery(item).find('.storm-builder-preview').empty();
			jQuery(item).find('.storm-builder-preview').stormTransition({
				transitionType : type,
				transitionObject : trObject,
				imgPath : imgPath,
				delay: 0.1
			});

		}
		catch(err) {

			//Stop preview
			transitionBuild.stopPreview(el);

			//Show erroe message
			alert('Oops, someting went wrong, please check your transition settings and enter vaild values. Erros: ' + err);
		}
	},

	stopPreview: function(el) {

		jQuery(el).text('Preview').removeClass('playing');

		jQuery(el).closest('.transition-item').find('.storm-builder-preview').stormTransition('forceStop');

		jQuery(el).closest('.transition-item').find('.storm-builder-preview').append(jQuery('<img>', {'src': imgPath+'slide1-preview.png'}));

	}
};

jQuery(document).ready(function($) {
	transitionData = window.transitionDefaultData;

	// jQuery ui tooltip
	$('.template-container').tooltip({
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

	$(document).on('click', '.build-collaspe-toggle', function() {
		transitionBuild.toggleTableGroup(this);
	});

	$('.add-slide-icon').on('click', function() {
		transitionBuild.addTransition(this);
	});

	$(document).on('click', '.remove-option', function(e) {
		e.preventDefault();
		transitionBuild.removeTransition(this);
	});

	$('.transition-template-select').on('change', function() {
		transitionBuild.selectTransition(this);
	});

	$(document).on('click', '.storm-builder-preview-button a', function(e) {
		e.preventDefault();
		transitionBuild.playPreview(this);
	});

	$('.transition-property .btn').on('click', function(e) {
		e.preventDefault();
		transitionBuild.addTransitionProperty(this);
	});

	$('.transition-tag').on('click', 'a', function(e) {
		e.preventDefault();
		transitionBuild.removeTransitionProperty(this);
	});

	$('#storm-slider-transition-template-form').submit(function(e) {
		e.preventDefault();
		transitionBuild.save(this);
	});
});
