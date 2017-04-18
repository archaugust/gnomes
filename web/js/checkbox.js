$('#check-all').click(function(){
	$(".item").prop('checked', this.checked);
	if (this.checked) {
		$('thead').addClass('active');
		$('.with-checked').show();
		countSelected();
	}
	else {
		$('thead').removeClass('active');
		$('.with-checked').hide();
	}
});

$(".item").click(function(){
	countSelected();
});

function countSelected() {
	var ctr = 0;
	var item = ' item';

	$(".item").each(function(){
		if (this.checked) 
			ctr++;
	});

	if (ctr > 0) {
		if (ctr > 1) 
			item += 's';
		$('#selected').html(ctr + item + ' selected');
		$('thead').addClass('active');
		$('.with-checked').show();
	}
	else {
		$('thead').removeClass('active');
		$('.with-checked').hide();
	}
}