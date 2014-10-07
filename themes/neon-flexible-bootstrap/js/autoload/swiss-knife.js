
$(function() {

	// ------------ Afficher tout les produits
	$('.jqDisplayedAllProductsButton').on('click', function() {
		$('#nb_item option.jqDisplayedAllProducts').attr('selected', true);
		$('#nb_item option.jqDisplayedAllProducts').change();
	});

	// ------------ Fiche Produit tabs
	$('ul#more_info_tabs li a').on('click', function() {
		$('ul#more_info_tabs li').removeClass('active');
		$(this).parents('li').addClass('active');
	});


	// ------------ Mode d'affichage des catégories :

	if ($.totalStorage('category-mode')) {
		$('#product_list').addClass('mode-' + $.totalStorage('category-mode'));

		$('img[data-mode-affichage]').css('display', 'none');
		$('img[data-mode-affichage=' + $.totalStorage('category-mode') + ']').css('display', 'block');
	}

	$('.jqDisplayed').click(function(e) {
		mode = $(this).attr('data-type-displayed');

		$.totalStorage('category-mode', mode);
		$.totalStorage('display',       mode);

		$('#product_list').removeClass('mode-block');
		$('#product_list').removeClass('mode-list');

		$('#product_list').addClass('mode-' + $.totalStorage('category-mode'));

		imageHover  = $('img[data-type-displayed=block]').attr('data-image-hover');
		image       = $('img[data-type-displayed=block]').attr('src');

		$('img[data-type-displayed=block]').attr('src', imageHover);
		$('img[data-type-displayed=block]').attr('data-image-hover', image);
		imageHover  = $('img[data-type-displayed=list]').attr('data-image-hover');
		image       = $('img[data-type-displayed=list]').attr('src');

		$('img[data-type-displayed=list]').attr('src', imageHover);
		$('img[data-type-displayed=list]').attr('data-image-hover', image);

		$('img[data-mode-affichage]').css('display', 'none');
		$('img[data-mode-affichage=' + mode + ']').css('display', 'block');

		return false;
	});

	$('.jqDisplayed').hover(function(e) {
		getImageHover($(this));

		return false;
	});

	var listType = $.totalStorage('category-mode');

	if (listType == 'block') {
		imageHover  = $('img[data-type-displayed=block]').attr('data-image-hover');
		image       = $('img[data-type-displayed=block]').attr('src');

		$('img[data-type-displayed=block]').attr('src', imageHover);
		$('img[data-type-displayed=block]').attr('data-image-hover', image);
	} else {
		imageHover  = $('img[data-type-displayed=list]').attr('data-image-hover');
		image       = $('img[data-type-displayed=list]').attr('src');

		$('img[data-type-displayed=list]').attr('src', imageHover);
		$('img[data-type-displayed=list]').attr('data-image-hover', image);
	}
});

getImageHover = function(el) {
	imageHover  = $(el).attr('data-image-hover');
	image       = $(el).attr('src');

	$(el).attr('src', imageHover);
	$(el).attr('data-image-hover', image);
}