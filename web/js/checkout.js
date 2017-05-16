$(function () {
	recalculateCheckout();
})
function recalculateCheckout() {
	var in_cart = $('.o-checkout-item').length;
	
	if (in_cart == 0) {
		$('.cart-empty').hide().removeClass('u-hidden').slideDown();
		$('#summaryDiv').slideUp();
	}
	else {
		var	subTotal = 0,
    		shippingCost = parseFloat($(".shipping-cost").val()),
    		grandTotal = 0
    	;

    	$('.o-checkout-item').each(function(){
    		var e = $(this);
    		subTotal += (parseFloat(e.find('.o-cart-price').val()) * parseFloat(e.find('.o-cart-item-qty').val()));
    	});
    
    	$('#subtotalDiv').html('$'+ subTotal.formatMoney(2,'.',','));
    	if (shippingCost == 0) {
    		$('#shippingDiv').html("FREE");
    	}
    	else {
    		$('#shippingDiv').html("$"+ shippingCost.formatMoney(2,'.',','));
    	}
    
    	grandTotal = subTotal + shippingCost;
    	$('#grandTotalDiv').html('$'+ grandTotal.formatMoney(2,'.',','));
	}
}

$('.o-cart-item-qty').change(function(){
	var e = $(this);
	var	parent = e.closest('tr');
	var product = parent.find('.o-cart-key').val(),
		qty = e.val(),
		subtotal;
	
    $.ajax({
        url: "/cart/update",
        data: 'product='+ product +'&qty='+ qty,
        type: "POST",
        success: function (data) {
        	e.notify(data,{style:'default', position: 'top center'});
        	subtotal = parseFloat(parent.find('.o-cart-price').val()) * parseFloat(parent.find('.o-cart-item-qty').val());
        	parent.find('.priceDiv').html('$'+ subtotal.formatMoney(2,'.',','));
        	reloadCart();
        	setTimeout(recalculateCheckout, 1000);
        }
    });
});

$('.remove').click(function(){
	var e = $(this);
	var parent = e.closest('tr');
	var product = parent.find('.o-cart-key').val();
    
    $.ajax({
        url: "/cart/remove",
        data: 'product='+ product,
        type: "POST",
        success: function (data) {
        	parent.remove();
        	setTimeout(recalculateCheckout, 1000);
        	setTimeout(reloadCart, 1000);
        	reloadShipping();
        }
    });
});
