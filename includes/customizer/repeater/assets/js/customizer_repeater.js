jQuery(function($) {

	"use strict";

    var entityMap = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        '\'': '&#39;',
        '/': '&#x2F;'
    };

    /* sanitizer */
    function escapeHtml(string) {
        'use strict';
        //noinspection JSUnresolvedFunction
        string = String(string).replace(new RegExp('\r?\n', 'g'), '<br />');
        string = String(string).replace(/\\/g, '&#92;');
        return String(string).replace(/[&<>"'\/]/g, function (s) {
            return entityMap[s];
        });
    }

    /* image upload */
    function media_upload( button_class ) {

        $('body').on('click', button_class, function () {
            var button_id = '#' + $(this).attr('id');
            var display_field = $(this).parent().children('input:text');
            var _custom_media = true;

            wp.media.editor.send.attachment = function (props, attachment) {

                if (_custom_media) {
                    if (typeof display_field !== 'undefined') {
                        switch (props.size) {
                            case 'full':
                                display_field.val(attachment.sizes.full.url);
                                display_field.trigger('change');
                                break;
                            case 'medium':
                                display_field.val(attachment.sizes.medium.url);
                                display_field.trigger('change');
                                break;
                            case 'thumbnail':
                                display_field.val(attachment.sizes.thumbnail.url);
                                display_field.trigger('change');
                                break;
                            default:
                                display_field.val(attachment.url);
                                display_field.trigger('change');
                        }
                    }
                    _custom_media = false;
                } else {
                    return wp.media.editor.send.attachment(button_id, [props, attachment]);
                }
            };
            wp.media.editor.open(button_class);
            window.send_to_editor = function (html) {

            };
            return false;
        });
    }

    /* Generate unique id */
    function customizer_repeater_uniqid( prefix, more_entropy ) {

        if (typeof prefix === 'undefined') {
            prefix = '';
        }

        var retId;
        var php_js;
        var formatSeed = function (seed, reqWidth) {
            seed = parseInt(seed, 10)
                .toString(16); // to hex str
            if (reqWidth < seed.length) { // so long we split
                return seed.slice(seed.length - reqWidth);
            }
            if (reqWidth > seed.length) { // so short we pad
                return new Array(1 + (reqWidth - seed.length))
                    .join('0') + seed;
            }
            return seed;
        };

        // BEGIN REDUNDANT
        if (!php_js) {
            php_js = {};
        }
        // END REDUNDANT
        if (!php_js.uniqidSeed) { // init seed with big random int
            php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
        }
        php_js.uniqidSeed++;

        retId = prefix; // start with prefix, add current milliseconds hex string
        retId += formatSeed(parseInt(new Date()
            .getTime() / 1000, 10), 8);
        retId += formatSeed(php_js.uniqidSeed, 5); // add seed hex string
        if (more_entropy) {
            // for more entropy we add a float lower to 10
            retId += (Math.random() * 10)
                .toFixed(8)
                .toString();
        }

        return retId;
    }

    function customizer_repeater_refresh_general_control_values() {

        $('.customizer-repeater-general-control-repeater').each(function () {
            var values = [];
            var th = $(this);
            th.find('.customizer-repeater-general-control-repeater-container').each(function () {

				var choice = $(this).find('.customizer-repeater-image-choice').val();
                var link = $(this).find('.customizer-repeater-link-control').val();
                var image_url = $(this).find('.custom-media-url').val();
				var icon_slug = $(this).find('.customizer-repeater-theme-default-icon-choice').val();
                var title = $(this).find('.customizer-repeater-title-control').val();
                var id = $(this).find('.social-repeater-box-id').val();
                if (!id) {
                    id = 'social-' + customizer_repeater_uniqid();
                    $(this).find('.social-repeater-box-id').val(id);
                }

                if (icon_slug !== '' || image_url !== '' || title !== '' || link !== '') {
                    values.push({
						'choice': choice,
                        'link': link,
                        'image_url': image_url,
						'icon_slug' : icon_slug,
                        'title': escapeHtml(title),
                        'id': id,
                    });
                }

            });
            th.find('.customizer-repeater-colector').val(JSON.stringify(values));
            th.find('.customizer-repeater-colector').trigger('change');
        });
    }

	$(function() {

		$('.customizer-repeater-image-choice').each( function() {
			if ($(this).val() === 'customizer_repeater_image') {
				$(this).parent().parent().find('.customizer-repeater-image-control').show();

			}
			if ($(this).val() === 'customizer_repeater_theme_default') {
				$(this).parent().parent().find('.customizer-repeater-theme-default-icon-control').show();
			}
		});

	    var theme_controls = $('#customize-theme-controls');
	    theme_controls.on('click', '.customizer-repeater-customize-control-title', function () {
	        $(this).next().slideToggle('medium', function () {
	            if ($(this).is(':visible')){
	                $(this).prev().addClass('repeater-expanded');
	                $(this).css('display', 'block');
	            } else {
	                $(this).prev().removeClass('repeater-expanded');
	            }
	        });
	    });

	    media_upload('.customizer-repeater-custom-media-button');
	    $('.custom-media-url').on('change', function () {
	        customizer_repeater_refresh_general_control_values();
	        return false;
	    });

	    /* This adds a new box to repeater */
	    theme_controls.on('click', '.customizer-repeater-new-field', function () {
	        var th = $(this).parent();
	        var id = 'social-' + customizer_repeater_uniqid();
	        if (typeof th !== 'undefined') {
	            /* Clone the first box*/
	            var field = th.find('.customizer-repeater-general-control-repeater-container:first').clone( true, true );

	            if (typeof field !== 'undefined') {

					field.find('.customizer-repeater-customize-control-title').html('Social Media Profile');

					/*Set the default value for choice between image and icon to icon*/
	                field.find('.customizer-repeater-image-choice').val('customizer_repeater_theme_default');

					field.find('.customizer-repeater-theme-default-icon-control').show();

		            field.find('.customizer-repeater-image-control').hide();

	                /*Show delete box button because it's not the first box*/
	                field.find('.social-repeater-general-control-remove-field').show();

	                /*Remove all repeater fields except first one*/

	                field.find('.customizer-repeater-social-repeater').find('.customizer-repeater-social-repeater-container').not(':first').remove();
	                field.find('.customizer-repeater-social-repeater-link').val('');

	                /*Remove value from link field*/
	                field.find('.customizer-repeater-link-control').val('');

	                /*Set box id*/
	                field.find('.social-repeater-box-id').val(id);

	                /*Remove value from media field*/
	                field.find('.custom-media-url').val('');

	                /*Remove value from title field*/
	                field.find('.customizer-repeater-title-control').val('');

	                /*Append new box*/
	                th.find('.customizer-repeater-general-control-repeater-container:first').parent().append(field);

	                /*Refresh values*/
	                customizer_repeater_refresh_general_control_values();
	            }

	        }
	        return false;
	    });

		theme_controls.on('change', '.customizer-repeater-image-choice', function () {
	        if ($(this).val() === 'customizer_repeater_image') {
	            $(this).parent().parent().find('.customizer-repeater-theme-default-icon-control').hide();
	            $(this).parent().parent().find('.customizer-repeater-image-control').show();

	        }
	        if ($(this).val() === 'customizer_repeater_theme_default') {
	            $(this).parent().parent().find('.customizer-repeater-theme-default-icon-control').show();
	            $(this).parent().parent().find('.customizer-repeater-image-control').hide();
	        }

	        customizer_repeater_refresh_general_control_values();
	        return false;
	    });

		theme_controls.on('change', '.customizer-repeater-theme-default-icon-choice', function () {
	        customizer_repeater_refresh_general_control_values();
	        return false;
	    });

	    theme_controls.on('click', '.social-repeater-general-control-remove-field', function () {
	        if (typeof $(this).parent() !== 'undefined') {
	            $(this).parent().parent().remove();
	            customizer_repeater_refresh_general_control_values();
	        }
	        return false;
	    });

	    theme_controls.on('keyup', '.customizer-repeater-title-control', function () {
	        customizer_repeater_refresh_general_control_values();
			$(this).parent().parent().find('.customizer-repeater-customize-control-title').html($(this).val());
	    });

	    theme_controls.on('keyup', '.customizer-repeater-link-control', function () {
	        customizer_repeater_refresh_general_control_values();
	    });

	    /*Drag and drop to change icons order*/
	    $('.customizer-repeater-general-control-droppable').sortable({
	        axis: 'y',
	        update: function () {
	            customizer_repeater_refresh_general_control_values();
	        }
	    });
	});
});
