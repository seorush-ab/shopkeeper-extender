(function( $ ) {

    'use strict';

    $( document ).ready( function( $ ) {

        $(document).on('click', '.editor-block-list__block[data-type="getbowtied/slider"] .editor-inner-blocks .editor-block-list__block .wp-block-slide-title-wrapper', function(e){
			var block = $(this).closest('.editor-block-list__block');

			if( block.hasClass('clicked') )
			{
				block.toggleClass('is-selected');
			}
			block.addClass('clicked');
		});
    }); 

} )( jQuery );