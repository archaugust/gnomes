$(window).load(function(){
	$(window).resize(function(){
		if ($(this).width() > 767)
			$('.c-main-menu').fadeIn();
	});
	
	$('.c-main-menu-mobile').click(function(){
		$('.c-main-menu').toggle();
	});
	
	$('.close-popup').click(function(){
		$('.c-menu-popup').fadeOut();
	});
	
	$('.u-tooltip').hover(function(){
	    var title = $(this).attr('title');
	    $(this).data('tipText', title).removeAttr('title');
	    $('<p class="u-tooltip-text"></p>').text(title).appendTo('body').fadeIn('slow');
	}, function() {
	        $(this).attr('title', $(this).data('tipText'));
	        $('.u-tooltip-text').remove();
	}).mousemove(function(e) {
	        var mousex = e.pageX - 40; //Get X coordinates
	        var mousey = e.pageY + 20; //Get Y coordinates
	        $('.u-tooltip-text').css({ top: mousey, left: mousex })
	});

	$('.toolbar-link').click(function (event){
		showPopup($(this));
	});

	$('.toolbar-link').mouseover(function (event){
		showPopup($(this));
	});
	
	$('.no-link').click(function (event){
	    var id = $(this).prop('id');
	    $('.no-link').removeClass('active');
	    $(this).addClass('active');
	    if ($('#popup-'+ id).hasClass('active') == false) {
	    	$('.c-menu-popup').fadeOut().removeClass('active');
	    	$('#popup-'+ id).hide().addClass('active').fadeIn();
	    	
			if ($(window).width() < 768) {
				$('#popup-'+ id).height($(window).height() - 37);
			}
	    	
	    }
	    else 
	    	$('.c-menu-popup').fadeOut().removeClass('active');

	    event.preventDefault();
	});
	
	$('html').click(function() {
		$('.menu-link').removeClass('active');
		$('.c-toolbar-popup').fadeOut();
		$('.c-menu-popup').fadeOut();
		
		if ($(window).width() < 768) {
			$('.c-main-menu').fadeOut();
		}
	});
	$('.c-main-menu-mobile').click(function(event){
		event.stopPropagation();
	});
	$('.toolbar-link').click(function(event){
		event.stopPropagation();
	});
	$('.c-toolbar-popup').click(function(event){
		event.stopPropagation();
	});
	$('.menu-link').click(function(event){
		event.stopPropagation();
	});
	$('.c-menu-popup').click(function(event){
		event.stopPropagation();
	});

	$(window).scroll(function() {
	    if ($(this).scrollTop() >= 50) {
	        $('#return-to-top').fadeIn(200);
	    } else {
	        $('#return-to-top').fadeOut(200);
	    }
	});

	$('#return-to-top').click(function() {
	    $('body,html').animate({
	        scrollTop : 0
	    }, 500);
	});

	$('a[href*=\\#]').on('click', function(event){
		if ($(this.hash).length > 0)
			$('html,body').animate({scrollTop:$(this.hash).offset().top - 140}, 500);
	});
	
	$('.c-header').affix({
	    offset: {
	        top: 145
	    }
	});
	
    $('figure.c-blog').click(function(){
    	$(this).toggleClass('active').fadeIn();
    });

    recalculateCart();
});

Number.prototype.formatMoney = function(c, d, t){
	var n = this, 
	    c = isNaN(c = Math.abs(c)) ? 2 : c, 
	    d = d == undefined ? "." : d, 
	    t = t == undefined ? "," : t, 
	    s = n < 0 ? "-" : "", 
	    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
	    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

function showPopup(e) {
    var id = e.prop('id');
    if ($('#popup-'+ id).hasClass('active') == false) {
    	$('.c-toolbar-popup').fadeOut().removeClass('active');
    	$('#popup-'+ id).hide().addClass('active').fadeIn();
    }
    else 
    	$('.c-toolbar-popup').fadeOut().removeClass('active');
}
	 
function recalculateCart() {
	var in_cart = $('.o-cart-item').length;

	if (in_cart == 0) {
		$('.o-cart-items').html('<div class="u-pl-40 u-pb-40">Your shopping cart is empty.</div>');
		$('.o-cart-total').slideUp();
		$('.shipping').slideUp();
	}
	else {
	    var totalPrice = 0; 
	    $('.o-cart-item').each(function(){
	    	totalPrice += parseFloat($(this).find('.o-cart-price').val());
	    });
	    
	    $('.o-cart-subtotal').html('$'+ totalPrice.formatMoney(2,'.',',')).hide().removeClass('u-hidden').slideDown();
	    $('.o-cart-total').hide().removeClass('u-hidden').slideDown();
	    
	    if (totalPrice > 100) 
	    	$('.shipping').hide().removeClass('u-hidden').slideDown();
	}
	
	$('#in_cart').html(in_cart);
}

function reloadCart() {
    $.ajax({
        url: "/cart/reload",
        success: function (data) {
        	$('.o-cart-items').html(data);
        	recalculateCart();
        }
    });
}

$('.o-cart-item-remove').click(function(){
	var parent = $(this).closest('.o-cart-item'),
		product = parent.find('.o-cart-key').val(),
		in_cart;

    $.ajax({
        url: "/cart/remove",
        data: 'product='+ product,
        type: "POST",
        success: function (data) {
        	parent.remove();
        	setTimeout(recalculateCart, 1000);
        }
    });

});

function reloadWishlist() {
    $.ajax({
        url: "/wishlist/reload",
        success: function (data) {
        	$('.o-wishlist-items').html(data);
        }
    });
}

$('.o-wishlist-item-remove').click(function(){
	var parent = $(this).closest('.o-wishlist-item'),
		handle = parent.find('.o-wishlist-key').val();

    $.ajax({
	    url: "/wishlist-toggle",
	    data: 'mode=remove&handle='+ handle,
        type: "POST",
        success: function (data) {
        	parent.remove();
        	setTimeout(reloadWishlist, 1000);
        }
    });

});