$(window).load(function(){
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
	    var id = $(this).prop('id');
	    if ($('#popup-'+ id).hasClass('active') == false) {
	    	$('.c-toolbar-popup').fadeOut().removeClass('active');
	    	$('#popup-'+ id).hide().addClass('active').fadeIn();
	    }
	    else 
	    	$('.c-toolbar-popup').fadeOut().removeClass('active');

	    event.preventDefault();
	});

	$('.menu-link').click(function (event){
	    var id = $(this).prop('id');
	    $('.menu-link').removeClass('active');
	    $(this).addClass('active');
	    if ($('#popup-'+ id).hasClass('active') == false) {
	    	$('.c-menu-popup').fadeOut().removeClass('active');
	    	$('#popup-'+ id).hide().addClass('active').fadeIn();
	    }
	    else 
	    	$('.c-menu-popup').fadeOut().removeClass('active');

	    event.preventDefault();
	});
	
	$('html').click(function() {
		$('.menu-link').removeClass('active');
		$('.c-toolbar-popup').fadeOut();
		$('.c-menu-popup').fadeOut();
	});
	$('.toolbar-link').click(function(event){
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

	$('.c-header').affix({
	    offset: {
	        top: 145
	    }
	});
});