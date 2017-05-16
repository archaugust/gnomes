if ('results' in localStorage) {
    $("#resultDiv").html(localStorage.getItem('results'));
}

$(window).unload(function () {
    var results = $("#resultDiv").html();
    localStorage.setItem('results', results);
});

$(function(){

	$('.o-criteria-box').click(function(){
		$(this).find('.choices').slideToggle();
	});
	
	$('.choices div').click(function(){
		var e = $(this);
		var parent = e.closest('.o-criteria-box'),
			value = e.find('input').val(),
			text = e.find('.text').html();
		
		parent.find('i').hide();
		parent.find('.selected').val(value);
		parent.find('.current').html(text);
		e.closest('.choices').slideUp();
		console.log(parent.find('.selected').val())
	});
	
	$('.o-option').click(function(){
		var e = $(this);
		var checkbox = e.find('input');
		if (e.hasClass('radio')) {
			var parent = e.parent('.o-checkboxes');
			parent.find('input').prop('checked', false);
			parent.find('div').removeClass('active');
		}
		checkbox.prop('checked', !checkbox.prop("checked"));
		e.find('div').toggleClass('active');
	});
	
	$('.o-option').each(function(){
		var e = $(this);
		var checkbox = e.find('input');
		if (checkbox.prop('checked'))
			e.find('div').addClass('active');
		else
			e.find('div').removeClass('active');
	});

	$('html').click(function() {
		$('.choices').slideUp();
	});
	$('.o-criteria-box').click(function(event){
		event.stopPropagation();
	});
	$('.choices').click(function(event){
		event.stopPropagation();
	});


	$('.o-sort-order').click(function(){
		$(this).find('.popup').slideToggle();
	});
	
	$('html').click(function() {
		$('.popup').slideUp();
	});

	$('.o-sort-order').click(function(event){
		event.stopPropagation();
	});
	
	$('.popup a').click(function(){
		$('#current').text($(this).text());
	});
	
});

