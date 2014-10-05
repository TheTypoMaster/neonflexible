
$('.carousel-slick-category').slick({
	dots: false,
	infinite: true,
	speed: 300,
	slidesToShow: 9,
	slidesToScroll: 1,
	responsive: [
		{
			breakpoint: 1024,
			settings: {
				slidesToShow: 7,
				slidesToScroll: 1,
				infinite: true,
				dots: true
			}
		},
		{
			breakpoint: 600,
			settings: {
				slidesToShow: 5,
				slidesToScroll: 1
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 1
			}
		}
	]
});