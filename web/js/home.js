
$(function () {
    jQuery.fn.verticalAlign = function ()
	{
	    return this
	            .css("margin-top",($(this).parent().height() - $(this).height())/2 + 'px' )
	};

	function valign() {
		$('.menuDiv').verticalAlign();
		$('.logoDiv').verticalAlign();
		$('.bookDiv').verticalAlign();
		$('.caption-book').verticalAlign();
	}

	function resizeH1() {
		var preferredHeight = 278;
		var fontsize = 54;
		var top = 0;
		var height = $('#spacer').height();
		var percentage = height / preferredHeight;
		var newFontSize = Math.floor(fontsize * percentage);
		var padding = (height-newFontSize)/2;  
		if (padding != Math.floor(padding)) {
			padding = Math.floor(padding);
			top = 1;
		}
		$("h1").css({"font-size": newFontSize+'px', "line-height": (newFontSize)+'px', "padding-top": (padding+top)+'px', "padding-bottom": padding+'px'});

	}
	
	valign();
	
	$(window).resize(function(){ 
		var width = $(window).width();  
		if(width < 768){
	    	$('#menubar').attr('data-toggle','');
		}
		else {
			valign(); 
			$('#menubar').attr('data-toggle','modal')
			resizeH1()
		}
		
	});

	var width = $(window).width();  

	if(width < 768){
    	$('#menubar').attr('data-toggle','');
	}

	$(window).scroll(function() {
		$("#menu-mobile").removeClass('in');
	});

	
	var $grid = $('.grid').imagesLoaded( function() {
		resizeH1();
		  $grid.isotope({
			  itemSelector: '.grid-item',
			  percentPosition: true,
			  masonry: {
			    columnWidth: '.grid-sizer'
			  }
		  });
	});
});

