$(function(){
	$('#menu-customers').addClass('active');
	$('.btn-filter').click(function (){
		$('.filter-box').slideToggle();
	});
	$('html').click(function() {
		$('.filter-box').slideUp();
	});
	$('.btn-filter').click(function(event){
		event.stopPropagation();
	});
	$('.filter-box').click(function(event){
		event.stopPropagation();
	});
});
var mode = $('#mode').val();

$('.query').change(function() {
	$('#filter-form').submit();
});
$('#filter-field').change(function(){
	if ($(this).val() != '') {
		switch ($(this).val()) {
			case 'total_spent':
			case 'order_count':
				$('#filter-operator').hide().html('<select id="filter-o" class="form-control" name="o"><option value="">Select a value...</option><option>equal to</option><option>not equal to</option><option>greater than</option><option>less than</option></select>').slideDown();
				$('#filter-o').change(function(){
					$('#filter-value').hide().html('<input id="filter-v" type="text" class="form-control" name="v">').slideDown();
					$('#filter-v').keyup(function(){
						if ($(this).val() != '')
							$('#filter-submit').hide().removeClass('hidden').slideDown();
						else
							$('#filter-submit').slideUp();
					});
				});
				break;
			case 'updated_at':
				var today = new Date();
				var last_week = Math.floor(new Date(today.getTime() - (1000*60*60*24*7))/1000);
				var last_month = Math.floor(new Date(today.getTime() - (1000*60*60*24*30))/1000);
				var last_3_months = Math.floor(new Date(today.getTime() - (1000*60*60*24*90))/1000);
				var last_year = Math.floor(new Date(today.getTime() - (1000*60*60*24*365))/1000);
				
				$('#filter-operator').html('<input type="hidden" name="o" value="equal to" />');
				$('#filter-value').hide().html('<select id="filter-v" class="form-control" name="v"><option value="">Select a value...</option><option value="'+ last_week +'">in the last week</option><option value="'+ last_month +'">in the last month</option><option value="'+ last_3_months +'">in the last 3 months</option><option value="'+ last_year +'">in the last year</option></select>').slideDown();
				$('#filter-v').change(function(){
					if ($(this).val() != '')
						$('#filter-submit').hide().removeClass('hidden').slideDown();
					else
						$('#filter-submit').slideUp();
				});
				break;
			case 'accepts_marketing':
				$('#filter-operator').html('<input type="hidden" name="o" value="equal to" />');
				$('#filter-value').hide().html('<select id="filter-v" class="form-control" name="v"><option value="">Select a value...</option><option value="0">no</option><option value="1">yes</option></select>').slideDown();
				$('#filter-v').change(function(){
					if ($(this).val() != '')
						$('#filter-submit').hide().removeClass('hidden').slideDown();
					else
						$('#filter-submit').slideUp();
				});
				break;
			case 'enabled':
				$('#filter-operator').html('<input type="hidden" name="o" value="equal to" />');
				$('#filter-value').hide().html('<select id="filter-v" class="form-control" name="v"><option value="">Select a value...</option><option value="0">unconfirmed/disabled</option><option value="1">active</option></select>').slideDown();
				$('#filter-v').change(function(){
					if ($(this).val() != '')
						$('#filter-submit').hide().removeClass('hidden').slideDown();
					else
						$('#filter-submit').slideUp();
				});
				break;
		}
		
	}
	else {
		$('#filter-operator').slideUp();
		$('#filter-value').slideUp();
		$('#filter-submit').slideUp();
	}
});
$('#filter-form').submit(function(){
	$('.details').html('<i class="fa fa-refresh fa-spin fa-5x"></i><span class="sr-only">Loading...</span>');
	var data = $(this).serialize();
    $.ajax({
        url: "/admin/customer/ajax_",
        type: 'GET',
        data: data,
        cache: false,
        success: function (data) {
            $('.details').hide().html(data).slideDown();
        }
    });
});

$('.filters a').click(function(){
	$('.details').html('<i class="fa fa-refresh fa-spin fa-5x"></i><span class="sr-only">Loading...</span>');
	var remove = $(this).find('span').text();
    $.ajax({
        url: "/admin/customer/ajax_",
        type: 'GET',
        data: 'remove='+ remove,
        cache: false,
        success: function (data) {
            $('.details').hide().html(data).slideDown();
        }
    });
});

$('#check-all').click(function(){
	$(".customer").prop('checked', this.checked);
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

$(".customer").click(function(){
	countSelected();
});

function countSelected() {
	var ctr = 0;
	var customer = ' customer';

	$(".customer").each(function(){
		if (this.checked) 
			ctr++;
	});

	if (ctr > 0) {
		if (ctr > 1) 
			customer += 's';
		$('#selected').html(ctr + customer + ' selected');
		$('thead').addClass('active');
		$('.with-checked').show();
	}
	else {
		$('thead').removeClass('active');
		$('.with-checked').hide();
	}
}

