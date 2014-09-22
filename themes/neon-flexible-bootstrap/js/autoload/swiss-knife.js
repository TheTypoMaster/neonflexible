
$(function() {
	if ($.totalStorage('category-mode')) {
		$('#product_list').addClass('mode-' + $.totalStorage('category-mode'));
	}

	$('.jqDisplayed').click(function(e) {
		mode = $(this).attr('data-type-displayed');

		$.totalStorage('category-mode', mode);

		$('#product_list').removeClass('mode-block');
		$('#product_list').removeClass('mode-list');

		$('#product_list').addClass('mode-' + $.totalStorage('category-mode'));

		return false;
	});
});