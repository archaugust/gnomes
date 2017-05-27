$(function () {
	recalculateCheckout();
	reloadShipping();
	
	$('label').each(function(){
		if ($(this).find('span').length == 0)
			$(this).html($(this).html().replace('*', '<span class="u-blue">*</span>'));
	});
})

function recalculateCheckout() {
	var	subTotal = 0,
		shippingCost = parseFloat($(".shipping-cost").val()),
		grandTotal = 0
	;
	
	$('.o-summary-item').each(function(){
		var e = $(this);
		subTotal += (parseFloat(e.find('.o-cart-price').val()) * parseFloat(e.find('.o-cart-item-qty').val()));
	});
	
	if (shippingCost == 0) {
		$('#shippingDiv').html("<b>FREE</b>");
	}
	else {
		$('#shippingDiv').html("<b>$"+ shippingCost.formatMoney(2,'.',',') +"</b>");
	}

	grandTotal = parseFloat(subTotal) + parseFloat(shippingCost);
	
	$('#grandTotalDiv').html("<b>$"+ grandTotal.formatMoney(2,'.',',') +"</b>");
}

function reloadShipping() {
	$.ajax({
		url: "/reload-shipping",
		success: function(data) {
			$('#shippingMethods').html(data);
			
			$("input[name='shipping']").change(function(){
				$.ajax({
					url: "/get-product-price/"+ $(this).val(),
					success: function (data) {
						$('.shipping-cost').val(data);
						setTimeout(recalculateCheckout, 500);
					}
				})
			});
		}
	});
}

$("input[name='shipping']").change(function(){
	$.ajax({
		url: "/get-product-price/"+ $(this).val(),
		success: function (data) {
			$('.shipping-cost').val(data);
			setTimeout(recalculateCheckout, 2000);
		}
	})
});
