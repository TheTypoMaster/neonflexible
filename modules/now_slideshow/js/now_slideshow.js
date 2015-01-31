
$('.carousel-slick').slick({
	dots: true,
	infinite: true,
	speed: 300,
	slidesToShow: 1,
	/*adaptiveHeight: true,*/
	autoplay: true,
	autoplaySpeed:5000
});

$('.carousel-customer-references').slick({
	infinite: true,
	slidesToShow: 2,
	autoplay: true,
	autoplaySpeed:5000,
	arrows:false,
	responsive: [
		{
			breakpoint: 900,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}
	]
});