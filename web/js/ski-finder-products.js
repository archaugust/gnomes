var products = $('.o-product');
var	brands = JSON.parse(localStorage.getItem("brands_finder")) || [];
var	tags = JSON.parse(localStorage.getItem("tags_finder")) || [];
var	prices = JSON.parse(localStorage.getItem("prices_finder")) || [];
var	test_brand, test_tag, test_price, tmp;

function show_products() {
	var brand;
	$('.o-product').hide();
	
	$('.o-product').each(function(){
		test_brand = test_tag = test_price = 0;
		if (brands.length > 0) {
			brand = $(this).find('.filter-brand').text();
			$.each(brands, function(index, value){
				if (brand == value)
					test_brand = 1;
			});
		}
		else
			test_brand = 1;
		
		if (tags.length > 0) {
			tag = $(this).find('.filter-tags').text();
			$.each(tags, function(index, value){
				if (tag.indexOf(value) != -1)
					test_tag = 1;
			});
		}
		else
			test_tag = 1;

		if (prices.length > 0) {
			price = parseInt($(this).find('.filter-price').text());
			$.each(prices, function(index, value){
				tmp = value.split('-');
				if (price >= tmp[0] && price <= tmp[1])
					test_price = 1;
			});
		}
		else
			test_price = 1;
		
		if (test_brand == 1 && test_tag == 1 && test_price == 1)
			$(this).fadeIn();
	});
	
	$('.o-ski-finder').fadeIn();
	
	localStorage.setItem("brands_finder", JSON.stringify(brands));
	localStorage.setItem("tags_finder", JSON.stringify(tags));
	localStorage.setItem("prices_finder", JSON.stringify(prices));
}

$('.check-brand').click(function(){
	brands = [];
	$('.check-brand').each(function(){
		if ($(this).is(":checked")) {
			brands.push($(this).val());
		}
	});
	
	show_products();
});

$('.check-tag').click(function(){
	tags = [];
	$('.check-tag').each(function(){
		if ($(this).is(":checked")) {
			tags.push($(this).val());
		}
	});
	
	show_products();
});

$('.check-price').click(function(){
	prices = [];
	$('.check-price').each(function(){
		if ($(this).is(":checked")) {
			prices.push($(this).val());
		}
	});
	
	show_products();
});

$('#name-asc').click(function () {
    var alphabeticallyOrderedDivs = products.sort(function (a, b) {
        return $(a).find('.name').text() > $(b).find('.name').text();
    });
    $('.o-products').hide().html(alphabeticallyOrderedDivs).fadeIn();
});

$('#name-desc').click(function () {
    var alphabeticallyOrderedDivs = products.sort(function (a, b) {
        return $(a).find('.name').text() < $(b).find('.name').text();
    });
    $('.o-products').hide().html(alphabeticallyOrderedDivs).fadeIn();
});

$('#price-asc').click(function () {
    var numericallyOrderedDivs = products.sort(function (a, b) {
        return parseInt($(a).find('.filter-price').text()) > parseInt($(b).find('.filter-price').text());
    });
    $('.o-products').hide().html(numericallyOrderedDivs).fadeIn();
});

$('#price-desc').click(function () {
    var numericallyOrderedDivs = products.sort(function (a, b) {
        return parseInt($(a).find('.filter-price').text()) < parseInt($(b).find('.filter-price').text());
    });
    $('.o-products').hide().html(numericallyOrderedDivs).fadeIn();
});

show_products();
