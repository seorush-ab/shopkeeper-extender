( function( blocks ) {
	var blockCategories = blocks.getCategories();
	blockCategories.unshift({ 'slug': 'shopkeeper', 'title': 'Shopkeeper Blocks'});
	blocks.setCategories(blockCategories);
})(
	window.wp.blocks
);

//@prepros-append blocks/banner.js
//@prepros-append blocks/slide.js
//@prepros-append blocks/slider.js
