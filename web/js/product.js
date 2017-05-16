$(document).ready(function(){
	$('.slider-main').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: false,
	  fade: true,
	  asNavFor: '.slider-images'
	});
	$('.slider-images').slick({
	  slidesToShow: 3,
	  slidesToScroll: 1,
	  asNavFor: '.slider-main',
	  dots: true,
	  centerMode: true,
	  focusOnSelect: true
	});
	
	$('.o-variant').click(function(){
		var e = $(this),
			id = e.find('input').val();
		
		$('#product').val(id);
		$('#price').html(e.find('.price_variant').val());
		if (e.find('.price_variant_discount').val() == '$0.00') 
			$('#price_discount').html('');
		else
			$('#price_discount').html(e.find('.price_variant_vip').val());

		$('.o-variant').removeClass('active');
		e.addClass('active');
	});
	
	$('.o-dropdown-details').click(function(){
		var e = $(this);
		
		e.find('i').toggleClass('fa-angle-down');
		e.find('.o-dropdown-details-content').slideToggle();
	})
});